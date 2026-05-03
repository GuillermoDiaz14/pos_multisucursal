# Manual de Configuración — Impresoras Zebra ZD421

**Sistema:** POS Multisucursal  
**Impresoras:** 2× Zebra ZD421-203dpi ZPL  
**Roles:** una para tickets (80mm), una para etiquetas (39×16mm)

---

## 1. Requisitos previos

- Zebra Browser Print instalado y ejecutándose en la PC del cajero
  - Descarga: https://www.zebra.com/us/en/support-downloads/software/printer-software/browser-print.html
  - Debe quedar corriendo en segundo plano (icono en la bandeja del sistema)
- Ambas impresoras conectadas por USB a la misma PC
- Rollos correctos instalados en cada impresora:
  - **Tickets**: rollo continuo 80mm de ancho
  - **Etiquetas**: rollo 39mm × 16mm (con gap entre etiquetas)

---

## 2. Instalación física

### 2.1 Cargar el rollo
1. Abre la tapa frontal de la impresora
2. Coloca el rollo en el soporte interior
3. Pasa el papel por debajo del cabezal y por las guías
4. **Ajusta las guías** al ancho del rollo (importantes: si quedan flojas el papel se tuerce)
5. Cierra la tapa

### 2.2 Calibración de sensores (obligatorio al cambiar rollo)
1. **Apaga** la impresora
2. Mantén presionado el **botón Feed**
3. **Enciende** la impresora sin soltar el botón
4. Espera hasta que el LED parpadee **una vez** → suelta el botón
5. La impresora avanzará papel automáticamente para calibrar el gap
6. Cuando se detenga, la calibración está completa

> ⚠️ Si omites la calibración, las etiquetas/tickets saldrán desalineados.

---

## 3. Configurar el tamaño de etiqueta (impresora de etiquetas, una sola vez)

Abre un navegador y ve a: `https://localhost:9101`

En la consola del navegador (F12 → Console) ejecuta este fetch para enviar el comando de configuración:

```javascript
fetch('https://localhost:9101/available')
  .then(r => r.json())
  .then(d => console.log(d.printer.map(p => p.name)));
```

Copia el `uid` de la impresora de etiquetas, luego envía:

```javascript
fetch('https://localhost:9101/write', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    device: { uid: 'PEGA_AQUI_EL_UID_DE_ETIQUETAS' },
    data: '^XA^MMT^PW312^LL128^MFA^XZ'
  })
});
```

- `^PW312` = 39mm × 8 dots/mm = 312 dots de ancho  
- `^LL128` = 16mm × 8 dots/mm = 128 dots de alto  
- `^MFA` = modo arranque manual (tear-off)

---

## 4. Identificar los nombres de las impresoras

1. Con ambas impresoras conectadas y Zebra Browser Print corriendo, abre:
   ```
   https://localhost:9101/available
   ```
2. Verás un JSON con la lista de impresoras. Ejemplo:
   ```json
   {
     "printer": [
       { "name": "ZD421-203dpi ZPL (D8N231501292)", "uid": "usb#vid_..." },
       { "name": "ZD421-203dpi ZPL (D8N231501328)", "uid": "usb#vid_..." }
     ]
   }
   ```
3. El número entre paréntesis es el **número de serie** de la impresora (está en la etiqueta del fondo del equipo)

---

## 5. Actualizar configuración en el POS

Edita el archivo:
```
application/config/zebra_printers.php
```

Cambia los valores por los nombres que aparecieron en el paso 4:

```php
// Impresora de TICKETS (rollo 80mm) — verifica el número de serie
$config['zebra_ticket_printer'] = 'ZD421-203dpi ZPL (SERIE_TICKETS)';

// Impresora de ETIQUETAS (rollo 39x16mm) — verifica el número de serie
$config['zebra_label_printer']  = 'ZD421-203dpi ZPL (SERIE_ETIQUETAS)';
```

> 💡 Para saber cuál impresora tiene qué rol: desconecta una, ve a `/available`, el que queda es el que está conectado. Repite con la otra.

---

## 6. Verificación final

1. Abre el POS en el navegador
2. Abre la consola del navegador (F12 → Console)
3. Deberías ver al cargar la página:
   ```
   [Zebra] Impresoras disponibles: ["ZD421-203dpi ZPL (XXXX)", "ZD421-203dpi ZPL (YYYY)"]
   ```
4. Si alguna impresora no se encuentra, verás:
   ```
   [Zebra] Impresora de tickets NO encontrada: ZD421-203dpi ZPL (XXXX)
   ```
   → Revisa que el nombre en `zebra_printers.php` coincida exactamente con el que aparece en `/available`

---

## 7. Solución de problemas frecuentes

| Problema | Causa | Solución |
|----------|-------|----------|
| "No se pudo conectar a Zebra Browser Print" | El servicio no está corriendo | Busca "Zebra Browser Print" en el menú inicio y ábrelo |
| Etiquetas/tickets desalineados | Calibración pendiente | Realiza el proceso de calibración del paso 2.2 |
| Solo aparece una impresora en `/available` | Cable USB desconectado o impresora apagada | Revisa conexiones y que ambas estén encendidas |
| "Impresora no encontrada" en consola del POS | Nombre en config no coincide | Copia el nombre exacto desde `/available` y pégalo en `zebra_printers.php` |
| Impresora imprime pero sale en blanco | Rollo cargado al revés | El lado térmico (brillante) debe quedar hacia abajo, contra el cabezal |

---

## 8. Números de serie de referencia (instalación original)

| Rol | Modelo | Serie |
|-----|--------|-------|
| Tickets (80mm) | ZD421-203dpi ZPL | D8N231501292 |
| Etiquetas (39×16mm) | ZD421-203dpi ZPL | D8N231501328 |

> Estos son los números de serie de las impresoras originales. En producción usa los de los equipos instalados allá.
