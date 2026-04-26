<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['reportList'] = array(
    array(
        'key' => 'ventas_diarias',
        'title' => 'Ventas diarias',
        'category' => 'Ventas',
        'description' => 'Resumen diario de ventas con tendencia y totales por fecha.',
        'icon' => 'fa-line-chart',
        'single_url' => 'reporte/reporte_venta_diario',
        'multi_url' => 'reporte/seleccionar_sucursal/ventas_diarias'
    ),
    array(
        'key' => 'ventas_periodo',
        'title' => 'Ventas por periodo',
        'category' => 'Ventas',
        'description' => 'Listado y filtros por fecha para analizar ventas por periodo.',
        'icon' => 'fa-calendar',
        'single_url' => 'reporte/reporte_venta_por_fecha',
        'multi_url' => 'reporte/seleccionar_sucursal/ventas_periodo'
    ),
    array(
        'key' => 'ventas_mensuales',
        'title' => 'Ventas mensuales',
        'category' => 'Ventas',
        'description' => 'Comportamiento mensual de ventas para ver tendencia y volumen.',
        'icon' => 'fa-area-chart',
        'single_url' => 'reporte/reporte_venta_mensual',
        'multi_url' => 'reporte/seleccionar_sucursal/ventas_mensuales'
    ),
    array(
        'key' => 'productos_mas_vendidos',
        'title' => 'Productos más vendidos',
        'category' => 'Ventas',
        'description' => 'Top de productos vendidos para detectar demanda y rotación.',
        'icon' => 'fa-bar-chart',
        'single_url' => 'reporte/reporte_venta_productos_mas_vendidos',
        'multi_url' => 'reporte/seleccionar_sucursal/productos_mas_vendidos'
    ),
    array(
        'key' => 'utilidad_estimada',
        'title' => 'Utilidad estimada',
        'category' => 'Ventas',
        'description' => 'Estimación de utilidad basada en costo y precio actual del producto.',
        'icon' => 'fa-money',
        'single_url' => 'reporte/reporte_ganancias_por_fecha',
        'multi_url' => 'reporte/seleccionar_sucursal/utilidad_estimada'
    ),
    array(
        'key' => 'ventas_por_vendedor',
        'title' => 'Ventas por vendedor',
        'category' => 'Ventas',
        'description' => 'Desempeño por vendedor con tickets, ventas y utilidad estimada.',
        'icon' => 'fa-users',
        'single_url' => 'reporte/ventas_por_vendedor',
        'multi_url' => 'reporte/seleccionar_sucursal/ventas_por_vendedor'
    ),
    array(
        'key' => 'compras_periodo',
        'title' => 'Compras por periodo',
        'category' => 'Compras',
        'description' => 'Control de compras por fecha y proveedor para cada sucursal.',
        'icon' => 'fa-shopping-cart',
        'single_url' => 'reporte/reporte_compra_por_fecha',
        'multi_url' => 'reporte/seleccionar_sucursal/compras_periodo'
    ),
    array(
        'key' => 'compras_mensuales',
        'title' => 'Compras mensuales',
        'category' => 'Compras',
        'description' => 'Vista mensual de compras para detectar tendencia y volumen.',
        'icon' => 'fa-area-chart',
        'single_url' => 'reporte/reporte_compra_mensual',
        'multi_url' => 'reporte/seleccionar_sucursal/compras_mensuales'
    ),
    array(
        'key' => 'compras_por_proveedor',
        'title' => 'Compras por proveedor',
        'category' => 'Compras',
        'description' => 'Concentrado de compras por proveedor con órdenes y monto acumulado.',
        'icon' => 'fa-industry',
        'single_url' => 'reporte/compras_por_proveedor',
        'multi_url' => 'reporte/seleccionar_sucursal/compras_por_proveedor'
    ),
    array(
        'key' => 'caja_operativa',
        'title' => 'Caja operativa',
        'category' => 'Caja y Flujo',
        'description' => 'Control operativo de efectivo por sucursal y periodo.',
        'icon' => 'fa-money',
        'single_url' => 'reporte/caja_operativa',
        'multi_url' => 'reporte/seleccionar_sucursal/caja_operativa'
    ),
    array(
        'key' => 'flujo_total',
        'title' => 'Flujo total',
        'category' => 'Caja y Flujo',
        'description' => 'Vista gerencial del negocio separando contado, crédito y apartado.',
        'icon' => 'fa-exchange',
        'single_url' => 'reporte/flujo_total',
        'multi_url' => 'reporte/seleccionar_sucursal/flujo_total'
    ),
    array(
        'key' => 'stock_actual',
        'title' => 'Stock actual',
        'category' => 'Inventario',
        'description' => 'Existencias actuales por producto, categoría y sucursal.',
        'icon' => 'fa-cubes',
        'single_url' => 'reporte/stock_actual',
        'multi_url' => 'reporte/seleccionar_sucursal/stock_actual'
    ),
    array(
        'key' => 'stock_bajo',
        'title' => 'Stock bajo',
        'category' => 'Inventario',
        'description' => 'Productos con pocas existencias para reposición rápida.',
        'icon' => 'fa-exclamation-triangle',
        'single_url' => 'reporte/stock_bajo',
        'multi_url' => 'reporte/seleccionar_sucursal/stock_bajo'
    ),
    array(
        'key' => 'traslados_enviados',
        'title' => 'Traslados enviados',
        'category' => 'Inventario',
        'description' => 'Seguimiento de movimientos enviados entre sucursales.',
        'icon' => 'fa-truck',
        'single_url' => '',
        'multi_url' => 'reporte/seleccionar_sucursal/traslados_enviados'
    ),
    array(
        'key' => 'traslados_recibidos',
        'title' => 'Traslados recibidos',
        'category' => 'Inventario',
        'description' => 'Seguimiento de movimientos recibidos entre sucursales.',
        'icon' => 'fa-sign-in',
        'single_url' => '',
        'multi_url' => 'reporte/seleccionar_sucursal/traslados_recibidos'
    )
);
