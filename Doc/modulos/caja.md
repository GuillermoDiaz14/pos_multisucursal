# Módulo: Caja

> Gestiona la sesión de caja por sucursal y por cajero: apertura con fondo inicial, movimientos (ventas, ingresos, gastos) y cierre con arqueo.

- **Controlador:** `application/controllers/Caja.php`
- **Modelo:** `application/models/Caja_model.php`
- **Vistas:** `application/views/caja/`
- **Módulo de permisos:** `Caja`

---

## 1. Conceptos

- **Caja**: sesión operativa con estado `abierto` o `cerrado`. Solo una caja abierta por par `(sucursal, usuario)`.
- **`monto_apertura`**: fondo inicial al abrir; no varía durante la sesión.
- **`saldo`**: monto corriente; se incrementa con ventas/ingresos y se decrementa con gastos/retiros.
- **Cierre**: registra `fecha_cierre`, `id_usuario_cierre`, marca `estado = 'cerrado'`.

---

## 2. Apertura

### 2.1 Flujo

```
1. Usuario accede a /caja/add
2. Caja_model::getCajaAbiertaPorSucursal($id_sucursal, $id_usuario)
   ├─ Si retorna caja: mostrar estado actual (no permitir abrir otra)
   └─ Si null: mostrar formulario de apertura
3. POST /caja/addNewCaja
   ├─ Valida monto_apertura >= 0
   ├─ Re-verifica que NO haya caja abierta (race condition)
   └─ INSERT tbl_caja en transacción
```

### 2.2 Datos requeridos

| Campo | Origen |
|-------|--------|
| `monto_apertura` | Input usuario |
| `saldo` | = `monto_apertura` (inicial) |
| `estado` | `'abierto'` |
| `id_sucursal` | Sesión |
| `id_usuario` | Sesión |
| `fecha_apertura` | `NOW()` |

### 2.3 Validaciones

- `monto_apertura >= 0`.
- No existe otra caja abierta para `(id_sucursal, id_usuario)`.
- Permiso `Caja.total_access = 1`.

---

## 3. Movimientos durante la sesión

Cualquier operación que afecte efectivo debe **sumar o restar a `saldo`** dentro de la misma transacción que genera el registro:

| Operación | Δ saldo |
|-----------|---------|
| Venta contado | `+ total` |
| Abono crédito | `+ cuota` |
| Anticipo apartado | `+ anticipo` |
| Ingreso (extra) | `+ monto` |
| Gasto | `− monto` |
| Devolución | `− total` |

> **Crítico:** si una venta falla parcialmente y se hace rollback, el saldo no debe quedar tocado. Mantener la actualización de caja dentro de la misma transacción que la venta.

---

## 4. Cierre / arqueo

### 4.1 Flujo

```
1. Usuario solicita cierre
2. Sistema calcula:
   ├─ Saldo teórico = monto_apertura + Σ(movimientos)
   ├─ Detalle de ventas, ingresos, gastos del período
   └─ Diferencia esperada vs efectivo declarado
3. UPDATE tbl_caja
   ├─ estado = 'cerrado'
   ├─ fecha_cierre = NOW()
   ├─ id_usuario_cierre = sesión
   └─ (opcional) saldo_final declarado
```

### 4.2 Método modelo

`Caja_model::cerrarCaja($id_sucursal, $id_usuario_cierre, $id_usuario)`

---

## 5. Multi-cajero

El sistema soporta **una caja por usuario** dentro de la misma sucursal. Implicación:

- Dos cajeros en la misma sucursal abren cajas distintas.
- Cada venta queda asociada al `id_usuario` del cajero que la realizó.
- El cierre lo puede hacer el propio cajero o un usuario con permiso (`id_usuario_cierre` distinto).

> Validar siempre que la caja afectada por una venta sea la del usuario que la realiza, no cualquiera abierta en la sucursal.

---

## 6. Métodos clave

### Controller

| Método | Función |
|--------|---------|
| `add()` | Formulario o estado de caja activa |
| `addNewCaja()` | Crear apertura |
| `add_reparacion()` / `addNewCajaReparacion()` | Apertura por reparación (legado) |

### Model

| Método | Función |
|--------|---------|
| `addNewCaja($info)` | INSERT en transacción |
| `getCajaInfo($id_caja)` | Lookup por ID |
| `getCajaAbiertaPorSucursal($id_sucursal, $id_usuario)` | Caja abierta del cajero |
| `cerrarCaja(...)` | Cierre |
| `cajaListing($search, $limit, $offset)` | Historial de cajas cerradas |

---

## 7. Tabla `tbl_caja`

| Columna | Tipo | Notas |
|---------|------|-------|
| `id_caja` | INT PK | |
| `fecha_apertura` | DATETIME | |
| `fecha_cierre` | DATETIME nullable | |
| `monto_apertura` | DECIMAL | Inmutable |
| `saldo` | DECIMAL | Variable |
| `estado` | VARCHAR | `abierto` \| `cerrado` |
| `id_sucursal` | INT FK | |
| `id_usuario` | INT FK | Apertura |
| `id_usuario_cierre` | INT FK nullable | Cierre |

---

## 8. Reportes asociados

Definidos en `application/config/reports.php`:

- `caja_operativa` — flujo de efectivo por sucursal/período.
- `flujo_total` — totales por concepto.
- `historial_cajas` — lista de cajas cerradas con totales.

Ver [Reportes](reportes.md).

---

## 9. Errores comunes

| Error | Causa | Solución |
|-------|-------|----------|
| Dos cajas abiertas para el mismo cajero | Validación previa sin lock | Validar dentro de transacción, considerar UNIQUE parcial `(id_sucursal, id_usuario, estado='abierto')` |
| Saldo no cuadra al cierre | Movimientos fuera de transacción | Asegurar que ventas/gastos/ingresos actualicen `saldo` dentro de la misma transacción |
| No se puede vender | Caja cerrada o de otro cajero | Verificar `getCajaAbiertaPorSucursal` por `id_usuario` actual |
| Caja queda abierta tras crash | Cierre interrumpido | Permitir cierre manual con permiso elevado |

---

## 10. Referencias

- [Modelo de datos — tbl_caja](../modelo_datos.md#tbl_caja)
- [Módulo Ventas](ventas.md)
- [Módulo Reportes](reportes.md)
