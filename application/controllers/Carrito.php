<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Carrito extends BaseController
{
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
            $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal);

            if ($contador_cajas == 1) {

              
                // Hay cajas abiertas, realiza la acción correspondiente
                $data['productos'] = $this->cm->get_productos_com_stock($id_sucursal);
                $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['clientes'] = $this->cm->get_clientes($id_sucursal);

                $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal);
       
               
                $data['idusuario'] =  $this->vendorId;
                $this->global['pageTitle'] = 'Punto de venta';
                
                $this->loadViews("carrito/carrito", $this->global, $data, NULL);
            } else {
                // No hay cajas abiertas, realiza otra acción
                $this->global['pageTitle'] = 'Abrir caja';
                
                $this->loadViews("caja/add", $this->global, NULL, NULL);
            }

    
        }
    }
    //eliminar venta
    function eliminar_venta($id_venta = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

                 $id_sucursal = $this->session->userdata('id_sucursal');
            $total=0;
            $data['ventas'] = $this->cm->get_venta($id_venta);

            
            foreach ($data['ventas'] as $venta) {
              $total= $venta->total;
            }
         
            $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal);
            $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal);
            foreach ($data['cajaabierta'] as $cajaabierta) {
            $saldo= $cajaabierta->saldo;
          }

      if ($contador_cajas == 1 && $saldo>0) {
                // sacar total ventas
      
         //disminuir este total ventas en caja
         $total=$total*(-1);
         $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total,$id_sucursal);
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
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

               $id_sucursal = $this->session->userdata('id_sucursal');
           $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal);

            if ($contador_cajas == 1) {

                // Hay cajas abiertas, realiza la acción correspondiente
            $data['productos'] = $this->cm->get_productos_com_stock($id_sucursal);
            $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
                $data['clientes'] = $this->cm->get_clientes($id_sucursal);

             
       
                    $data['detalles'] = $this->cm->get_detalle_venta($id_venta);
                      $data['ventas'] = $this->cm->get_venta($id_venta);

               $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal);
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
           $contador_cajas = $this->cm->hayCajasAbiertas($id_sucursal);

            if ($contador_cajas == 1) {
                // Hay cajas abiertas, realiza la acción correspondiente
         
                $data['configuracion'] = $this->cm->get_configuracion($id_sucursal);
              

             
                $data['cuotas'] = $this->cm->get_cuota($id_venta);
                   
                      $data['ventas'] = $this->cm->get_venta($id_venta);

                $data['cajaabierta'] = $this->cm->get_saldo_cajaabierta($id_sucursal);
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
            $this->session->set_flashdata('error', 'No se recibieron productos para la venta');
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
                $this->session->set_flashdata('error', 'Algun producto no tiene stock suficiente, revise que tengan stock suficiente');
                echo json_encode(array('success' => false));
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
            $this->session->set_flashdata('error', 'No se encontraron productos validos para registrar la venta');
            echo json_encode(array('success' => false));
            return;
        }

        $primerProducto = $productos[0];
        $cliente = isset($primerProducto['cliente']) ? intval($primerProducto['cliente']) : (isset($primerProducto[9]) ? intval($primerProducto[9]) : 0);
        $descuento = isset($primerProducto['descuento']) ? floatval($primerProducto['descuento']) : (isset($primerProducto[8]) ? floatval($primerProducto[8]) : 0);
        $total = isset($primerProducto['total']) ? floatval($primerProducto['total']) : (isset($primerProducto[7]) ? floatval($primerProducto[7]) : 0);
        $impuesto = isset($primerProducto['impuesto']) ? floatval($primerProducto['impuesto']) : (isset($primerProducto[10]) ? floatval($primerProducto[10]) : 0);
        $base_imponible = isset($primerProducto['base_imponible']) ? floatval($primerProducto['base_imponible']) : (isset($primerProducto[11]) ? floatval($primerProducto[11]) : 0);
        $tipo_pago = isset($primerProducto['tipo_pago']) ? $primerProducto['tipo_pago'] : (isset($primerProducto[12]) ? $primerProducto[12] : 'contado');
        $id_metodo_pago = isset($primerProducto['id_metodo_pago']) ? intval($primerProducto['id_metodo_pago']) : (isset($primerProducto[13]) ? intval($primerProducto[13]) : 0);
        $monto_recibido = isset($primerProducto['monto_recibido']) ? floatval($primerProducto['monto_recibido']) : 0;
        $cambio = isset($primerProducto['cambio']) ? floatval($primerProducto['cambio']) : 0;

        if ($tipo_pago === 'credito') {
            $id_metodo_pago = 0;
            $monto_recibido = 0;
            $cambio = 0;
        }

        if ($tipo_pago === 'contado' && $monto_recibido < $total) {
            $this->session->set_flashdata('error', 'El monto recibido no puede ser menor al total de la venta');
            echo json_encode(array('success' => false));
            return;
        }

        $saldo = 0;
        $id_usuario = $this->vendorId;
        $carritoInfo = array(
            'fecha_venta' => date('Y-m-d'),
            'cliente' => $cliente,
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

        $id_venta = $this->cm->addNewVenta($carritoInfo);

        if($id_venta <= 0) {
            $this->session->set_flashdata('error', 'Error al agregar venta');
            echo json_encode(array('success' => false));
            return;
        }

        if($tipo_pago == "contado"){
            $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total,$id_sucursal);
            if($validacioncaja != true) {
                $this->session->set_flashdata('error', 'Error actualizando caja');
                echo json_encode(array('success' => false));
                return;
            }
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

        $this->session->set_flashdata('success', 'Venta agregada correctamente');
        echo json_encode(array('success' => true, 'id_venta' => $id_venta));
    }


    function ActualizarVenta()
    {
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
$total_anterior=$total_anterior*(-1);
//restando monto total de venta anterior
$validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total_anterior,$id_sucursal);
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


