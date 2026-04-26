# Checklist de Testing - Correcciones de BD

## Antes de Empezar
- [ ] Hacer backup de la BD (ya realizado: `bd_backup_20260426_010334.sql`)
- [ ] Borrar sesión en navegador para forzar nuevo login
- [ ] Verificar que XAMPP está corriendo (MySQL + Apache)

---

## 1. PRUEBA DE ACCESO AL SISTEMA

### 1.1 Login
- [ ] Acceder a `http://localhost/pos_multisucursal/`
- [ ] Ver que carga la página de login (sin errores)
- [ ] Login con usuario válido
- [ ] Seleccionar sucursal
- [ ] Ver que carga dashboard sin errores en consola

### 1.2 Navegación Básica
- [ ] Ver menú principal sin errores
- [ ] Acceder a "Ventas" → "Carrito"
- [ ] Acceder a "Reportes" → "Reporte operativo"
- [ ] Acceder a "Reportes" → "Reporte administrativo"

---

## 2. PRUEBA CRÍTICA: VENTAS (precio_venta DECIMAL)

### 2.1 Hacer una Venta de Contado
**Objetivo**: Verificar que los precios se registran como DECIMAL, no como string

1. [ ] Ir a Carrito
2. [ ] Seleccionar un producto con precio definido (ej: $100.00)
3. [ ] Verificar que se muestra el precio correctamente
4. [ ] Cambiar cantidad (ej: 3 unidades)
5. [ ] Verificar que el subtotal = 300.00 (no 100100100 o similar)
6. [ ] Seleccionar "Contado" como tipo de pago
7. [ ] Ingresar monto recibido (ej: 300.00)
8. [ ] Procesar venta
9. [ ] Ver mensaje de éxito y botón de imprimir ticket
10. [ ] **VERIFICACIÓN BD**:
    ```sql
    SELECT id_venta, total, base_imponible, precio_venta 
    FROM tbl_venta v 
    JOIN tbl_detalle_venta dv ON v.id_venta = dv.id_venta 
    ORDER BY v.id_venta DESC LIMIT 3;
    ```
    - Confirmar que `total` y `precio_venta` son números, no strings

### 2.2 Hacer una Venta de Crédito
1. [ ] Repetir pasos 1-6 de 2.1
2. [ ] Seleccionar "Crédito" como tipo de pago
3. [ ] Seleccionar cliente
4. [ ] Procesar venta
5. [ ] Ver mensaje de éxito
6. [ ] **VERIFICACIÓN BD**: Confirmar que aparece en `tbl_venta` con `id_cliente` (no `cliente`)
    ```sql
    SELECT id_venta, id_cliente, total 
    FROM tbl_venta 
    WHERE tipo_pago = 'credito' 
    ORDER BY id_venta DESC LIMIT 1;
    ```

### 2.3 Hacer una Venta a Plazos (Apartado)
1. [ ] Repetir pasos 1-6 de 2.1
2. [ ] Seleccionar "Apartado" como tipo de pago
3. [ ] Ingresar anticipo (ej: 100.00)
4. [ ] Seleccionar cliente
5. [ ] Procesar venta
6. [ ] Ver mensaje de éxito

---

## 3. PRUEBA IMPORTANTE: CANTIDADES (cantidad INT)

### 3.1 Venta con Diferentes Cantidades
1. [ ] Hacer venta con cantidad fraccionada (ej: 2.5 unidades)
2. [ ] Procesar venta
3. [ ] **VERIFICACIÓN BD**:
    ```sql
    SELECT id_detalle_venta, cantidad, precio_venta, sub_total 
    FROM tbl_detalle_venta 
    ORDER BY id_detalle_venta DESC LIMIT 1;
    ```
    - Confirmar que `cantidad` es INT (no float con decimales)
    - Confirmar que `sub_total` = `cantidad` × `precio_venta` correctamente

---

## 4. PRUEBA IMPORTANTE: REPORTES (id_cliente en JOINs)

### 4.1 Reporte Operativo
1. [ ] Ir a Reportes → Reporte operativo
2. [ ] Seleccionar rango de fechas (últimos 7 días)
3. [ ] Hacer clic en "Generar reporte"
4. [ ] **ESPERADO**: Ver tabla con ventas, incluyendo nombre del cliente
5. [ ] **VERIFICACIÓN**: 
    - [ ] Si sale error: revisar consola browser (F12)
    - [ ] Si muestra datos: verificar que nombre del cliente aparece correctamente
    - [ ] Los totales deben ser números y sumar correctamente

### 4.2 Reporte Administrativo
1. [ ] Ir a Reportes → Reporte administrativo
2. [ ] Ver gráficas y datos consolidados
3. [ ] **ESPERADO**: Las gráficas cargan sin errores
4. [ ] Hacer clic en cada sección (Ventas, Ingresos, Gastos)
5. [ ] Verificar que se carga información

---

## 5. PRUEBA IMPORTANTE: COMPRAS (Entrada)

