# Contexto Técnico del Proyecto `pos_multisucursal`

## 1. Resumen

El proyecto es un sistema web de punto de venta multisucursal construido sobre **CodeIgniter 3** con patrón **MVC**. Su foco principal es operar varias sucursales con inventario separado por ubicación, ventas, compras, traslados entre sucursales, caja, reportes, usuarios y roles.

A nivel funcional, el sistema cubre:

- autenticación de usuarios por sucursal
- gestión de roles y matriz de acceso
- administración de usuarios
- sucursales
- productos y stock por sucursal
- clientes y proveedores
- métodos de pago
- ventas y ventas a crédito
- compras/entradas
- caja
- ingresos y gastos
- traslados de inventario entre sucursales
- reportes operativos y administrativos
- importación CSV en varios catálogos
- generación de PDF y etiquetas/códigos de barras

La aplicación tiene una fuerte dependencia del dato de sesión `id_sucursal`, lo que convierte a la sucursal activa en el eje del aislamiento de información operativa.

---

## 2. Stack tecnológico real

### Backend

- PHP
- CodeIgniter 3
- MySQL/MariaDB vía `mysqli`
- patrón MVC clásico de CodeIgniter

### Frontend

- AdminLTE
- Bootstrap
- jQuery
- jQuery Validate

### Librerías y componentes externos detectados

- `picqer/php-barcode-generator`
- `zendframework/zend-barcode`
- TCPDF
- jsPDF
- SheetJS
- librerías de barcode incluidas en `assets/src`

### Configuración base observada

- URL base local: `http://localhost/pos_multisucursal/`
- base de datos: `pos_multisucursal`
- autoload de librerías: `database`, `session`
- helpers autoload: `url`, `file`, `cias_helper`
- controlador por defecto: `login`
- override 404 configurado a `error_404`

---

## 3. Estructura real del proyecto

### Raíz

- `index.php`: front controller de CodeIgniter
- `composer.json`: dependencias PHP para barcode
- `composer.lock`
- `README.md`: descripción general, parcialmente desalineada del código real
- `usuarios.txt`
- `REQUERIMIENTO MODIFICACION MULTISUCURSAL.docx`
- `bd actual/`: scripts SQL
- `manual instalacion/`
- `uploads/`: plantillas e insumos para importación

### Carpetas principales

- `application/`: código de negocio real
- `system/`: framework CodeIgniter
- `assets/`: frontend, librerías y recursos estáticos
- `vendor/`: dependencias de Composer

### Métricas rápidas del código de aplicación

- 19 controladores en `application/controllers`
- 18 modelos en `application/models`
- 131 archivos de vista en `application/views`
- 205 archivos dentro de `application/`

---

## 4. Arquitectura general

### 4.1 Flujo de arranque

1. `index.php` define entorno y carga CodeIgniter.
2. `application/config/config.php` define `base_url`, paths y comportamiento general.
3. `application/config/routes.php` redirige el arranque a `Login`.
4. `application/config/autoload.php` carga:
   - base de datos
   - sesión
   - helpers comunes
   - config `modules`
5. Los controladores de negocio heredan normalmente de `BaseController`.

### 4.2 Patrón de control de acceso

La mayor parte del sistema usa `application/libraries/BaseController.php`, que centraliza:

- validación de sesión
- carga de datos globales del usuario
- validación de admin
- helpers de autorización por módulo
- carga de vistas con header/footer
- paginación

Propiedades relevantes del `BaseController`:

- `role`
- `vendorId`
- `name`
- `roleText`
- `isAdmin`
- `accessInfo`
- `global`
- `lastLogin`
- `module`

Métodos clave:

- `isLoggedIn()`: valida sesión y rellena contexto global
- `isAdmin()`: compara contra `SYSTEM_ADMIN`
- `hasListAccess()`
- `hasCreateAccess()`
- `hasUpdateAccess()`
- `hasDeleteAccess()`
- `loadThis()`: pantalla de acceso denegado
- `logout()`
- `loadViews()`
- `paginationCompress()`

### 4.3 Matriz de permisos

`application/config/modules.php` define una lista de módulos para permisos:

- `Carrito`
- `Entrada`
- `Gasto`
- `Ingreso`
- `Metodo_pago`
- `Producto`
- `Proveedor`
- `Trasladar`
- `Reporte`
- `Reporte_administrador`

La lógica sugiere que el acceso granular vive en `tbl_access_matrix`, pero en la implementación actual `BaseController` revisa casi siempre solo `total_access`.

---

## 5. Estructura MVC real por capas

## 5.1 Controladores

### 5.1.1 Autenticación y usuarios

#### `Login` (`application/controllers/Login.php`)

Responsabilidad:

- pantalla de login
- autenticación
- recuperación de contraseña
- creación de nueva contraseña
- carga de información de acceso por rol

Modelo cargado:

- `login_model`

Métodos:

- `__construct()`: carga `login_model`
- `index()`: entrada principal
- `isLoggedIn()`: si no hay sesión muestra `users/login`
- `loginMe()`: autentica por email, password y sucursal
- `forgotPassword()`: vista de recuperación
- `resetPasswordUser()`: genera token de recuperación y envía correo
- `resetPasswordConfirmUser()`: valida activación desde URL
- `createPasswordUser()`: guarda nueva contraseña
- `accessInfo()`: obtiene matriz de acceso del rol

Observación funcional:

- el login exige `id_sucursal`, por lo que un mismo usuario queda asociado a una sucursal de operación.

#### `User` (`application/controllers/User.php`)

Responsabilidad:

- dashboard principal
- administración de usuarios
- historial de login
- perfil
- cambio de contraseña

Modelo cargado:

- `user_model`

Métodos:

- `__construct()`
- `index()`: dashboard con ventas del año y sucursal actual
- `userListing()`: lista de usuarios
- `addNew()`: formulario de alta
- `checkEmailExists()`
- `addNewUser()`
- `editOld()`
- `editUser()`
- `deleteUser()`
- `pageNotFound()`
- `loginHistoy()`
- `profile()`
- `profileUpdate()`
- `changePassword()`
- `emailExists()`

#### `Roles` (`application/controllers/Roles.php`)

Responsabilidad:

- CRUD de roles
- gestión de matriz de accesos

Modelo cargado:

- `role_model` como alias `rm`

Métodos:

- `__construct()`
- `index()`
- `roleListing()`
- `add()`
- `checkRoleExists()`
- `addNewRole()`
- `edit()`
- `editRole()`
- `addRoleMatrix()`
- `storeAccessMatrix()`
- `filterroles()`
- `confirmar_eliminar_rol()`

### 5.1.2 Catálogos y administración base

#### `Sucursal`

Responsabilidad:

- CRUD de sucursales
- alta inicial de stock por sucursal

Modelo:

- `Sucursal_model` alias `scm`

Métodos:

- `index()`
- `sucursal_lista()`
- `add()`
- `addNewsucursal()`
- `edit()`
- `editsucursal()`
- `confirmar_eliminar_sucursal()`
- `filtersucursal()`

#### `Categoria`

Responsabilidad:

- CRUD de categorías de producto

Modelo:

- `Categoria_model` alias `cm`

Métodos:

- `index()`
- `categoria_lista()`
- `add()`
- `addNewCategoria()`
- `edit()`
- `editCategoria()`
- `confirmar_eliminar_categoria()`
- `filterCategorias()`

#### `Cliente`

Responsabilidad:

- CRUD de clientes por sucursal
- importación CSV

Modelo:

- `Cliente_model` alias `ccm`

Métodos:

- `index()`
- `cliente_lista()`
- `add()`
- `addNewcliente()`
- `edit()`
- `editcliente()`
- `confirmar_eliminar_cliente()`
- `filterclientes()`
- `importar()`
- `importar_cliente()`

#### `Proveedor`

Responsabilidad:

- CRUD de proveedores por sucursal
- importación CSV

Modelo:

- `Proveedor_model` alias `prm`

Métodos:

- `index()`
- `proveedor_lista()`
- `add()`
- `addNewproveedor()`
- `edit()`
- `editproveedor()`
- `confirmar_eliminar_proveedor()`
- `filterproveedors()`
- `importar()`
- `importar_proveedor()`

#### `Metodo_pago`

Responsabilidad:

- CRUD de métodos de pago por sucursal

Modelo:

- `Metodo_pago_model` alias `mpm`

Métodos:

- `index()`
- `metodo_pago_lista()`
- `add()`
- `addNewMetodo_pago()`
- `edit()`
- `editMetodo_pago()`
- `confirmar_eliminar_metodo_pago()`
- `filtermetodo_pagos()`

#### `Empleado`

Responsabilidad:

- CRUD de empleados
- importación CSV

Modelo:

- `Empleado_model` alias `em`

Métodos:

- `index()`
- `importar()`
- `importar_empleado()`
- `empleadoListing()`
- `add()`
- `addNewEmpleado()`
- `edit()`
- `editEmpleado()`
- `confirmar_eliminar_empleado()`
- `filterEmployees()`

#### `Configuracion`

Responsabilidad aparente:

- edición de configuración general del sistema

Modelo esperado:

- `Configuracion_model` alias `conf`

Métodos:

- `index()`
- `editconfiguracion()`

Observación importante:

- el controlador existe, pero **no se encontró `application/models/Configuracion_model.php`**. Esto indica archivo faltante, código no versionado o inconsistencia del proyecto.

### 5.1.3 Operación comercial

#### `Producto`

Responsabilidad:

- CRUD de productos
- carga y edición de imagen
- compresión de imágenes
- importación CSV
- impresión/generación de etiquetas
- integración con barcode
- consulta de stock por sucursal

Modelo:

- `Producto_model` alias `pm`

Métodos:

- `index()`
- `producto_lista()`
- `add()`
- `addNewProducto()`
- `comprimir_imagen()`
- `editProductoImagen()`
- `edit()`
- `editar_imagen()`
- `editProducto()`
- `confirmar_eliminar_producto()`
- `filterProductos()`
- `importar()`
- `importar_producto()`
- `etiqueta()`
- `etiqueta_por_categoria()`
- `generar_etiquetas()`

#### `Caja`

Responsabilidad:

- apertura y cierre de caja por sucursal
- manejo de caja para ventas y reparaciones

Modelo:

- `Caja_model` alias `xm`

Métodos:

- `index()`
- `add()`
- `add_reparacion()`
- `addNewCaja()`
- `addNewCajaReparacion()`
- `cerrarCaja()`
- `cerrarCajaReparacion()`

#### `Carrito`

Responsabilidad:

- flujo principal de ventas
- ventas contado/crédito
- edición y eliminación de ventas
- impresión de ticket
- exportación PDF
- registro de cuotas de crédito
- validación de caja abierta
- ajuste de inventario y saldo de caja

Modelo:

- `Carrito_model` alias `cm`

