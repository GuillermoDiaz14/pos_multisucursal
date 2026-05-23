# Arquitectura del sistema

> Documento técnico que describe la arquitectura interna del POS Multisucursal: capas, flujo de petición, piezas transversales y patrones de diseño aplicados.

---

## 1. Visión general

El sistema sigue el patrón **MVC clásico de CodeIgniter 3**, con una capa adicional transversal de autenticación, autorización y datos globales centralizada en `BaseController`.

```
                  ┌────────────────────┐
   HTTP Request ─►│   index.php (FC)   │
                  └────────┬───────────┘
                           ▼
                  ┌────────────────────┐
                  │  CI Router         │ ◄── application/config/routes.php
                  └────────┬───────────┘
                           ▼
                  ┌────────────────────┐
                  │   Controller       │ extends BaseController
                  │                    │ ──► ModuleAccess (permisos)
                  │                    │ ──► cias_helper   (utilitarios)
                  └────────┬───────────┘
                           ▼
                  ┌────────────────────┐
                  │   Model            │ ──► MySQL (pos_multisucursal)
                  └────────┬───────────┘
                           ▼
                  ┌────────────────────┐
                  │   View (AdminLTE)  │ includes/header.php + footer.php
                  └────────────────────┘
```

---

## 2. Capas

### 2.1 Front Controller — `index.php`

Punto único de entrada. Define el ambiente (`ENVIRONMENT`), carga el bootstrap de CodeIgniter y delega al router.

### 2.2 Router — `application/config/routes.php`

