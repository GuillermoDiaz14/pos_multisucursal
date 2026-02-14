Sistema Punto de Venta Web - PHP Multisucursal

Descripción del Proyecto

Sistema de punto de venta (POS) web desarrollado en PHP con arquitectura multisucursal, construido sobre el framework CodeIgniter 3. La aplicación proporciona un panel de administración robusto con gestión de usuarios basada en roles, control de inventario multialmacén, procesamiento de ventas y reportes avanzados.

Características Técnicas Principales

🏗️ Arquitectura

Framework MVC: CodeIgniter 3.1.9+ (PHP 5.6+)
Base de datos: MySQL 5.1+ / MySQLi
Frontend: AdminLTE Bootstrap Theme + jQuery
Autenticación: Sistema RBAC (Role-Based Access Control)
Multisucursal: Gestión centralizada con aislamiento de datos por sucursal
👥 Gestión de Usuarios

Sistema de autenticación seguro con hash bcrypt
Roles personalizables con permisos granulares
Historial de sesiones y auditoría de acceso
Recuperación de contraseña por email
Perfiles de usuario multisucursal
🏪 Funcionalidades Multisucursal

Inventario independiente por sucursal
Transferencias entre sucursales
Reportes consolidados y por sucursal
Configuración específica por ubicación
Control de acceso basado en sucursal
💰 Módulo de Ventas

Interfaz POS optimizada para rápido procesamiento
Gestión de clientes y historial de compras
Múltiples métodos de pago
Impresión de tickets y facturas
Cierre de caja por turno y sucursal
📦 Gestión de Inventario

Control de stock multialmacén
Sistema de categorías y subcategorías
Alertas de stock mínimo
Movimientos de inventario con trazabilidad
Gestión de proveedores
📊 Reportes y Analytics

Dashboard ejecutivo con KPIs
Reportes de ventas por período y sucursal
Análisis de inventario y rotación
Reportes financieros
Exportación a Excel/PDF
Requisitos del Sistema

Servidor

PHP: Versión 5.6 o superior (recomendado 7.2+)
Extensión PHP: MySQLi, OpenSSL, MBstring, JSON
Servidor Web: Apache con mod_rewrite habilitado
Base de datos: MySQL 5.1+ o MariaDB 10.0+

API Reference

Endpoints Principales
POST   /api/v1/login        # Autenticación de usuario
GET    /api/v1/products     # Listado de productos
POST   /api/v1/sales        # Crear nueva venta
GET    /api/v1/reports      # Generar reportes


Estructura del Proyecto

pos-multisucursal/
├── application/
│   ├── config/          # Configuraciones del sistema
│   ├── controllers/     # Controladores MVC
│   │   ├── Admin/       # Panel de administración
│   │   ├── Pos/         # Punto de venta
│   │   ├── Inventory/   # Gestión de inventario
│   │   └── Reports/     # Sistema de reportes
│   ├── models/          # Modelos de datos
│   │   ├── User_model.php
│   │   ├── Branch_model.php
│   │   ├── Product_model.php
│   │   └── Sale_model.php
│   ├── views/           # Vistas y templates
│   └── libraries/       # Librerías personalizadas
├── system/              # Core de CodeIgniter
├── assets/
│   ├── css/             # Estilos personalizados
│   ├── js/              # Scripts JavaScript
│   └── images/          # Imágenes y recursos
├── uploads/             # Archivos subidos
├── database/            # Scripts y migraciones
└── documentation/       # Documentación técnica