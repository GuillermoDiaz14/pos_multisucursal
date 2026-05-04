<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Configuración de impresoras Zebra
|--------------------------------------------------------------------------
| Nombres exactos como aparecen en Zebra Browser Print
| endpoint: https://localhost:9101/available
|
| Para cambiar en producción: actualiza los valores con los nombres
| que aparezcan en el endpoint /available de esa máquina.
|--------------------------------------------------------------------------
*/

// Impresora de TICKETS (rollo 80mm)
$config['zebra_ticket_printer'] = 'ZD421-203dpi ZPL (D8N231501266)';

// Impresora de ETIQUETAS (rollo 39x16mm)
$config['zebra_label_printer']  = 'ZD420-203dpi ZPL (D2J190306603)';