Métodos:

- `index()`
- `carrito()`
- `eliminar_venta()`
- `carrito_editar()`
- `credito()`
- `addNewVentaPrueba()`
- `addNewVenta()`
- `ActualizarVenta()`
- `calculateAndStoreCantidad()`
- `filtrarProductos()`
- `imprimirticket()`
- `ventas_lista()`
- `ventas_lista_contado()`
- `ventas_lista_credito()`
- `filterVentas()`
- `filterVentas_contado()`
- `filterVentas_credito()`
- `exportToPDF()`
- `cuota_agregar()`

Notas de negocio:

- antes de vender valida que haya caja abierta
- descuenta stock desde `tbl_producto_stock`
- incrementa saldo de caja
- maneja ventas con saldo pendiente mediante `tbl_cuota`

#### `Entrada`

Responsabilidad:

- registro de compras/entradas de inventario
- edición y eliminación de compras
- ajuste de inventario
- ticket/PDF de compra

Modelo:

- `Entrada_model` alias `e`

Métodos:

- `index()`
- `entrada()`
- `compra_editar()`
- `eliminar_compra()`
- `addNewCompraPrueba()`
- `ActualizarCompra()`
- `addNewCompra()`
- `calculateAndStoreCantidad()`
- `filtrarProductos()`
- `entradas_lista()`
- `filterEntradas()`
- `exportToPDF()`

#### `Trasladar`

Responsabilidad:

- traslado de inventario entre sucursales
- validación de stock en sucursal origen
- aumento/disminución de existencias entre sucursales
- listados de enviados y recibidos
- PDF del traslado

Modelo:

- `Trasladar_model` alias `tm`

Métodos:

- `index()`
- `trasladar()`
- `addNewTrasladarPrueba()`
- `addNewTrasladar1()`
- `addNewTrasladar()`
- `calculateAndStoreCantidad()`
- `filtrarProductos()`
- `trasladar_lista()`
- `filterTrasladar()`
- `trasladar_lista_Recibidos()`
- `filterTrasladarRecibidos()`
- `exportToPDF()`

### 5.1.4 Finanzas y reportes

#### `Gasto`

Responsabilidad:

- CRUD de gastos por sucursal

Modelo:

- `Gasto_model` alias `gm`

Métodos:

- `index()`
- `gasto_lista()`
- `add()`
- `addNewGasto()`
- `edit()`
- `editGasto()`
- `confirmar_eliminar_gasto()`
- `filterGastos()`

#### `Ingreso`

Responsabilidad:

- CRUD de ingresos por sucursal

Modelo:

- `Ingreso_model` alias `im`

Métodos:

- `index()`
- `ingreso_lista()`
- `add()`
- `addNewIngreso()`
- `edit()`
- `editIngreso()`
- `confirmar_eliminar_ingreso()`
- `filterIngresos()`

#### `Reporte`

Responsabilidad:

- reportes operativos de la sucursal activa

Modelo:

- `Reporte_model` alias `repm`

Métodos:

- `index()`
- `reporte_venta_por_fecha()`
- `reporte_compra_por_fecha()`
- `reporte_venta_mensual()`
- `reporte_compra_mensual()`
- `reporte_venta_productos_mas_vendidos()`
- `reporte_ganancias_por_fecha()`
- `reporte_venta_diario()`
- `organizarTotalesPorDia()`
- `filterVenta_fechas()`
- `filterCompra_fechas()`
- `filterVenta_entre_dos_fechas()`
- `filterCompra_entre_dos_fechas()`
- `filterGanancia_entre_dos_fechas()`
- `generatePDF()`
- `exportToPDF()`

#### `Reporte_administrador`

Responsabilidad:

- reportes consolidados o seleccionados por sucursal
- reportes de traslados enviados/recibidos
- vistas de selección de sucursal para análisis comparativo

Modelo:

- `Reporte_administrador_model` alias `rpam`

Métodos:

- `seleccion_traslado()`
- `seleccion_traslado_recibido()`
- `seleccion_sucursal_venta_diario()`
- `seleccion_sucursal_venta_mensual()`
- `seleccion_sucursal_venta_por_fecha()`
- `seleccion_sucursal_venta_productos_mas_vendidos()`
- `seleccion_sucursal_compra_por_fecha()`
- `seleccion_sucursal_compra_mensual()`
- `seleccion_sucursal_ganancias_ventas_productos()`
- `reporte_venta_por_fecha()`
- `reporte_compra_por_fecha()`
- `reporte_venta_mensual()`
- `reporte_compra_mensual()`
- `reporte_venta_productos_mas_vendidos()`
- `reporte_ganancias_por_fecha()`
- `reporte_venta_diario()`
- `organizarTotalesPorDia()`
- `filterVenta_fechas()`
- `filterCompra_fechas()`
- `filterVenta_entre_dos_fechas()`
- `filterCompra_entre_dos_fechas()`
- `filterGanancia_entre_dos_fechas()`
- `generatePDF()`
- `exportToPDF()`
- `trasladar_lista()`
- `filterTrasladar()`
- `trasladar_lista_Recibidos()`
- `filterTrasladarRecibidos()`

### 5.1.5 Controlador de error

#### `Errorr_404` en archivo `application/controllers/Error_404`

Responsabilidad:

- redirigir login o `pageNotFound`

Métodos:

- `__construct()`
- `index()`
- `isLoggedIn()`

Observaciones:

- el archivo no tiene extensión `.php`
- la ruta 404 apunta a `error_404`, pero la clase real es `Errorr_404`
- hay una inconsistencia nominal importante entre archivo, ruta y clase

---

## 5.2 Modelos

### `Caja_model`

Tablas principales:

- `tbl_caja`

Responsabilidades:

- listar cajas
- alta de apertura
- recuperar datos de caja
- cierre de caja

Métodos:

- `cajaListingCount()`
- `cajaListing()`
- `addNewCaja()`
- `getCajaInfo()`
- `cerrarCaja()`

### `Carrito_model`

Tablas principales:

- `tbl_venta`
- `tbl_detalle_venta`
- `tbl_cuota`
- `tbl_caja`
- `tbl_producto`
- `tbl_producto_stock`
- `tbl_cliente`
- `tbl_sucursal`
- `tbl_metodo_pago`

Responsabilidades:

- persistencia de ventas
- persistencia de detalle de venta
- cuotas para crédito
- recuperación de clientes y productos con stock
- validación y ajuste de inventario
- validación de caja abierta
- incremento de saldo de caja
- listados de ventas contado/crédito

Métodos:

- `addNewVenta()`
- `addNewDetalleVenta()`
- `getEmpleadoInfo()`
- `editEmpleado()`
- `eliminar_detalles()`
- `eliminar_venta()`
- `get_productos()`
- `get_productos_com_stock()`
- `get_clientes()`
- `get_configuracion()`
- `get_saldo_cajaabierta()`
- `get_venta()`
- `get_cuota()`
- `get_detalle_venta()`
- `hayCajasAbiertas()`
- `aumentarSaldoCajasAbiertas()`
- `aumentarSaldoCredito()`
- `actualizarInventarioProducto()`
- `validarInventarioproducto()`
- `ventas_lista_Count()`
- `ventas_lista()`
- `ventas_lista_contado_Count()`
- `ventas_lista_contado()`
- `ventas_lista_credito_Count()`
- `ventas_lista_credito()`
- `get_metodos()`
- `get_met()`
- `edit_venta()`
- `addNewcuota()`

### `Categoria_model`

Tabla:

- `tbl_categoria`

Responsabilidades:

- CRUD y listados de categorías

Métodos:

- `categoriaListingCount()`
- `categoriaListing()`
- `addNewCategoria()`
- `editCategoria()`
- `eliminar_categoria()`
- `getCategoriaInfo()`

### `Cliente_model`

Tabla:

- `tbl_cliente`

Responsabilidades:

- CRUD de clientes
- importación masiva

Métodos:

- `clienteListingCount()`
- `clienteListing()`
- `addNewcliente()`
- `editcliente()`
- `eliminar_cliente()`
- `getclienteInfo()`
- `importar_clientes()`

### `Empleado_model`

Tabla:

- `tbl_empleado`

Responsabilidades:

- CRUD de empleados
- importación
- lectura de categorías

Métodos:

- `empleadoListingCount()`
- `empleadoListing()`
- `addNewEmpleado()`
- `getEmpleadoInfo()`
- `editEmpleado()`
- `eliminar_empleado()`
- `get_categorias()`
- `importar_empleados()`

### `Entrada_model`

Tablas:

- `tbl_compra`
- `tbl_detalle_compra`
- `tbl_producto_stock`
- `tbl_proveedor`

Responsabilidades:

- compras
- detalle de compras
- actualización de inventario por entrada
- listados de compras

Métodos:

- `addNewCompra()`
- `addNewDetalleCompra()`
- `editCompra()`
- `eliminar_detalles()`
- `eliminar_compra()`
- `get_productos()`
- `get_proveedores()`
- `get_configuracion()`
- `get_compra()`
- `get_detalle_compra()`
- `edit_compra()`
- `actualizarInventarioProducto()`
- `compras_lista_Count()`
- `compras_lista()`

### `Gasto_model`

Tabla:

- `tbl_gasto`

Responsabilidades:

- CRUD de gastos

Métodos:

- `gastoListingCount()`
- `gastoListing()`
- `addNewGasto()`
- `editGasto()`
- `eliminar_gasto()`
- `getGastoInfo()`

### `Ingreso_model`

Tabla:

- `tbl_ingreso`

Responsabilidades:

- CRUD de ingresos

Métodos:

- `ingresoListingCount()`
- `ingresoListing()`
- `addNewIngreso()`
- `editIngreso()`
- `eliminar_ingreso()`
- `getIngresoInfo()`

### `Login_model`

Tablas:

- `tbl_users`
- `tbl_roles`
- `tbl_sucursal`
- `tbl_reset_password`
- `tbl_last_login`
- `tbl_access_matrix`

Responsabilidades:

- autenticación
- recuperación de contraseña
- bitácora de login
- carga de sucursales en login
- obtención de acceso por rol

Métodos:

- `loginMe()`
- `get_sucursal()`
- `checkEmailExist()`
- `resetPasswordUser()`
- `getCustomerInfoByEmail()`
- `checkActivationDetails()`
- `createPasswordUser()`
- `lastLogin()`
- `lastLoginInfo()`
- `getRoleAccessMatrix()`

### `Metodo_pago_model`

Tabla:

- `tbl_metodo_pago`

Responsabilidades:

- CRUD de métodos de pago

Métodos:

- `metodo_pagoListingCount()`
- `metodo_pagoListing()`
- `addNewmetodo_pago()`
- `editMetodo_pago()`
- `eliminar_metodo_pago()`
- `getmetodo_pagoInfo()`

### `Producto_model`

