# POS Multisucursal

Sistema web de punto de venta multisucursal desarrollado en PHP sobre CodeIgniter 3. El proyecto permite operar varias sucursales con inventario separado por ubicación, ventas, compras, caja, traslados, reportes y administración de usuarios con roles.

## Funcionalidades principales

- Login de usuarios con selección de sucursal
- Administración de usuarios, roles y matriz de acceso
- Gestión de sucursales
- Catálogos de productos, categorías, clientes, proveedores y métodos de pago
- Control de stock por sucursal
- Ventas de contado y crédito
- Registro de cuotas/abonos
- **Apartados y ventas a plazos**
- Compras y entradas de inventario
- Apertura y cierre de caja
- Ingresos y gastos
- Traslados de inventario entre sucursales
- Reportes operativos por sucursal con múltiples opciones de análisis
- Reportes administrativos y consolidados con visualización mejorada
- Dashboard dinámico con gráficas de ventas, ingresos y métricas de desempeño
- Información de reportes integrada en el dashboard
- Importación CSV en varios módulos
- Generación de tickets, PDFs y etiquetas con código de barras
- Validación y generación automática de códigos EAN-13

## Stack técnico real

- PHP
- CodeIgniter 3
- MySQL/MariaDB
- MySQLi
- AdminLTE
- Bootstrap
- jQuery
- TCPDF
- jsPDF
- SheetJS
- `picqer/php-barcode-generator`
- `zendframework/zend-barcode`

## Arquitectura

El proyecto sigue una arquitectura MVC clásica de CodeIgniter:

- `application/controllers`: flujo HTTP y lógica de interacción
- `application/models`: acceso a datos y lógica de persistencia
- `application/views`: vistas por módulo
- `application/libraries/BaseController.php`: autenticación, autorización, datos globales y renderizado base
- `application/helpers/cias_helper.php`: utilidades de contraseñas, correo, agente del navegador y flash messages

El sistema es multisucursal principalmente por:

- sesión con `id_sucursal`
- filtros por sucursal en ventas, compras, clientes, proveedores y métodos de pago
- tabla `tbl_producto_stock` para separar existencias por sucursal
- tabla `tbl_traslado` para movimientos entre sucursales

## Estructura real del proyecto

```text
pos_multisucursal/
├── application/
│   ├── config/
│   ├── controllers/
│   ├── helpers/
│   ├── libraries/
│   ├── models/
│   └── views/
├── assets/
│   ├── bower_components/
│   ├── dist/
│   ├── js/
│   ├── TCPDF-main/
│   ├── jsPDF-master/
│   ├── sheetjs/
│   └── src/
├── bd actual/
├── manual instalacion/
├── system/
├── uploads/
├── vendor/
├── composer.json
├── composer.lock
├── contexto.md
├── index.php
└── README.md
```

## Módulos principales del sistema

### Autenticación y administración

- `Login`: login, recuperación de contraseña y activación de nueva contraseña
- `User`: dashboard, usuarios, perfil, historial de acceso y cambio de contraseña
- `Roles`: roles y matriz de permisos

### Catálogos

- `Sucursal`
- `Categoria`
- `Producto`
- `Cliente`
- `Proveedor`
- `Metodo_pago`
- `Empleado`
- `Configuracion`

### Operación

- `Caja`: apertura, movimientos y cierre de caja
- `Carrito`: ventas, crédito, tickets y PDF
- `Apartados`: gestión de ventas a plazos y apartados
- `Entrada`: compras y entradas de inventario
- `Trasladar`: traslados entre sucursales
- `Ingreso`: registro de ingresos adicionales
- `Gasto`: registro de gastos operacionales

### Reportes y Analytics

- `Dashboard`: panel con gráficas dinámicas, métricas de ventas e información de reportes
- `Reporte`: reportes operativos detallados de la sucursal activa con múltiples opciones de análisis
- `Reporte_administrador`: reportes administrativos consolidados por sucursal

## Modelos más importantes

- `User_model`
- `Login_model`
- `Role_model`
- `Producto_model` (incluye validación EAN-13)
- `Carrito_model`
- `Apartado_model` (nuevo: ventas a plazos)
- `Entrada_model`
- `Trasladar_model`
- `Reporte_model` (mejorado con más opciones de análisis)
- `Reporte_administrador_model`
- `Caja_model`

## Vistas

Las vistas están organizadas por módulo dentro de `application/views/`, por ejemplo:

- `users/`
- `carrito/`
- `compra/`
- `producto/`
- `traslado/`
- `reporte/`
- `reporte_administrador/`
- `includes/`

Los layouts globales están en:

- `application/views/includes/header.php`
- `application/views/includes/footer.php`

## Base de datos

El proyecto usa la base de datos:

- `pos_multisucursal`

Configuración actual observada en `application/config/database.php`:

- host: `localhost`
- usuario: `root`
- password: vacío
- driver: `mysqli`

Script SQL principal detectado:

- `bd actual/Only DB Structure.sql`

### Tablas principales

- `tbl_users`
- `tbl_roles`
- `tbl_access_matrix`
- `tbl_sucursal`
- `tbl_producto`
- `tbl_producto_stock`
- `tbl_cliente`
- `tbl_proveedor`
- `tbl_metodo_pago`
- `tbl_venta`
- `tbl_detalle_venta`
- `tbl_cuota`
- `tbl_apartado` (nuevos en v2: ventas a plazos)
- `tbl_detalle_apartado` (nuevos en v2)
- `tbl_compra`
- `tbl_detalle_compra`
- `tbl_caja`
- `tbl_traslado`
- `tbl_detalle_traslado`
- `tbl_ingreso`
- `tbl_gasto`
- `tbl_last_login`
- `tbl_reset_password`
- `tbl_configuracion`

