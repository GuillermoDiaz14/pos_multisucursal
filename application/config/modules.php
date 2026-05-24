<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| MIME TYPES
| -------------------------------------------------------------------
| This file contains an array of mime types.  It is used by the
| Upload class to help identify allowed file types.
|
*/
$config['moduleList'] = array(
        array('module'=>'Caja',
    	'total_access'=>0),
        array('module'=>'Ventas',
    	'total_access'=>0,
    	'editar'=>0,
    	'eliminar'=>0,
    	'configurar_ticket'=>0),
        array('module'=>'Compras',
    	'total_access'=>0),
        array('module'=>'Gastos',
    	'total_access'=>0),
        array('module'=>'Ingresos',
    	'total_access'=>0),
        array('module'=>'Métodos de Pago',
    	'total_access'=>0),
        array('module'=>'Productos',
    	'total_access'=>0,
    	'ver_precio_compra'=>0,
    	'gestionar'=>0),
        array('module'=>'Proveedores',
    	'total_access'=>0),
        array('module'=>'Traslados',
    	'total_access'=>0),
        array('module'=>'Sucursal',
    	'total_access'=>0,
    	'crear'=>0,
    	'editar'=>0,
    	'eliminar'=>0),
        array('module'=>'Empleado',
    	'total_access'=>0),
        array('module'=>'Cliente',
    	'total_access'=>0),
        array('module'=>'Usuarios',
    	'total_access'=>0,
    	'crear'=>0,
    	'editar'=>0,
    	'eliminar'=>0),
        array('module'=>'Roles',
    	'total_access'=>0,
    	'crear'=>0,
    	'editar'=>0,
    	'eliminar'=>0),
        array('module'=>'Reportes',
    	'total_access'=>0)
);