Tablas:

- `tbl_producto`
- `tbl_producto_stock`
- `tbl_categoria`
- `tbl_sucursal`

Responsabilidades:

- CRUD de productos
- stock por sucursal
- importación CSV
- búsqueda filtrada
- altas de stock por sucursal
- consulta de configuración/etiquetas

Métodos:

- `productoListingCount()`
- `getProductoConStock()`
- `productoListing()`
- `get_productos_sin_sucursal()`
- `get_productos_filtrados()`
- `addNewProducto()`
- `addNewProductoStock()`
- `getProductoInfo()`
- `editProducto()`
- `eliminar_producto()`
- `eliminar_producto_stock()`
- `get_productos()`
- `get_categorias()`
- `get_sucursales()`
- `get_categoriasarray()`
- `importar_productos()`
- `detectar_separador()`
- `getconfiguracionInfo()`
- `actualizarStock()`

### `Proveedor_model`

Tabla:

- `tbl_proveedor`

Responsabilidades:

- CRUD de proveedores
- importación

Métodos:

- `proveedorListingCount()`
- `proveedorListing()`
- `addNewproveedor()`
- `editproveedor()`
- `eliminar_proveedor()`
- `getproveedorInfo()`
- `importar_proveedores()`

### `Reporte_model`

Tablas consultadas:

- `tbl_venta`
- `tbl_detalle_venta`
- `tbl_compra`
- `tbl_producto`
- `tbl_caja`

Responsabilidades:

- reportes de ventas
- reportes de compras
- totales diarios
- conteos para paginación
- ganancias por fecha

Métodos:

- `hayCajasAbiertas()`
- `get_ventas()`
- `get_detalles_ventas()`
- `get_sumatoriaPorDia()`
- `get_sumatoriaReparacionPorDia()`
- `get_detalles_ventas_sumatorias()`
- `get_detalles_ganancias_sumatorias_entre_dos_fechas()`
- `get_detalles_ganancias_sumatorias_entre_dos_fechas_Count()`
- `reporte_venta_entre_dos_fechas()`
- `reporte_compra_entre_dos_fechas()`
- `venta_lista_Count_entre_dos_fechas()`
- `compra_lista_Count_entre_dos_fechas()`
- `reporte_venta_por_fecha()`
- `venta_lista_Count_por_fecha()`
- `reporte_compra_por_fecha()`
- `compra_lista_Count_por_fecha()`
- `get_compras()`

### `Reporte_administrador_model`

Extiende la lógica del modelo de reportes con foco administrativo:

- reportes por sucursal seleccionada
- lista de sucursales
- traslados enviados y recibidos

Métodos adicionales relevantes:

- `get_sucursales()`
- `traslado_lista_Count()`
- `traslado_lista_recibidos_Count()`
- `traslado_lista_recibidos()`
- `traslado_lista()`

### `Role_model`

Tablas:

- `tbl_roles`
- `tbl_access_matrix`

Responsabilidades:

- CRUD de roles
- generación, inserción y actualización de matriz de accesos

Métodos:

- `roleListingCount()`
- `roleListing()`
- `getUserRoles()`
- `checkEmailExists()`
- `addNewRole()`
- `getRoleInfo()`
- `editRole()`
- `deleteUser()`
- `getRoleAccessMatrix()`
- `getRoleAccessMatrixQuery()`
- `insertAccessMatrix()`
- `getFromAccessMatrix2()`
- `generateMatrix()`
- `updateAccessMatrix()`
- `eliminar_rol()`

Observación:

- el método `deleteUser()` dentro de `Role_model` está mal nombrado para su contexto; por el resto del código parece ser herencia/adaptación de plantilla.

### `Sucursal_model`

Tablas:

- `tbl_sucursal`
- `tbl_producto_stock`

Responsabilidades:

- CRUD de sucursales
- inicialización o registro de stock por sucursal
- conteos de productos

Métodos:

- `Get_cantidad_productos()`
- `sucursalListingCount()`
- `sucursalListing()`
- `addNewProductoStock()`
- `addNewsucursal()`
- `editsucursal()`
- `eliminar_producto_stock()`
- `eliminar_sucursal()`
- `getsucursalInfo()`

### `Trasladar_model`

Tablas:

- `tbl_traslado`
- `tbl_detalle_traslado`
- `tbl_producto_stock`
- `tbl_sucursal`
- `tbl_producto`

Responsabilidades:

- persistencia de traslados
- detalle de traslados
- listas de enviados y recibidos
- validación de stock
- decremento e incremento de inventario entre sucursales

Métodos:

- `addNewtraslado()`
- `addNewDetalletraslado()`
- `getEmpleadoInfo()`
- `get_productos()`
- `get_productos_com_stock()`
- `get_configuracion()`
- `get_traslado()`
- `get_detalle_traslado()`
- `validarStocktrasladoproducto()`
- `traslado_lista_Count()`
- `traslado_lista_recibidos_Count()`
- `traslado_lista_recibidos()`
- `traslado_lista()`
- `get_sucursales()`
- `actualizarInventarioProductorestar()`
- `actualizarInventarioproductosumar()`
- `validarInventarioproducto()`

### `User_model`

Tablas:

- `tbl_users`
- `tbl_roles`
- `tbl_sucursal`
- `tbl_last_login`
- `tbl_venta`

Responsabilidades:

- CRUD de usuarios
- carga de roles
- historial de login
- perfil
- métricas para dashboard

Métodos:

- `userListingCount()`
- `userListing()`
- `getUserRoles()`
- `checkEmailExists()`
- `addNewUser()`
- `getUserInfo()`
- `getSucursalInfo()`
- `editUser()`
- `deleteUser()`
- `matchOldPassword()`
- `changePassword()`
- `loginHistoryCount()`
- `loginHistory()`
- `getUserInfoById()`
- `getUserInfoWithRole()`
- `get_ventas()`
- `get_reparaciones()`
- `get_sucursal()`

---

## 5.3 Librerías y helpers propios

### `BaseController`

Ya descrito arriba. Es la pieza central de autorización, sesión y renderizado.

### `ModuleAccess`

Archivo: `application/libraries/ModuleAccess.php`

Responsabilidad:

- inicializar lectura de `moduleList` desde config

Método:

- `__construct()`

Observación:

- es una librería muy delgada; actualmente parece más un stub que una abstracción completa de permisos.

### `cias_helper.php`

Funciones detectadas:

- `pre($data)`: imprime con `<pre>`
- `get_instance()`: intenta exponer CI instance, pero la implementación es cuestionable
- `getHashedPassword($plainPassword)`: usa `password_hash`
- `verifyHashedPassword($plainPassword, $hashedPassword)`: usa `password_verify`
- `getBrowserAgent()`: inspecciona navegador/dispositivo
- `setProtocol()`: inicializa librería email con constantes SMTP
- `emailConfig()`
- `resetPasswordEmail($detail)`: envía template de reseteo
- `setFlashData($status, $flashMsg)`

Observaciones:

- el helper depende de constantes como `PROTOCOL`, `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`, `EMAIL_FROM`, `FROM_NAME`
- esas constantes no fueron localizadas en `application/config`, lo que sugiere configuración externa o faltante

---

## 6. Vistas y organización visual

La carpeta `application/views` está organizada por módulo, lo cual refleja bastante bien la estructura funcional del sistema.

### Carpetas de vistas detectadas

- `caja`
- `carrito`
- `categoria`
- `cliente`
- `compra`
- `configuracion`
- `email`
- `empleado`
- `general`
- `includes`
- `gasto`
- `ingreso`
- `metodo_pago`
- `producto`
- `proveedor`
- `reporte`
- `reporte_administrador`
- `roles`
- `sucursal`
- `traslado`
- `users`

### Vistas clave

#### Layout global

- `includes/header.php`
- `includes/footer.php`

El `header.php` confirma:

- uso de AdminLTE
- Bootstrap
- Font Awesome
- jQuery
- SheetJS
- menú lateral basado en sesión/rol

#### Vistas de autenticación

- `users/login.php`
- `users/forgotPassword.php`
- `users/newPassword.php`
- `email/resetPassword.php`

#### Dashboard

- `general/dashboard.php`
- `general/access.php`
- `general/404.php`

#### Operación

- ventas: `carrito/*`
- compras: `compra/*`
- traslados: `traslado/*`
- caja: `caja/add.php`

#### Catálogos

- `cliente/*`
- `proveedor/*`
- `producto/*`
- `categoria/*`
- `metodo_pago/*`
- `empleado/*`
- `sucursal/*`

#### Reportes

- `reporte/*`
- `reporte_administrador/*`

Patrón visual repetido:

- vista principal de listado
- formulario `add`
- formulario `edit`
- `table_partial` para refresco parcial/filtrado
- algunas vistas de importación
- algunas vistas para PDF/ticket

---

## 7. Base de datos y modelo de negocio

El script `bd actual/Only DB Structure.sql` muestra la estructura principal.

### Tablas detectadas

- `ci_sessions`
- `tbl_access_matrix`
- `tbl_caja`
- `tbl_categoria`
- `tbl_cliente`
- `tbl_compra`
- `tbl_configuracion`
- `tbl_cuota`
- `tbl_detalle_compra`
- `tbl_detalle_traslado`
- `tbl_detalle_venta`
- `tbl_empleado`
- `tbl_gasto`
- `tbl_ingreso`
- `tbl_last_login`
- `tbl_metodo_pago`
- `tbl_producto`
- `tbl_producto_stock`
- `tbl_proveedor`
- `tbl_reset_password`
- `tbl_roles`
- `tbl_sucursal`
- `tbl_traslado`
- `tbl_users`
- `tbl_venta`

### Entidades principales del negocio

#### `tbl_sucursal`

Representa cada sucursal física/lógica.

Campos relevantes:

- `id_sucursal`
- `nombre_sucursal`
- `impuesto`
- `celular`
- `direccion`
- `ciudad`
- `correo`
- `simbolo_moneda`

#### `tbl_users`

Usuarios del sistema.

Campos relevantes:

- `userId`
- `email`
- `password`
- `name`
- `mobile`
- `roleId`
- `isAdmin`
- `isDeleted`
- `id_sucursal`

#### `tbl_roles` y `tbl_access_matrix`

Modelo RBAC.

- `tbl_roles`: catálogo de roles
- `tbl_access_matrix`: permisos serializados/matriz de acceso por rol

#### `tbl_producto`

Catálogo maestro de productos.

Campos:

- `id_producto`
- `nombre_producto`
- `precio_compra`
- `precio_venta`
- `codigo`
- `categoria`
- `imagen`
- `detalles`

#### `tbl_producto_stock`

Stock por sucursal, pieza clave del multisucursal.

Campos:

- `id_producto_stock`
- `id_producto`
- `stock`
- `id_sucursal`

#### `tbl_venta` y `tbl_detalle_venta`