$id_actualizar_validar = $this->cm->validarInventarioproducto($idproducto,$cantidad,$id_sucursal); 
if($id_actualizar_validar == true) {



} else {
$this->session->set_flashdata('error', 'algun producto no tiene sotck sufiente, revise que tengan stock suficiente');

}


//cerando actualizar inventario productos
echo "Cantidad en fila $index: $cantidad<br>";

    }
}
//fin validar inventario




if($id_actualizar_validar == true){



        
  
   $total=0; 
   $tipo_pago="";
   $id_metodo_pago=0; 
   foreach ($productos as $index => $producto) {

            foreach ($producto as $subIndex => $subProducto) {

                if($subIndex==12)
                {
                $tipo_pago=$subProducto;
                echo "tipo_pago: $subProducto:<br>";
                }
                if($subIndex==13)
                {
                    $id_metodo_pago=$subProducto;
                    echo "id_metodo_pago: $subProducto:<br>";
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
            $total= $subtotal;
          
            foreach ($producto as $subIndex => $subProducto) {
                echo "$subIndex: $subProducto<br>";
            }
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




    
    $validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($total,$id_sucursal);
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


//cerando actualizar inventario productos
   echo "Cantidad en fila $index: $cantidad<br>";

            // Imprime los valores de la fila
            echo "Valores en fila $index:<br>";
            foreach ($producto as $subIndex => $subProducto) {
                echo "$subIndex: $subProducto<br>";
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
    if(!$this->hasUpdateAccess())
    {
        $this->loadThis();
    }
    else
    {
        if($id_venta == null)
        {
            redirect('carrito/carrito');
        }

        // Obtener venta y detalles
        $ventas = $this->cm->get_venta($id_venta);
        $detalles = $this->cm->get_detalle_venta($id_venta);

        // Tomamos solo la primera venta (porque es una)
        $venta = $ventas[0];

        // Preparar datos para la vista
        $data = array();
        $data['id_venta'] = $venta->id_venta;
        $data['fecha_venta'] = $venta->fecha_venta;
        $data['nombre_cliente'] = $venta->nombre_cliente;
        $data['descuento'] = $venta->descuento;
        $data['total'] = $venta->total;
        $data['detalles'] = $detalles;

        $this->global['pageTitle'] = 'Ticket';

        // Cargar vista del ticket
        $this->loadViews("carrito/ticket_view", $this->global, $data, NULL);
    }
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
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->cm->ventas_lista_Count($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "ventas_lista/", $count, $count );
            
            $data['records'] = $this->cm->ventas_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
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
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->cm->ventas_lista_contado_Count($searchText,$id_sucursal);

            $returns = $this->paginationCompress ( "ventas_lista_contado/", $count, $count );
            
            $data['records'] = $this->cm->ventas_lista_contado($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Ventas de contado';
            
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
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->cm->ventas_lista_credito_Count($searchText,$id_sucursal);

            $returns = $this->paginationCompress ( "ventas_lista_credito/", $count, $count );
            
            $data['records'] = $this->cm->ventas_lista_credito($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Ventas a crédito';
            
            $this->loadViews("carrito/ventas_lista_credito", $this->global, $data, NULL);
        }
    }
    public function filterVentas()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->cm->ventas_lista_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "ventas_lista/", $count, $count );
        
        $data['records'] = $this->cm->ventas_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Lista de ventas';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('carrito/table_partial', $data);
    }

    public function filterVentas_contado()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->cm->ventas_lista_contado_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "ventas_lista_contado/", $count, $count );
        
        $data['records'] = $this->cm->ventas_lista_contado($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Ventas de contado';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('carrito/table_partial_contado', $data);
    }

    public function filterVentas_credito()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->cm->ventas_lista_credito_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "ventas_lista_credito/", $count, $count );
        
        $data['records'] = $this->cm->ventas_lista_credito($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Ventas a crédito';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('carrito/table_partial_credito', $data);
    }


    public function exportToPDF($id_venta = NULL) {

    $data['ventas'] = $this->cm->get_venta($id_venta);
    $data['detalles'] = $this->cm->get_detalle_venta($id_venta);

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
                <td align="right"><b>Fecha:</b> '.$venta->fecha_venta.'</td>
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
        </table>
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
                $id_venta = $this->security->xss_clean($this->input->post('id_venta'));
                $cuota = $this->security->xss_clean($this->input->post('cuota'));
        
                
                $cuotaInfo = array('cuota'=>$cuota, 'fecha_pago'=>date('Y-m-d'), 'id_venta'=>$id_venta);
                
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

$validacioncaja = $this->cm->aumentarSaldoCajasAbiertas($cuota,$id_sucursal);
    if($validacioncaja == true) {
        $this->session->set_flashdata('success', 'caja actualizada');
    } else {
        $this->session->set_flashdata('error', 'error actualizando caja');
    }
                
                redirect('carrito/ventas_lista');
            }
        }
    }







}

?>
