<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Entrada extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Entrada_model', 'e');
        $this->isLoggedIn();
        $this->module = 'Entrada';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('entrada/entrada');
    }
    
    /**
     * This function is used to load the booking list
     */
    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function entrada()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

          
         
    $id_sucursal = $this->session->userdata('id_sucursal');
    
                // Hay cajas abiertas, realiza la acción correspondiente
                $data['proveedores'] = $this->e->get_proveedores($id_sucursal);
                $data['productos'] = $this->e->get_productos($id_sucursal);
             
          
    
    
                $this->global['pageTitle'] = ' compra';
                
                $this->loadViews("compra/compra", $this->global, $data, NULL);
      

    
            }
    }

   function compra_editar($id_compra = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

          $id_sucursal = $this->session->userdata('id_sucursal');
 
                // Hay cajas abiertas, realiza la acción correspondiente
                  $data['productos'] = $this->e->get_productos($id_sucursal);
 
                    $data['proveedores'] = $this->e->get_proveedores($id_sucursal);

             
       
                    $data['detalles'] = $this->e->get_detalle_compra($id_compra);
                      $data['compras'] = $this->e->get_compra($id_compra);


                $data['idusuario'] =  $this->vendorId;
                $this->global['pageTitle'] = ' compra';
                
                $this->loadViews("compra/compra_edit", $this->global, $data, NULL);


    
        }
    }

    //eliminar compra
    function eliminar_compra($id_compra = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
       $id_sucursal = $this->session->userdata('id_sucursal');
       
//sacando los detalles compra anteriores
$data['detalles'] = $this->e->get_detalle_compra($id_compra);


//
foreach ($data['detalles'] as $detalle) {

$cantidad_anterior=($detalle->cantidad)*(-1);
//disminuimos stock de detalles de los anteriores productos
$id_actualizar = $this->e->actualizarInventarioproducto($detalle->id_producto,$cantidad_anterior,$id_sucursal); 


}
//ceramos el foreach
//ahora eliminamos detalles compra con id_venta
$this->e->eliminar_detalles($id_compra);
//ahora eliminamos compra
$this->e->eliminar_compra($id_compra);
$this->session->set_flashdata('success', 'se elimino correctamente');
redirect('entrada/entradas_lista');
      

    
        }
    }
    //cerrando eliminar compra

    function addNewCompraPrueba()
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
//funcion actulaizar
function ActualizarCompra()
{
//1ro disminuir el stock de del anterior stock para esto scamos el stock anterior con el id_compra
$productos = $this->input->post('productos');
   $total=0;  
    // Itera sobre los productos e imprime cada valor
    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {
            // Obtiene el subtotal y el precio de venta de la fila actual
    
    
            foreach ($producto as $subIndex => $subProducto) {
                
     

                echo "$subIndex: $subProducto<br>";
                if($subIndex=="total"){
                    $total=$subProducto;
                    //echo "total d".$total;
                }
                if($subIndex=="nota"){
                    $nota=$subProducto;
                 
                }
                if($subIndex=="idproveedor"){
                    $proveedor=$subProducto;
               
                }
                if($subIndex=="id_compra"){
                    $id_compra=$subProducto;
               
                }
            }
 
            
        }
    }//cerramos este foreach doble

     $id_sucursal = $this->session->userdata('id_sucursal');
//capturamos detalles anteriores
    $data['detalles'] = $this->e->get_detalle_compra($id_compra);
    
//
foreach ($data['detalles'] as $detalle) {

    $cantidad_anterior=($detalle->cantidad)*(-1);
    //disminuir stock de detalles de los anteriores productos
    $id_actualizar = $this->e->actualizarInventarioproducto($detalle->id_producto,$cantidad_anterior,$id_sucursal); 
    if($id_actualizar == true) {
     $this->session->set_flashdata('success', 'inventario actualizado');
    } else {
     $this->session->set_flashdata('error', 'error actualizar inventario');
    }
    }//cerrando foreach

    //eliminamos detalles de venta anterior
    $this->e->eliminar_detalles($id_compra);

    //registramos actuales detalles
