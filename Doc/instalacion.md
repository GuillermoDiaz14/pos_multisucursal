# Guía de instalación

> Instrucciones detalladas para preparar un entorno local o de servidor del POS Multisucursal.

---

## 1. Requisitos

| Componente | Mínimo | Recomendado |
|------------|--------|-------------|
| PHP | 5.6 | 7.4 |
| MySQL / MariaDB | 5.6 / 10.2 | 5.7 / 10.4+ |
| Apache | 2.4 | 2.4 con `mod_rewrite` |
| Composer | 2.x | 2.x |
| Extensiones PHP | `mysqli`, `mbstring`, `openssl`, `json`, `gd`, `curl` | |

**Entorno de referencia:** XAMPP en macOS, ruta `/Applications/XAMPP/xamppfiles/htdocs/pos_multisucursal`.

---

## 2. Preparación del entorno

### 2.1 Verificar PHP

```bash
php -v
php -m | grep -Ei "mysqli|mbstring|openssl|gd|curl"
```

### 2.2 Verificar Composer

```bash
composer --version
```

Si no está instalado: [https://getcomposer.org/download/](https://getcomposer.org/download/)

### 2.3 Verificar Apache con mod_rewrite

```bash
apachectl -M | grep rewrite
```

Si no aparece `rewrite_module`, habilitarlo en `httpd.conf`:

```
LoadModule rewrite_module modules/mod_rewrite.so
```

Y permitir `.htaccess` en el `DocumentRoot`:

```
<Directory "/Applications/XAMPP/xamppfiles/htdocs">
    AllowOverride All
</Directory>
```

---

## 3. Obtener el proyecto

### Opción A — Clonar repositorio

```bash
cd /Applications/XAMPP/xamppfiles/htdocs
git clone <repo-url> pos_multisucursal
cd pos_multisucursal
```

### Opción B — Copia local

Colocar la carpeta en `htdocs` (XAMPP) o equivalente del servidor.

---

## 4. Instalar dependencias

```bash
composer install
```

Esto descargará a `vendor/`:

- `picqer/php-barcode-generator`
- `zendframework/zend-barcode`

---

## 5. Crear la base de datos

### 5.1 Crear esquema

```sql
CREATE DATABASE pos_multisucursal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

### 5.2 Importar estructura

Importar el script SQL más reciente disponible en el repositorio (carpeta de scripts SQL). Vía CLI:

```bash
mysql -u root -p pos_multisucursal < ruta/al/script.sql
```

O vía **phpMyAdmin** → seleccionar la base → "Importar".

### 5.3 (Opcional) Datos iniciales

Si se cuenta con un dump de datos de prueba/demo, importarlo a continuación. **Nunca** importar datos de producción a un ambiente local sin sanitizar.

---

## 6. Configuración del proyecto

### 6.1 Base de datos — `application/config/database.php`

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'pos_multisucursal',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    ...
);
```

> En producción usar un usuario MySQL dedicado con permisos limitados a esta base.

### 6.2 URL base — `application/config/config.php`

```php
$config['base_url'] = 'http://localhost/pos_multisucursal/';
```

En producción:

```php
$config['base_url'] = 'https://pos.midominio.com/';
```

### 6.3 Ambiente — `index.php`

```php
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```

Para producción establecer:

```php
define('ENVIRONMENT', 'production');
```

Esto desactiva la salida de errores detallados.

### 6.4 SMTP (correo) — `application/config/constants.php`

Crear o ajustar las constantes:

```php
define('PROTOCOL', 'smtp');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'cuenta@dominio.com');
define('SMTP_PASS', 'app-password');
define('EMAIL_FROM', 'cuenta@dominio.com');
define('FROM_NAME', 'POS Multisucursal');
```

> Para Gmail usar **app password**, no la contraseña real.

### 6.5 Acceso de emergencia — `application/config/emergency.php`

```php
$config['emergency_token_hash'] = '$2y$10$....'; // password_hash('mi-token-seguro', PASSWORD_DEFAULT)
$config['emergency_ttl']        = 1800;          // 30 minutos
```

Generar el hash:

```bash
php -r "echo password_hash('mi-token-seguro', PASSWORD_DEFAULT);"
```

### 6.6 CSRF (recomendado activar)

En `application/config/config.php`:

```php
$config['csrf_protection']  = TRUE;
$config['csrf_token_name']  = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
$config['csrf_expire']      = 7200;
$config['csrf_regenerate']  = TRUE;
```

---

## 7. Permisos de archivos

```bash
chmod -R 775 uploads/
chmod -R 775 application/logs/   # si existe
chown -R www-data:www-data .     # en Linux con Apache www-data
```

En macOS/XAMPP:

```bash
chmod -R 775 uploads/
```

---

## 8. Verificación

### 8.1 Acceso

Abrir el navegador en:

```
http://localhost/pos_multisucursal/
```

Debe aparecer la pantalla de login.

### 8.2 Credenciales iniciales

Crear usuario admin si el script SQL no lo incluye. Insertar manualmente:

```sql
INSERT INTO tbl_users (email, password, name, roleId, isAdmin, id_sucursal, createdDtm)
VALUES (
    'admin@local',
    -- hash bcrypt de 'admin123'
    '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxx',
    'Administrador',
    1, 1, 1, NOW()
);
```

Generar el hash:

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

### 8.3 Verificación funcional

1. Login con admin.
2. Crear una **sucursal** si no existe.
3. Crear un **rol** con permisos totales.
4. Crear **categoría** y **producto** de prueba.
5. Verificar **stock por sucursal** en `tbl_producto_stock`.
6. Abrir **caja** y realizar una **venta de prueba**.

---

## 9. Impresoras Zebra (opcional)

Si el entorno usa impresoras Zebra para etiquetas/tickets:

1. Configurar en `application/config/zebra_printers.php`.
2. Seguir [Doc/manual_impresoras_zebra.md](manual_impresoras_zebra.md).
3. Verificar setup operativo en [ZEBRA_SETUP.md](../ZEBRA_SETUP.md).

---

## 10. Despliegue a producción

### 10.1 Checklist pre-producción

- [ ] `ENVIRONMENT = 'production'` en `index.php`.
- [ ] `base_url` con HTTPS.
- [ ] Usuario MySQL dedicado con permisos mínimos.
- [ ] Backups automáticos de BD configurados.
- [ ] SMTP funcional probado.
- [ ] CSRF activado.
- [ ] `display_errors = Off` en PHP.
- [ ] Logs rotando (no en disco ilimitado).
- [ ] HTTPS forzado vía `.htaccess` o vhost.
- [ ] `uploads/` fuera del docroot público o con `.htaccess` que deniegue ejecución PHP.
- [ ] Cambiar contraseña del admin inicial.
- [ ] Acceso de emergencia con token fuerte y TTL corto.

### 10.2 Deshabilitar ejecución PHP en `uploads/`

Crear `uploads/.htaccess`:

```apache
<FilesMatch "\.(php|phtml|php3|php4|php5|php7|phar)$">
    Require all denied
</FilesMatch>
```

### 10.3 HTTPS forzado (`.htaccess` raíz)

```apache
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 11. Solución de problemas frecuentes

| Síntoma | Causa probable | Solución |
|---------|----------------|----------|
| Página en blanco | `display_errors` desactivado y error PHP | Revisar logs Apache/PHP, activar errores en desarrollo |
| "Database error" al login | `database.php` mal configurado | Verificar credenciales, base existe, usuario tiene permisos |
| 404 en rutas amigables | `mod_rewrite` no habilitado o `AllowOverride None` | Habilitar `mod_rewrite` y permitir `.htaccess` |
| Sesión se cierra inmediatamente | Cookie de sesión bloqueada / dominio incorrecto | Verificar `base_url`, cookies en navegador |
| No envía correos | SMTP no configurado o credenciales inválidas | Revisar constantes SMTP en `constants.php` |
| Subida de imagen falla | Permisos en `uploads/` | `chmod 775 uploads/` |
| "Access denied" tras crear rol | Matriz de permisos vacía | Asignar permisos al rol en `tbl_access_matrix` |
| Stock incorrecto tras traslado | Transacción incompleta | Revisar logs, verificar `tbl_producto_stock` por sucursal |

---

## 12. Backups

Backup de BD:

```bash
mysqldump -u root -p pos_multisucursal > backup_$(date +%Y%m%d_%H%M).sql
```

Backup de archivos (incluye `uploads/`):

```bash
tar -czf pos_files_$(date +%Y%m%d).tar.gz \
    application/config \
    application/views \
    uploads/
```

> En producción, automatizar con cron y enviar a almacenamiento externo.

---

## 13. Referencias

- [README principal](../README.md)
- [Arquitectura](arquitectura.md)
- [Seguridad](seguridad.md)