### 5.1 Registrar una Compra
1. [ ] Ir a Operación → Entrada (Compra)
2. [ ] Seleccionar proveedor
3. [ ] Agregar productos con cantidad (ej: 10 unidades @ $50.00 c/u)
4. [ ] Verificar que el subtotal = 500.00
5. [ ] Procesar compra
6. [ ] **VERIFICACIÓN BD**:
    ```sql
    SELECT id_detalle_compra, cantidad, precio_compra, sub_total 
    FROM tbl_detalle_compra 
    ORDER BY id_detalle_compra DESC LIMIT 1;
    ```
    - Confirmar que `precio_compra` es DECIMAL(10,2)
    - Confirmar que `cantidad` es INT

---

## 6. PRUEBA: INVENTARIO (unique_producto_sucursal)

### 6.1 Verificar Stock Único por Sucursal
1. [ ] Ir a Catálogos → Producto
2. [ ] Seleccionar un producto
3. [ ] Ver detalles de stock
4. [ ] **VERIFICACIÓN BD**: Confirmar que hay máximo 1 registro por (producto, sucursal)
    ```sql
    SELECT id_producto, id_sucursal, COUNT(*) as count 
    FROM tbl_producto_stock 
    GROUP BY id_producto, id_sucursal 
    HAVING count > 1;
    ```
    - Debería retornar 0 filas (sin duplicados)

---

## 7. PRUEBA: ROLES (roleId SMALLINT)

### 7.1 Crear Nuevo Rol
1. [ ] Ir a Administración → Roles
2. [ ] Hacer clic en "Nuevo rol"
3. [ ] Ingresar nombre del rol (ej: "Supervisor Avanzado")
4. [ ] Asignar permisos
5. [ ] Guardar
6. [ ] Ver que se crea sin errores
7. [ ] **VERIFICACIÓN BD**:
    ```sql
    SELECT roleId, role FROM tbl_roles ORDER BY roleId DESC LIMIT 1;
    ```
    - Confirmar que `roleId` es SMALLINT y se incrementó correctamente

---

## 8. PRUEBA: MONTOS EN GENERAL (DECIMAL)

### 8.1 Ingresos/Gastos
1. [ ] Ir a Operación → Ingreso
2. [ ] Registrar ingreso con monto (ej: $250.50)
3. [ ] Guardar
4. [ ] Ir a Operación → Gasto
5. [ ] Registrar gasto con monto (ej: $75.75)
6. [ ] Guardar
7. [ ] **VERIFICACIÓN BD**:
    ```sql
    SELECT monto FROM tbl_ingreso ORDER BY id_ingreso DESC LIMIT 1;
    SELECT monto FROM tbl_gasto ORDER BY id_gasto DESC LIMIT 1;
    ```
    - Confirmar que `monto` es DECIMAL(10,2)

---

## 9. VERIFICACIÓN CONSOLA DE ERRORES

### 9.1 Abrir DevTools
- [ ] Presionar F12 en cualquier página
- [ ] Ver pestaña "Console"
- [ ] **ESPERADO**: No debe haber errores rojos (warnings amarillos están OK)
- [ ] Si hay errores: anotar y reportar

---

## 10. PRUEBA FINAL: FLUJO COMPLETO

### 10.1 Ciclo Completo de Venta
1. [ ] Hacer una compra (entrada)
2. [ ] Hacer una venta de ese producto
3. [ ] Ver reporte de ventas
4. [ ] Verificar en dashboard que aparecen las métricas
5. [ ] Exportar reporte a Excel/PDF (si está disponible)

---

## Resultados

### ✅ TODO PASÓ - Cambios Exitosos

Si todo test pasó exitosamente:
- [ ] Los cambios de tipos de datos están funcionando
- [ ] Las referencias a `id_cliente` funcionan correctamente
- [ ] Los reportes muestran datos correctamente
- [ ] No hay errores de sintaxis o lógica

### ⚠️ ALGUNAS PRUEBAS FALLARON

Si algo falló:
1. [ ] Anotar el número de prueba que falló (ej: "2.1", "4.1")
2. [ ] Anotar el mensaje de error exacto
3. [ ] Anotar qué esperabas ver vs. qué viste
4. [ ] Reportar para debugging

---

## Comandos SQL Rápidos para Verificación

```sql
-- Ver estructura actual de tbl_venta
DESCRIBE tbl_venta;

-- Ver últimas 3 ventas con cliente
SELECT v.id_venta, v.id_cliente, v.total, c.nombre, v.fecha_venta
FROM tbl_venta v
LEFT JOIN tbl_cliente c ON v.id_cliente = c.id_cliente
ORDER BY v.id_venta DESC LIMIT 3;

-- Ver tipos de datos críticos
SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME IN ('tbl_producto', 'tbl_detalle_venta', 'tbl_venta', 'tbl_roles')
AND COLUMN_NAME IN ('precio_venta', 'cantidad', 'roleId', 'id_cliente')
ORDER BY TABLE_NAME;
```

---

## Notas
- Los cambios fueron aplicados exitosamente en la BD
- El código PHP fue actualizado para usar los nuevos nombres de campos
- Se recomienda hacer backup antes de iniciar las pruebas (ya realizado)