///
//

foreach ($productos as $index => $producto) {
    // Verifica si estamos en una fila de productos
    if (is_array($producto)) {

 
        foreach ($producto as $subIndex => $subProducto) {
            
            $datos_proveedor[$subIndex] = $subProducto;

           
        }
      // Accede a los datos directamente desde el arreglo
$id_producto = $datos_proveedor["idProducto"];
$precio_compra = $datos_proveedor["precioCompra"];
$cantidad = $datos_proveedor["cantidad"];
$sub_total = $datos_proveedor["subtotal"];
// Imprime los datos
echo "id_producto dons: $id_producto<br>";
echo "precio_compra dons: $precio_compra<br>";
echo "cantidad dons: $cantidad<br>";
echo "subtotal dons: $sub_total<br>";


//guardando
$detallesInfo = array('id_producto'=>$id_producto, 'precio_compra'=>$precio_compra, 'cantidad'=>$cantidad, 'sub_total'=>$sub_total, 'id_compra'=>$id_compra);
$id_detalle = $this->e->addNewDetalleCompra($detallesInfo); 
if($id_detalle > 0) {
$this->session->set_flashdata('success', 'detalle compra agregado');
} else {
$this->session->set_flashdata('error', 'error detalle compra agregado');
}


$id_actualizar = $this->e->actualizarInventarioproducto($id_producto,$cantidad,$id_sucursal); 
if($id_actualizar == true) {
$this->session->set_flashdata('success', 'inventario actualizado');
} else {
$this->session->set_flashdata('error', 'error actualizar inventario');
}
        
    }
}//cerrando foreach

//actualizamos tabla compras
         
$compraInfo = array('proveedor'=>$proveedor, 'nota' => $nota, 'total' => $total);
                
$result = $this->e->edit_compra($compraInfo, $id_compra);

if($result == true)


{
    $this->session->set_flashdata('success', 'Actualizado correctamente compra');
}
else
{
    $this->session->set_flashdata('error', 'actualizacion compra fallo');
}


}
//////
// AGREGANDO COMPRA
///
///////////////
///////////////
///////////
    function addNewCompra()
    {

           $id_sucursal = $this->session->userdata('id_sucursal');
        $datos_unicos = array(); 
   $productos = $this->input->post('productos');
   $total=0;  
    // Itera sobre los productos e imprime cada valor
    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {
            // Obtiene el subtotal y el precio de venta de la fila actual
    
    
            foreach ($producto as $subIndex => $subProducto) {
                
     

                echo "$subIndex: $subProducto<br>";
                if($subIndex=="total"){
                    $total=$subProducto;
                    //echo "total d".$total;
                }
                if($subIndex=="nota"){
                    $nota=$subProducto;
                 
                }
                if($subIndex=="idproveedor"){
                    $proveedor=$subProducto;
               
                }
            }
 
            
        }
    }
   

