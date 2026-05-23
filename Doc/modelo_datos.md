# Modelo de datos

> Diccionario de datos y diagrama lógico de la base `pos_multisucursal`. Motor: MySQL/MariaDB (InnoDB).

---

## 1. Mapa lógico

```
                  ┌────────────┐
                  │  tbl_roles │
                  └─────┬──────┘
                        │ 1
                        │
                        │ N
   ┌─────────────┐ ┌────▼───────────────┐
   │ tbl_access_ │◄┤      tbl_users      │
   │   matrix    │ └──┬──────────────────┘
   └─────────────┘    │
                      │  N
                      ▼
                ┌────────────┐
                │tbl_sucursal│──┐
                └────┬───────┘  │ N
                     │ 1        │
                     │          ▼
       ┌─────────────┼─────────────────────────────┐
       │             │                             │
       ▼             ▼                             ▼
  ┌─────────┐  ┌──────────────────┐         ┌──────────────┐
  │tbl_caja │  │tbl_producto_stock│◄──┐     │  tbl_venta   │
  └─────────┘  └──────────────────┘   │     └──────┬───────┘
                                      │            │ 1
                                      │            │ N
                              ┌───────┴─────┐      ▼
                              │ tbl_producto │  ┌──────────────────┐
                              └──┬───┬───────┘  │tbl_detalle_venta │
                                 │   │          └──────────────────┘
                                 │   │  (variantes)
                                 │   ▼
                                 │ ┌──────────────────┐
                                 │ │tbl_producto_     │
                                 │ │ variante         │
                                 │ └─────┬────────────┘
                                 │       │ N
                                 │       ▼
                                 │ ┌──────────────────┐
                                 │ │tbl_stock_variante│
                                 │ └──────────────────┘
                                 │
            ┌────────────────────┴────────────────────┐
            │                                         │
            ▼                                         ▼
      ┌──────────┐  ┌──────────────────┐    ┌─────────────────────┐
      │tbl_compra│──┤tbl_detalle_compra│    │   tbl_traslado      │
      └──────────┘  └──────────────────┘    └──────┬──────────────┘
                                                   │
                                                   ▼
                                            ┌──────────────────────┐
                                            │tbl_detalle_traslado  │
                                            └──────────────────────┘
```

---

## 2. Tablas por dominio

### 2.1 Seguridad y administración

#### `tbl_users`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `userId` | INT PK | Identificador |
| `email` | VARCHAR | Login |
| `password` | VARCHAR | Hash bcrypt (`PASSWORD_DEFAULT`) |
| `name` | VARCHAR | Nombre completo |
| `mobile` | VARCHAR | Teléfono |
| `roleId` | INT FK → `tbl_roles` | Rol asignado |
| `isAdmin` | TINYINT | Bandera admin |
| `isDeleted` | TINYINT | Borrado lógico |
| `createdDtm`, `updatedDtm` | DATETIME | Auditoría |
| `id_sucursal` | INT FK → `tbl_sucursal` | Sucursal por defecto |

#### `tbl_roles`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `roleId` | INT PK | |
| `role` | VARCHAR | Nombre del rol |
| `status` | TINYINT | 1 activo / 0 inactivo |
| `isDeleted` | TINYINT | |
| `createdDtm`, `updatedDtm` | DATETIME | |

#### `tbl_access_matrix`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | INT PK | |
| `roleId` | INT FK → `tbl_roles` | |
| `access` | LONGTEXT (JSON) | Matriz de permisos por módulo |
| `isDeleted` | TINYINT | |
| `createdDtm`, `updatedDtm` | DATETIME | Detección de cambios para refrescar sesión |

**Estructura del JSON `access`:**

```json
[
  {
    "module": "Ventas",
    "total_access": 1,
    "editar": 1,
    "eliminar": 0
  },
  {
    "module": "Productos",
    "total_access": 1,
    "ver_precio_compra": 0,
    "gestionar": 1
  },
  {
    "module": "Reportes",
    "total_access": 1,
    "scope": "sucursal",
    "reports": {
      "ventas_diarias": 1,
      "ventas_periodo": 0
    }
  }
]
```

#### `tbl_last_login`

Bitácora de accesos: `userId`, `loginDtm`, `ipAddress`, `userAgent`.

#### `tbl_reset_password`

Tokens de recuperación: `email`, `activation_id`, `agent`, `client_ip_address`, `agent_string`, `platform`, `createdDtm`.

#### `tbl_configuracion`

Parámetros globales clave/valor (encabezado de tickets, datos fiscales, etc.).

---

### 2.2 Catálogos