Cabecera y detalle de venta.

Campos relevantes de venta:

- `fecha_venta`
- `cliente`
- `descuento`
- `base_imponible`
- `impuesto`
- `total`
- `id_usuario`
- `tipo_pago`
- `id_metodo_pago`
- `saldo`
- `id_sucursal`

#### `tbl_cuota`

Abonos/cuotas ligados a venta a crédito.

#### `tbl_compra` y `tbl_detalle_compra`

Registro de entradas de inventario por proveedor.

#### `tbl_traslado` y `tbl_detalle_traslado`

Movimiento entre sucursal origen y destino.

Campos de `tbl_traslado`:

- `id_usuario`
- `id_sucursal_descuento`
- `id_sucursal_aumento`
- `comentario`

#### `tbl_caja`

Apertura, saldo y cierre de caja por sucursal.

Campos:

- `fecha_apertura`
- `fecha_cierre`
- `saldo`
- `estado`
- `id_sucursal`

#### `tbl_cliente`, `tbl_proveedor`, `tbl_metodo_pago`, `tbl_ingreso`, `tbl_gasto`

Catálogos/operación ligados casi siempre a `id_sucursal`.

### Relaciones lógicas más importantes

- `tbl_users.roleId -> tbl_roles.roleId`
- `tbl_users.id_sucursal -> tbl_sucursal.id_sucursal`
- `tbl_producto_stock.id_producto -> tbl_producto.id_producto`
- `tbl_producto_stock.id_sucursal -> tbl_sucursal.id_sucursal`
- `tbl_venta.cliente -> tbl_cliente.id_cliente`
- `tbl_venta.id_metodo_pago -> tbl_metodo_pago.id_metodo_pago`
- `tbl_venta.id_usuario -> tbl_users.userId`
- `tbl_venta.id_sucursal -> tbl_sucursal.id_sucursal`
- `tbl_detalle_venta.id_venta -> tbl_venta.id_venta`
- `tbl_detalle_venta.id_producto -> tbl_producto.id_producto`
- `tbl_compra.proveedor -> tbl_proveedor.id_proveedor`
- `tbl_compra.id_usuario -> tbl_users.userId`
- `tbl_detalle_compra.id_compra -> tbl_compra.id_compra`
- `tbl_detalle_compra.id_producto -> tbl_producto.id_producto`
- `tbl_traslado.id_sucursal_descuento -> tbl_sucursal.id_sucursal`
- `tbl_traslado.id_sucursal_aumento -> tbl_sucursal.id_sucursal`
- `tbl_detalle_traslado.id_traslado -> tbl_traslado.id_traslado`
- `tbl_detalle_traslado.id_producto -> tbl_producto.id_producto`

---

## 8. Cómo está implementado el multisucursal

El concepto multisucursal no está resuelto mediante “módulos por sucursal”, sino mediante:

- sesión con `id_sucursal`
- filtrado de consultas por `id_sucursal`
- stock desacoplado en `tbl_producto_stock`
- usuarios asociados a sucursal
- clientes/proveedores/métodos de pago asociados a sucursal
- traslados entre sucursales con decremento/incremento de stock

En la práctica, el sistema usa dos niveles:

1. catálogo maestro compartido
   - productos
   - categorías
   - roles
2. operación contextual por sucursal
   - stock
   - clientes
   - proveedores
   - métodos de pago
   - ventas
   - caja
   - compras
   - ingresos/gastos

---

## 9. Dependencias externas y assets

### `assets/bower_components`

Componentes frontend base:

- Bootstrap
- Font Awesome
- Ionicons
- jQuery
- datepicker

### `assets/dist`

Recursos AdminLTE:

- CSS
- JS
- imágenes

### `assets/js`

Scripts auxiliares:

- validación
- formularios de usuarios/roles
- utilitarios

### `assets/TCPDF-main`

Generación de PDFs en backend.

### `assets/jsPDF-master` y `assets/js/jsPDF-1.3.2`

Generación de PDFs desde frontend o integraciones complementarias.

### `assets/sheetjs`

Lectura/escritura de hojas de cálculo; probablemente soporte a importación/exportación.

### `assets/src`

Implementaciones de barcode y clases relacionadas:

- `Barcode`
- `BarcodeBar`
- `BarcodeGenerator`
- `BarcodeGeneratorPNG`
- `BarcodeGeneratorSVG`
- `BarcodeGeneratorHTML`
- `BarcodeGeneratorJPG`
- `BarcodeGeneratorDynamicHTML`
- múltiples tipos de código en `assets/src/Types`

### `vendor/`

Composer con:

- `picqer`
- `zendframework`
- `psr`
- `container-interop`

---

## 10. Flujo funcional por módulo

### Venta

1. Usuario inicia sesión con sucursal.
2. El módulo `Carrito` valida caja abierta.
3. Carga productos con stock disponible en la sucursal.
4. Registra cabecera de venta en `tbl_venta`.
5. Registra líneas en `tbl_detalle_venta`.
6. Descuenta stock en `tbl_producto_stock`.
7. Incrementa saldo de caja.
8. Si es crédito, administra saldo y cuotas en `tbl_cuota`.
9. Puede imprimir ticket o exportar PDF.

### Compra

1. `Entrada` carga proveedores y productos de sucursal.
2. Registra compra en `tbl_compra`.
3. Registra detalle en `tbl_detalle_compra`.
4. Incrementa inventario por sucursal.
5. Permite edición, eliminación y PDF.

### Traslado

1. `Trasladar` carga productos con stock en sucursal origen.
2. Selecciona sucursal destino.
3. Valida inventario suficiente.
4. Inserta cabecera en `tbl_traslado`.
5. Inserta detalles en `tbl_detalle_traslado`.
6. Resta stock en sucursal origen.
7. Suma stock en sucursal destino.
8. Permite ver enviados y recibidos.

### Caja

1. Apertura por sucursal.
2. Saldo se altera con ventas y posiblemente otros flujos.
3. Cierre con registro de fecha/corte.

---

## 11. Hallazgos técnicos importantes

Estos puntos conviene que cualquier desarrollador senior los conozca antes de tocar el proyecto.

### 11.1 Desalineación entre README y código real

El `README.md` habla de una estructura con carpetas como `Admin/`, `Pos/`, `Inventory/`, `Reports/` que no existen realmente en `application/controllers`. El código real está organizado por controladores planos.

### 11.2 `Configuracion_model` faltante

`Configuracion.php` carga `Configuracion_model`, pero el archivo no está en `application/models`.

Impacto:

- el módulo de configuración podría fallar en ejecución
- o el repositorio está incompleto

### 11.3 Inconsistencia del 404

Se detectó:

- ruta: `error_404`
- archivo: `application/controllers/Error_404`
- clase: `Errorr_404`

Esto es inconsistente y puede romper el manejo de 404 dependiendo del autoload y del filesystem.

### 11.4 Constantes críticas no localizadas

No se localizaron en `application/config` definiciones para:

- `SYSTEM_ADMIN`
- `SEGMENT`
- `PROTOCOL`
- `SMTP_HOST`
- `SMTP_PORT`
- `SMTP_USER`
- `SMTP_PASS`
- `EMAIL_FROM`
- `FROM_NAME`

Posibles explicaciones:

- existen archivos locales no versionados
- vienen de un bootstrap alterno
- faltan piezas del repositorio

### 11.5 Código con señales de plantilla adaptada

Hay varios nombres heredados de una plantilla genérica:

- comentarios que hablan de `booking`
- métodos como `getEmpleadoInfo()` en modelos no relacionados
- `deleteUser()` dentro de `Role_model`

Eso sugiere que el proyecto fue construido sobre una base previa y luego adaptado al dominio POS.

### 11.6 Mucha lógica de negocio en controladores

Módulos como `Carrito`, `Entrada` y `Trasladar` contienen bastante lógica procedural dentro del controlador:

- recorridos complejos de arreglos POST
- cálculos de totales
- validaciones de stock
- operaciones de inventario

Esto hace que:

- el controlador esté sobrecargado
- el mantenimiento sea más costoso
- sea más difícil probar la lógica en forma aislada

### 11.7 Filtrado por sucursal como responsabilidad transversal

La mayoría de los módulos operativos dependen de `session->userdata('id_sucursal')`. Es correcto para el dominio, pero vuelve a la sesión una dependencia muy fuerte.

---

## 12. Evaluación de madurez del proyecto

### Fortalezas

- estructura MVC reconocible
- negocio central del POS ya modelado
- soporte multisucursal real a nivel de stock y operación
- roles y acceso por módulo
- reportes separados entre operación y administración
- soporte de importación masiva
- integración con PDFs y etiquetas

### Debilidades

- inconsistencias de archivos y nombres
- posibles piezas faltantes
- baja separación entre lógica de negocio y controladores
- probable ausencia de pruebas automatizadas
- fuerte acoplamiento a sesión y consultas directas
- nomenclatura irregular

---

## 13. Archivos más importantes para empezar a trabajar

Si un desarrollador senior tuviera que entrar al proyecto, los primeros archivos que debería leer serían:

1. `index.php`
2. `application/config/config.php`
3. `application/config/routes.php`
4. `application/config/autoload.php`
5. `application/config/database.php`
6. `application/libraries/BaseController.php`
7. `application/helpers/cias_helper.php`
8. `application/controllers/Login.php`
9. `application/controllers/User.php`
10. `application/controllers/Carrito.php`
11. `application/models/Carrito_model.php`
12. `application/controllers/Entrada.php`
13. `application/models/Entrada_model.php`
14. `application/controllers/Trasladar.php`
15. `application/models/Trasladar_model.php`
16. `application/controllers/Producto.php`
17. `application/models/Producto_model.php`
18. `bd actual/Only DB Structure.sql`

---

## 14. Conclusión técnica

Este proyecto sí corresponde a un **POS multisucursal funcional sobre CodeIgniter 3**, con una base relativamente amplia de operación comercial. Su arquitectura real no es moderna ni especialmente desacoplada, pero sí expresa claramente el dominio del negocio: sucursales, inventario, ventas, compras, caja, traslados, usuarios y reportes.

Desde una mirada senior, el sistema parece haber evolucionado por capas de adaptación sobre una plantilla previa, lo que explica:

- nombres heredados
- lógica mezclada
- algunas inconsistencias estructurales

Aun así, el repositorio contiene suficiente información para entender el negocio y empezar a trabajar sobre él, especialmente desde:

- `application/controllers`
- `application/models`
- `application/views`
- el SQL de estructura

Si se quisiera llevar este proyecto a un siguiente nivel, las áreas más valiosas para ordenar serían:

- normalizar configuraciones faltantes
- reparar inconsistencias de archivos/clases
- mover lógica de negocio a servicios/modelos
- mejorar permisos granulares
- agregar pruebas y documentación operativa

