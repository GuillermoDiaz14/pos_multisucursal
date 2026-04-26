# Diagrama Entidad-Relación (E-R) - POS Multisucursal

## Diagrama Mermaid

```mermaid
erDiagram
    tbl_users ||--o{ tbl_roles : "has"
    tbl_users ||--o{ tbl_sucursal : "belongs_to"
    tbl_roles ||--o{ tbl_access_matrix : "has"
    
    tbl_sucursal ||--o{ tbl_cliente : "has"
    tbl_sucursal ||--o{ tbl_proveedor : "has"
    tbl_sucursal ||--o{ tbl_producto_stock : "manages"
    tbl_sucursal ||--o{ tbl_venta : "has"
    tbl_sucursal ||--o{ tbl_compra : "has"
    tbl_sucursal ||--o{ tbl_traslado : "has"
    tbl_sucursal ||--o{ tbl_caja : "has"
    tbl_sucursal ||--o{ tbl_ingreso : "has"
    tbl_sucursal ||--o{ tbl_gasto : "has"
    
    tbl_categoria ||--o{ tbl_producto : "contains"
    tbl_producto ||--o{ tbl_producto_stock : "tracks"
    tbl_producto ||--o{ tbl_detalle_venta : "is_sold_in"
    tbl_producto ||--o{ tbl_detalle_compra : "is_bought_in"
    tbl_producto ||--o{ tbl_detalle_traslado : "is_moved_in"
    
    tbl_cliente ||--o{ tbl_venta : "makes"
    tbl_proveedor ||--o{ tbl_compra : "provides"
    tbl_metodo_pago ||--o{ tbl_venta : "used_in"
    
    tbl_venta ||--o{ tbl_detalle_venta : "has"
    tbl_venta ||--o{ tbl_cuota : "has"
    
    tbl_compra ||--o{ tbl_detalle_compra : "has"
    
    tbl_traslado ||--o{ tbl_detalle_traslado : "has"
    
    tbl_users ||--o{ tbl_venta : "creates"
    tbl_users ||--o{ tbl_compra : "creates"
    tbl_users ||--o{ tbl_caja : "manages"
    tbl_users ||--o{ tbl_last_login : "has"
    
    tbl_empleado ||--o{ tbl_sucursal : "works_in"
    
    tbl_reset_password ||--o{ tbl_users : "for"
    
    ci_sessions ||--o{ tbl_users : "session_for"

    tbl_users {
        int userId PK
        string email UK
        string name
        string mobile
        int isAdmin
        int id_sucursal FK
        int roleId FK
        timestamp createdDtm
        timestamp updatedDtm
        int isDeleted
    }
    
    tbl_roles {
        smallint roleId PK
        string role UK
        int status
        int isDeleted
        int createdBy
        timestamp createdDtm
        int updatedBy
        timestamp updatedDtm
    }
    
    tbl_sucursal {
        int id_sucursal PK
        string nombre_sucursal
        string direccion
    }
    
    tbl_producto {
        int id_producto PK
        string nombre_producto
        decimal precio_compra
        decimal precio_venta
        string codigo UK
        int categoria FK
        string imagen
        string detalles
        string talla
    }
    
    tbl_producto_stock {
        int id_producto_stock PK
        int id_producto FK
        int id_sucursal FK
        int stock
    }
    
    tbl_cliente {
        int id_cliente PK
        string nombre
        string correo
        string doc_identidad
        string celular
        int id_sucursal FK
    }
    
    tbl_venta {
        int id_venta PK
        date fecha_venta
        int id_cliente FK
        decimal descuento
        decimal base_imponible
        decimal impuesto
        decimal total
        int id_usuario FK
        string tipo_pago
        int id_metodo_pago FK
        decimal saldo
        decimal monto_recibido
        decimal cambio
        int id_sucursal FK
        string tipo_venta
        string estado_apartado
        decimal anticipo
    }
    
    tbl_detalle_venta {
        int id_detalle_venta PK
        int id_producto FK
        decimal precio_venta
        int cantidad
        decimal sub_total
        int id_venta FK
    }
    
    tbl_compra {
        int id_compra PK
        date fecha_compra
        int proveedor FK
        string nota
        decimal total
        int id_usuario FK
        int id_sucursal FK
    }
    
    tbl_detalle_compra {
        int id_detalle_compra PK
        int id_producto FK
        decimal precio_compra
        int cantidad
        decimal sub_total
        int id_compra FK
    }
    
    tbl_caja {
        int id_caja PK
        date fecha_apertura
        date fecha_cierre
        decimal saldo
        string estado
        int id_sucursal FK
        int id_usuario FK
    }
    
    tbl_categoria {
        int id_categoria PK
        string nombre_categoria
    }
    
    tbl_proveedor {
        int id_proveedor PK
        string nombre
        string correo
        string telefono
        string doc_identidad
        int id_sucursal FK
    }
    
    tbl_metodo_pago {
        int id_metodo_pago PK
        string metodo
        int id_sucursal FK
    }
    
    tbl_cuota {
        int id_cuota PK
        decimal cuota
        date fecha_pago
        int id_venta FK
    }
    
    tbl_traslado {
        int id_traslado PK
        date fecha_traslado
        int sucursal_origen FK
        int sucursal_destino FK
        int id_usuario FK
        string estado
    }
    
    tbl_detalle_traslado {
        int id_detalle_traslado PK
        int id_producto FK
        int cantidad
        int id_traslado FK
    }
    
    tbl_ingreso {
        int id_ingreso PK
        string descripcion
        decimal monto
        date fecha
        int id_sucursal FK
    }
    
    tbl_gasto {
        int id_gasto PK
        string descripcion
        decimal monto
        date fecha
        int id_sucursal FK
    }
    
    tbl_empleado {
        int id_empleado PK
        string nombre
        string puesto
        int id_sucursal FK
    }
    
    tbl_access_matrix {
        int id PK
        text access
        smallint roleId FK
        int isDeleted
        int createdBy
        timestamp createdDtm
        int updatedBy
        timestamp updatedDtm
    }
    
    tbl_last_login {
        int id PK
        int userId FK
        timestamp login_time
    }
    
    tbl_reset_password {
        int id PK
        int userId FK
        string reset_token
        timestamp created_at
        timestamp expires_at
        int is_used
    }
    
    ci_sessions {
        string session_id PK
        string ip_address
        string user_agent
        int last_activity
        text user_data
    }
    
    tbl_configuracion {
        int id_configuracion PK
        string nombre_empresa
        int telefono
        decimal impuesto
        string simbolo_moneda
    }
```