#### `tbl_sucursal`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_sucursal` | INT PK | |
| `nombre_sucursal` | VARCHAR | |
| `impuesto` | DECIMAL | % impuesto aplicable |
| `celular`, `direccion`, `ciudad`, `correo` | VARCHAR | Datos de contacto |
| `simbolo_moneda` | VARCHAR | Símbolo monetario |

#### `tbl_categoria`

`id_categoria` PK, `nombre_categoria`.

#### `tbl_producto`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_producto` | INT PK | |
| `nombre_producto` | VARCHAR | |
| `precio_compra` | DECIMAL | Precio base |
| `precio_venta` | DECIMAL | Precio público |
| `codigo` | VARCHAR(13) | EAN-13 validado |
| `categoria` | INT FK → `tbl_categoria` | |
| `imagen` | VARCHAR | Ruta en `uploads/` |
| `detalles` | TEXT | Descripción |
| `talla` | VARCHAR | "NA" cuando no aplica |
| `tiene_variantes` | TINYINT | 0 simple / 1 con variantes |

#### `tbl_producto_stock`

Stock de productos **simples** por sucursal.

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_producto_stock` | INT PK | |
| `id_producto` | INT FK | |
| `id_sucursal` | INT FK | |
| `stock` | INT | |

> **UNIQUE** sugerido sobre `(id_producto, id_sucursal)`.

#### `tbl_producto_variante`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_variante` | INT PK | |
| `id_producto` | INT FK | |
| `talla` | VARCHAR | |
| `precio_compra`, `precio_venta` | DECIMAL | |
| `stock` | INT | Stock global (ver `tbl_stock_variante` para por-sucursal) |
| `orden` | INT | Orden de despliegue |
| `activo` | TINYINT | 0/1 |

#### `tbl_stock_variante`

Stock de variantes por sucursal.

| Columna | Tipo |
|---------|------|
| `id_variante` | INT FK |
| `id_sucursal` | INT FK |
| `stock` | INT |

#### `tbl_cliente`

| Columna | Tipo |
|---------|------|
| `id_cliente` | INT PK |
| `nombre` | VARCHAR |
| `correo`, `doc_identidad`, `celular` | VARCHAR |
| `id_sucursal` | INT FK |

#### `tbl_proveedor`

`id_proveedor`, `nombre`, `correo`, `doc_identidad`, `celular`.

#### `tbl_metodo_pago`

`id_metodo_pago`, `nombre_metodo_pago`, `id_sucursal`.

#### `tbl_empleado`

`id_empleado`, `nombre`, datos de contacto, `id_sucursal`.

---

### 2.3 Operación de ventas

#### `tbl_venta`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_venta` | INT PK | |
| `fecha_venta` | DATETIME | |
| `id_cliente` | INT FK | |
| `descuento` | DECIMAL | |
| `base_imponible` | DECIMAL | Subtotal sin impuesto |
| `impuesto` | DECIMAL | Monto impuesto |
| `total` | DECIMAL | Total final |
| `id_usuario` | INT FK | Vendedor |
| `tipo_pago` | VARCHAR | `contado` \| `crédito` |
| `id_metodo_pago` | INT FK | |
| `saldo` | DECIMAL | Pendiente en crédito (0 en contado) |
| `monto_recibido`, `cambio` | DECIMAL | Efectivo y vuelto |
| `id_sucursal` | INT FK | |
| `tipo_venta` | VARCHAR | `normal` \| `apartado` |
| `estado_apartado` | VARCHAR | `en_proceso` \| `entregado` \| `cancelado` |
| `anticipo` | DECIMAL | Pago inicial en apartado |

#### `tbl_detalle_venta`

| Columna | Tipo |
|---------|------|
| `id_detalle_venta` | INT PK |
| `id_venta` | INT FK |
| `id_producto` | INT FK |
| `id_variante` | INT FK nullable |
| `precio_venta` | DECIMAL |
| `cantidad` | INT |
| `sub_total` | DECIMAL |

#### `tbl_cuota`

Abonos para ventas a crédito y apartados.

| Columna | Tipo |
|---------|------|
| `id_cuota` | INT PK |
| `id_venta` | INT FK |
| `cuota` | DECIMAL |
| `fecha_pago` | DATETIME |

---

### 2.4 Compras / entradas de inventario

#### `tbl_compra`

| Columna | Tipo |
|---------|------|
| `id_compra` | INT PK |
| `fecha_compra` | DATETIME |
| `proveedor` | INT FK |
| `nota` | TEXT |
| `total` | DECIMAL |
| `id_usuario` | INT FK |
| `id_sucursal` | INT FK |

