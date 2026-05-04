# Manual de Configuración — Impresoras Zebra ZD421

**Sistema:** POS Multisucursal  
**Impresoras:** 2× Zebra ZD421-203dpi ZPL  
**Roles:** una para tickets (80mm), una para etiquetas (39×16mm)

> ⚠️ **Cada sucursal configura sus propias impresoras desde el panel de administración.**  
> No hay que editar ningún archivo de código. Los nombres se guardan en la base de datos por sucursal.

---

## 1. Requisitos previos

- **Zebra Browser Print** instalado y ejecutándose en la PC de caja de cada sucursal
  - Descarga: https://www.zebra.com/us/en/support-downloads/software/printer-software/browser-print.html
  - Debe quedar corriendo en segundo plano (icono en la bandeja del sistema)
  - ⚠️ Se instala **en cada PC de caja**, no en el servidor
- Ambas impresoras conectadas por USB a la PC de caja
- Rollos correctos instalados:
  - **Tickets**: rollo continuo 80mm de ancho
  - **Etiquetas**: rollo 39mm × 16mm (con gap entre etiquetas)

---

## 2. Instalación física de las impresoras

### 2.1 Cargar el rollo
1. Abre la tapa frontal de la impresora
2. Coloca el rollo en el soporte interior
3. Pasa el papel por debajo del cabezal y por las guías
4. **Ajusta las guías** al ancho del rollo (si quedan flojas el papel se tuerce)
5. Cierra la tapa

> 💡 El lado térmico (brillante) debe quedar hacia **abajo**, contra el cabezal. Si imprime en blanco, el rollo está al revés.

### 2.2 Calibración de sensores (obligatorio al cambiar rollo)
1. **Apaga** la impresora
2. Mantén presionado el **botón Feed**
3. **Enciende** la impresora sin soltar el botón
4. Espera hasta que el LED parpadee **una vez** → suelta el botón
5. La impresora avanzará papel automáticamente para calibrar el gap
6. Cuando se detenga, la calibración está completa

> ⚠️ Si omites la calibración, las etiquetas/tickets saldrán desalineados.

---

## 3. Configurar tamaño de etiqueta (impresora de etiquetas, una sola vez por equipo)

Esto le dice a la impresora que el rollo mide 39mm × 16mm.

1. Con la PC encendida y Zebra Browser Print corriendo, abre Chrome
2. Ve a `https://localhost:9101` y acepta el certificado si lo pide
3. Abre la consola del navegador (F12 → Console) y ejecuta:

```javascript
// Paso 1: ver impresoras disponibles
fetch('https://localhost:9101/available')
  .then(r => r.json())
  .then(d => console.log(d.printer.map(p => p.name)));
```

4. Identifica cuál es la impresora de etiquetas (ver sección 4 para distinguirlas)
5. Ejecuta el siguiente comando reemplazando `SERIE_ETIQUETAS` con el nombre exacto que apareció:

```javascript
fetch('https://localhost:9101/available')
  .then(r => r.json())
  .then(d => {
    var device = d.printer.find(p => p.name.includes('SERIE_ETIQUETAS'));
    return fetch('https://localhost:9101/write', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        device: device,
        data: '^XA^MMT^PW312^LL128^MFA^XZ'
      })
    });
  })
  .then(r => r.text())
  .then(t => console.log('Resultado:', t));
```

- `^PW312` = 39mm × 8 dots/mm = 312 dots de ancho  
- `^LL128` = 16mm × 8 dots/mm = 128 dots de alto  
- `^MFA` = modo arranque manual (tear-off)

---

## 4. Identificar los nombres exactos de cada impresora

1. Con ambas impresoras conectadas y Zebra Browser Print corriendo, abre:
   ```
   https://localhost:9101/available
   ```
2. Verás un JSON con la lista. Ejemplo:
   ```json
   {
     "printer": [
       { "name": "ZD421-203dpi ZPL (D8N231501292)", "uid": "usb#vid_..." },
       { "name": "ZD421-203dpi ZPL (D8N231501328)", "uid": "usb#vid_..." }
     ]
   }
   ```
