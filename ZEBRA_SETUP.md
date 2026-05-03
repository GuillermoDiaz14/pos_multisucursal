# Configuración de Impresora Zebra ZD421 (USB)

## Cambios realizados

1. **CSS optimizado** (`application/views/carrito/ticket_view.php`)
   - Ancho exacto: 80mm
   - Márgenes cero
   - Formato PDF nativo

2. **Impresión con html2pdf + autoprint**
   - Genera PDF dinámicamente
   - Abre diálogo una sola vez
   - Activa autoprint automáticamente

## Configuración

### Paso 1: Instalar controladores (Windows)
1. Descargar driver Zebra ZD421 desde: https://www.zebra.com/en/us/products/printers/desktop/zd421t.html
2. Conectar impresora por USB
3. Instalar driver

### Paso 2: Configurar como impresora por defecto

**Windows:**
1. `Configuración` → `Dispositivos` → `Impresoras y escáneres`
2. Click en Zebra ZD421
3. Seleccionar "Establecer como predeterminada"

**Mac:**
1. `System Preferences` → `Printers & Scanners`
2. Seleccionar Zebra ZD421
3. Click "Set default printer"

### Paso 3: Probar

1. Abre POS → Realizar venta
2. Click en "Imprimir"
3. El navegador abre diálogo de impresión automáticamente
4. Verifica que esté seleccionada la Zebra ZD421
5. Click "Imprimir"

## Flujo de impresión

```
Click "Imprimir" 
  ↓
html2pdf genera PDF (80mm x altura dinámica)
  ↓
Abre diálogo print del navegador
  ↓
Selecciona Zebra ZD421 (ya predeterminada)
  ↓
Click Imprimir
  ↓
Ticket listo
```

## Optimizaciones aplicadas

- **Sin diálogos de área de impresión**: PDF ya tiene tamaño correcto
- **Sin "Tamaño real"**: html2pdf configura escala automáticamente
- **Una sola selección**: Impresora por defecto = no cambiar cada vez
- **Altura flexible**: Se adapta al número de productos

## Solución de problemas

| Problema | Solución |
|----------|----------|
| Aún pide área de impresión | Verifica que PDF esté a 80mm en diálogo |
| Espacios en blanco | Revisa márgenes en `@page { margin: 0 }` |
| Producto cortado | Aumenta altura dinámica en html2pdf |
| Impresora no aparece | Reinstala driver y recarga navegador (F5) |

## Archivos modificados

- `application/views/carrito/ticket_view.php` - CSS + JavaScript html2pdf
- `application/views/compra/ticket_view.php` - Igual que carrito