$id_usuario=$this->vendorId;

   $compraInfo = array('fecha_compra'=>date('Y-m-d'), 'proveedor'=>$proveedor, 'nota'=>$nota,'total'=>$total,'id_usuario'=>$id_usuario,'id_sucursal'=>$id_sucursal);
    $id_compra=$this->e->addNewCompra($compraInfo);
   

    
    if($id_compra > 0) {
        $this->session->set_flashdata('success', 'compra agregada');
    } else {
        $this->session->set_flashdata('error', 'error agregar compra');
    }
    
    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {

     
            foreach ($producto as $subIndex => $subProducto) {
                
                $datos_proveedor[$subIndex] = $subProducto;

                echo "$subIndex: $subProducto<br>";
                if($subIndex=="total"){
                    $total=$subProducto;
                    //echo "total d".$total;
                }
                if($subIndex=="nota"){
                    $nota=$subProducto;
                    //echo "nota d".$nota;
                }
                if($subIndex=="idproveedor"){
                    $proveedor=$subProducto;
                    //echo "proveedor d".$proveedor;
                }
            }
          // Accede a los datos directamente desde el arreglo
$id_producto = $datos_proveedor["idProducto"];
$precio_compra = $datos_proveedor["precioCompra"];
$cantidad = $datos_proveedor["cantidad"];
$sub_total = $datos_proveedor["subtotal"];
// Imprime los datos
echo "id_producto dons: $id_producto<br>";
echo "precio_compra dons: $precio_compra<br>";
echo "cantidad dons: $cantidad<br>";
echo "subtotal dons: $sub_total<br>";


//guardando
$detallesInfo = array('id_producto'=>$id_producto, 'precio_compra'=>$precio_compra, 'cantidad'=>$cantidad, 'sub_total'=>$sub_total, 'id_compra'=>$id_compra);
$id_detalle = $this->e->addNewDetalleCompra($detallesInfo); 
if($id_detalle > 0) {
 $this->session->set_flashdata('success', 'detalle compra agregado');
} else {
 $this->session->set_flashdata('error', 'error detalle compra agregado');
}


$id_actualizar = $this->e->actualizarInventarioproducto($id_producto,$cantidad,$id_sucursal); 
if($id_actualizar == true) {
$this->session->set_flashdata('success', 'inventario actualizado');
} else {
$this->session->set_flashdata('error', 'error actualizar inventario');
}
            
        }
    }

     
             
 $this->session->set_flashdata('success', 'Compra agregada satisfactoriamente');

    

      
        
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
  

 


    function entradas_lista()
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
            
            $count = $this->e->compras_lista_Count($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "compras_lista/", $count, $count );
            
            $data['records'] = $this->e->compras_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' lista compras';
            
            $this->loadViews("compra/compras_lista", $this->global, $data, NULL);
        }
    }
    public function filterEntradas()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->e->compras_lista_Count($searchText,$id_sucursal);
    
       $returns = $this->paginationCompress ( "compras_lista/", $count, $count );
        
        $data['records'] = $this->e->compras_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = ' lista Compras';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('compra/table_partial', $data);
    }






   public function exportToPDF($id_compra = NULL) {
        // Cargar la biblioteca TCPDF
    
        $data['compras'] = $this->e->get_compra($id_compra);
        $data['detalles'] = $this->e->get_detalle_compra($id_compra);
    
        require_once('assets//TCPDF-main/tcpdf.php');
    
        // Crear una instancia de TCPDF con el tamaño de página para un ticket
        $pdf = new TCPDF('P', 'mm', array(80, 200));
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();
    
        // Crear el contenido HTML del PDF a partir de los datos
        $html = '<div style="margin-bottom: 10px;">'; // Ajusta el margen inferior
        foreach ($data['compras'] as $compra) {
            $html .= '<p>N° Compra: ' . $compra->id_compra . '</p>';
            $html .= '<p>Fecha: ' . $compra->fecha_compra . '</p>';
            $html .= '<p>Proveedor: ' . $compra->proveedor . '</p>';

        }
        $html .= '</div>';
    
        $html .= '<table style="width: 100%;">';
        $html .= '<tr><th>Producto</th><th>Precio </th><th>Cantidad</th><th>Subtotal</th></tr>';
    
        foreach ($data['detalles'] as $detalle) {
            $html .= '<tr>';
            $html .= '<td>' . $detalle->nombre_producto . '</td>';
            $html .= '<td>' . $detalle->precio_individual . '</td>';
            $html .= '<td>' . $detalle->cantidad . '</td>';
            $html .= '<td>' . $detalle->sub_total . '</td>';
            $html .= '</tr>';
        }
    
        $html .= '</table>';
        // Mostrar valores de impuesto, descuento y total después de los detalles
$html .= '<div>';


$html .= '<p>Total: ' . $compra->total . '</p>';
$html .= '</div>';
    
        // Escribe el contenido HTML en el PDF
        $pdf->writeHTML($html);
    
        // Guardar el PDF o mostrarlo en el navegador
        $pdf->Output('reporte.pdf', 'I'); // 'I' para mostrar en el navegador
    }


}

?>