3. El número entre paréntesis es el **número de serie** (está en la etiqueta del fondo de cada impresora)
4. Para saber cuál es cuál: desconecta una impresora → recarga `/available` → el que queda es el que está conectado. Repite con la otra.

---

## 5. Configurar las impresoras en el POS (por sucursal)

> Esta es la parte nueva. Ya no se edita ningún archivo — todo se configura desde el panel.

1. Inicia sesión en el POS como **administrador**
2. Ve al menú **Sucursal → Lista de sucursales**
3. Haz clic en **Editar** en la sucursal que quieres configurar
4. Al final del formulario verás la sección **"Impresoras Zebra (por sucursal)"**:
   - **Impresora de Tickets (80mm):** pega el nombre exacto de la impresora de tickets  
     Ejemplo: `ZD421-203dpi ZPL (D8N231501292)`
   - **Impresora de Etiquetas (39×16mm):** pega el nombre exacto de la impresora de etiquetas  
     Ejemplo: `ZD421-203dpi ZPL (D8N231501328)`
5. Haz clic en **Editar** para guardar
6. **Cierra sesión y vuelve a iniciar sesión** para que los cambios surtan efecto en tu sesión activa

> 💡 Si el administrador que edita la sucursal ya tiene sesión activa en esa sucursal, los nombres se actualizan automáticamente sin necesidad de re-login.

> ⚠️ El nombre debe ser **exactamente igual** al que aparece en `/available`, incluyendo mayúsculas, espacios y el número de serie entre paréntesis.

---

## 6. Repetir por cada sucursal

Cada sucursal es independiente:

| Sucursal | PC de caja | Zebra Browser Print | Impresoras configuradas en POS |
|----------|------------|---------------------|-------------------------------|
| Sucursal 1 | PC-1 | Corriendo en PC-1 | Nombres de las impresoras de PC-1 |
| Sucursal 2 | PC-2 | Corriendo en PC-2 | Nombres de las impresoras de PC-2 |

Repite los pasos 1–5 para cada sucursal con sus propias impresoras.

---

## 7. Verificación final

1. Inicia sesión en el POS desde la PC de caja (donde están las impresoras)
2. Abre la consola del navegador (F12 → Console)
3. Al cargar la página deberías ver:
   ```
   [Zebra] Impresoras disponibles: ["ZD421-203dpi ZPL (XXXX)", "ZD421-203dpi ZPL (YYYY)"]
   ```
4. Realiza una venta de prueba y presiona **Imprimir Ticket** — debe imprimir sin diálogos
5. Si ves este error:
   ```
   [Zebra] Impresora de tickets NO encontrada: ZD421-203dpi ZPL (XXXX)
   ```
   → El nombre guardado en el POS no coincide con el de `/available`. Vuelve al paso 5 y cópialo exactamente.

---

## 8. Solución de problemas

| Problema | Causa probable | Solución |
|----------|---------------|----------|
| "No se pudo conectar a Zebra Browser Print" | El servicio no está corriendo | Busca "Zebra Browser Print" en el menú inicio y ábrelo |
| Impresora no encontrada en consola del POS | Nombre no coincide exactamente | Copia el nombre desde `/available` y pégalo en Sucursal → Editar |
| Etiquetas/tickets desalineados | Calibración pendiente | Realiza la calibración del paso 2.2 |
| Solo aparece una impresora en `/available` | USB desconectado o impresora apagada | Revisa cables y que ambas estén encendidas |
| Imprime en blanco | Rollo cargado al revés | El lado térmico (brillante) debe quedar hacia abajo |
| Configuré las impresoras pero no imprime | Sesión antigua sin los nuevos datos | Cierra sesión y vuelve a entrar |

---

## 9. Números de serie de referencia (instalación original)

| Rol | Modelo | Serie |
|-----|--------|-------|
| Tickets (80mm) | ZD421-203dpi ZPL | D8N231501292 |
| Etiquetas (39×16mm) | ZD421-203dpi ZPL | D8N231501328 |

> Estos son los números de serie del equipo de desarrollo. En producción usa los números de los equipos físicos instalados en cada sucursal.