Mapea URLs a `controlador/método`. Las rutas relevantes están listadas en el [README §11](../README.md#11-rutas-relevantes).

Convención: las URLs no listadas explícitamente siguen el patrón estándar de CI:

```
http://host/pos_multisucursal/{controlador}/{método}/{param1}/{param2}
```

### 2.3 Controllers — `application/controllers/`

- Una clase por módulo (`Carrito`, `Caja`, `Producto`, etc.).
- **Todos extienden `BaseController`** (no `CI_Controller` directamente).
- En el constructor declaran `$this->module = 'NombreModulo'` para resolución de permisos.
- Responsabilidad: validar input HTTP, invocar el modelo, decidir vista o redirección. No deben contener lógica de negocio compleja.

### 2.4 Models — `application/models/`

- Acceso a datos por dominio (`Carrito_model`, `Trasladar_model`, etc.).
- Usan **Active Record** o **queries con bindings** (nunca SQL concatenado).
- Las operaciones multi-tabla deben envolverse en transacciones (`$this->db->trans_begin/commit/rollback`).

### 2.5 Views — `application/views/`

Organizadas por módulo. Cada acción de controlador suele renderizar:

```php
$this->loadViews('modulo/vista', $headerInfo, $pageData, $footerInfo);
```

`loadViews()` carga en orden:

1. `includes/header.php`
2. La vista del módulo (cuerpo)
3. `includes/footer.php`

### 2.6 Configuración — `application/config/`

| Archivo | Función |
|---------|---------|
| `config.php` | `base_url`, claves, sesión |
| `database.php` | Conexión MySQL |
| `routes.php` | Mapeo URL → controlador |
| `autoload.php` | `database`, `session`, helpers `url`/`file`/`cias_helper`, configs `modules`/`reports` |
| `constants.php` | Constantes globales |
| `modules.php` | Catálogo de módulos para matriz de permisos |
| `reports.php` | Definición de reportes disponibles |
| `zebra_printers.php` | Configuración de impresoras Zebra |
| `emergency.php` | Hash de token + TTL para acceso de emergencia |

---

## 3. Capa transversal

### 3.1 `BaseController` — `application/libraries/BaseController.php`

Clase base que **todos los controladores del sistema extienden**. Centraliza:

#### Datos globales en sesión

Tras login, la sesión contiene:

- `isLoggedIn` — bandera de sesión activa
- `userId` — ID del usuario
- `role`, `roleText` — rol y nombre legible
- `name` — nombre del usuario
- `lastLogin` — timestamp del último login
- `id_sucursal` — sucursal activa
- `accessInfo` — matriz de permisos decodificada (JSON de `tbl_access_matrix`)

#### Validación de sesión

`isLoggedIn()` se ejecuta al inicio de cada acción autenticada:

- Verifica que `isLoggedIn` esté en sesión.
- Aplica **timeout absoluto de 12h** desde el login.
- Aplica **timeout por inactividad de 2h**.
- Si el rol o la matriz de permisos cambian en BD (`updatedDtm`), refresca la sesión automáticamente — ver `_refreshRoleIfNeeded()` y `_refreshAccessInfoIfNeeded()`.

#### Métodos de permisos

| Método | Propósito |
|--------|-----------|
| `hasListAccess()` | Permiso de lectura en el módulo actual (`$this->module`) |
| `hasCreateAccess()` | Permiso de creación |
| `hasUpdateAccess()` | Permiso de edición |
| `hasDeleteAccess()` | Permiso de eliminación |
| `hasAccessToModule($nombre)` | Acceso genérico a un módulo arbitrario |
| `hasAdminPanelAccess()` | Acceso al módulo "Configuracion" |
| `getProductoPermisos()` | Permisos finos de Productos (`ver_precio_compra`, `gestionar`) |
| `hasProductPermission($p)` | Permiso específico en Productos |
| `hasVentaPermission($p)` | Permiso específico en Ventas (`editar`, `eliminar`) |
| `hasReportAccess($key)` | Acceso a un reporte concreto |
| `canAccessAllBranchesReports()` | Reportes multisucursal (`scope === 'todas'`) |
| `getAccessibleReports()` | Lista de reportes permitidos para el usuario |
| `isAdmin()` | Sesión de emergencia activa con token vigente |
| `loadThis()` | Renderiza vista de acceso denegado |
| `logout()` | Destruye sesión y redirige a login |

#### Render

`loadViews($view, $header, $page, $footer)` encapsula el render con layout AdminLTE.

### 3.2 `ModuleAccess` — `application/libraries/ModuleAccess.php`

Carga el catálogo de módulos desde `config/modules.php`. Es el punto de partida para la construcción de la matriz de permisos por rol.

### 3.3 `cias_helper` — `application/helpers/cias_helper.php`

Helpers utilitarios cargados automáticamente:

| Función | Uso |
|---------|-----|
| `getHashedPassword($plain)` | Hash bcrypt (`PASSWORD_DEFAULT`) |
| `verifyHashedPassword($plain, $hash)` | Verificación de password |
| `getBrowserAgent()` | Detección de navegador/dispositivo |
| `setProtocol()` / `emailConfig()` | Configuración SMTP |
| `resetPasswordEmail($detail)` | Envío de correo de recuperación |
| `setFlashData($status, $msg)` | Mensaje flash en sesión |
| `fmt_fecha($fecha, $con_hora)` | Formatea fecha de BD a formato legible |
| `pre($data)` | Debug en `<pre>` |

---

## 4. Flujo de una petición autenticada

Ejemplo: `GET /carrito/carrito`

1. **Router** resuelve a `Carrito::carrito()`.
2. **Constructor** del controlador llama a `parent::__construct()`.
3. `BaseController` ejecuta `isLoggedIn()`:
   - Si no hay sesión válida → redirige a `/login`.
   - Si la matriz de permisos cambió → recarga `accessInfo` desde `tbl_access_matrix`.
   - Carga `$this->global` con datos de usuario, rol, sucursal.
4. El controlador valida `hasListAccess()` para el módulo `Ventas`.
5. Llama al modelo `Carrito_model` para datos (catálogo, caja abierta, etc.).
6. Renderiza la vista vía `loadViews()`.

---

## 5. Multisucursalidad

La separación por sucursal opera a tres niveles:

### 5.1 Sesión

`id_sucursal` se establece en login y se usa como filtro implícito en todas las operaciones.

### 5.2 Datos

| Entidad | Mecanismo |
|---------|-----------|
| Stock | `tbl_producto_stock` y `tbl_stock_variante` con clave compuesta `(producto/variante, sucursal)` |
| Ventas, compras, caja | Cada registro lleva `id_sucursal` |
| Catálogos por sucursal | `tbl_cliente`, `tbl_metodo_pago` |
| Catálogos globales | `tbl_producto`, `tbl_categoria`, `tbl_proveedor` |
| Traslados | Modelan origen (`id_sucursal_descuento`) y destino (`id_sucursal_aumento`) |

### 5.3 Reportes

- **Operativos** (`Reporte`): siempre filtran por `id_sucursal` de sesión.
- **Administrativos** (`Reporte_administrador`): pueden agregar multi-sucursal si el rol tiene `scope === 'todas'` en `accessInfo['Reportes']`.

---

## 6. Transacciones e integridad

Operaciones que afectan múltiples tablas **deben** ejecutarse en transacción:

- **Venta**: cabecera (`tbl_venta`) + detalle (`tbl_detalle_venta`) + actualización de stock.
- **Compra/Entrada**: cabecera (`tbl_compra`) + detalle + incremento de stock.
- **Traslado**: cabecera (`tbl_traslado`) + detalle + decremento origen + incremento destino. Si el stock origen es insuficiente, rollback completo.
- **Caja**: apertura y cierre se hacen en transacción para evitar dobles aperturas concurrentes.

Patrón estándar:

```php
$this->db->trans_begin();
// ... operaciones ...
if ($this->db->trans_status() === FALSE || $errorInterno) {
    $this->db->trans_rollback();
    return false;
}
$this->db->trans_commit();
```

---

## 7. Inventario y movimientos

No existe una tabla física `tbl_movimiento_inventario` con histórico atómico. En su lugar, la "bitácora" se reconstruye dinámicamente vía `Movimiento_inventario_model::getMovimientos()` con `UNION ALL` sobre:

- Ventas (`tbl_detalle_venta`) — salida
- Compras (`tbl_detalle_compra`) — entrada
- Traslados (`tbl_detalle_traslado`) — salida en origen + entrada en destino

> **Consecuencia:** cualquier nueva fuente de movimiento de stock (ej. ajustes manuales, mermas) debe agregarse explícitamente al `UNION` para aparecer en el reporte de movimientos.

---

## 8. Periféricos

### 8.1 Impresoras Zebra

- Configuración: `application/config/zebra_printers.php`
- Generación de ZPL: controlador `Zebra` + `Producto::etiquetas*`
- Documentación: [manual_impresoras_zebra.md](manual_impresoras_zebra.md), [ZEBRA_SETUP.md](../ZEBRA_SETUP.md)

### 8.2 PDFs y tickets

- Servidor: **TCPDF** (`assets/TCPDF-main/`)
- Cliente: **jsPDF** (`assets/jsPDF-master/`)

### 8.3 Importación/exportación CSV-Excel

- **SheetJS** en `assets/sheetjs/`
- Usado en importación masiva de productos.

---

## 9. Patrones recomendados

- **Single responsibility**: un controlador por módulo, un modelo por dominio.
- **Validación temprana**: input → validación → modelo. Nunca pasar input crudo al modelo.
- **Active Record / bindings**: prohibido construir SQL concatenando variables.
- **Permisos antes de mutar**: toda acción mutante valida permiso explícitamente.
- **Transacciones obligatorias** en flujos multi-tabla.
- **Sucursal explícita**: incluso si está en sesión, las consultas la pasan como parámetro al modelo para facilitar testing y reuso (ej. reportes admin con `id_sucursal` arbitrario).

---

## 10. Referencias

- [README principal](../README.md)
- [Modelo de datos](modelo_datos.md)
- [Seguridad y autenticación](seguridad.md)
- [Módulo Ventas](modulos/ventas.md)
- [Módulo Caja](modulos/caja.md)
- [Módulo Traslados](modulos/traslados.md)
- [Módulo Productos](modulos/productos.md)
- [Módulo Reportes](modulos/reportes.md)
