<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Trasladar extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Trasladar_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Trasladar';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('trasladar/trasladar');
    }
    
    /**
     * This function is used to load the booking list
     */
    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function trasladar()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {

            $id_sucursal = $this->session->userdata('id_sucursal');
        

          

              
                // Hay cajas abiertas, realiza la acción correspondiente
                $data['productos'] = $this->tm->get_productos_com_stock($id_sucursal);
                $data['configuracion'] = $this->tm->get_configuracion($id_sucursal);
                $data['sucursales'] = $this->tm->get_sucursales($id_sucursal);

             
       
               
                $data['idusuario'] =  $this->vendorId;
                $this->global['pageTitle'] = ' trasladar venta';
                
                $this->loadViews("traslado/traslado", $this->global, $data, NULL);
          
            
            

    
        }
    }



















    function addNewTrasladarPrueba()
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
    function addNewTrasladar1()
    {
    $id_sucursal_descuento = $this->session->userdata('id_sucursal');
        $productos = $this->input->post('productos');
//validar si existe inventario
foreach ($productos as $index => $producto) {
    // Verifica si estamos en una fila de productos
    if (is_array($producto)) {
  
        

$idproducto = isset($producto[4]) ? intval($producto[4]) : 1;

$cantidad = isset($producto[2]) ? intval($producto[2]) : 1;
$id_sucursal = isset($producto[5]) ? intval($producto[5]) : 1;
$comentario = isset($producto[6]) ? $producto[6] : 1;

$id_actualizar_validar = $this->tm->validarInventarioproducto($idproducto,$cantidad,$id_sucursal_descuento); 
echo $id_actualizar_validar;

if($id_actualizar_validar == true) {



} else {
$this->session->set_flashdata('error', 'algun producto no tiene sotck sufiente, revise que tengan stock suficiente');

}

echo "idproducto en fila $index: $idproducto<br>";
//cerando actualizar inventario productos
echo "Cantidad en fila $index: $cantidad<br>";
echo "id_sucursal en fila $index: $id_sucursal<br>";
echo "comentario en fila $index: $comentario<br>";
    }
}
//fin validar inventario





$this->session->set_flashdata('success', 'traslado agregado correctamente'); 
        
    }

    //////////
    //////
    ///
    //
    //
    function addNewTrasladar()
    {
    $id_sucursal_descuento = $this->session->userdata('id_sucursal');
        $productos = $this->input->post('productos');











        
  



    // Itera sobre los productos e imprime cada valor
    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {
 
            $id_sucursal_aumento = isset($producto[5]) ? intval($producto[5]) : 1;
      
            $comentario = isset($producto[6]) ? $producto[6] : 1;
            foreach ($producto as $subIndex => $subProducto) {
                echo "$subIndex: $subProducto<br>";
            }
        }
    }
    //aca estamos comenzando con los registros a la base de datos
    //primero registrarmos en la tabla ventas los datos generales del traslado




