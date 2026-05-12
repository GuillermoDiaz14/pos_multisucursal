<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Carrito extends BaseController
{
    private function requireAdminVentaAccess($redirect = 'carrito/ventas_lista_contado')
    {
        if ($this->isAdmin()) {
            return true;
        }

        $this->session->set_flashdata('error', 'Solo el administrador puede ver o modificar esta venta.');
        redirect($redirect);
        return false;
    }

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Carrito_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Ventas';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('carrito/carrito');
    }
    
    /**
     * This function is used to load the booking list
     */
    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function carrito()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal, $id_usuario);

            if ($contador_cajas == 1) {


                // Hay cajas abiertas, realiza la acción correspondiente
                $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['clientes'] = $this->cm->get_clientes($id_sucursal);

                $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
       
               
                $data['idusuario'] = $this->vendorId;
                $data['nombre_vendedor'] = $this->name;
                $this->global['pageTitle'] = 'Punto de venta';

                $this->loadViews("carrito/carrito", $this->global, $data, NULL);
            } else {
                // No hay cajas abiertas, realiza otra acción
                $this->global['pageTitle'] = 'Abrir caja';
                
                $this->loadViews("caja/add", $this->global, NULL, NULL);
            }

    
        }
    }
    public function buscarPOS()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $termino     = trim($this->security->xss_clean($this->input->post('q', TRUE)));
        $productos   = $this->cm->buscar_productos_pos($id_sucursal, $termino);

        $base = base_url('uploads/');
        $resultado = [];
        foreach ($productos as $p) {
            $resultado[] = [
                'id'      => (int)$p->id_producto,
                'nombre'  => $p->nombre_producto,
                'codigo'  => $p->codigo,
                'precio'  => (float)$p->precio_venta,
                'stock'   => (int)$p->stock,
                'imagen'  => $base . (empty($p->imagen) ? '11carrito22.png' : $p->imagen),
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    //eliminar venta
    function eliminar_venta($id_venta = NULL)
    {
        if(!$this->hasUpdateAccess() || !$this->hasVentaPermission('eliminar'))
        {
            $this->session->set_flashdata('error', 'No tienes permiso para eliminar ventas');
            redirect('carrito/ventas_lista');
            return;
        }
        else
        {

                 $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');

            // Bloqueo de seguridad: si la venta pertenece a una caja ya cerrada,
            // eliminarla descuadraría el arqueo de ese turno. Para corregir, el
            // ajuste debe registrarse manualmente como gasto/ingreso en la caja actual.
            $estado_caja_venta = $this->cm->getCajaEstadoPorVenta($id_venta);
            if ($estado_caja_venta === 'cerrado') {
                $this->session->set_flashdata('error',
                    'No se puede eliminar esta venta: pertenece a una caja ya cerrada. '
                    . 'Para revertirla, registra un ajuste manual (gasto/ingreso) en la caja actual.');
                redirect('carrito/ventas_lista');
                return;
            }

            $total=0;
            $id_metodo_pago_venta = null;
            $tipo_pago_venta = null;
            $saldo_cobrado_venta = 0;
            $data['ventas'] = $this->cm->get_venta($id_venta);


            foreach ($data['ventas'] as $venta) {
              $total= $venta->total;
              $id_metodo_pago_venta = isset($venta->id_metodo_pago) ? (int)$venta->id_metodo_pago : null;
              $tipo_pago_venta = isset($venta->tipo_pago) ? $venta->tipo_pago : null;
              // En crédito/apartado, lo realmente cobrado en caja es $venta->saldo (acumulado de cuotas/anticipo).
              $saldo_cobrado_venta = isset($venta->saldo) ? (float)$venta->saldo : 0;
            }

            $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal, $id_usuario);
            $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
            foreach ($data['cajaabierta'] as $cajaabierta) {
            $saldo= $cajaabierta->saldo;
          }

      if ($contador_cajas == 1 && $saldo>0) {
                // sacar total ventas

         //disminuir este total ventas en caja
         // Para crédito/apartado, lo que entró a caja fue el saldo cobrado (cuotas/anticipo),
         // no el total de la venta. Para venta de contado, sí es el total.
         if ($tipo_pago_venta === 'credito' || $tipo_pago_venta === 'apartado') {
             $monto_revertir = $saldo_cobrado_venta * (-1);
             // Las cuotas/anticipo se asumen en efectivo (legacy, hasta capturar método).
             $metodo_revertir = null;
         } else {
             $monto_revertir = $total * (-1);
             $metodo_revertir = $id_metodo_pago_venta;
         }
         $total = $monto_revertir; // mantiene compat con código abajo si lo usa
         $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($monto_revertir, $id_sucursal, $metodo_revertir, $id_usuario);
         if($validacioncaja == true) {
             $this->session->set_flashdata('success', 'caja actualizada');
         } else {
             $this->session->set_flashdata('error', 'error actualizando caja');
         }
       
//sacando los detalles venta anteriores
$data['detalles'] = $this->cm->get_detalle_venta($id_venta);


//
foreach ($data['detalles'] as $detalle) {

$cantidad_anterior=($detalle->cantidad)*(-1);
//aumentar stock de detalles de los anteriores productos
$id_actualizar = $this->cm->actualizarInventarioproducto($detalle->id_producto,$cantidad_anterior,$id_sucursal); 


}
//ceramos el foreach
//ahora eliminamos detalles ventas con id_venta
$this->cm->eliminar_detalles($id_venta);
//ahora eliminamos venta
$this->cm->eliminar_venta($id_venta);
$this->session->set_flashdata('success', 'se elimino correctamente');
redirect('carrito/ventas_lista');
            } else {
                // No hay cajas abiertas, realiza otra acción
                $this->session->set_flashdata('error', 'no hay caja abierta / o no tienes saldo sufiente en caja para eliminar');
                redirect('carrito/ventas_lista');
            }

    
        }
    }
    //cerrando eliminar venta






   function carrito_editar($id_venta = NULL)
    {
        if(!$this->hasUpdateAccess() || !$this->hasVentaPermission('editar'))
        {
            $this->session->set_flashdata('error', 'No tienes permiso para editar ventas');
            redirect('carrito/ventas_lista');
            return;
        }
        else
        {

               $id_sucursal = $this->session->userdata('id_sucursal');
           $id_usuario  = $this->session->userdata('userId');

           // Bloqueo: editar una venta de una caja cerrada descuadraría el arqueo
           // de ese turno. Si la caja ya está cerrada, no permitimos siquiera abrir
           // la pantalla de edición.
           $estado_caja_venta = $this->cm->getCajaEstadoPorVenta($id_venta);
           if ($estado_caja_venta === 'cerrado') {
               $this->session->set_flashdata('error',
                   'No se puede editar esta venta: pertenece a una caja ya cerrada. '
                   . 'Para corregirla, registra un ajuste manual (gasto/ingreso) en la caja actual.');
               redirect('carrito/ventas_lista');
               return;
           }

           $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal, $id_usuario);

            if ($contador_cajas == 1) {

                // Hay cajas abiertas, realiza la acción correspondiente
            $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['clientes'] = $this->cm->get_clientes($id_sucursal);



                    $data['detalles'] = $this->cm->get_detalle_venta($id_venta);
                      $data['ventas'] = $this->cm->get_venta($id_venta);

               $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
                $data['idusuario'] =  $this->vendorId;
                $this->global['pageTitle'] = 'Editar venta';
                
                $this->loadViews("carrito/carrito_edit", $this->global, $data, NULL);
            } else {
                // No hay cajas abiertas, realiza otra acción
                $this->global['pageTitle'] = 'Abrir caja';
                
                $this->loadViews("caja/add", $this->global, NULL, NULL);
            }

    
        }
    }

    function credito($id_venta = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

          
                     $id_sucursal = $this->session->userdata('id_sucursal');
           $id_usuario  = $this->session->userdata('userId');
           $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal, $id_usuario);

            if ($contador_cajas == 1) {
                // Hay cajas abiertas, realiza la acción correspondiente

                $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['metodos_pago'] = $this->cm->get_metodos_pago_sucursal($id_sucursal);


                $data['cuotas'] = $this->cm->get_cuota($id_venta);

                      $data['ventas'] = $this->cm->get_venta($id_venta);

                $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
                $data['idusuario'] =  $this->vendorId;
                $this->global['pageTitle'] = 'Crédito';

                $this->loadViews("carrito/credito", $this->global, $data, NULL);
            } else {
                // No hay cajas abiertas, realiza otra acción
                $this->global['pageTitle'] = 'Abrir caja';
                
                $this->loadViews("caja/add", $this->global, NULL, NULL);
            }

    
        }
    }












    function addNewVentaPrueba()
    {


        $productosSeleccionados = $this->input->post('productos');

    // Imprimir los datos en la consola
    foreach ($productosSeleccionados as $producto) {
        // Imprimir cada producto y sus campos (incluyendo "total" y "descuento")
        print_r($producto);
    }

    $respuesta = array('mensaje' => 'Datos recibidos correctamente.');
    echo json_encode($respuesta);
    }
    //funcion agregar venta
    //////////////
    ////////////////////
    //////////////////
    /////////////////
    //////////
    //////
    ///
    //
    //
    function addNewVenta()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $productos = $this->input->post('productos');

        if (empty($productos) || !is_array($productos)) {
            echo json_encode(array('success' => false));
            return;
        }

        $detalleProductos = array();
        $id_actualizar_validar = true;

        foreach ($productos as $index => $producto) {
            if (!is_array($producto)) {
                continue;
            }

            $idproducto = isset($producto['id_producto']) ? intval($producto['id_producto']) : (isset($producto[5]) ? intval($producto[5]) : 0);
            $nombre = isset($producto['nombre']) ? $producto['nombre'] : (isset($producto[0]) ? $producto[0] : '');
            $precioVenta = isset($producto['precio_venta']) ? floatval($producto['precio_venta']) : (isset($producto[1]) ? floatval($producto[1]) : 0);
            $cantidad = isset($producto['cantidad']) ? floatval($producto['cantidad']) : 0;
            $subtotal = isset($producto['subtotal']) ? floatval($producto['subtotal']) : (isset($producto[3]) ? floatval($producto[3]) : 0);

            if ($cantidad <= 0 && $precioVenta > 0) {
                $cantidad = $subtotal / $precioVenta;
            }

            if ($idproducto <= 0 || $cantidad <= 0) {
                continue;
            }

            $id_actualizar_validar = $this->cm->validarInventarioproducto($idproducto, $cantidad, $id_sucursal);
            if ($id_actualizar_validar !== true) {
                $this->db->select('stock');
                $this->db->where('id_producto', $idproducto);
                $this->db->where('id_sucursal', $id_sucursal);
                $stock_query = $this->db->get('tbl_producto_stock');
                $stock_actual = $stock_query->num_rows() > 0 ? $stock_query->row()->stock : 0;

                $mensaje = "❌ Stock insuficiente para '$nombre'. Disponible: $stock_actual, Solicitado: $cantidad";
                echo json_encode(array('success' => false, 'message' => $mensaje));
                return;
            }

            $detalleProductos[] = array(
                'id_producto' => $idproducto,
                'nombre' => $nombre,
                'precio_venta' => $precioVenta,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            );
        }

        if (empty($detalleProductos)) {
            echo json_encode(array('success' => false));
            return;
        }

        $primerProducto = $productos[0];
        $cliente = isset($primerProducto['id_cliente']) ? intval($primerProducto['id_cliente']) : (isset($primerProducto[9]) ? intval($primerProducto[9]) : 0);
        $descuento = isset($primerProducto['descuento']) ? floatval($primerProducto['descuento']) : (isset($primerProducto[8]) ? floatval($primerProducto[8]) : 0);
        $total = isset($primerProducto['total']) ? floatval($primerProducto['total']) : (isset($primerProducto[7]) ? floatval($primerProducto[7]) : 0);
        $impuesto = isset($primerProducto['impuesto']) ? floatval($primerProducto['impuesto']) : (isset($primerProducto[10]) ? floatval($primerProducto[10]) : 0);
        $base_imponible = isset($primerProducto['base_imponible']) ? floatval($primerProducto['base_imponible']) : (isset($primerProducto[11]) ? floatval($primerProducto[11]) : 0);
        $tipo_pago = isset($primerProducto['tipo_pago']) ? $primerProducto['tipo_pago'] : (isset($primerProducto[12]) ? $primerProducto[12] : 'contado');
        $id_metodo_pago = isset($primerProducto['id_metodo_pago']) ? intval($primerProducto['id_metodo_pago']) : (isset($primerProducto[13]) ? intval($primerProducto[13]) : 0);
        $monto_recibido = isset($primerProducto['monto_recibido']) ? floatval($primerProducto['monto_recibido']) : 0;
        $cambio = isset($primerProducto['cambio']) ? floatval($primerProducto['cambio']) : 0;
        $tipo_venta = isset($primerProducto['tipo_venta']) ? $primerProducto['tipo_venta'] : 'normal';
        $anticipo = isset($primerProducto['anticipo']) ? floatval($primerProducto['anticipo']) : 0;

        if ($tipo_pago === 'credito' || $tipo_pago === 'apartado') {
            $id_metodo_pago = 0;
            $monto_recibido = 0;
            $cambio = 0;
        }

        if ($tipo_pago === 'contado' && $monto_recibido < $total) {
            echo json_encode(array('success' => false));
            return;
        }

        if ($tipo_pago === 'apartado' && $anticipo > $total) {
            echo json_encode(array('success' => false));
            return;
        }

        $saldo = ($tipo_pago === 'apartado') ? $anticipo : 0;
        $id_usuario = $this->vendorId;
        $estado_apartado = ($tipo_pago === 'apartado') ? 'en_proceso' : NULL;

        $carritoInfo = array(
            'fecha_venta' => date('Y-m-d'),
            'id_cliente' => $cliente,
            'descuento' => $descuento,
            'base_imponible' => $base_imponible,
            'impuesto' => $impuesto,
            'total' => $total,
            'id_usuario' => $id_usuario,
            'tipo_pago' => $tipo_pago,
            'id_metodo_pago' => $id_metodo_pago,
            'saldo' => $saldo,
            'id_sucursal' => $id_sucursal
        );

        if ($this->db->field_exists('monto_recibido', 'tbl_venta')) {
            $carritoInfo['monto_recibido'] = $monto_recibido;
        }
        if ($this->db->field_exists('cambio', 'tbl_venta')) {
            $carritoInfo['cambio'] = $cambio;
        }
        if ($this->db->field_exists('tipo_venta', 'tbl_venta')) {
            $carritoInfo['tipo_venta'] = $tipo_venta;
        }
        if ($this->db->field_exists('estado_apartado', 'tbl_venta')) {
            $carritoInfo['estado_apartado'] = $estado_apartado;
        }
        if ($this->db->field_exists('anticipo', 'tbl_venta')) {
            $carritoInfo['anticipo'] = $anticipo;
        }
        // Asociamos la venta a la caja abierta del cajero (multi-cajero).
        // Si la migración 03 aún no está aplicada, el campo no existe y se omite.
        $id_caja_actual = $this->cm->getIdCajaAbierta($id_sucursal, $id_usuario);
        if ($this->db->field_exists('id_caja', 'tbl_venta') && $id_caja_actual !== null) {
            $carritoInfo['id_caja'] = $id_caja_actual;
        }

        $id_venta = $this->cm->addNewVenta($carritoInfo);

        if($id_venta <= 0) {
            echo json_encode(array('success' => false));
            return;
        }

        if ($tipo_pago == 'contado') {
            $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total, $id_sucursal, $id_metodo_pago, $id_usuario);
            if ($validacioncaja != true) {
                echo json_encode(array('success' => false));
                return;
            }
        } elseif ($tipo_pago == 'apartado' && $anticipo > 0) {
            // TODO: el form de apartado aún no captura método de pago del anticipo.
            // Por ahora se asume efectivo (null = comportamiento legacy, siempre afecta caja).
            $this->cm->aumentarSaldoCajasAbiertas($anticipo, $id_sucursal, null, $id_usuario);
            $cuotaInfo = array('cuota' => $anticipo, 'fecha_pago' => date('Y-m-d'), 'id_venta' => $id_venta);
            if ($this->db->field_exists('id_caja', 'tbl_cuota') && $id_caja_actual !== null) {
                $cuotaInfo['id_caja'] = $id_caja_actual;
            }
            $this->cm->addNewcuota($cuotaInfo);
        }

        foreach ($detalleProductos as $detalleProducto) {
            $detallesInfo = array(
                'id_producto' => $detalleProducto['id_producto'],
                'precio_venta' => $detalleProducto['precio_venta'],
                'cantidad' => $detalleProducto['cantidad'],
                'sub_total' => $detalleProducto['subtotal'],
                'id_venta' => $id_venta
            );

            $this->cm->addNewDetalleVenta($detallesInfo);
            $this->cm->actualizarInventarioproducto($detalleProducto['id_producto'], $detalleProducto['cantidad'], $id_sucursal);
        }

        echo json_encode(array('success' => true, 'id_venta' => $id_venta, 'total' => $total, 'tipo_pago' => $tipo_pago));
    }

    public function getSaldoCaja()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $id_usuario  = $this->session->userdata('userId');
        $cajaabierta = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
        $saldo = 0;
        if (!empty($cajaabierta)) {
            $saldo = $cajaabierta[0]->saldo;
        }
        echo json_encode(array('saldo' => $saldo));
    }

    function ActualizarVenta()
    {
$id_sucursal = $this->session->userdata('id_sucursal');
$id_usuario  = $this->session->userdata('userId');
//primero capturamos el idventa
$productos = $this->input->post('productos');
//validar si existe inventario
foreach ($productos as $index => $producto) {
    // Verifica si estamos en una fila de productos
    if (is_array($producto)) {     

$id_venta = isset($producto[12]) ? intval($producto[12]) : 1;
$total_anterior = isset($producto[13]) ? floatval($producto[13]) : 1;



    }
}
// Bloqueo: si la venta original pertenece a una caja ya cerrada, actualizarla
// descuadraría el arqueo de ese turno. Cortamos antes de tocar nada.
$estado_caja_venta = $this->cm->getCajaEstadoPorVenta($id_venta);
if ($estado_caja_venta === 'cerrado') {
    $this->session->set_flashdata('error',
        'No se puede modificar esta venta: pertenece a una caja ya cerrada. '
        . 'Para corregirla, registra un ajuste manual (gasto/ingreso) en la caja actual.');
    redirect('carrito/ventas_lista');
    return;
}

$total_anterior=$total_anterior*(-1);
//restando monto total de venta anterior
// Leemos la venta original para conocer su método de pago y solo revertir caja si era efectivo.
$ventaOriginal = $this->cm->get_venta($id_venta);
$id_metodo_pago_anterior = null;
if (!empty($ventaOriginal) && isset($ventaOriginal[0]->id_metodo_pago)) {
    $id_metodo_pago_anterior = (int)$ventaOriginal[0]->id_metodo_pago;
}
$validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total_anterior,$id_sucursal,$id_metodo_pago_anterior,$id_usuario);
if($validacioncaja == true) {
    $this->session->set_flashdata('success', 'caja actualizada');
} else {
    $this->session->set_flashdata('error', 'error actualizando caja');
}
//sacando los detalles venta anteriores
$data['detalles'] = $this->cm->get_detalle_venta($id_venta);