## Requisitos

- PHP 5.6 o superior
- Recomendable PHP 7.2+
- Apache
- mod_rewrite habilitado
- MySQL o MariaDB
- Extensiones PHP para MySQLi, OpenSSL, MBstring y JSON

## Instalación local

### 1. Colocar el proyecto en el servidor local

Ejemplo actual:

`/Applications/XAMPP/xamppfiles/htdocs/pos_multisucursal`

### 2. Crear la base de datos

Crea una base de datos llamada:

`pos_multisucursal`

### 3. Importar el SQL

Importa:

`bd actual/Only DB Structure.sql`

Si estás trabajando con otra versión de datos, revisa también:

`bd actual/pos_multisucursal_code (3).sql`

### 4. Revisar configuración de base de datos

Archivo:

`application/config/database.php`

Valores actuales:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'pos_multisucursal',
'dbdriver' => 'mysqli',
```

### 5. Revisar URL base

Archivo:

`application/config/config.php`

Valor actual:

```php
$config['base_url'] = 'http://localhost/pos_multisucursal/';
```

### 6. Verificar permisos de carga

La ruta de subida configurada es:

```php
$config['upload_path'] = './uploads/';
```

Asegúrate de que `uploads/` tenga permisos de escritura.

### 7. Acceder al sistema

URL local esperada:

[http://localhost/pos_multisucursal/](http://localhost/pos_multisucursal/)

## Rutas importantes

Configuradas en `application/config/routes.php`:

- controlador por defecto: `login`
- dashboard: `/dashboard`
- login: `/loginMe`
- logout: `/logout`
- usuarios: `/userListing`
- roles: `/roleListing`
- recuperación de contraseña: `/forgotPassword`

## Dependencias Composer

El proyecto incluye en `composer.json`:

```json
{
  "require": {
    "picqer/php-barcode-generator": "^0.1",
    "zendframework/zend-barcode": "^2.8"
  }
}
```

Si necesitas reinstalar dependencias:

```bash
composer install
```

## Mejoras recientes (v2.x)

El sistema ha evolucionado con las siguientes mejoras:

- **Dashboard dinámico**: Panel interactivo con gráficas de ventas, ingresos y métricas de desempeño
- **Módulo de Apartados**: Nuevo módulo para gestionar ventas a plazos independientes del sistema de crédito tradicional
- **Reportes mejorados**: Estructura rediseñada con más opciones de análisis y mejor visualización de datos
- **Información de reportes en dashboard**: Los reportes se integran directamente en el panel principal
- **Validación EAN-13**: Generación automática y validación de códigos de barras estándar en la importación de productos
- **Mejoras en etiquetado**: Impresión optimizada de etiquetas con códigos de barras
- **Validación mejorada de roles**: Sistema de permisos más robusto y consistente

## Consideraciones técnicas importantes

Antes de trabajar en el proyecto conviene tener presentes estos puntos:

- El sistema depende fuertemente de `id_sucursal` en sesión.
- Hay bastante lógica de negocio en controladores, especialmente en `Carrito`, `Entrada`, `Apartados` y `Trasladar`.
- El manejo de permisos está centralizado en `BaseController` y `tbl_access_matrix`.
- Los códigos de barras EAN-13 se validan y generan automáticamente en el módulo de importación de productos.
- El dashboard incluye gráficas dinámicas que requieren datos apropiados en los reportes.
- El módulo de apartados gestiona ventas a plazos con seguimiento de cuotas independiente.
- El README anterior incluía una sección de API REST que no corresponde al código real detectado.

## Hallazgos e inconsistencias detectadas

Se identificaron algunos puntos que conviene revisar:

- `application/controllers/Configuracion.php` carga `Configuracion_model`, pero no se encontró `application/models/Configuracion_model.php`.
- Existe una inconsistencia en el manejo del 404:
  - ruta configurada: `error_404`
  - archivo detectado: `application/controllers/Error_404`
  - clase encontrada: `Errorr_404`
- No se localizaron en `application/config` algunas constantes usadas por el sistema, por ejemplo:
  - `SYSTEM_ADMIN`
  - `SEGMENT`
  - `PROTOCOL`
  - `SMTP_HOST`
  - `SMTP_PORT`
  - `SMTP_USER`
  - `SMTP_PASS`
  - `EMAIL_FROM`
  - `FROM_NAME`

Esto sugiere que puede haber archivos locales no versionados o piezas faltantes en el repositorio.

## Archivos recomendados para onboarding

Si vas a mantener o extender el sistema, empieza por:

- `index.php`
- `application/config/config.php`
- `application/config/routes.php`
- `application/config/autoload.php`
- `application/config/database.php`
- `application/libraries/BaseController.php`
- `application/helpers/cias_helper.php`
- `application/controllers/Login.php`
- `application/controllers/User.php`
- `application/controllers/Dashboard.php` (incluye gráficas dinámicas)
- `application/controllers/Carrito.php`
- `application/models/Carrito_model.php`
- `application/controllers/Apartados.php` (nuevo)
- `application/models/Apartado_model.php` (nuevo)
- `application/controllers/Entrada.php`
- `application/models/Entrada_model.php`
- `application/controllers/Trasladar.php`
- `application/models/Trasladar_model.php`
- `application/controllers/Producto.php`
- `application/models/Producto_model.php` (con validación EAN-13)
- `application/controllers/Reporte.php` (mejorado)
- `application/models/Reporte_model.php` (mejorado)
- `bd actual/Only DB Structure.sql`

## Documentación adicional

- documentación técnica extendida: [contexto.md](/Applications/XAMPP/xamppfiles/htdocs/pos_multisucursal/contexto.md)
- manual adicional en repositorio: `manual instalacion/manual.docx`

## Licencia

Por Revisar:
