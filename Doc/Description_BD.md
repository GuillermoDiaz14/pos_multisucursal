# 📚 Descripción de la Base de Datos - POS Multisucursal

## 🎯 Propósito

La base de datos **pos_multisucursal** es un sistema de gestión de punto de venta (POS) diseñado para operar múltiples sucursales con inventario, usuarios, ventas, compras y reportes independientes por ubicación. Utiliza **MariaDB 10.4.27** y está optimizada para aplicaciones CodeIgniter 3.

---

## 📋 Índice

1. [Especificaciones Técnicas](#especificaciones-técnicas)
2. [Módulos Principales](#módulos-principales)
3. [Descripción de Tablas](#descripción-de-tablas)
4. [Relaciones y Flujos](#relaciones-y-flujos)
5. [Características de Seguridad](#características-de-seguridad)
6. [Integridad de Datos](#integridad-de-datos)
7. [Mejoras Realizadas](#mejoras-realizadas)
8. [Consideraciones de Producción](#consideraciones-de-producción)

---

## 🔧 Especificaciones Técnicas

### Motor de Base de Datos
- **SGBD**: MariaDB 10.4.27
- **Charset**: utf8mb4 (soporte para caracteres especiales)
- **Collation**: utf8mb4_general_ci (case-insensitive)
- **Engine**: InnoDB (transacciones, integridad referencial)

### Configuración de Desarrollo
```
Host: localhost
Usuario: root
Contraseña: vacía (desarrollo local)
Puerto: 3306
Base de datos: pos_multisucursal
```

### Tamaño y Escala
- **Tablas**: 27 tablas
- **Registros típicos**: 10,000-100,000 registros por tabla en producción
- **Crecimiento anual estimado**: 5-10% según volumen de transacciones
- **Backups recomendados**: Diarios en producción

---

## 📦 Módulos Principales

### 1. 🔐 Autenticación y Control de Acceso
**Tablas**: `tbl_users`, `tbl_roles`, `tbl_access_matrix`, `tbl_last_login`, `tbl_reset_password`

Gestiona:
- Usuarios del sistema con roles asignados
- Permisos por rol (matriz de acceso JSON)
- Historial de acceso y intentos de login
- Reset de contraseñas

**Características**:
- Roles definibles (Administrador, Vendedor, Gerente, etc.)
- Matriz de permisos por rol (módulos y acciones)
- Auditoría de acceso por usuario

**Mejoras v2.0**:
- roleId ampliado de tinyint(4) a smallint(6) para soportar más roles

---

### 2. 🏪 Catálogos Maestros
**Tablas**: `tbl_sucursal`, `tbl_producto`, `tbl_categoria`, `tbl_cliente`, `tbl_proveedor`, `tbl_metodo_pago`, `tbl_empleado`, `tbl_configuracion`

#### tbl_sucursal
- ID única: `id_sucursal`
- Nombre y dirección
- Base para segregación de datos (multisucursal)
- Todos los registros operacionales están ligados por `id_sucursal`

#### tbl_producto
- ID única: `id_producto`
- Código de producto (debe ser único)
- **Precios**: `precio_compra`, `precio_venta` (DECIMAL(10,2) tras corrección)
- Categoría asignada
- Imagen y detalles
- Global para todas las sucursales

#### tbl_categoria
- Categorización de productos
- Relación 1:N con tbl_producto

#### tbl_cliente
- Datos del cliente: nombre, email, documento identidad, celular
- Vinculado a sucursal (`id_sucursal`)
- Usado en ventas de crédito

#### tbl_proveedor
- Datos del proveedor: nombre, email, teléfono, documento
- Vinculado a sucursal
- Usado en compras

#### tbl_metodo_pago
- Métodos disponibles: Efectivo, Tarjeta, Cheque, etc.
- Vinculado a sucursal

#### tbl_empleado
- Datos del empleado: nombre, puesto
- Vinculado a sucursal
- *Nota: Desconectado de tbl_users (relación débil)*

#### tbl_configuracion
- Configuración global: nombre empresa, impuesto, símbolo moneda
- Registro único del sistema

---

### 3. 📊 Inventario y Stock
**Tablas**: `tbl_producto_stock`, `tbl_traslado`, `tbl_detalle_traslado`

#### tbl_producto_stock
- Stock actual por producto y sucursal
- Clave compuesta: `(id_producto, id_sucursal)`
- **Índice UNIQUE agregado** (v2.0) para prevenir duplicados
- Se actualiza con cada compra y venta

#### tbl_traslado
- Movimientos de inventario entre sucursales
- Campos: sucursal origen, sucursal destino, usuario, estado
- Auditoría completa de movimientos

#### tbl_detalle_traslado
- Detalles de cada traslado (productos y cantidades)
- Relación 1:N con tbl_traslado

**Flujo de Stock**:
```
Compra → tbl_compra → tbl_detalle_compra → tbl_producto_stock ↑
Venta  → tbl_venta   → tbl_detalle_venta → tbl_producto_stock ↓
Traslado → tbl_traslado → tbl_detalle_traslado → actualiza ambas sucursales
```

---

### 4. 💰 Ventas
**Tablas**: `tbl_venta`, `tbl_detalle_venta`, `tbl_cuota`

#### tbl_venta
Registra cada transacción de venta con:
- **Cliente**: `id_cliente` (renombrado de `cliente` en v2.0)
- **Montos**: base_imponible, descuento, impuesto, total (DECIMAL(10,2))
- **Pago**: tipo_pago (contado/crédito/apartado), método, monto recibido, cambio
- **Sucursal**: `id_sucursal`
- **Vendedor**: `id_usuario`
- **Tipo**: normal o apartado (venta a plazos)
- **Estado**: para apartados (en_proceso, completado, cancelado)
- **Anticipo**: para apartados

#### Tipos de Venta
1. **Contado**: Pago inmediato, sin saldo
2. **Crédito**: Pago diferido, genera cuotas
3. **Apartado**: Venta a plazos con anticipo y cuotas

#### tbl_detalle_venta
- Productos vendidos: `id_producto`, `cantidad` (INT), `precio_venta` (DECIMAL)
- Subtotal automático calculado

#### tbl_cuota
- Cuotas generadas para ventas de crédito
- Monto y fecha de pago
- Relación con tbl_venta

**Validaciones**:
- No se puede vender sin stock
- Cantidad debe ser INT positivo
- Precios deben ser DECIMAL válidos
- Cliente requerido en crédito/apartado

---

### 5. 🛒 Compras
**Tablas**: `tbl_compra`, `tbl_detalle_compra`

#### tbl_compra
- Registro de compras a proveedores
- Campos: fecha, proveedor, total (DECIMAL), usuario, sucursal
- Nota: descripción opcional

#### tbl_detalle_compra
- Detalle de productos comprados
- `cantidad` (INT), `precio_compra` (DECIMAL)
- Se actualiza tbl_producto_stock al confirmar

**Validaciones**:
- Proveedor requerido
- Cantidad debe ser positiva
- Total calculado desde detalles

---

### 6. 💳 Caja
**Tablas**: `tbl_caja`, `tbl_ingreso`, `tbl_gasto`

#### tbl_caja
- Cajas abiertas/cerradas por sucursal
- **Auditoría**: `id_usuario` (v2.0) registra quién abre/cierra
- Estado: abierto/cerrado
- Saldo: suma de ingresos - gastos - ventas

#### tbl_ingreso
- Ingresos adicionales (no ventas): devoluciones, servicios, etc.
- Monto: DECIMAL(10,2)
- Por sucursal y fecha

#### tbl_gasto
- Gastos operacionales: servicios, mantenimiento, etc.
- Monto: DECIMAL(10,2)
- Por sucursal y fecha

**Flujo de Caja**:
```
Caja Abierta
├── Ventas → Ingresos
├── Devoluciones → Ingresos adicionales
├── Gastos operacionales
└── Cierre → Saldo final
```

---

### 7. 📈 Sesiones (CodeIgniter)
**Tablas**: `ci_sessions`

- Gestiona sesiones de usuario
- ID de sesión, IP, user agent
- Datos de sesión (JSON)
- Índice en last_activity para limpieza automática

---

## 📊 Descripción Detallada de Tablas

### A. Tablas de Usuarios (3 tablas)

#### `tbl_users` - Usuarios del Sistema
```sql
CREATE TABLE tbl_users (
    userId INT PK,
    email VARCHAR(200) UNIQUE,
    password VARCHAR(200),
    name VARCHAR(200),
    mobile VARCHAR(20),
    isAdmin TINYINT (0=regular, 1=admin),
    id_sucursal INT FK,
    roleId SMALLINT FK,
    isDeleted TINYINT,
    createdDtm DATETIME,
    updatedDtm DATETIME
)
```
- **Registros típicos**: 5-50 usuarios
- **Búsquedas**: Por email, userId
- **Relaciones**: 1 rol, 1 sucursal

#### `tbl_roles` - Roles del Sistema
```sql
CREATE TABLE tbl_roles (
    roleId SMALLINT PK (AUTO_INCREMENT),
    role VARCHAR(50) UNIQUE,
    status TINYINT (0=inactivo, 1=activo),
    isDeleted TINYINT,
    createdBy INT,
    createdDtm DATETIME,
    updatedBy INT,
    updatedDtm DATETIME
)
```
- **Registros típicos**: 5-20 roles
- **Ejemplos**: Administrador, Vendedor, Gerente, Supervisor
- **v2.0 Change**: Ampliado de tinyint (máx 127) a smallint (máx 32,767)

#### `tbl_access_matrix` - Matriz de Permisos
```sql
CREATE TABLE tbl_access_matrix (
    id INT PK,
    roleId SMALLINT FK,
    access TEXT (JSON with permissions),
    isDeleted TINYINT,
    createdBy INT,
    createdDtm DATETIME
)
```
- **Estructura access**: JSON con módulos y permisos
- **Ejemplo**: `{"ventas":1, "reportes":1, "usuarios":0}`
- **Nota**: Se recomienda normalizar en tabla `tbl_role_permissions`

---

### B. Tablas de Catálogos (8 tablas)

#### `tbl_sucursal` - Sucursales
```sql
CREATE TABLE tbl_sucursal (
    id_sucursal INT PK,
    nombre_sucursal VARCHAR(200),
    direccion VARCHAR(200)
)
```
- **Registros típicos**: 2-10 sucursales
- **Clave para multisucursal**: Todos los datos operacionales incluyen `id_sucursal`

#### `tbl_producto` - Catálogo de Productos
```sql
CREATE TABLE tbl_producto (
    id_producto INT PK,
    nombre_producto VARCHAR(200),
    precio_compra DECIMAL(10,2) /* v2.0: changed from varchar */,
    precio_venta DECIMAL(10,2) /* v2.0: changed from varchar */,
    codigo VARCHAR(200) UNIQUE,
    categoria INT FK,
    imagen VARCHAR(200),
    detalles VARCHAR(200),
    talla VARCHAR(50)
)
```
- **Registros típicos**: 100-5,000 productos
- **Global**: Mismo catálogo para todas las sucursales
- **Stock**: Se gestiona en tbl_producto_stock (por sucursal)
- **v2.0 Changes**: Precios cambiados de VARCHAR a DECIMAL para operaciones aritméticas

#### `tbl_categoria` - Categorías de Productos
```sql
CREATE TABLE tbl_categoria (
    id_categoria INT PK,
    nombre_categoria VARCHAR(200)
)
```
- **Registros típicos**: 10-50 categorías
- **Relación**: 1:N con tbl_producto

#### `tbl_cliente` - Clientes
```sql
CREATE TABLE tbl_cliente (
    id_cliente INT PK,
    nombre VARCHAR(200),
    correo VARCHAR(200),
    doc_identidad VARCHAR(200),
    celular VARCHAR(100),
    id_sucursal INT FK
)
```
- **Registros típicos**: 50-1,000 por sucursal
- **Uso**: Ventas de crédito y apartados
- **Por sucursal**: Cada sucursal tiene sus propios clientes

#### `tbl_proveedor` - Proveedores
```sql
CREATE TABLE tbl_proveedor (
    id_proveedor INT PK,
    nombre VARCHAR(200),
    correo VARCHAR(200),
    telefono VARCHAR(20),
    doc_identidad VARCHAR(200),
    id_sucursal INT FK
)
```
- **Registros típicos**: 10-100 por sucursal
- **Uso**: Compras de inventario

#### `tbl_metodo_pago` - Métodos de Pago
```sql
CREATE TABLE tbl_metodo_pago (
    id_metodo_pago INT PK,
    metodo VARCHAR(200),
    id_sucursal INT FK
)
```
- **Registros típicos**: 3-5 por sucursal
- **Ejemplos**: Efectivo, Débito, Crédito, Cheque

#### `tbl_empleado` - Empleados
```sql
CREATE TABLE tbl_empleado (
    id_empleado INT PK,
    nombre VARCHAR(200),
    puesto VARCHAR(200),
    id_sucursal INT FK
)
```
- **Registros típicos**: 5-50 por sucursal
- **Nota**: Débilmente ligado a tbl_users (sin FK explícita)

#### `tbl_configuracion` - Configuración Global
```sql
CREATE TABLE tbl_configuracion (
    id_configuracion INT PK,
    nombre_empresa VARCHAR(200),
    telefono INT,
    impuesto FLOAT,
    simbolo_moneda VARCHAR(200)
)
```
- **Registros típicos**: 1 (global del sistema)
- **Uso**: En reportes y tickets

---

### C. Tablas de Inventario (3 tablas)

#### `tbl_producto_stock` - Stock Actual
```sql
CREATE TABLE tbl_producto_stock (
    id_producto_stock INT PK,
    id_producto INT FK,
    id_sucursal INT FK,
    stock INT /* v2.0: no longer float */,
    UNIQUE KEY unique_producto_sucursal (id_producto, id_sucursal) /* v2.0: added */
)
```
- **Registros típicos**: 100-5,000 (1 por producto por sucursal)
- **Clave única**: Un registro por (producto, sucursal)
- **Updates**: Con cada compra y venta
- **v2.0 Changes**: Índice UNIQUE para prevenir duplicados

#### `tbl_traslado` - Traslados Entre Sucursales
```sql
CREATE TABLE tbl_traslado (
    id_traslado INT PK,
    fecha_traslado DATE,
    sucursal_origen INT FK,
    sucursal_destino INT FK,
    id_usuario INT FK,
    estado VARCHAR(200)
)
```
- **Registros típicos**: 100-1,000 anuales
- **Auditoría**: Usuario que realiza, sucursales, fecha
- **Flujo**: origen ↓ stock, destino ↑ stock

#### `tbl_detalle_traslado` - Productos Trasladados
```sql
CREATE TABLE tbl_detalle_traslado (
    id_detalle_traslado INT PK,
    id_producto INT FK,
    cantidad INT,
    id_traslado INT FK
)
```
- **Relación 1:N**: Varios productos por traslado

---

### D. Tablas de Ventas (3 tablas)

#### `tbl_venta` - Registro de Ventas
```sql
CREATE TABLE tbl_venta (
    id_venta INT PK,
    fecha_venta DATE,
    id_cliente INT FK /* v2.0: renamed from "cliente" */,
    descuento DECIMAL(10,2) /* v2.0: changed from float */,
    base_imponible DECIMAL(10,2) /* v2.0: changed from float */,
    impuesto DECIMAL(10,2) /* v2.0: changed from float */,
    total DECIMAL(10,2) /* v2.0: changed from float */,
    id_usuario INT FK,
    tipo_pago VARCHAR(20) (contado/credito/apartado),
    id_metodo_pago INT FK,
    saldo DECIMAL(10,2),
    monto_recibido DECIMAL(10,2),
    cambio DECIMAL(10,2),
    id_sucursal INT FK,
    tipo_venta VARCHAR(20) (normal/apartado),
    estado_apartado VARCHAR(20),
    anticipo DECIMAL(10,2)
)
```
- **Registros típicos**: 1,000-50,000 anuales por sucursal
- **Campos críticos**: total, base_imponible (DECIMAL para precisión)
- **Tipos de venta**: Contado (inmediato), Crédito (cuotas), Apartado (anticipo + cuotas)
- **v2.0 Changes**: Campo `cliente` renombrado a `id_cliente`

#### `tbl_detalle_venta` - Productos Vendidos
```sql
CREATE TABLE tbl_detalle_venta (
    id_detalle_venta INT PK,
    id_producto INT FK,
    precio_venta DECIMAL(10,2) /* v2.0: changed from float */,
    cantidad INT /* v2.0: changed from float */,
    sub_total DECIMAL(10,2) /* v2.0: changed from float */,
    id_venta INT FK
)
```
- **Relación 1:N**: Múltiples productos por venta
- **Cálculo**: sub_total = cantidad × precio_venta
- **v2.0 Changes**: Todos los campos monetarios y cantidad con tipos precisos

#### `tbl_cuota` - Cuotas de Crédito
```sql
CREATE TABLE tbl_cuota (
    id_cuota INT PK,
    cuota DECIMAL(10,2) /* v2.0: changed from float */,
    fecha_pago DATE,
    id_venta INT FK
)
```
- **Relación 1:N**: Una venta puede tener múltiples cuotas
- **Uso**: Para ventas de crédito y apartados
- **v2.0 Changes**: Monto como DECIMAL

---

### E. Tablas de Compras (2 tablas)

#### `tbl_compra` - Registro de Compras
```sql
CREATE TABLE tbl_compra (
    id_compra INT PK,
    fecha_compra DATE,
    proveedor INT FK,
    nota VARCHAR(400),
    total DECIMAL(10,2) /* v2.0: changed from float */,
    id_usuario INT FK,
    id_sucursal INT FK
)
```
- **Registros típicos**: 100-2,000 anuales por sucursal
- **Total**: DECIMAL para precisión
- **Auditoría**: Usuario y fecha

#### `tbl_detalle_compra` - Productos Comprados
```sql
CREATE TABLE tbl_detalle_compra (
    id_detalle_compra INT PK,
    id_producto INT FK,
    precio_compra DECIMAL(10,2) /* v2.0: changed from float */,
    cantidad INT /* v2.0: changed from float */,
    sub_total DECIMAL(10,2) /* v2.0: changed from float */,
    id_compra INT FK
)
```
- **Relación 1:N**: Múltiples productos por compra
- **Actualiza**: tbl_producto_stock (incrementa stock)
- **v2.0 Changes**: Tipos precisos para dinero y cantidad

---

### F. Tablas de Caja (3 tablas)

#### `tbl_caja` - Cajas Abiertas/Cerradas
```sql
CREATE TABLE tbl_caja (
    id_caja INT PK,
    fecha_apertura DATE,
    fecha_cierre DATE,
    saldo FLOAT,
    estado VARCHAR(200) (abierto/cerrado),
    id_sucursal INT FK,
    id_usuario INT FK /* v2.0: added for audit trail */
)
```
- **Registros típicos**: 1-3 cajas activas por sucursal
- **Estado**: abierto (en operación), cerrado (finalizadas)
- **v2.0 Changes**: Agregado `id_usuario` para auditoría
- **Nota**: saldo aún es FLOAT, se recomienda cambiar a DECIMAL en próxima versión

#### `tbl_ingreso` - Ingresos Adicionales
```sql
CREATE TABLE tbl_ingreso (
    id_ingreso INT PK,
    descripcion VARCHAR(200),
    monto DECIMAL(10,2) /* v2.0: changed from float */,
    fecha DATE,
    id_sucursal INT FK
)
```
- **Registros típicos**: 5-100 anuales
- **Ejemplos**: Devoluciones, servicios, otros ingresos

#### `tbl_gasto` - Gastos Operacionales
```sql
CREATE TABLE tbl_gasto (
    id_gasto INT PK,
    descripcion VARCHAR(200),
    monto DECIMAL(10,2) /* v2.0: changed from float */,
    fecha DATE,
    id_sucursal INT FK
)
```
- **Registros típicos**: 10-100 anuales
- **Ejemplos**: Servicios, mantenimiento, gastos operacionales

---

### G. Tablas de Seguridad (2 tablas)

#### `tbl_last_login` - Historial de Acceso
```sql
CREATE TABLE tbl_last_login (
    id INT PK,
    userId INT FK,
    login_time DATETIME
)
```
- **Propósito**: Auditoría y detección de accesos
- **Registros típicos**: Crece indefinidamente (se recomienda purgar)

#### `tbl_reset_password` - Reset de Contraseña
```sql
CREATE TABLE tbl_reset_password (
    id INT PK,
    userId INT FK,
    reset_token VARCHAR(200),
    created_at DATETIME,
    expires_at DATETIME,
    is_used TINYINT
)
```
- **Propósito**: Flujo seguro de reset de contraseña
- **Token**: Único y con expiración

---

## 🔄 Relaciones y Flujos

### Flujo de Venta
```
1. Usuario abre sesión (tbl_users)
2. Selecciona cliente (tbl_cliente)
3. Agrega productos (tbl_producto)
4. Genera tbl_venta + tbl_detalle_venta
5. Si crédito: genera tbl_cuota
6. Actualiza tbl_producto_stock (↓)
7. Registra en tbl_caja
8. Imprime ticket
```

### Flujo de Compra
```
1. Usuario registra compra (tbl_compra)
2. Selecciona proveedor (tbl_proveedor)
3. Agrega productos (tbl_detalle_compra)
4. Registra en tbl_compra
5. Actualiza tbl_producto_stock (↑)
6. Registra en tbl_caja como ingreso
```

### Flujo de Traslado
```
1. Crea tbl_traslado (origen, destino, usuario)
2. Agrega productos (tbl_detalle_traslado)
3. Actualiza tbl_producto_stock:
   - id_sucursal origen: ↓
   - id_sucursal destino: ↑
4. Auditoría completa
```

---

## 🔒 Características de Seguridad

### Autenticación
- Hash de contraseña (no almacenadas en texto plano)
- Sesiones en tbl_ci_sessions
- Token de reset de contraseña

### Autorización
- Roles (tbl_roles)
- Matriz de acceso por rol (tbl_access_matrix)
- isDeleted soft-delete pattern

### Auditoría
- createdBy, createdDtm en tablas clave
- updatedBy, updatedDtm en tablas clave
- Historial de login (tbl_last_login)
- **v2.0**: id_usuario en tbl_caja para auditar cajas

### Multisucursal
- Cada registro operacional incluye id_sucursal
- Usuarios ligados a sucursal específica
- Datos completamente segregados por sucursal

---

## 🛡️ Integridad de Datos

### Tipos de Datos Actuales (Post v2.0)

#### Monetarios
- **DECIMAL(10,2)** - Para todos los montos (máximo 99,999,999.99)
- Previene errores de punto flotante
- Precisión garantizada para operaciones financieras

#### Cantidades
- **INT(11)** - Para todos los stocks y cantidades
- Enteros positivos
- Operaciones aritméticas precisas

#### Identificadores
- **INT(11)** - Para IDs de tabla
- **SMALLINT(6)** - Para roleId (soporta hasta 32,767 roles)

#### Texto
- **VARCHAR(50-400)** - Para la mayoría de campos
- **TEXT** - Para JSON de permisos

#### Fechas
- **DATE** - Para fechas sin hora
- **DATETIME** - Para auditoría (timestamps)

### Validaciones de Aplicación
- Email único en tbl_users
- Código único en tbl_producto
- Cliente requerido para ventas de crédito
- Stock validado antes de venta
- Cantidad positiva requerida

### Índices Implementados (Post v2.0)
- PRIMARY KEY en todas las tablas
- UNIQUE en email (tbl_users), código (tbl_producto)
- **UNIQUE en (id_producto, id_sucursal) en tbl_producto_stock**
- Índice en last_activity (tbl_ci_sessions)

---

## ✨ Mejoras Realizadas (v2.0)

### Problemas Corregidos

| Problema | Solución | Impacto |
|----------|----------|--------|
| Precios como VARCHAR | → DECIMAL(10,2) | ✅ Operaciones aritméticas precisas |
| Cantidades como FLOAT | → INT | ✅ Sin errores de punto flotante |
| Montos inconsistentes | → DECIMAL(10,2) en todas partes | ✅ Precisión garantizada |
| roleId limitado a 127 | → SMALLINT (máx 32,767) | ✅ Sistema escalable |
| Campo `cliente` inconsistente | → `id_cliente` | ✅ Nombres semánticos consistentes |
| Sin auditoría en cajas | → Agregado `id_usuario` | ✅ Trazabilidad completa |
| Duplicados en stock | → Índice UNIQUE agregado | ✅ Integridad garantizada |

### Scripts de Actualización
- [BD_Structure_Current.sql](BD_Structure_Current.sql) - Estructura actual con todas las correcciones

---

## 🚀 Consideraciones de Producción

### Backups
```bash
# Backup completo
mysqldump -u root pos_multisucursal > backup.sql

# Backup solo estructura
mysqldump -u root --no-data pos_multisucursal > structure.sql

# Backup con compresión
mysqldump -u root pos_multisucursal | gzip > backup.sql.gz
```

### Restore
```bash
mysql -u root pos_multisucursal < backup.sql
```

### Mantenimiento
```sql
-- Optimizar tablas
OPTIMIZE TABLE tbl_venta, tbl_compra, tbl_detalle_venta;

-- Revisar integridad
CHECK TABLE tbl_venta, tbl_producto_stock;

-- Purgar sesiones antiguas (opcional)
DELETE FROM tbl_last_login WHERE login_time < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Ver tamaño de BD
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.TABLES 
WHERE table_schema='pos_multisucursal';
```

### Monitoreo
- Verificar espacio disponible: `df -h`
- Monitorear conexiones: `SHOW PROCESSLIST;`
- Revisar slow queries: Habilitar slow query log

### Seguridad
- Cambiar contraseña de usuario root en producción
- Usar credenciales separadas por ambiente (dev/staging/prod)
- Implementar SSL/TLS en conexiones remotas
- Configurar firewall para puerto 3306 (solo acceso local)

### Escalabilidad Futura
- Considerar índices adicionales según patrón de queries
- Evaluar particionamiento de tbl_venta (por fecha)
- Implementar réplicas de lectura si es necesario
- Considerar data warehouse separado para reportes

---

## 📖 Cómo Usar Esta Documentación

### Para Desarrolladores
1. Consulta **Descripción de Tablas** para estructura exacta
2. Revisa **Relaciones y Flujos** para entender cómo se relacionan
3. Mira **BD_EntityRelationshipDiagram.md** para visualización

### Para DBAs
1. Lee **Especificaciones Técnicas** para configuración
2. Consulta **Consideraciones de Producción** para mantenimiento
3. Usa **BD_Structure_Current.sql** para deployments

### Para Nuevos Miembros del Equipo
1. Comienza con **Módulos Principales** (vista general)
2. Profundiza en **Descripción Detallada de Tablas**
3. Estudia **Relaciones y Flujos** con diagramas

---

## 📞 Soporte y Actualización

**Última Actualización**: 26 de Abril de 2026
**Versión de BD**: 2.0 (post-correcciones)
**Estado**: ✅ Producción lista

**Contacto para actualizaciones**: Revisar git log para histórico de cambios
**Documentación de cambios**: Ver commits con "docs:" en mensaje

---

## 📚 Archivos Relacionados

- **BD_Structure_Current.sql** - Script SQL completo (estructura)
- **BD_EntityRelationshipDiagram.md** - Diagrama E-R en Mermaid
- **BD_FIXES_SUMMARY.md** - Resumen técnico de cambios v2.0
- **TESTING_CHECKLIST.md** - Pruebas manuales
- **TESTING_RESULTS.md** - Resultados de testing automatizado

---

*Esta documentación es la referencia oficial para la estructura y operación de la base de datos pos_multisucursal. Se actualiza con cada versión importante.*