//
foreach ($data['detalles'] as $detalle) {

$cantidad_anterior=($detalle->cantidad)*(-1);
//aumentar stock de detalles de los anteriores productos
$id_actualizar = $this->cm->actualizarInventarioproducto($detalle->id_producto,$cantidad_anterior,$id_sucursal); 
if($id_actualizar == true) {
 $this->session->set_flashdata('success', 'inventario actualizado');
} else {
 $this->session->set_flashdata('error', 'error actualizar inventario');
}



}

//eliminar los detalles anteriores de la tabla detalles
$this->cm->eliminar_detalles($id_venta);












      
//validar si existe inventario
foreach ($productos as $index => $producto) {
    // Verifica si estamos en una fila de productos
    if (is_array($producto)) {
        // Obtiene el subtotal y el precio de venta de la fila actual
        $subtotal = isset($producto[3]) ? floatval($producto[3]) : 0.0;
        $precioVenta = isset($producto[1]) ? floatval($producto[1]) : 1.0;
        

$idproducto = isset($producto[5]) ? intval($producto[5]) : 1;

        $cantidad = ($precioVenta != 0) ? $subtotal / $precioVenta : 0;


$id_actualizar_validar = $this->cm->validarInventarioproducto($idproducto, $cantidad, $id_sucursal);
        if (!$id_actualizar_validar) {
            $this->session->set_flashdata('error', 'Algún producto no tiene stock suficiente.');
        }
    }
}
//fin validar inventario




if($id_actualizar_validar == true){



        
  
   $total=0; 
   $tipo_pago="";
   $id_metodo_pago=0; 
   foreach ($productos as $index => $producto) {

            foreach ($producto as $subIndex => $subProducto) {

                if($subIndex==12) {
                    $tipo_pago = $subProducto;
                }
                if($subIndex==13) {
                    $id_metodo_pago = $subProducto;
                }
                
            }
        }


    // Itera sobre los productos e imprime cada valor
    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {
            // Obtiene el subtotal y el precio de venta de la fila actual
            $subtotal = isset($producto[7]) ? floatval($producto[7]) : 1.0;
            $precioVenta = isset($producto[1]) ? floatval($producto[1]) : 1.0;
            $cliente = isset($producto[9]) ? intval($producto[9]) : 1;
            $descuento = isset($producto[8]) ? floatval($producto[8]) : 1.0;
            $impuesto = isset($producto[10]) ? floatval($producto[10]) : 1.0;
            $base_imponible = isset($producto[11]) ? floatval($producto[11]) : 1.0;

            // Calcula la cantidad para la fila actual
            $total = $subtotal;
        }
    }

//actualizar tabla carrito
$id_usuario=$this->vendorId;
         
$ventaInfo = array('cliente'=>$cliente, 'descuento' => $descuento, 'base_imponible' => $base_imponible, 'impuesto' => $impuesto, 'total' => $total);
                
$result = $this->cm->edit_venta($ventaInfo, $id_venta);

if($result == true)
{
    $this->session->set_flashdata('success', 'Actualizado correctamente venta');
}
else
{
    $this->session->set_flashdata('error', 'actualizacion venta fallo');
}




    
    $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total,$id_sucursal,$id_metodo_pago,$id_usuario);
    if($validacioncaja == true) {
        $this->session->set_flashdata('success', 'caja actualizada');
    } else {
        $this->session->set_flashdata('error', 'error actualizando caja');
    }
    $cantidadArray = [];  // Arreglo para almacenar las cantidades
    $nombreArray = [];  // Arreglo para almacenar las nombre
     $idProductoArray = [];  // Arreglo para almacenar las idProducto
         $precioVentaArray = [];  // Arreglo para almacenar las idProducto
           $subtotalArray = [];  // Arreglo para almacenar las idProducto

    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {
            // Obtiene el subtotal y el precio de venta de la fila actual
            $subtotal = isset($producto[3]) ? floatval($producto[3]) : 0.0;
            $precioVenta = isset($producto[1]) ? floatval($producto[1]) : 1.0;
            
     
 $nombre = isset($producto[0]) ? $producto[0] : '';

 $idproducto = isset($producto[5]) ? intval($producto[5]) : 1;
            // Almacena el nombre en el arreglo
            $nombreArray[$index] = $nombre;
            $idProductoArray[$index] = $idproducto;
            $precioVentaArray[$index] = $precioVenta;
              $subtotalArray[$index] = $subtotal;
            // Calcula la cantidad para la fila actual
            $total= $subtotal+$total;
            $cantidad = ($precioVenta != 0) ? $subtotal / $precioVenta : 0;

            // Almacena la cantidad en el arreglo
            $cantidadArray[$index] = $cantidad;

   $detallesInfo = array('id_producto'=>$idproducto, 'precio_venta'=>$precioVenta, 'cantidad'=>$cantidad, 'sub_total'=>$subtotal, 'id_venta'=>$id_venta);
   $id_detalle = $this->cm->addNewDetalleVenta($detallesInfo); 
   if($id_detalle > 0) {
    $this->session->set_flashdata('success', 'detalle venta actualizado');
} else {
    $this->session->set_flashdata('error', 'error detalle venta actualizando');
}


//aca voya actualizar el inventario de los productos
//$detallesInfo = array('id_producto'=>$idproducto, 'precio_venta'=>$precioVenta, 'cantidad'=>$cantidad, 'sub_total'=>$subtotal, 'id_venta'=>$id_venta);
$id_actualizar = $this->cm->actualizarInventarioproducto($idproducto,$cantidad,$id_sucursal); 
if($id_actualizar == true) {
 $this->session->set_flashdata('success', 'inventario actualizado');
} else {
 $this->session->set_flashdata('error', 'error actualizar inventario');
}


        }
    }
}

$this->session->set_flashdata('success', 'Venta actualizada correctamente');    
        
    }













function calculateAndStoreCantidad($productos)
{
    $cantidadArray = [];

    // Itera sobre los productos
    foreach ($productos as $index => $producto) {
        if ($index == 3 || $index == 1) {


            $cantidadArray['cantidad'] = ($productos[1] != 0) ? $productos[3] / $productos[1] : 0;
        }
    }

    return $cantidadArray;
}
    
    // Tu controlador y tu método para filtrar productos
    public function filtrarProductos() {
        $terminoBusqueda = $this->input->post('terminoBusqueda');
        $productosFiltrados = array_filter($productos, function($producto) use ($terminoBusqueda) {
            return stripos($producto->nombre_producto, $terminoBusqueda) !== false;
        });
    
        // Genera la lista de productos actualizada
        $listaProductos = '<ul id="productos">';
        foreach ($productosFiltrados as $producto) {
            $nombreProducto = htmlspecialchars($producto->nombre_producto);
            $listaProductos .= '<li><a href="#" onclick="seleccionarProducto(' . $producto->id_producto . ', \'' . $nombreProducto . '\')">' . $nombreProducto . '</a></li>';
        }
        $listaProductos .= '</ul>';
        echo $listaProductos;
    }
  

    public function imprimirticket($id_venta = NULL)
    {
        // Vista eliminada — la impresión ahora es ZPL directo vía printZebraApartado()
        redirect('carrito/apartado_lista');
    }



    function ventas_lista()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText']     = $searchText;
            $data['per_page']       = 50;
            $data['page']           = 1;
            $data['total_count']    = $this->cm->ventas_lista_Count($searchText, $id_sucursal);
            $data['records']        = $this->cm->ventas_lista($searchText, $id_sucursal, 50, 0);
            $data['is_admin']       = $this->isAdmin() ? 1 : 0;
            $data['puede_editar']   = $this->hasVentaPermission('editar');
            $data['puede_eliminar'] = $this->hasVentaPermission('eliminar');
            $this->global['pageTitle'] = 'Lista de ventas';

            $this->loadViews("carrito/ventas_lista", $this->global, $data, NULL);
        }
    }

    function ventas_lista_contado()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText']     = $searchText;
            $data['per_page']       = 50;
            $data['page']           = 1;
            $data['total_count']    = $this->cm->ventas_lista_contado_Count($searchText, $id_sucursal);
            $data['records']        = $this->cm->ventas_lista_contado($searchText, $id_sucursal, 50, 0);
            $data['is_admin']       = $this->isAdmin() ? 1 : 0;
            $data['puede_editar']   = $this->hasVentaPermission('editar');
            $data['puede_eliminar'] = $this->hasVentaPermission('eliminar');
            $this->global['pageTitle'] = 'Ventas al contado';

            $this->loadViews("carrito/ventas_lista_contado", $this->global, $data, NULL);
        }
    }

    function ventas_lista_credito()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText']     = $searchText;
            $data['per_page']       = 50;
            $data['page']           = 1;
            $data['total_count']    = $this->cm->ventas_lista_credito_Count($searchText, $id_sucursal);
            $data['records']        = $this->cm->ventas_lista_credito($searchText, $id_sucursal, 50, 0);
            $data['is_admin']       = $this->isAdmin() ? 1 : 0;
            $data['puede_editar']   = $this->hasVentaPermission('editar');
            $data['puede_eliminar'] = $this->hasVentaPermission('eliminar');
            $this->global['pageTitle'] = 'Ventas a crédito';

            $this->loadViews("carrito/ventas_lista_credito", $this->global, $data, NULL);
        }
    }

    private function _filterVentasResponse($countMethod, $listMethod, $partialView)
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = $this->security->xss_clean($this->input->post('searchText') ?? '');
        $tipo_pago   = $this->security->xss_clean($this->input->post('tipo_pago') ?? '');
        $page        = max(1, (int)$this->input->post('page'));
        $limit       = 50;
        $offset      = ($page - 1) * $limit;

        // Los métodos tipados (contado/credito/apartado) ignoran tipo_pago extra
        $supportsFilter = in_array($countMethod, ['ventas_lista_Count', 'ventas_lista']);
        $total = $supportsFilter
            ? $this->cm->$countMethod($searchText, $id_sucursal, $tipo_pago)
            : $this->cm->$countMethod($searchText, $id_sucursal);
        $records = $supportsFilter
            ? $this->cm->$listMethod($searchText, $id_sucursal, $limit, $offset, $tipo_pago)
            : $this->cm->$listMethod($searchText, $id_sucursal, $limit, $offset);

        $data = [
            'records'        => $records,
            'is_admin'       => $this->isAdmin() ? 1 : 0,
            'puede_editar'   => $this->hasVentaPermission('editar'),
            'puede_eliminar' => $this->hasVentaPermission('eliminar'),
        ];

        $html = $this->load->view('carrito/' . $partialView, $data, true);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'html'  => $html,
            'total' => $total,
            'page'  => $page,
            'pages' => (int)ceil($total / $limit),
            'limit' => $limit,
        ]);
    }

    public function filterVentas()
    {
        $this->_filterVentasResponse('ventas_lista_Count', 'ventas_lista', 'table_partial');
    }

    public function ventasNuevas()
    {
        if (!$this->hasListAccess()) { echo json_encode(['ventas' => []]); return; }
        $since_id    = max(0, (int)$this->input->post('since_id'));
        $id_sucursal = $this->session->userdata('id_sucursal');
        $records     = $this->cm->ventas_nuevas_desde($since_id, $id_sucursal);
        $data = [
            'records'        => $records,
            'is_admin'       => $this->isAdmin() ? 1 : 0,
            'puede_editar'   => $this->hasVentaPermission('editar'),
            'puede_eliminar' => $this->hasVentaPermission('eliminar'),
        ];
        $html = empty($records) ? '' : $this->load->view('carrito/table_partial', $data, true);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['html' => $html, 'count' => count($records)]);
    }

    public function filterVentas_contado()
    {
        $this->_filterVentasResponse('ventas_lista_contado_Count', 'ventas_lista_contado', 'table_partial_contado');
    }

    public function filterVentas_credito()
    {
        $this->_filterVentasResponse('ventas_lista_credito_Count', 'ventas_lista_credito', 'table_partial_credito');
    }


    public function exportToPDF($id_venta = NULL) {
        // Redirige al detalle del apartado; la impresión es ZPL vía printZebraApartado()
        redirect('carrito/apartado_detalle/' . (int)$id_venta);
    }

    // ── Genera ZPL y lo devuelve como JSON para impresión directa ────────────
    public function getZPL($id_venta = NULL) {
        $ventas = $this->cm->get_venta($id_venta);

        if (empty($ventas)) {
            $this->db->select('tbl_venta.*, tbl_cliente.nombre as nombre_cliente, tbl_sucursal.*');
            $this->db->from('tbl_venta');
            $this->db->join('tbl_cliente', 'tbl_cliente.id_cliente = tbl_venta.id_cliente', 'left');
            $this->db->join('tbl_sucursal', 'tbl_sucursal.id_sucursal = tbl_venta.id_sucursal', 'left');
            $this->db->where('tbl_venta.id_venta', $id_venta);
            $ventas = $this->db->get()->result();
        }

        if (empty($ventas)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Venta no encontrada']));
            return;
        }

        $v   = $ventas[0];
        $cfg = $v; // sucursal config viene en el mismo objeto

        $detalles       = $this->cm->get_detalle_venta($id_venta);
        $descuento      = (float)($v->descuento      ?? 0);
        $monto_recibido = (float)($v->monto_recibido ?? 0);
        $cambio         = (float)($v->cambio         ?? 0);

        // ── Configuración del ticket desde tbl_sucursal ───────────────────
        $mostrar_logo    = (bool)($cfg->ticket_mostrar_logo    ?? 1);
        $mostrar_tel     = (bool)($cfg->ticket_mostrar_tel     ?? 1);
        $mostrar_dir     = (bool)($cfg->ticket_mostrar_dir     ?? 1);
        $mostrar_ciudad  = (bool)($cfg->ticket_mostrar_ciudad  ?? 1);
        $mostrar_correo  = (bool)($cfg->ticket_mostrar_correo  ?? 0);
        $mostrar_num     = (bool)($cfg->ticket_mostrar_num     ?? 1);
        $mostrar_fecha   = (bool)($cfg->ticket_mostrar_fecha   ?? 1);
        $mostrar_cliente = (bool)($cfg->ticket_mostrar_cliente ?? 1);
        $mostrar_desc    = (bool)($cfg->ticket_mostrar_desc    ?? 1);
        $mostrar_cambio  = (bool)($cfg->ticket_mostrar_cambio  ?? 1);
        $msg_gracias     = self::zpl_utf8(trim($cfg->ticket_msg_gracias ?? '¡Gracias por su compra!'));
        $politica        = self::zpl_utf8(trim($cfg->ticket_politica    ?? ''));
        $subtitulo       = self::zpl_utf8(trim($cfg->ticket_subtitulo   ?? ''));
        // Logo
        $logo_opacidad   = max(0.05, min(0.80, (int)($cfg->ticket_logo_opacidad ?? 30) / 100));
        $logo_ancho_mm   = max(30,   min(78,   (int)($cfg->ticket_logo_ancho    ?? 70)));
        // Diseño
        $margen_mm       = max(3,  min(15, (int)($cfg->ticket_margen    ?? 5)));
        $sep_dots        = max(1,  min(6,  (int)($cfg->ticket_separador ?? 3)));

        // Tamaños de fuente (dots ZPL)
        $fs_tit  = max(32, min(72, (int)($cfg->ticket_fs_titulo  ?? 48)));
        $fs_info = max(16, min(36, (int)($cfg->ticket_fs_info    ?? 22)));
        $fs_norm = max(18, min(40, (int)($cfg->ticket_fs_normal  ?? 24)));
        $fs_tot  = max(28, min(60, (int)($cfg->ticket_fs_total   ?? 40)));
        $fs_grac = max(18, min(44, (int)($cfg->ticket_fs_gracias ?? 28)));
        // Anchos (85% de altura = proporción A0N recomendada)
        $fw = function($h) { return (int)round($h * 0.85); };
        $fw_tit = $fw($fs_tit); $fw_info = $fw($fs_info); $fw_norm = $fw($fs_norm);
        $fw_tot = $fw($fs_tot); $fw_grac = $fw($fs_grac);

        // Datos de la sucursal
        $nombre_suc = self::zpl_utf8($cfg->nombre_sucursal ?? 'Mi Tienda');
        $celular    = self::zpl_utf8($cfg->celular   ?? '');
        $direccion  = self::zpl_utf8($cfg->direccion ?? '');
        $ciudad     = self::zpl_utf8($cfg->ciudad    ?? '');
        $correo     = self::zpl_utf8($cfg->correo    ?? '');

        // ── Constantes de diseño (203 DPI, 80mm) ─────────────────────────
        $pw      = 640;  // 80mm total
        $margin  = (int)round($margen_mm * 8.0267); // mm → dots (203dpi)
        $inner   = $pw - ($margin * 2);
        $logo_w  = (int)round($logo_ancho_mm * 8.0267);
        $logo_x  = (int)(($pw - $logo_w) / 2);  // centrado horizontalmente

        // ── Logo watermark ────────────────────────────────────────────────
        $logo_grf    = '';
        $logo_h_dots = 0;
        if ($mostrar_logo) {
            $result = self::png_to_zpl_grf(
                FCPATH . 'assets/dist/img/logo.png', $logo_w, $logo_opacidad
            );
            if (is_array($result)) {
                $logo_grf    = $result['grf'];
                $logo_h_dots = $result['h'];
            }
        }

        // ── Construir body ZPL ────────────────────────────────────────────
        $body = '';
        $y    = $margin;

        if ($logo_grf) {
            // Logo centrado: posición X = (640 - logo_w) / 2
            $body .= "^FO{$logo_x},{$y}{$logo_grf}\n";
            // El texto EMPIEZA desde la parte superior, superpuesto al logo (marca de agua)
            // pero ^LL debe cubrir hasta donde termina el logo O el texto (lo que sea mayor)
        }

        // $y_text = posición vertical de inicio del texto (misma que el logo → watermark)
        $y_text_start = $y;  // guardamos para calcular ^LL al final

        // ── ENCABEZADO ────────────────────────────────────────────────────
        $nombre_completo = $subtitulo !== '' ? "{$nombre_suc} {$subtitulo}" : $nombre_suc;
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_tit},{$fw_tit}^FD{$nombre_completo}^FS\n";
        $y += $fs_tit + 6;

        if ($mostrar_tel && $celular !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FDTel: {$celular}^FS\n";
            $y += $fs_info + 4;
        }
        if ($mostrar_dir && $direccion !== '') {
            $dir_str = $direccion . ($mostrar_ciudad && $ciudad !== '' ? ', '.$ciudad : '');
            $body .= "^FO{$margin},{$y}^FB{$inner},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$dir_str}^FS\n";
            $y += $fs_info + 4;
        } elseif ($mostrar_ciudad && $ciudad !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$ciudad}^FS\n";
            $y += $fs_info + 4;
        }
        if ($mostrar_correo && $correo !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$correo}^FS\n";
            $y += $fs_info + 4;
        }

        $y += 4;
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── DATOS VENTA ───────────────────────────────────────────────────
        $col_fecha = $pw - $margin - ($fs_norm * 11); // espacio para fecha
        if ($mostrar_num || $mostrar_fecha) {
            if ($mostrar_num)
                $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FD# ".self::zpl_utf8($v->id_venta)."^FS\n";
            if ($mostrar_fecha)
                $body .= "^FO{$col_fecha},{$y}^A0N,{$fs_norm},{$fw_norm}^FD".self::zpl_utf8(date('d-m-Y', strtotime($v->fecha_venta)))."^FS\n";
            $y += $fs_norm + 4;
        }
        if ($mostrar_cliente) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDCliente: ".self::zpl_utf8($v->nombre_cliente)."^FS\n";
            $y += $fs_norm + 4;
        }
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── TABLA PRODUCTOS ───────────────────────────────────────────────
        // Columnas fijas en dots (suma = inner 560):
        // Producto: 0..240 (240) | Precio: 245..375 (130) | Cant: 380..430 (50) | Sub: 435..560 (125)
        $c1 = $margin;           // 40  → producto
        $c2 = $margin + 245;     // 285 → precio
        $c3 = $margin + 385;     // 425 → cant
        $c4 = $margin + 445;     // 485 → sub
        $fw_col = $fw_norm;
        $fs_col = $fs_norm;

        $body .= "^FO{$c1},{$y}^A0N,{$fs_col},{$fw_col}^FDPRODUCTO^FS\n";
        $body .= "^FO{$c2},{$y}^A0N,{$fs_col},{$fw_col}^FDPRECIO^FS\n";
        $body .= "^FO{$c3},{$y}^A0N,{$fs_col},{$fw_col}^FDCNT^FS\n";
        $body .= "^FO{$c4},{$y}^A0N,{$fs_col},{$fw_col}^FDSUB^FS\n";
        $y += $fs_col + 2;
        $body .= "^FO{$margin},{$y}^GB{$inner},2,2^FS\n"; $y += 8;

        foreach ($detalles as $det) {
            $nom    = self::zpl_utf8($det->nombre_producto);
            $maxCh  = (int)floor(240 / ($fw_col * 0.6)); // chars que caben en 240 dots
            if (mb_strlen($nom) > $maxCh) $nom = mb_substr($nom, 0, $maxCh - 1).'.';
            $precio = '$'.number_format($det->precio_individual, 2);
            $cant   = self::zpl_utf8($det->cantidad);
            $sub    = '$'.number_format($det->sub_total, 2);

            $body .= "^FO{$c1},{$y}^A0N,{$fs_col},{$fw_col}^FD{$nom}^FS\n";
            $body .= "^FO{$c2},{$y}^A0N,{$fs_col},{$fw_col}^FD{$precio}^FS\n";
            $body .= "^FO{$c3},{$y}^A0N,{$fs_col},{$fw_col}^FD{$cant}^FS\n";
            $body .= "^FO{$c4},{$y}^A0N,{$fs_col},{$fw_col}^FD{$sub}^FS\n";
            $y += $fs_col + 4;
        }
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── TOTALES ───────────────────────────────────────────────────────
        $col_r = $pw - $margin - ($fw_norm * 9);

        if ($mostrar_desc && $descuento > 0) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDDescuento:^FS\n";
            $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($descuento,2)."^FS\n";
            $y += $fs_norm + 4;
        }
        if ($mostrar_cambio && $monto_recibido > 0) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDRecibido:^FS\n";
            $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($monto_recibido,2)."^FS\n";
            $y += $fs_norm + 4;
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDCambio:^FS\n";
            $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($cambio,2)."^FS\n";
            $y += $fs_norm + 4;
        }

        // TOTAL grande alineado a la derecha con ^FB
        $total_str = 'TOTAL  $'.number_format($v->total, 2);
        $body .= "^FO{$margin},{$y}^FB{$inner},1,0,R,0^A0N,{$fs_tot},{$fw_tot}^FD{$total_str}^FS\n";
        $y += $fs_tot + 6;
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── PIE ───────────────────────────────────────────────────────────
        if ($msg_gracias !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_grac},{$fw_grac}^FD{$msg_gracias}^FS\n";
            $y += $fs_grac + 6;
        }
        if ($politica !== '') {
            $lines = max(1, min(8, (int)ceil(mb_strlen($politica) / 52) + 1));
            $body .= "^FO{$margin},{$y}^FB{$inner},{$lines},2,C,0^A0N,{$fs_info},{$fw_info}^FD{$politica}^FS\n";
            $y += ($lines * ($fs_info + 3)) + 6;
        }

        $y += 16;  // margen inferior mínimo (~2mm)

        // ^LL = máximo entre altura del texto y altura del logo
        $label_len = max($y, $y_text_start + $logo_h_dots + 16);

        // ── ZPL final ─────────────────────────────────────────────────────
        // Un solo label. ^JUS guarda PW/LL en NVRAM para que la impresora
        // no ignore el ^LL aunque tenga un valor distinto almacenado.
        // ^MNY = papel continuo/rollo, ^MMT = tear-off mode.
        $zpl = "^XA\n^PW{$pw}\n^LL{$label_len}\n^CI28\n^LH0,0\n"
             . $body
             . "^XZ";

        $json = json_encode(['zpl' => $zpl], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            // Fallback: limpiar ZPL a ASCII puro y reintentar
            $zpl_safe = preg_replace('/[^\x20-\x7E\n]/', '?', $zpl);
            $json = json_encode(['zpl' => $zpl_safe], JSON_UNESCAPED_UNICODE);
        }
        if ($json === false) {
            $json = json_encode(['error' => 'json_encode falló: ' . json_last_error_msg()]);
        }

        $this->output->set_content_type('application/json')->set_output($json);
    }

    // ── Genera ZPL para ticket de APARTADO ───────────────────────────────────
    public function getZPL_apartado($id_venta = NULL) {
        $ventas = $this->cm->get_venta((int)$id_venta);
        if (empty($ventas)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Apartado no encontrado']));
            return;
        }

        $v       = $ventas[0];
        $cfg     = $v;
        $total   = (float)($v->total   ?? 0);
        $saldo   = (float)($v->saldo   ?? 0);
        $anticipo= (float)($v->anticipo ?? 0);
        $deuda   = $total - $saldo;
        $estado  = self::zpl_utf8($v->estado_apartado ?? 'en_proceso');

        $detalles = $this->cm->get_detalle_venta((int)$id_venta);
        $cuotas   = $this->cm->get_cuota((int)$id_venta);

        // ── Config ticket desde sucursal (idéntico a getZPL) ─────────────
        $mostrar_logo    = (bool)($cfg->ticket_mostrar_logo    ?? 1);
        $mostrar_tel     = (bool)($cfg->ticket_mostrar_tel     ?? 1);
        $mostrar_dir     = (bool)($cfg->ticket_mostrar_dir     ?? 1);
        $mostrar_ciudad  = (bool)($cfg->ticket_mostrar_ciudad  ?? 1);
        $mostrar_correo  = (bool)($cfg->ticket_mostrar_correo  ?? 0);
        $mostrar_num     = (bool)($cfg->ticket_mostrar_num     ?? 1);
        $mostrar_fecha   = (bool)($cfg->ticket_mostrar_fecha   ?? 1);
        $mostrar_cliente = (bool)($cfg->ticket_mostrar_cliente ?? 1);
        $msg_gracias     = self::zpl_utf8(trim($cfg->ticket_msg_gracias ?? '¡Gracias por su compra!'));
        $politica        = self::zpl_utf8(trim($cfg->ticket_politica    ?? ''));
        $subtitulo       = self::zpl_utf8(trim($cfg->ticket_subtitulo   ?? ''));
        $logo_opacidad   = max(0.05, min(0.80, (int)($cfg->ticket_logo_opacidad ?? 30) / 100));
        $logo_ancho_mm   = max(30,   min(78,   (int)($cfg->ticket_logo_ancho    ?? 70)));
        $margen_mm       = max(3,  min(15, (int)($cfg->ticket_margen    ?? 5)));
        $sep_dots        = max(1,  min(6,  (int)($cfg->ticket_separador ?? 3)));
        $fs_tit  = max(32, min(72, (int)($cfg->ticket_fs_titulo  ?? 48)));
        $fs_info = max(16, min(36, (int)($cfg->ticket_fs_info    ?? 22)));
        $fs_norm = max(18, min(40, (int)($cfg->ticket_fs_normal  ?? 24)));
        $fs_tot  = max(28, min(60, (int)($cfg->ticket_fs_total   ?? 40)));
        $fs_grac = max(18, min(44, (int)($cfg->ticket_fs_gracias ?? 28)));
        $fw = function($h) { return (int)round($h * 0.85); };
        $fw_tit  = $fw($fs_tit);  $fw_info = $fw($fs_info);
        $fw_norm = $fw($fs_norm); $fw_tot  = $fw($fs_tot); $fw_grac = $fw($fs_grac);
        $nombre_suc = self::zpl_utf8($cfg->nombre_sucursal ?? 'Mi Tienda');
        $celular    = self::zpl_utf8($cfg->celular   ?? '');
        $direccion  = self::zpl_utf8($cfg->direccion ?? '');
        $ciudad     = self::zpl_utf8($cfg->ciudad    ?? '');
        $correo     = self::zpl_utf8($cfg->correo    ?? '');

        // ── Constantes (203 DPI, 80mm) ────────────────────────────────────
        $pw     = 640;
        $margin = (int)round($margen_mm * 8.0267);
        $inner  = $pw - ($margin * 2);
        $logo_w = (int)round($logo_ancho_mm * 8.0267);
        $logo_x = (int)(($pw - $logo_w) / 2);

        // ── Logo ──────────────────────────────────────────────────────────
        $logo_grf = ''; $logo_h_dots = 0;
        if ($mostrar_logo) {
            $result = self::png_to_zpl_grf(FCPATH . 'assets/dist/img/logo.png', $logo_w, $logo_opacidad);
            if (is_array($result)) { $logo_grf = $result['grf']; $logo_h_dots = $result['h']; }
        }

        $body = ''; $y = $margin;
        if ($logo_grf) { $body .= "^FO{$logo_x},{$y}{$logo_grf}\n"; }
        $y_text_start = $y;

        // ── ENCABEZADO ────────────────────────────────────────────────────
        $nombre_completo = $subtitulo !== '' ? "{$nombre_suc} {$subtitulo}" : $nombre_suc;
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_tit},{$fw_tit}^FD{$nombre_completo}^FS\n"; $y += $fs_tit + 6;
        if ($mostrar_tel && $celular !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FDTel: {$celular}^FS\n"; $y += $fs_info + 4;
        }
        if ($mostrar_dir && $direccion !== '') {
            $dir_str = $direccion . ($mostrar_ciudad && $ciudad !== '' ? ', '.$ciudad : '');
            $body .= "^FO{$margin},{$y}^FB{$inner},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$dir_str}^FS\n"; $y += $fs_info + 4;
        } elseif ($mostrar_ciudad && $ciudad !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$ciudad}^FS\n"; $y += $fs_info + 4;
        }
        if ($mostrar_correo && $correo !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_info},{$fw_info}^FD{$correo}^FS\n"; $y += $fs_info + 4;
        }
        $y += 4;
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── TIPO: APARTADO + ESTADO ───────────────────────────────────────
        $estado_label = ($v->estado_apartado === 'entregado') ? 'Entregado' :
                        (($v->estado_apartado === 'cancelado') ? 'Cancelado' : 'En proceso');
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_norm},{$fw_norm}^FDAPARTADO #".self::zpl_utf8($v->id_venta)." - {$estado_label}^FS\n"; $y += $fs_norm + 4;

        // ── DATOS ─────────────────────────────────────────────────────────
        $col_fecha = $pw - $margin - ($fs_norm * 11);
        if ($mostrar_fecha) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDFecha: ".self::zpl_utf8(date('d-m-Y', strtotime($v->fecha_venta)))."^FS\n"; $y += $fs_norm + 4;
        }
        if ($mostrar_cliente) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDCliente: ".self::zpl_utf8($v->nombre_cliente)."^FS\n"; $y += $fs_norm + 4;
        }
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── TABLA PRODUCTOS ───────────────────────────────────────────────
        $c1 = $margin; $c2 = $margin + 245; $c3 = $margin + 385; $c4 = $margin + 445;
        $body .= "^FO{$c1},{$y}^A0N,{$fs_norm},{$fw_norm}^FDPRODUCTO^FS\n";
        $body .= "^FO{$c2},{$y}^A0N,{$fs_norm},{$fw_norm}^FDPRECIO^FS\n";
        $body .= "^FO{$c3},{$y}^A0N,{$fs_norm},{$fw_norm}^FDCNT^FS\n";
        $body .= "^FO{$c4},{$y}^A0N,{$fs_norm},{$fw_norm}^FDSUB^FS\n"; $y += $fs_norm + 2;
        $body .= "^FO{$margin},{$y}^GB{$inner},2,2^FS\n"; $y += 8;

        foreach ($detalles as $det) {
            $nom   = self::zpl_utf8($det->nombre_producto);
            $maxCh = (int)floor(240 / ($fw_norm * 0.6));
            if (mb_strlen($nom) > $maxCh) $nom = mb_substr($nom, 0, $maxCh - 1).'.';
            $body .= "^FO{$c1},{$y}^A0N,{$fs_norm},{$fw_norm}^FD{$nom}^FS\n";
            $body .= "^FO{$c2},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($det->precio_individual, 2)."^FS\n";
            $body .= "^FO{$c3},{$y}^A0N,{$fs_norm},{$fw_norm}^FD".self::zpl_utf8($det->cantidad)."^FS\n";
            $body .= "^FO{$c4},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($det->sub_total, 2)."^FS\n";
            $y += $fs_norm + 4;
        }
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── TOTALES APARTADO ──────────────────────────────────────────────
        $col_r = $pw - $margin - ($fw_norm * 9);
        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDTotal venta:^FS\n";
        $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($total, 2)."^FS\n"; $y += $fs_norm + 4;
        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDAnticipo:^FS\n";
        $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($anticipo, 2)."^FS\n"; $y += $fs_norm + 4;
        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDTotal pagado:^FS\n";
        $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD\$".number_format($saldo, 2)."^FS\n"; $y += $fs_norm + 4;

        // Deuda restante grande
        $total_str = 'RESTANTE  $'.number_format(max(0, $deuda), 2);
        $body .= "^FO{$margin},{$y}^FB{$inner},1,0,R,0^A0N,{$fs_tot},{$fw_tot}^FD{$total_str}^FS\n"; $y += $fs_tot + 6;
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;

        // ── HISTORIAL DE CUOTAS ───────────────────────────────────────────
        if (!empty($cuotas)) {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_info},{$fw_info}^FDPagos realizados:^FS\n"; $y += $fs_info + 4;
            $col_fecha_cuota = $pw - $margin - ($fw_info * 9);
            foreach ($cuotas as $c) {
                $body .= "^FO{$margin},{$y}^A0N,{$fs_info},{$fw_info}^FD".self::zpl_utf8(date('d-m-Y', strtotime($c->fecha_pago)))."^FS\n";
                $body .= "^FO{$col_fecha_cuota},{$y}^A0N,{$fs_info},{$fw_info}^FD\$".number_format((float)$c->cuota, 2)."^FS\n";
                $y += $fs_info + 3;
            }
            $body .= "^FO{$margin},{$y}^GB{$inner},{$sep_dots},{$sep_dots}^FS\n"; $y += $sep_dots + 6;
        }

        // ── PIE ───────────────────────────────────────────────────────────
        if ($msg_gracias !== '') {
            $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_grac},{$fw_grac}^FD{$msg_gracias}^FS\n"; $y += $fs_grac + 6;
        }
        if ($politica !== '') {
            $lines = max(1, min(8, (int)ceil(mb_strlen($politica) / 52) + 1));
            $body .= "^FO{$margin},{$y}^FB{$inner},{$lines},2,C,0^A0N,{$fs_info},{$fw_info}^FD{$politica}^FS\n";
            $y += ($lines * ($fs_info + 3)) + 6;
        }
        $y += 16;
        $label_len = max($y, $y_text_start + $logo_h_dots + 16);
        $zpl = "^XA\n^PW{$pw}\n^LL{$label_len}\n^CI28\n^LH0,0\n" . $body . "^XZ";

        $json = json_encode(['zpl' => $zpl], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            $zpl_safe = preg_replace('/[^\x20-\x7E\n]/', '?', $zpl);
            $json = json_encode(['zpl' => $zpl_safe], JSON_UNESCAPED_UNICODE);
        }
        $this->output->set_content_type('application/json')->set_output($json);
    }

    // ── PNG → ZPL GRF con Floyd-Steinberg dithering + opacidad ────────────
    // Retorna ['grf'=>string, 'h'=>int] donde h es la altura real en dots.
    // $opacity 0.0–1.0: qué tan oscuro se ve (0.30 = 30% de los puntos impresos)
    private static function png_to_zpl_grf($path, $targetW = 560, $opacity = 0.30) {
        if (!file_exists($path) || !function_exists('imagecreatefrompng')) return '';
        $src = @imagecreatefrompng($path);
        if (!$src) return '';

        $origW   = imagesx($src);
        $origH   = imagesy($src);
        $targetH = (int)round($origH * ($targetW / $origW));

        // Redimensionar con fondo blanco
        $dst = imagecreatetruecolor($targetW, $targetH);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        // Componer PNG (con canal alpha) sobre fondo blanco
        imagealphablending($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);
        imagedestroy($src);

        // Construir mapa de grises flotante (0.0 = negro, 255.0 = blanco)
        // Aplicar opacidad: mezclar con blanco → gray_final = gray + (255-gray)*(1-opacity)
        $gray = [];
        for ($row = 0; $row < $targetH; $row++) {
            $gray[$row] = [];
            for ($px = 0; $px < $targetW; $px++) {
                $c = imagecolorat($dst, $px, $row);
                $r = ($c >> 16) & 0xFF;
                $g = ($c >>  8) & 0xFF;
                $b =  $c        & 0xFF;
                $lum = 0.299*$r + 0.587*$g + 0.114*$b; // 0=negro,255=blanco
                // Mezclar con blanco según opacidad (opacidad 30% → la imagen aporta 30%)
                $gray[$row][$px] = $lum + (255.0 - $lum) * (1.0 - $opacity);
            }
        }
        imagedestroy($dst);

        // Floyd-Steinberg dithering sobre el mapa de grises
        // Umbral: 128 (mitad). Errores se propagan a vecinos derecha/abajo.
        $bytesPerRow = (int)ceil($targetW / 8);
        $bits = []; // $bits[$row][$px] = 1 imprimir, 0 no imprimir

        for ($row = 0; $row < $targetH; $row++) {
            for ($px = 0; $px < $targetW; $px++) {
                $old = $gray[$row][$px];
                // Umbral: si más oscuro que 128 → imprimir
                $new = ($old < 128) ? 0.0 : 255.0;
                $bits[$row][$px] = ($new == 0.0) ? 1 : 0;
                $err = $old - $new;
                // Distribuir error
                if ($px + 1 < $targetW)
                    $gray[$row][$px+1]     = min(255, max(0, $gray[$row][$px+1]     + $err * 7/16));
                if ($row + 1 < $targetH) {
                    if ($px > 0)
                        $gray[$row+1][$px-1] = min(255, max(0, $gray[$row+1][$px-1] + $err * 3/16));
                    $gray[$row+1][$px]     = min(255, max(0, $gray[$row+1][$px]     + $err * 5/16));
                    if ($px + 1 < $targetW)
                        $gray[$row+1][$px+1] = min(255, max(0, $gray[$row+1][$px+1] + $err * 1/16));
                }
            }
        }

        // Construir hex GRF
        $hexData = '';
        for ($row = 0; $row < $targetH; $row++) {
            for ($b = 0; $b < $bytesPerRow; $b++) {
                $byte = 0;
                for ($bit = 0; $bit < 8; $bit++) {
                    $px = $b * 8 + $bit;
                    if ($px < $targetW && !empty($bits[$row][$px])) {
                        $byte |= (0x80 >> $bit);
                    }
                }
                $hexData .= sprintf('%02X', $byte);
            }
        }

        $total = $bytesPerRow * $targetH;
        return ['grf' => "^GFA,{$total},{$total},{$bytesPerRow},{$hexData}", 'h' => $targetH];
    }

    // ── Limpia texto para ZPL (^CI28 = UTF-8) ───────────────────────────────
    // Garantiza UTF-8 válido. Si la BD devuelve Latin-1 los convierte.
    // json_encode falla con bytes no-UTF-8; esto lo previene.
    private static function zpl_utf8($s) {
        $s = strip_tags((string)$s);
        $s = str_replace(['^', '~'], ['', ''], $s);   // control chars ZPL
        // Si no es UTF-8 válido, asumir Latin-1 y convertir
        if (!mb_check_encoding($s, 'UTF-8')) {
            $s = mb_convert_encoding($s, 'UTF-8', 'ISO-8859-1');
        }
        return $s;
    }

    private static function zpl_str($s) {
        return self::zpl_utf8($s);
    }

    public function exportToPDF_legacy($id_venta = NULL) {
    $data['ventas'] = $this->cm->get_venta($id_venta);
    $data['detalles'] = $this->cm->get_detalle_venta($id_venta);
    $data['cuotas'] = $this->cm->get_cuota($id_venta);

    // Si no hay datos, intentar con LEFT JOIN para obtener los detalles aunque el cliente no exista
    if (empty($data['ventas'])) {
        $this->db->select('tbl_venta.*, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_cliente.id_cliente = tbl_venta.id_cliente', 'left');
        $this->db->where('tbl_venta.id_venta', $id_venta);
        $query = $this->db->get();
        $data['ventas'] = $query->result();
    }

    if (empty($data['ventas'])) {
        show_error('No se encontró la venta ' . $id_venta, 404);
        return;
    }

    require_once('assets//TCPDF-main/tcpdf.php');

    // Tamaño ticket 80mm
    $pdf = new TCPDF('P', 'mm', array(80, 200));
    $pdf->SetMargins(2, 2, 2);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->AddPage();

// Agregar imagen - Sin getimagesize()
    $image_path = FCPATH . 'assets/dist/img/logo.png';

    if (file_exists($image_path) && is_readable($image_path)) {
        try {
            // Centrar la imagen
            $pageWidth = 80;
            $imageWidth = 40;
            $x = ($pageWidth - $imageWidth) / 2;

            $pdf->Image($image_path, $x, 5, $imageWidth, 0, 'PNG');
        } catch (Exception $e) {
            // Si hay error, continuar sin imagen
            log_message('error', 'Error al insertar imagen: ' . $e->getMessage());
        }
    }
    $html = '';

    foreach ($data['ventas'] as $venta) {

        $html .= '
        <div style="text-align:center; font-size:12px; font-weight:bold;">
            Boutique Paty
        </div>
        <hr>

        <table width="100%" style="font-size:8px;">
            <tr>
                <td><b>Venta:</b> '.$venta->id_venta.'</td>
                <td align="right"><b>Fecha:</b> '.date('d-m-Y', strtotime($venta->fecha_venta)).'</td>
            </tr>
        </table>

        <div style="font-size:8px;">
            <b>Cliente:</b> '.$venta->nombre_cliente.'
        </div>

        <hr>
        ';
    }

    // Encabezados tabla
    $html .= '
    <table width="100%" style="font-size:7px;">
        <tr>
            <th width="40%">Producto</th>
            <th width="20%" align="right">Precio</th>
            <th width="15%" align="right">Cant</th>
            <th width="25%" align="right">Sub</th>
        </tr>
    </table>
    <hr>
    ';

    // Detalles
    $html .= '<table width="100%" style="font-size:7px;">';

    foreach ($data['detalles'] as $detalle) {
        $html .= '
        <tr>
            <td width="40%">'.$detalle->nombre_producto.'</td>
            <td width="20%" align="right">$'.number_format((float)$detalle->precio_individual,2).'</td>
            <td width="15%" align="right">'.$detalle->cantidad.'</td>
            <td width="25%" align="right">$'.number_format((float)$detalle->sub_total,2).'</td>
        </tr>';
    }

    $html .= '</table><hr>';

    // Totales (sin impuesto ni base)
    foreach ($data['ventas'] as $venta) {
        $filasCobro = '';
        $isApartado = isset($venta->tipo_venta) && $venta->tipo_venta === 'apartado';

        if ($isApartado) {
            // Apartado: mostrar pago inicial y historial
            $pagoInicial = floatval($venta->anticipo ?? 0);
            $totalPagado = 0;

            // Sumar solo las cuotas (ya incluyen el anticipo)
            if (!empty($data['cuotas'])) {
                foreach ($data['cuotas'] as $cuota) {
                    $totalPagado += floatval($cuota->cuota);
                }
            }
            $restante = floatval($venta->total) - $totalPagado;

            $html .= '
            <table width="100%" style="font-size:8px;">
                <tr>
                    <td>Descuento:</td>
                    <td align="right">$'.number_format((float)$venta->descuento,2).'</td>
                </tr>
                <tr>
                    <td><b>TOTAL:</b></td>
                    <td align="right"><b>$'.number_format((float)$venta->total,2).'</b></td>
                </tr>
                <tr>
                    <td><b>Pago Inicial:</b></td>
                    <td align="right"><b>$'.number_format($pagoInicial,2).'</b></td>
                </tr>
            </table>
            <hr>
            <div style="text-align:center; font-size:8px; font-weight:bold;">Historial de Pagos</div>
            <table width="100%" style="font-size:7px;">
                <tr>
                    <th width="50%">Fecha</th>
                    <th width="50%" align="right">Monto</th>
                </tr>
            </table>';

            if (!empty($data['cuotas'])) {
                $html .= '<table width="100%" style="font-size:7px;">';
                foreach ($data['cuotas'] as $cuota) {
                    $html .= '<tr>
                        <td width="50%">'.date('d-m-Y', strtotime($cuota->fecha_pago)).'</td>
                        <td width="50%" align="right">$'.number_format((float)$cuota->cuota,2).'</td>
                    </tr>';
                }
                $html .= '</table>';
            }

            $html .= '<hr>
            <table width="100%" style="font-size:8px;">
                <tr>
                    <td><b>Total Pagado:</b></td>
                    <td align="right"><b>$'.number_format($totalPagado,2).'</b></td>
                </tr>
                <tr>
                    <td><b>Restante:</b></td>
                    <td align="right"><b>$'.number_format($restante,2).'</b></td>
                </tr>';

            if ($restante <= 0.009) {
                $html .= '<tr><td colspan="2" align="center"><b style="color:green;">PAGADO</b></td></tr>';
            }

            $html .= '</table>';
        } else {
            // Venta normal
            if (floatval($venta->monto_recibido) > 0) {
                $filasCobro .= '
                <tr>
                    <td>Recibido:</td>
                    <td align="right">$'.number_format((float)$venta->monto_recibido,2).'</td>
                </tr>
                <tr>
                    <td>Cambio:</td>
                    <td align="right">$'.number_format((float)$venta->cambio,2).'</td>
                </tr>';
            }

            $html .= '
            <table width="100%" style="font-size:8px;">
                <tr>
                    <td>Descuento:</td>
                    <td align="right">$'.number_format((float)$venta->descuento,2).'</td>
                </tr>
                '.$filasCobro.'
                <tr>
                    <td><b>TOTAL:</b></td>
                    <td align="right"><b>$'.number_format((float)$venta->total,2).'</b></td>
                </tr>
            </table>';
        }

        $html .= '
        <br>
        <div style="text-align:center; font-size:5px;">
            Para cambios la prenda o el articulo debe conservar su etiqueta y estar en óptimas condiciones. Por motivos de higiene, no aceptamos cambios en ropa interior y no realizamos devoluciones de efectivo. ¡Gracias por su prefrencia!
        </div>
       <div style="text-align:center; font-size:9px;">
            Gracias por su compra!
        </div>
        ';
    }

    $pdf->writeHTML($html);
    $pdf->Output('ticket.pdf', 'I');
}


    function cuota_agregar()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('id_venta','id_venta','trim|required|max_length[200]');
            $this->form_validation->set_rules('cuota', 'cuota','trim|required|max_length[50]');

            
            if($this->form_validation->run() == FALSE)
            {
                $this->ventas_lista();
            }
            else
            {

                $id_sucursal = $this->session->userdata('id_sucursal');
                $id_usuario  = $this->session->userdata('userId');
                $id_venta = $this->security->xss_clean($this->input->post('id_venta'));
                $cuota = $this->security->xss_clean($this->input->post('cuota'));
                // Método de pago de la cuota: si el form lo envía, lo respetamos; si no, null = legacy (efectivo asumido).
                $id_metodo_pago_cuota = $this->input->post('id_metodo_pago');
                $id_metodo_pago_cuota = ($id_metodo_pago_cuota === null || $id_metodo_pago_cuota === '')
                    ? null
                    : (int)$this->security->xss_clean($id_metodo_pago_cuota);


                $cuotaInfo = array('cuota'=>$cuota, 'fecha_pago'=>date('Y-m-d'), 'id_venta'=>$id_venta);

                // Asociamos la cuota a la caja abierta del cajero (multi-cajero).
                $id_caja_actual = $this->cm->getIdCajaAbierta($id_sucursal, $id_usuario);
                if ($this->db->field_exists('id_caja', 'tbl_cuota') && $id_caja_actual !== null) {
                    $cuotaInfo['id_caja'] = $id_caja_actual;
                }

                $result = $this->cm->addNewcuota($cuotaInfo);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo cuota agregada satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nueva cuota');
                }



                $validacionsaldo = $this->cm->aumentarSaldoCredito($id_venta,$cuota);
                if($validacionsaldo == true) {
                    $this->session->set_flashdata('success', 'aumentado saldo a venta correctamente');
                } else {
                    $this->session->set_flashdata('error', 'error aen aumentar saldo a venta');
                }

$validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($cuota,$id_sucursal,$id_metodo_pago_cuota,$id_usuario);
    if($validacioncaja == true) {
        $this->session->set_flashdata('success', 'caja actualizada');
    } else {
        $this->session->set_flashdata('error', 'error actualizando caja');
    }

                $ventas_redir = $this->cm->get_venta($id_venta);
                $tipo_venta_redir = (isset($ventas_redir[0]->tipo_venta) && $ventas_redir[0]->tipo_venta === 'apartado') ? 'apartado' : 'normal';

                if ($tipo_venta_redir === 'apartado') {
                    redirect('carrito/apartado_detalle/' . $id_venta);
                } else {
                    redirect('carrito/ventas_lista');
                }
            }
        }
    }








    function apartado_lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText']  = $searchText;
            $data['isAdmin']     = $this->isAdmin();
            $data['per_page']    = 50;
            $data['page']        = 1;
            $data['total_count'] = $this->cm->ventas_lista_apartado_Count($searchText, $id_sucursal);
            $data['records']     = $this->cm->ventas_lista_apartado($searchText, $id_sucursal, 50, 0);

            $this->global['pageTitle'] = 'Apartados';
            $this->loadViews('carrito/apartado_lista', $this->global, $data, NULL);
        }
    }

    public function filterVentas_apartado()
    {
        $this->_filterVentasResponse('ventas_lista_apartado_Count', 'ventas_lista_apartado', 'table_partial_apartado');
    }

    function apartado_detalle($id_venta = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal, $id_usuario);

            if ($contador_cajas == 1) {
                $data['ventas'] = $this->cm->get_venta($id_venta);
                $data['cuotas'] = $this->cm->get_cuota($id_venta);
                $data['detalles'] = $this->cm->get_detalle_venta($id_venta);
                $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['metodos_pago'] = $this->cm->get_metodos_pago_sucursal($id_sucursal);
                $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal, $id_usuario);
                $data['idusuario'] = $this->vendorId;
                $this->global['pageTitle'] = 'Detalle Apartado';
                $this->loadViews('carrito/apartado_detalle', $this->global, $data, NULL);
            } else {
                $this->global['pageTitle'] = 'Abrir caja';
                $this->loadViews('caja/add', $this->global, NULL, NULL);
            }
        }
    }

    function entregar_apartado($id_venta = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $ventas = $this->cm->get_venta($id_venta);
            if (empty($ventas)) {
                $this->session->set_flashdata('error', 'Apartado no encontrado');
                redirect('carrito/apartado_lista');
                return;
            }
            $venta = $ventas[0];
            $deuda = $venta->total - $venta->saldo;

            if ($deuda > 0.009) {
                $this->session->set_flashdata('error', 'No se puede entregar: aún hay una deuda pendiente de $' . number_format($deuda, 2));
                redirect('carrito/apartado_detalle/' . $id_venta);
                return;
            }

            $this->cm->marcar_entregado_apartado($id_venta);
            $this->session->set_flashdata('success', 'Apartado marcado como entregado. El producto ha sido entregado al cliente.');
            redirect('carrito/apartado_lista');
        }
    }

    function cancelar_apartado($id_venta = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            $ventas = $this->cm->get_venta($id_venta);

            if (empty($ventas)) {
                $this->session->set_flashdata('error', 'Apartado no encontrado');
                redirect('carrito/apartado_lista');
                return;
            }
            $venta = $ventas[0];

            if (isset($venta->estado_apartado) && $venta->estado_apartado === 'entregado') {
                $this->session->set_flashdata('error', 'No se puede cancelar un apartado que ya fue entregado');
                redirect('carrito/apartado_detalle/' . $id_venta);
                return;
            }

            if (isset($venta->estado_apartado) && $venta->estado_apartado === 'cancelado') {
                $this->session->set_flashdata('error', 'Este apartado ya fue cancelado');
                redirect('carrito/apartado_lista');
                return;
            }

            // Bloqueo: cancelar reversa pagos en la caja del cajero. Si el apartado
            // pertenece a una caja cerrada, esa reversa caería en otra caja (la actual)
            // y descuadraría arqueos. Pedimos ajuste manual.
            $estado_caja_venta = $this->cm->getCajaEstadoPorVenta($id_venta);
            if ($estado_caja_venta === 'cerrado') {
                $this->session->set_flashdata('error',
                    'No se puede cancelar este apartado: pertenece a una caja ya cerrada. '
                    . 'Para revertirlo, registra un ajuste manual (gasto/ingreso) en la caja actual.');
                redirect('carrito/apartado_detalle/' . $id_venta);
                return;
            }

            // Restaurar inventario
            $detalles = $this->cm->get_detalle_venta($id_venta);
            foreach ($detalles as $detalle) {
                $cantidad_restaurar = $detalle->cantidad * (-1);
                $this->cm->actualizarInventarioproducto($detalle->id_producto, $cantidad_restaurar, $id_sucursal);
            }

            // Revertir pagos de caja si los hay (asumido efectivo)
            if ($venta->saldo > 0) {
                $this->cm->aumentarSaldoCajasAbiertas($venta->saldo * (-1), $id_sucursal, null, $id_usuario);
            }

            $this->cm->cancelar_apartado($id_venta);
            $this->session->set_flashdata('success', 'Apartado cancelado. El inventario ha sido restaurado y los pagos revertidos en caja.');
            redirect('carrito/apartado_lista');
        }
    }

    function eliminar_apartado($id_venta = NULL)
    {
        if (!$this->isAdmin()) {
            $this->session->set_flashdata('error', 'Solo el administrador puede eliminar apartados');
            redirect('carrito/apartado_lista');
            return;
        }

        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
            return;
        }

        $id_sucursal = $this->session->userdata('id_sucursal');
        $ventas = $this->cm->get_venta($id_venta);

        if (empty($ventas)) {
            $this->session->set_flashdata('error', 'Apartado no encontrado');
            redirect('carrito/apartado_lista');
            return;
        }

        // Bloqueo: si el apartado pertenece a una caja cerrada, eliminarlo no debe
        // tocar saldo de cajas pasadas. Pedimos ajuste manual.
        $estado_caja_venta = $this->cm->getCajaEstadoPorVenta($id_venta);
        if ($estado_caja_venta === 'cerrado') {
            $this->session->set_flashdata('error',
                'No se puede eliminar este apartado: pertenece a una caja ya cerrada. '
                . 'Registra un ajuste manual en la caja actual si necesitas compensarlo.');
            redirect('carrito/apartado_lista');
            return;
        }

        $venta = $ventas[0];
        $this->cm->eliminar_apartado($id_venta);
        $this->session->set_flashdata('success', 'Apartado eliminado correctamente');
        redirect('carrito/apartado_lista');
    }

}

?>
