# Documentación técnica — POS Multisucursal

Documentación interna del sistema. Para una vista general empezar por el [README principal](../README.md).

---

## Índice

### Documentos transversales

| Documento | Contenido |
|-----------|-----------|
| [arquitectura.md](arquitectura.md) | Capas MVC, flujo de petición, `BaseController`, multisucursalidad, transacciones |
| [modelo_datos.md](modelo_datos.md) | Diccionario de datos, mapa lógico, índices recomendados |
| [seguridad.md](seguridad.md) | Autenticación, autorización, matriz de permisos, acceso de emergencia |
| [instalacion.md](instalacion.md) | Guía paso a paso de instalación, configuración y despliegue |

### Módulos

| Módulo | Documento |
|--------|-----------|
| Ventas (POS, contado, crédito, apartado) | [modulos/ventas.md](modulos/ventas.md) |
| Caja (apertura, movimientos, cierre) | [modulos/caja.md](modulos/caja.md) |
| Traslados de inventario entre sucursales | [modulos/traslados.md](modulos/traslados.md) |
| Productos (catálogo, variantes, EAN-13) | [modulos/productos.md](modulos/productos.md) |
| Reportes operativos y administrativos | [modulos/reportes.md](modulos/reportes.md) |

### Periféricos

| Documento | Contenido |
|-----------|-----------|
| [manual_impresoras_zebra.md](manual_impresoras_zebra.md) | Configuración y uso de impresoras Zebra |
| [../ZEBRA_SETUP.md](../ZEBRA_SETUP.md) | Setup operativo de impresoras |

### Especificaciones

| Documento | Contenido |
|-----------|-----------|
| [../specs.md](../specs.md) | Especificaciones funcionales |

---

## Mapa por audiencia

### Si vas a **instalar** el sistema

1. [instalacion.md](instalacion.md) — guía completa.
2. [seguridad.md](seguridad.md) — checklist pre-producción.

### Si vas a **operar** el sistema (usuario funcional)

1. [README principal](../README.md) — visión general.
2. Documentos por módulo según la operación: ventas, caja, traslados.

### Si vas a **desarrollar / mantener**

1. [arquitectura.md](arquitectura.md) — entender la estructura.
2. [modelo_datos.md](modelo_datos.md) — entender los datos.
3. [seguridad.md](seguridad.md) — checklist de nuevos módulos.
4. Módulo específico según lo que vayas a modificar.

### Si vas a **auditar seguridad**

1. [seguridad.md](seguridad.md) — modelo completo.
2. [arquitectura.md §3.1](arquitectura.md#31-basecontroller--applicationlibrariesbasecontrollerphp) — sesión y permisos.
3. [modelo_datos.md §2.1](modelo_datos.md#21-seguridad-y-administración) — tablas de seguridad.

---

## Convenciones de la documentación

- **Español** como idioma principal.
- **Markdown CommonMark** + tablas GitHub.
- Referencias entre documentos como links relativos.
- Bloques de código con lenguaje declarado (`php`, `sql`, `bash`, `json`).
- Capturas e imágenes en `Doc/img/` (si se agregan).

---

## Pendientes

- Diagrama ER en formato `.drawio` o `.png`.
- Documento de troubleshooting consolidado.
- Guía de migración entre versiones de BD.
- Manual de usuario funcional (no técnico) por módulo.
