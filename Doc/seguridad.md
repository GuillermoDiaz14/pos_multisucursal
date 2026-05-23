# Seguridad y control de acceso

> Modelo de autenticación, autorización, sesión, recuperación de contraseña y acceso de emergencia del POS Multisucursal.

---

## 1. Autenticación

### 1.1 Login

- Controlador: `Login` (`application/controllers/Login.php`).
- Acción: `loginMe` (ruta `/loginMe`).
- Algoritmo de hash: **bcrypt** vía `password_hash($plain, PASSWORD_DEFAULT)` (cias_helper::`getHashedPassword`).
- Verificación: `verifyHashedPassword($plain, $hashed)` → `password_verify`.

### 1.2 Sesión

Tras login exitoso, la sesión almacena:

| Clave | Contenido |
|-------|-----------|
| `isLoggedIn` | bool |
| `userId` | int |
| `role`, `roleText` | rol numérico y nombre |
| `name` | nombre del usuario |
| `lastLogin` | timestamp |
| `id_sucursal` | sucursal activa |
| `accessInfo` | matriz de permisos (JSON decodificado) |
| `login_time` | timestamp absoluto |
| `last_activity` | timestamp por actividad |

### 1.3 Timeouts

- **Absoluto**: 12 horas desde `login_time`.
- **Inactividad**: 2 horas desde `last_activity`.

Ambos se evalúan en `BaseController::isLoggedIn()`. Vencido cualquiera → sesión destruida y redirección a login.

### 1.4 Refresco automático de permisos

`BaseController` detecta cambios en tiempo real:

- `_refreshRoleIfNeeded()` — si cambia el `roleId` o `updatedDtm` del rol en BD, recarga la sesión.
- `_refreshAccessInfoIfNeeded()` — si cambia `tbl_access_matrix.updatedDtm`, recarga `accessInfo` sin requerir nuevo login.

> Implica que al modificar permisos en runtime, los usuarios afectados ven los cambios en su próxima petición.

---

## 2. Autorización

### 2.1 Matriz de permisos

La autorización se basa en `tbl_access_matrix.access` (JSON) ligado al `roleId` del usuario. Estructura:

```json
[
  { "module": "Ventas",     "total_access": 1, "editar": 1, "eliminar": 0 },
  { "module": "Productos",  "total_access": 1, "ver_precio_compra": 0, "gestionar": 1 },
  { "module": "Reportes",   "total_access": 1, "scope": "sucursal",
                            "reports": { "ventas_diarias": 1, "ventas_periodo": 0 } },
  { "module": "Caja",       "total_access": 1 },
  ...
]
```

### 2.2 Módulos definidos

Catálogo en `application/config/modules.php`:

`Caja`, `Ventas`, `Compras`, `Gastos`, `Ingresos`, `Métodos de Pago`, `Productos`, `Proveedores`, `Traslados`, `Sucursal`, `Empleado`, `Cliente`, `Configuracion`, `Reportes`.

### 2.3 Permisos finos

Algunos módulos exponen permisos adicionales sobre `total_access`:

| Módulo | Permisos extra |
|--------|----------------|
| Productos | `ver_precio_compra`, `gestionar` |
| Ventas | `editar`, `eliminar` |
| Reportes | `scope` (`sucursal` \| `todas`), `reports` (lista granular por reporte) |

### 2.4 Validación en runtime

Los controladores invocan al inicio de cada acción mutante:

```php
if (!$this->hasUpdateAccess()) {
    $this->loadThis();   // Vista 'access denied'
    return;
}
```

Métodos disponibles en `BaseController`:

| Método | Uso |
|--------|-----|
| `hasListAccess()` | Lectura del módulo actual |
| `hasCreateAccess()` | Creación |
| `hasUpdateAccess()` | Edición |
| `hasDeleteAccess()` | Eliminación |
| `hasAccessToModule($nombre)` | Acceso a módulo arbitrario |
| `hasProductPermission($p)` | Permiso fino en Productos |
| `hasVentaPermission($p)` | Permiso fino en Ventas |
| `hasReportAccess($key)` | Reporte específico |
| `canAccessAllBranchesReports()` | Reportes multisucursal |

> **Regla**: declarar `$this->module = 'NombreModulo'` en el constructor del controlador es obligatorio para que los `has*Access()` resuelvan correctamente.

---

## 3. Recuperación de contraseña

Flujo:

1. Usuario solicita en `/forgotPassword` (`Login::forgotPassword`).
2. Se genera token y se registra en `tbl_reset_password` con `activation_id` (token único), IP, user agent, plataforma, timestamp.
3. Correo enviado vía `cias_helper::resetPasswordEmail()` con la vista `application/views/email/resetPassword.php`.
4. Usuario abre el link `/resetPasswordConfirmUser/{email}/{token}`.
5. `Login::createPasswordUser` valida token + email y guarda nueva contraseña hasheada.

> **Requiere** constantes SMTP definidas: `PROTOCOL`, `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`, `EMAIL_FROM`, `FROM_NAME`. Si no existen, el correo no se envía.

---

## 4. Acceso de emergencia

Controlador: `Emergency` (`application/controllers/Emergency.php`).

### 4.1 Propósito

Acceso administrativo controlado por **token** cuando la sesión normal no es viable (rol bloqueado, base de roles corrupta, soporte remoto).

### 4.2 Flujo

1. URL: `/acceso-emergencia/{token}` → `Emergency::access($token)`.
2. Verifica `password_verify($token, $hash)` contra `emergency_token_hash` definido en `application/config/emergency.php`.
3. Si válido, establece sesión:
   - `emergency_admin = true`
   - `emergency_expires = time() + emergency_ttl`
4. Permite operar como admin durante el TTL.
5. Salida: `/acceso-emergencia/salir` → `Emergency::salir()` limpia sesión.

### 4.3 Respuesta a token inválido

Devuelve **404** — no revela que la ruta existe.

### 4.4 Auditoría

Todo acceso de emergencia debe ser:

- Justificado por un incidente real.
- Reportado posteriormente.
- Limitado en TTL (sugerido ≤ 30 minutos).

---

## 5. Buenas prácticas obligatorias

### 5.1 SQL

- Usar **Active Record** o **bindings** (`$this->db->query($sql, [$param])`).
- **Prohibido** concatenar input HTTP en SQL.

### 5.2 XSS

- Escapar output con `htmlspecialchars()` o `html_escape()` (helper CI) en vistas.
- AdminLTE renderiza muchas vistas con `echo` directo — revisar nuevas vistas explícitamente.

### 5.3 CSRF

CodeIgniter ofrece protección CSRF; verificar `application/config/config.php`:

```php
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
$config['csrf_expire'] = 7200;
```

Si está desactivado, evaluar habilitarlo en formularios sensibles (mínimo: login, cambios de password, operaciones de caja, traslados).

### 5.4 Validación de sucursal

Toda operación mutante debe validar que el `id_sucursal` involucrado corresponda al de la sesión, excepto en flujos administrativos explícitos (ej. traslados, reportes admin con scope `todas`).

```php
if ($id_sucursal_input != $this->session->userdata('id_sucursal')) {
    show_error('Sucursal no autorizada', 403);
}
```

### 5.5 Subida de archivos

- Configurada en `./uploads/` (`config.php` → `upload_path`).
- Validar tipo MIME y extensión.
- No exponer rutas absolutas del servidor en mensajes de error.

### 5.6 Contraseñas

- Política mínima sugerida: 8 caracteres, mezcla de letras y números.
- Hash siempre con bcrypt (no MD5/SHA1 legados).
- No transmitir contraseñas en URL ni en logs.

---

## 6. Cabeceras y servidor

Recomendaciones de servidor (Apache `.htaccess` o vhost):

- `Strict-Transport-Security` (si HTTPS).
- `X-Content-Type-Options: nosniff`.
- `X-Frame-Options: SAMEORIGIN`.
- `Referrer-Policy: same-origin`.
- Deshabilitar listing de directorios (`Options -Indexes`).

---

## 7. Checklist de seguridad para nuevos módulos

Al desarrollar un nuevo controlador:

- [ ] `class extends BaseController` (no `CI_Controller`).
- [ ] `$this->module = 'NombreModulo'` en constructor.
- [ ] Validar `hasListAccess()` / `hasCreateAccess()` / etc. antes de cada acción.
- [ ] Operaciones mutantes envueltas en transacción si tocan más de una tabla.
- [ ] Inputs filtrados con `$this->input->post(..., TRUE)` (XSS clean) o validados.
- [ ] Queries con Active Record o bindings.
- [ ] `id_sucursal` validado contra sesión.
- [ ] Errores no exponen rutas ni stack traces en producción (`ENVIRONMENT = 'production'`).
- [ ] Vistas escapan output dinámico.

---

## 8. Referencias

- [README principal](../README.md)
- [Arquitectura](arquitectura.md)
- [Modelo de datos — tbl_access_matrix](modelo_datos.md#tbl_access_matrix)