#### `tbl_detalle_compra`

| Columna | Tipo |
|---------|------|
| `id_detalle_compra` | INT PK |
| `id_compra` | INT FK |
| `id_producto` | INT FK |
| `precio_compra` | DECIMAL |
| `cantidad` | INT |
| `sub_total` | DECIMAL |

---

### 2.5 Traslados

#### `tbl_traslado`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_traslado` | INT PK | |
| `fecha_actual` | DATETIME | |
| `comentario` | VARCHAR | |
| `id_usuario` | INT FK | Quien generó |
| `id_sucursal_descuento` | INT FK | **Origen** |
| `id_sucursal_aumento` | INT FK | **Destino** |

> No existe campo `estado` — el traslado se considera completado al insertarse (es atómico).

#### `tbl_detalle_traslado`

| Columna | Tipo |
|---------|------|
| `id_detalle_traslado` | INT PK |
| `id_traslado` | INT FK |
| `id_producto` | INT FK |
| `id_variante` | INT FK nullable |
| `cantidad` | INT |

---

### 2.6 Caja

#### `tbl_caja`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_caja` | INT PK | |
| `fecha_apertura` | DATETIME | |
| `fecha_cierre` | DATETIME nullable | Null si abierta |
| `monto_apertura` | DECIMAL | Fondo inicial |
| `saldo` | DECIMAL | Saldo corriente |
| `estado` | VARCHAR | `abierto` \| `cerrado` |
| `id_sucursal` | INT FK | |
| `id_usuario` | INT FK | Quien abre |
| `id_usuario_cierre` | INT FK nullable | Quien cierra |

#### `tbl_ingreso`

`id_ingreso`, `descripcion`, `monto`, `fecha`, `id_sucursal`.

#### `tbl_gasto`

`id_gasto`, `descripcion`, `monto`, `fecha`, `id_sucursal`.

---

## 3. Bitácora de inventario

**No existe** una tabla física `tbl_movimiento_inventario`. La bitácora se reconstruye en tiempo de consulta con `UNION ALL` sobre:

| Origen | Signo | Filtro |
|--------|-------|--------|
| `tbl_detalle_venta` ↔ `tbl_venta` | − salida | `id_sucursal` |
| `tbl_detalle_compra` ↔ `tbl_compra` | + entrada | `id_sucursal` |
| `tbl_detalle_traslado` ↔ `tbl_traslado` | − origen / + destino | `id_sucursal_descuento` / `id_sucursal_aumento` |

> Cualquier nueva fuente de movimiento (ajustes, mermas, devoluciones) debe añadirse al `UNION` en `Movimiento_inventario_model::getMovimientos()`.

---

## 4. Índices recomendados (alto volumen)

Para entornos con volumen significativo, se recomienda verificar/crear:

1. `tbl_producto_stock (id_producto, id_sucursal)` — **UNIQUE** (lookup de stock).
2. `tbl_venta (id_sucursal, fecha_venta)` — agregaciones por período/sucursal.
3. `tbl_detalle_venta (id_venta)` y `tbl_detalle_venta (id_producto)` — joins de reportes.

---

## 5. Reglas de integridad operativas

1. Toda modificación de stock debe pasar por los métodos del modelo (`incrementar_stock_*` / `decrementar_stock_*`); **nunca** `UPDATE` directo en controlador.
2. Las operaciones multi-tabla (venta, compra, traslado) deben envolverse en transacción.
3. `id_sucursal` debe validarse contra la sesión en cada operación; no aceptar `id_sucursal` arbitrario desde input HTTP excepto en flujos administrativos explícitos.
4. Las eliminaciones deben preferir borrado lógico (`isDeleted = 1`) cuando exista esa columna.
5. Validar `tiene_variantes` antes de manipular `tbl_producto_stock` vs. `tbl_stock_variante` — son excluyentes.

---

## 6. Convenciones de naming

- Tablas en singular con prefijo `tbl_`.
- Claves primarias: `id_<entidad>` (excepto `tbl_users.userId` y `tbl_roles.roleId` heredados del scaffold original).
- FKs con el mismo nombre que la PK referenciada.
- Booleanos como `TINYINT(1)`.
- Fechas con sufijo `Dtm` (legado) o nombre semántico (`fecha_venta`, `fecha_compra`).

---

## 7. Referencias

- [Arquitectura](arquitectura.md)
- [Módulo Ventas](modulos/ventas.md) — flujo de venta y manipulación de stock
- [Módulo Traslados](modulos/traslados.md) — transacción de traslado
