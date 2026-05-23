# POS Multisucursal

Sistema web de Punto de Venta multisucursal construido sobre **PHP + CodeIgniter 3**, orientado a la operación simultánea de múltiples sucursales con inventario, caja, ventas y reportes independientes por ubicación, además de una capa administrativa consolidada.

> **Estado:** Producción interna. Rama de desarrollo activa: `Moludo_Caja`.

---

## Tabla de contenido

1. [Descripción general](#1-descripción-general)
2. [Stack tecnológico](#2-stack-tecnológico)
3. [Arquitectura](#3-arquitectura)
4. [Estructura del repositorio](#4-estructura-del-repositorio)
5. [Módulos funcionales](#5-módulos-funcionales)
6. [Modelo de datos](#6-modelo-de-datos)
7. [Seguridad y control de acceso](#7-seguridad-y-control-de-acceso)
8. [Requisitos](#8-requisitos)
9. [Instalación](#9-instalación)
10. [Configuración](#10-configuración)
11. [Rutas relevantes](#11-rutas-relevantes)
12. [Convenciones de desarrollo](#12-convenciones-de-desarrollo)
13. [Documentación complementaria](#13-documentación-complementaria)

---

## 1. Descripción general

El sistema permite que una organización con varias sucursales gestione su operación diaria desde una sola plataforma:

- Cada sucursal opera con su propio stock, caja, ventas y movimientos.
- La capa de administración consolida la información de todas las sucursales.
- El acceso al sistema se realiza con autenticación de usuario + selección de sucursal activa.
- Los permisos son granulares por módulo, controlados mediante una matriz de acceso por rol.

### Capacidades principales

| Área | Capacidades |
|------|-------------|
| Ventas | Contado, crédito, abonos/cuotas, tickets, PDF, generación de etiquetas |
| Inventario | Stock por sucursal, entradas (compras), traslados entre sucursales, movimientos |
| Caja | Apertura, movimientos, cierre, arqueo |
| Catálogos | Productos, categorías, clientes, proveedores, métodos de pago, empleados, sucursales |
| Reportes | Operativos por sucursal y administrativos consolidados |
| Administración | Usuarios, roles, matriz de permisos, configuración general |
| Periféricos | Generación de etiquetas y tickets ZPL para impresoras Zebra |
| Seguridad | Hash de contraseñas, recuperación por correo, historial de accesos, acceso de emergencia |

---

## 2. Stack tecnológico

**Backend**
- PHP 7.4+ (compatible con 5.6, recomendado 7.2+)
- CodeIgniter 3.x (MVC)
- MySQL / MariaDB (driver `mysqli`)
- Composer para dependencias externas

**Frontend**
- AdminLTE 2 + Bootstrap 3
- jQuery
- DataTables, Select2, Chart.js (vía AdminLTE)

**Librerías relevantes (Composer)**
- `picqer/php-barcode-generator` — generación de códigos de barras
- `zendframework/zend-barcode` — soporte adicional de barcode

**Librerías frontend embebidas (`assets/`)**
- `TCPDF` — generación de PDFs en servidor
- `jsPDF` — generación de PDFs en cliente
- `SheetJS` — importación / exportación Excel-CSV

**Periféricos**
- Impresoras Zebra (ZPL) — ver `ZEBRA_SETUP.md` y `Doc/manual_impresoras_zebra.md`

---

## 3. Arquitectura

Patrón **MVC** estándar de CodeIgniter 3, con una capa transversal de autenticación y autorización:

```
HTTP Request
     │
     ▼
[ index.php ]  →  Router (routes.php)
     │
     ▼
[ Controller ]  ──extends──►  BaseController  ──►  ModuleAccess
     │                              │
     │                              └── Valida sesión + matriz de permisos
     ▼
[ Model ]  ──►  MySQL (pos_multisucursal)
     │
     ▼
[ View ]  ──►  Layout AdminLTE (includes/header + footer)
```

**Piezas transversales clave**

- `application/libraries/BaseController.php`: clase base que todo controlador del sistema debe extender. Centraliza:
  - validación de sesión
  - carga de datos globales (usuario activo, sucursal activa)
  - render con layout (header + contenido + footer)
- `application/libraries/ModuleAccess.php`: control de permisos por módulo/acción, leyendo de `tbl_access_matrix`.
- `application/helpers/cias_helper.php`: hashing de contraseñas, envío de correo (SMTP), helpers de UA y flash messages.

**Multisucursalidad**

La separación por sucursal se implementa a nivel de datos y sesión:

- Variable `id_sucursal` persiste en la sesión tras el login.
- `tbl_producto_stock` mantiene existencias por par `(id_producto, id_sucursal)`.
- Las consultas operativas (ventas, caja, traslados, reportes) filtran por la sucursal activa.
- Los traslados (`tbl_traslado` / `tbl_detalle_traslado`) son el único mecanismo válido para mover stock entre sucursales.

---

## 4. Estructura del repositorio

```
pos_multisucursal/
├── application/
│   ├── config/            # config.php, database.php, routes.php, modules.php, reports.php, zebra_printers.php
│   ├── controllers/       # Capa HTTP, una clase por módulo (ver §5)
│   ├── models/            # Acceso a datos por módulo
│   ├── views/             # Vistas agrupadas por módulo + includes globales
│   ├── helpers/           # cias_helper.php
│   └── libraries/         # BaseController.php, ModuleAccess.php
├── assets/                # Frontend estático (AdminLTE, JS, CSS, jsPDF, TCPDF, SheetJS)
├── system/                # Núcleo de CodeIgniter 3 (no modificar)
├── vendor/                # Dependencias Composer
├── uploads/               # Archivos subidos por usuarios (requiere escritura)
├── Doc/                   # Documentación técnica del proyecto
├── composer.json
├── index.php              # Front controller
├── ZEBRA_SETUP.md         # Setup de impresoras Zebra
├── specs.md               # Especificaciones funcionales
└── README.md
```

---

## 5. Módulos funcionales

Cada módulo está representado por un controlador, su modelo y su carpeta de vistas.

### Autenticación y administración

| Controlador | Responsabilidad |
|-------------|-----------------|
| `Login` | Login, recuperación de contraseña, activación de cuenta |
| `Emergency` | Acceso de emergencia controlado (`acceso-emergencia/...`) |
| `User` | Dashboard, gestión de usuarios, perfil, historial de accesos |
| `Roles` | Roles y matriz de permisos |
| `Configuracion` | Parámetros generales del sistema |

### Catálogos

| Controlador | Responsabilidad |
|-------------|-----------------|
| `Sucursal` | ABM de sucursales |
| `Categoria` | ABM de categorías de productos |
| `Producto` | ABM de productos, importación CSV, generación/validación EAN-13, etiquetas |
| `Cliente` | ABM de clientes |
| `Proveedor` | ABM de proveedores |
| `Metodo_pago` | ABM de métodos de pago |
| `Empleado` | ABM de empleados |

### Operación de sucursal

| Controlador | Responsabilidad |
|-------------|-----------------|
| `Caja` | Apertura, movimientos y cierre/arqueo de caja |
| `Carrito` | Venta contado/crédito, abonos, ticket, PDF |
| `Entrada` | Compras y entradas de inventario |
| `Trasladar` | Generación y envío de traslados entre sucursales |
| `Transferencia_inventario` | Recepción y vista de traslados |
| `Ingreso` | Ingresos adicionales a caja |
| `Gasto` | Gastos operativos |
| `Zebra` | Generación de etiquetas/tickets ZPL para impresoras Zebra |

### Reportes

| Controlador | Responsabilidad |
|-------------|-----------------|
| `Reporte` | Reportes operativos de la sucursal activa |
| `Reporte_administrador` | Reportes consolidados multi-sucursal (rol administrador) |

> El dashboard se sirve desde `User::dashboard` (ruta `/dashboard`), no desde un controlador `Dashboard` independiente.

---

## 6. Modelo de datos

Base de datos: **`pos_multisucursal`** (MySQL/MariaDB, motor InnoDB).

### Tablas principales

**Seguridad y administración**
- `tbl_users` — usuarios del sistema
- `tbl_roles` — roles definidos
- `tbl_access_matrix` — matriz de permisos rol × módulo × acción
- `tbl_last_login` — historial de accesos
- `tbl_reset_password` — tokens de recuperación
- `tbl_configuracion` — parámetros generales

**Multisucursal y catálogos**
- `tbl_sucursal`
- `tbl_categoria`
- `tbl_producto`
- `tbl_producto_stock` — **existencias por sucursal**
- `tbl_cliente`, `tbl_proveedor`, `tbl_metodo_pago`, `tbl_empleado`

**Operación**
- `tbl_venta` / `tbl_detalle_venta` — ventas y detalle
- `tbl_cuota` — abonos / cuotas de crédito
- `tbl_compra` / `tbl_detalle_compra` — entradas de inventario
- `tbl_traslado` / `tbl_detalle_traslado` — movimientos entre sucursales
- `tbl_caja` — sesiones de caja (apertura/cierre)
- `tbl_ingreso`, `tbl_gasto` — movimientos de caja no asociados a venta
- `tbl_movimiento_inventario` — bitácora de cambios de stock

### Reglas de integridad clave

- Toda operación que altere stock debe registrar el movimiento en `tbl_movimiento_inventario`.
- El stock no se modifica directamente sobre `tbl_producto`; se opera sobre `tbl_producto_stock` por sucursal.
- Las ventas y compras deben ejecutarse dentro de una **transacción** (`$this->db->trans_start/complete`) para mantener consistencia entre cabecera, detalle y stock.

---

## 7. Seguridad y control de acceso

- **Autenticación:** `Login` valida credenciales contra `tbl_users` (hash en `cias_helper`). La sesión almacena `id_user`, `id_role`, `id_sucursal`.
- **Autorización:** cada acción se valida en `BaseController` / `ModuleAccess` contra `tbl_access_matrix`. El acceso no autorizado redirige al dashboard con flash de error.
- **Recuperación de contraseña:** flujo de token vía `tbl_reset_password` + correo SMTP (configurable en `application/config/`).
- **Acceso de emergencia:** rutas `acceso-emergencia/*` administradas por el controlador `Emergency` para bypass controlado en escenarios críticos. Usar con auditoría.
- **Buenas prácticas obligatorias** al extender el sistema:
  - Usar Active Record / queries parametrizadas (jamás concatenar input en SQL).
  - Validar `id_sucursal` de la sesión en todo módulo operativo.
  - Validar permisos antes de ejecutar cualquier acción mutante.

---

## 8. Requisitos

| Componente | Versión mínima | Recomendado |
|------------|----------------|-------------|
| PHP        | 5.6            | 7.4 |
| MySQL      | 5.6            | 5.7 / MariaDB 10.4+ |
| Apache     | 2.4            | 2.4 con `mod_rewrite` |
| Composer   | 2.x            | — |
| Extensiones PHP | `mysqli`, `mbstring`, `openssl`, `json`, `gd` | |

Entorno de referencia: **XAMPP** en macOS, ruta `/Applications/XAMPP/xamppfiles/htdocs/pos_multisucursal`.

---

## 9. Instalación

### 9.1 Clonar el proyecto

```bash
git clone <repo-url> pos_multisucursal
cd pos_multisucursal
```

Colocarlo en el `htdocs` del servidor local (XAMPP/MAMP/Apache).

### 9.2 Instalar dependencias

```bash
composer install
```

### 9.3 Crear la base de datos

```sql
CREATE DATABASE pos_multisucursal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importar el script de estructura más reciente disponible en el repositorio (carpeta de scripts SQL versionados internamente).

### 9.4 Configurar conexión

Editar `application/config/database.php`:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'pos_multisucursal',
'dbdriver' => 'mysqli',
```

### 9.5 Configurar URL base

Editar `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/pos_multisucursal/';
```

### 9.6 Permisos de escritura

```bash
chmod -R 775 uploads/
```

### 9.7 Acceso

Abrir: [http://localhost/pos_multisucursal/](http://localhost/pos_multisucursal/)

---

## 10. Configuración

Archivos de configuración relevantes en `application/config/`:

| Archivo | Propósito |
|---------|-----------|
| `config.php` | `base_url`, encriptación, sesión, upload |
| `database.php` | Conexión a MySQL |
| `routes.php` | Ruteo HTTP |
| `autoload.php` | Carga automática (`database`, `session`, helpers `url`, `file`, `cias_helper`) |
| `constants.php` | Constantes del sistema |
| `modules.php` | Catálogo interno de módulos para la matriz de permisos |
| `reports.php` | Definición de reportes disponibles |
| `zebra_printers.php` | Configuración de impresoras Zebra |
| `emergency.php` | Configuración del módulo de acceso de emergencia |

### Variables de entorno SMTP

El envío de correo (recuperación de contraseña, notificaciones) requiere constantes SMTP definidas a nivel de configuración (`SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`, `EMAIL_FROM`, `FROM_NAME`). Si no están presentes en el repositorio, deben crearse en `application/config/constants.php` o en un archivo local de configuración no versionado.

---

## 11. Rutas relevantes

Definidas en `application/config/routes.php`:

| URL | Destino |
|-----|---------|
| `/` | `login` (default) |
| `/loginMe` | `login/loginMe` |
| `/dashboard` | `user/dashboard` |
| `/logout` | `user/logout` |
| `/userListing` | `user/userListing` |
| `/profile` | `user/profile` |
| `/roleListing` | `roles/roleListing` |
| `/forgotPassword` | `login/forgotPassword` |
| `/resetPasswordConfirmUser/{token}` | `login/resetPasswordConfirmUser/$1` |
| `/acceso-emergencia/{token}` | `emergency/access/$1` |

El resto de URLs sigue el patrón estándar de CodeIgniter: `controlador/metodo/parametros`.

---

## 12. Convenciones de desarrollo

- **Controladores:** extienden `BaseController`. No instanciar lógica de negocio pesada en el controlador; delegarla al modelo.
- **Modelos:** un modelo por dominio. Las funciones deben recibir parámetros explícitos, no depender de superglobales.
- **Vistas:** organizadas por módulo (`views/<modulo>/`). Layout global mediante `includes/header.php` y `includes/footer.php`.
- **SQL:** usar **Active Record** o **bindings** en `$this->db->query()`. Nunca interpolar variables en SQL crudo.
- **Transacciones:** obligatorias en flujos multi-tabla (venta, compra, traslado).
- **Sucursal:** todo flujo operativo debe filtrar por `id_sucursal` de sesión y validar que el usuario tenga acceso a esa sucursal.
- **Permisos:** antes de ejecutar una acción mutante, verificar permiso vía `ModuleAccess`.
- **Frontend:** mantener AdminLTE + Bootstrap 3; no introducir frameworks JS adicionales sin revisión.
- **Commits:** usar prefijos `add:`, `fix:`, `update:`, `refactor:`, `doc:`.

---

## 13. Documentación complementaria

Documentación técnica adicional en la carpeta [`Doc/`](Doc/):

- [`Doc/manual_impresoras_zebra.md`](Doc/manual_impresoras_zebra.md) — configuración y uso de impresoras Zebra
- [`ZEBRA_SETUP.md`](ZEBRA_SETUP.md) — setup operativo de impresoras
- [`specs.md`](specs.md) — especificaciones funcionales

> Próximamente en `Doc/`: diagrama entidad-relación, descripción detallada de cada módulo, diccionario de datos, guía de despliegue y guía de troubleshooting.

---

## Licencia

Uso interno. Pendiente de definir licencia pública.