$id_usuario=$this->vendorId;
    $trasladarInfo = array('fecha_actual'=>date('Y-m-d'), 'comentario'=>$comentario, 'id_usuario'=>$id_usuario,'id_sucursal_descuento'=>$id_sucursal_descuento,'id_sucursal_aumento'=>$id_sucursal_aumento);
    $id_traslado=$this->tm->addNewtraslado($trasladarInfo);
   

    
    if($id_traslado > 0) {
        $this->session->set_flashdata('success', 'traslado realizado');
    } else {
        $this->session->set_flashdata('error', 'error agregar traslado');
    }
    //condicionamos aque actualize caja solo si es el contado



    foreach ($productos as $index => $producto) {
        // Verifica si estamos en una fila de productos
        if (is_array($producto)) {

            
     


 $idproducto = isset($producto[4]) ? intval($producto[4]) : 1;
 $cantidad = isset($producto[2]) ? intval($producto[2]) : 1;

   $detallesInfo = array('id_producto'=>$idproducto, 'cantidad'=>$cantidad,'id_traslado'=>$id_traslado);
   $id_detalle = $this->tm->addNewDetalletraslado($detallesInfo); 
   if($id_detalle > 0) {
    $this->session->set_flashdata('success', 'detalle venta agregado');
} else {
    $this->session->set_flashdata('error', 'error detalle venta agregado');
}


//aca voya actualizar el inventario de los productos que va descontar

$id_actualizar_restar = $this->tm->actualizarInventarioProductorestar($idproducto,$cantidad,$id_sucursal_descuento); 
if($id_actualizar_restar == true) {
 $this->session->set_flashdata('success', 'inventario actualizado');
} else {
 $this->session->set_flashdata('error', 'error actualizar inventario');
}
 
//aca voya actualizar el inventario de los productos que va aumentar

$id_actualizar_sumar = $this->tm->actualizarInventarioproductosumar($idproducto,$cantidad,$id_sucursal_aumento); 
if($id_actualizar_sumar == true) {
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



$this->session->set_flashdata('success', 'traslado agregado correctamente'); 
        
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
  



    function trasladar_lista()
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
            
            $count = $this->tm->traslado_lista_Count($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "traslado_lista/", $count, $count );
            
            $data['records'] = $this->tm->traslado_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' lista traslado';
            
            $this->loadViews("traslado/traslado_lista", $this->global, $data, NULL);
        }
    }



    public function filterTrasladar()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->tm->traslado_lista_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "traslado_lista/", $count, $count );
        
        $data['records'] = $this->tm->traslado_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = ' lista traslado';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('traslado/table_partial', $data);
    }

    function trasladar_lista_Recibidos()
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
            
            $count = $this->tm->traslado_lista_recibidos_Count($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "traslado_lista_recibidos/", $count, $count );
            
            $data['records'] = $this->tm->traslado_lista_recibidos($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' lista traslados';
            
            $this->loadViews("traslado/traslado_lista_recibidos", $this->global, $data, NULL);
        }
    }
    public function filterTrasladarRecibidos()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->tm->traslado_lista_recibidos_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "traslado_lista_recibidos/", $count, $count );
        
        $data['records'] = $this->tm->traslado_lista_recibidos($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = ' lista traslados';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('traslado/table_partial_recibidos', $data);
    }


    public function exportToPDF($id_traslado = NULL) {
        // Cargar la biblioteca TCPDF
    
        $data['traslados'] = $this->tm->get_traslado($id_traslado);
        $data['detalles'] = $this->tm->get_detalle_traslado($id_traslado);
    
        require_once('assets//TCPDF-main/tcpdf.php');
    
        // Crear una instancia de TCPDF con el tamaño de página para un ticket
        $pdf = new TCPDF('P', 'mm', array(80, 200));
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();
    
        // Crear el contenido HTML del PDF a partir de los datos
        $html = '<div style="margin-bottom: 10px;">'; // Ajusta el margen inferior
        foreach ($data['traslados'] as $traslado) {
            $html .= '<p>N° Traslado: ' . $traslado->id_traslado . '</p>';
            $html .= '<p>Fecha: ' . $traslado->fecha_actual . '</p>';
             $html .= '<p>Comentario: ' . $traslado->comentario . '</p>';
            $html .= '<p>Sucursal enviada: ' . $traslado->nombre_sucursal_aumento . '</p>';

        }
        $html .= '</div>';
    
        $html .= '<table style="width: 100%;">';
        $html .= '<tr><th>Producto</th><th>Cantidad</th></tr>';
    
        foreach ($data['detalles'] as $detalle) {
            $html .= '<tr>';
            $html .= '<td>' . $detalle->nombre_producto . '</td>';
            $html .= '<td>' . $detalle->cantidad . '</td>';
            $html .= '</tr>';
        }
    
        $html .= '</table>';
        // Mostrar valores de impuesto, descuento y total después de los detalles
$html .= '<div>';

$html .= '</div>';
    
        // Escribe el contenido HTML en el PDF
        $pdf->writeHTML($html);
    
        // Guardar el PDF o mostrarlo en el navegador
        $pdf->Output('reporte.pdf', 'I'); // 'I' para mostrar en el navegador
    }
    
    













}

?>