---

## Leyenda de Símbolos

- **PK**: Primary Key (Clave Primaria)
- **FK**: Foreign Key (Clave Foránea)
- **UK**: Unique Key (Clave Única)
- **||--o{**: Relación uno-a-muchos (1:N)
- **||--||**: Relación uno-a-uno (1:1)

---

## Tablas Principales por Módulo

### 🔐 Autenticación y Administración
- `tbl_users` - Usuarios del sistema
- `tbl_roles` - Roles y permisos
- `tbl_access_matrix` - Matriz de acceso por rol
- `tbl_last_login` - Historial de login
- `tbl_reset_password` - Reset de contraseñas

### 🏪 Catálogos Maestros
- `tbl_sucursal` - Sucursales/tiendas
- `tbl_producto` - Catálogo de productos
- `tbl_categoria` - Categorías de productos
- `tbl_cliente` - Clientes
- `tbl_proveedor` - Proveedores
- `tbl_metodo_pago` - Métodos de pago
- `tbl_empleado` - Empleados
- `tbl_configuracion` - Configuración del sistema

### 📊 Inventario
- `tbl_producto_stock` - Stock por sucursal
- `tbl_traslado` - Traslados entre sucursales
- `tbl_detalle_traslado` - Detalles de traslados

### 💰 Ventas
- `tbl_venta` - Registro de ventas
- `tbl_detalle_venta` - Detalles de ventas
- `tbl_cuota` - Cuotas para crédito

### 🛒 Compras
- `tbl_compra` - Registro de compras
- `tbl_detalle_compra` - Detalles de compras

### 💳 Caja
- `tbl_caja` - Cajas abiertas/cerradas
- `tbl_ingreso` - Ingresos adicionales
- `tbl_gasto` - Gastos operacionales

### 🔧 Sistema
- `ci_sessions` - Sesiones CodeIgniter

---

## Relaciones Críticas

### Relaciones de Sucursal (Multisucursal)
```
tbl_sucursal
├── tbl_cliente (clientes por sucursal)
├── tbl_proveedor (proveedores por sucursal)
├── tbl_venta (ventas por sucursal)
├── tbl_compra (compras por sucursal)
├── tbl_producto_stock (stock por sucursal)
├── tbl_caja (cajas por sucursal)
├── tbl_ingreso (ingresos por sucursal)
├── tbl_gasto (gastos por sucursal)
└── tbl_traslado (traslados entre sucursales)
```

### Relaciones de Venta
```
tbl_venta
├── tbl_cliente (quién compra)
├── tbl_users (quién vende)
├── tbl_metodo_pago (cómo paga)
├── tbl_detalle_venta (qué compra)
│   └── tbl_producto (productos)
└── tbl_cuota (si es crédito)
```

### Relaciones de Compra
```
tbl_compra
├── tbl_proveedor (de quién compra)
├── tbl_users (quién compra)
├── tbl_detalle_compra (qué compra)
│   └── tbl_producto (productos)
└── tbl_producto_stock (actualiza stock)
```

### Relaciones de Traslado
```
tbl_traslado
├── tbl_sucursal (origen y destino)
├── tbl_users (quién traslada)
└── tbl_detalle_traslado (qué traslada)
    └── tbl_producto (productos)
```

---

## Tipos de Datos Utilizados

### Numéricos
- `INT(11)` - Números enteros estándar
- `SMALLINT(6)` - Números enteros pequeños (para roles: -32,768 a 32,767)
- `DECIMAL(10,2)` - Números decimales precisos para moneda (hasta 99,999,999.99)

### Texto
- `VARCHAR(50)` - Textos cortos (códigos, estados)
- `VARCHAR(200)` - Textos medianos (nombres, direcciones)
- `VARCHAR(400)` - Textos largos (notas)
- `TEXT` - Textos muy largos (JSON de permisos)

### Fechas
- `DATE` - Fechas sin hora
- `DATETIME` - Fechas con hora

### Otros
- `TINYINT(4)` - Booleano (0/1)

---

## Convenciones de Nomenclatura

### Tablas
- Prefijo: `tbl_`
- Nombres en snake_case
- Nombres en singular
- Ejemplo: `tbl_producto`, `tbl_venta`

### Columnas
- PK (Primary Key): `id_nombre_tabla` o `nombre_tableId`
- FK (Foreign Key): `id_nombre_tabla_referenciada`
- Estados: `estado`, `status`, `isDeleted`
- Fechas: `fecha_accion`, `createdDtm`, `updatedDtm`
- Booleanos: `is_*` o `isDeleted`

### Índices
- PK: PRIMARY KEY
- UK: UNIQUE KEY (únicas sin valores NULL duplicados)
- FK: Se sugiere agregar
- Búsquedas frecuentes: Se sugiere agregar

---

## Cardinalidad de Relaciones

| Relación | Tipo | Ejemplo |
|----------|------|---------|
| Usuario → Rol | 1:1 | Un usuario tiene un rol |
| Rol → Acceso | 1:N | Un rol tiene múltiples permisos |
| Sucursal → Cliente | 1:N | Una sucursal tiene múltiples clientes |
| Sucursal → Stock | 1:N | Una sucursal tiene múltiple stock |
| Producto → Detalle Venta | 1:N | Un producto en múltiples ventas |
| Venta → Detalle Venta | 1:N | Una venta tiene múltiples productos |
| Venta → Cuota | 1:N | Una venta puede tener múltiples cuotas |
| Sucursal → Traslado | 1:N | Una sucursal participa en múltiples traslados |

---

## Notas Importantes

1. **No hay FK constraints explícitos declarados** - Se recomienda agregarlos en futuras versiones
2. **JSON en access_matrix** - Se recomienda normalizar a tabla `tbl_role_permissions`
3. **Float en tbl_caja.saldo** - Se recomienda cambiar a DECIMAL(10,2)
4. **sin único en tbl_producto_stock** - Se agregó índice UNIQUE en la corrección actual
5. **Multisucursal por id_sucursal** - Presente en casi todas las tablas operacionales

---

Fecha de Actualización: 26 de Abril de 2026
