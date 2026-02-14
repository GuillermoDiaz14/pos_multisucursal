<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Reporte_administrador extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reporte_administrador_model', 'rpam');
        $this->isLoggedIn();
        $this->module = 'Reporte_administrador';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    function seleccion_traslado()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/traslado_selec", $this->global, $data , NULL);
        }
    }


        function seleccion_traslado_recibido()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/traslado_recibido_selec", $this->global, $data , NULL);
        }
    }

   function seleccion_sucursal_venta_diario()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/venta_diario_selec_sucursal", $this->global, $data , NULL);
        }
    }
    function seleccion_sucursal_venta_mensual()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/venta_mensual_selec_sucursal", $this->global, $data , NULL);
        }
    }
     
    
    function seleccion_sucursal_venta_por_fecha()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();
// $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/venta_por_fecha_selec_sucursal", $this->global, $data , NULL);
        }
    }

    function seleccion_sucursal_venta_productos_mas_vendidos()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/venta_productos_mas_vendidos_selec_sucursal", $this->global, $data , NULL);
        }
    }
  function seleccion_sucursal_compra_por_fecha()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/compra_por_fecha_selec_sucursal", $this->global, $data , NULL);
        }
    }

      function seleccion_sucursal_compra_mensual()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/compra_mensual_selec_sucursal", $this->global, $data , NULL);
        }
    }
    function seleccion_sucursal_ganancias_ventas_productos()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
              $data['sucursales'] = $this->rpam->get_sucursales();

$this->global['pageTitle'] = 'seleccione sucursal';

$this->loadViews("reporte_administrador/ganancias_ventas_selec_sucursal", $this->global, $data , NULL);
        }
    }
    

    function reporte_venta_por_fecha()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else    
        {
            $id_sucursal = $this->input->post('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
            
            $this->load->library('pagination');
            
            $count = $this->rpam->venta_lista_Count_por_fecha($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "reporte_venta_por_fecha/", $count, $count );
            
            $data['records'] = $this->rpam->reporte_venta_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' reporte lista venta';
            
            $this->loadViews("reporte_administrador/reporte_venta_por_fecha", $this->global, $data, NULL);
        }
    }


        function reporte_compra_por_fecha()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->input->post('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
            $this->load->library('pagination');
            
            $count = $this->rpam->compra_lista_Count_por_fecha($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "reporte_compra_por_fecha/", $count, $count );
            
            $data['records'] = $this->rpam->reporte_compra_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' reporte lista compra';
            
            $this->loadViews("reporte_administrador/reporte_compra_por_fecha", $this->global, $data, NULL);
        }
    }




public function reporte_venta_mensual()
{


    $id_sucursal = $this->input->post('id_sucursal');
$data['ventas'] = $this->rpam->get_ventas($id_sucursal);
$this->global['pageTitle'] = 'Prometeo Service  : Reporte venta mensual';

$this->loadViews("reporte_administrador/reporte_venta_mensual", $this->global, $data , NULL);

}
public function reporte_compra_mensual()
{

    $id_sucursal = $this->input->post('id_sucursal');

$data['compras'] = $this->rpam->get_compras($id_sucursal);
$this->global['pageTitle'] = ' Dashboard';

$this->loadViews("reporte_administrador/reporte_compra_mensual", $this->global, $data , NULL);

}

public function reporte_venta_productos_mas_vendidos()
{


    $id_sucursal = $this->input->post('id_sucursal');
$data['ventas'] = $this->rpam->get_detalles_ventas($id_sucursal);
$this->global['pageTitle'] = ' Productos mas vendidos';

$this->loadViews("reporte_administrador/reporte_venta_productos_mas_vendidos", $this->global, $data , NULL);

}


public function reporte_ganancias_por_fecha()
{

    $id_sucursal = $this->input->post('id_sucursal');
    $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
$data['ventas'] = $this->rpam->get_detalles_ventas_sumatorias($id_sucursal);
$this->global['pageTitle'] = ' Reporte ganancias';

$this->loadViews("reporte_administrador/reporte_ganancias_por_fecha", $this->global, $data , NULL);

}




public function reporte_venta_diario()
{

    $id_sucursal = $this->input->post('id_sucursal');

//$id_sucursal = $this->session->userdata('id_sucursal');
$data['ventas'] = $this->rpam->get_sumatoriaPorDia($id_sucursal);

$this->global['pageTitle'] = ' Reporte diario';
$totalesPorDia = $this->organizarTotalesPorDia($data['ventas']);
$data['totalesPorDia'] = $totalesPorDia;

$this->loadViews("reporte_administrador/reporte_venta_diario", $this->global, $data , NULL);

}


   


private function organizarTotalesPorDia($ventas) {
    $totalesPorDia = [];

    $ultimoDiaMes = date('t', strtotime('first day of this month'));
    for ($dia = 1; $dia <= $ultimoDiaMes; $dia++) {
        $fecha = date('Y-m-d', strtotime('first day of this month') + ($dia - 1) * 86400);
        $totalesPorDia[$fecha] = [
            'suma_base_imponible' => 0,
            'suma_impuesto' => 0,
            'suma_descuento' => 0,
            'suma_total' => 0
        ];
    }

    foreach ($ventas as $venta) {
        $fecha_venta = $venta->fecha_venta;
        $totalesPorDia[$fecha_venta] = [
            'suma_base_imponible' => floatval($venta->suma_base_imponible),
            'suma_impuesto' => floatval($venta->suma_impuesto),
            'suma_descuento' => floatval($venta->suma_descuento),
            'suma_total' => floatval($venta->suma_total)
        ];
    }
    

    return $totalesPorDia;
}




public function filterVenta_fechas()
{

    $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
//    $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->rpam->venta_lista_Count_por_fecha($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "reporte_venta_por_fecha/", $count, $count );
    
    $data['records'] = $this->rpam->reporte_venta_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' Ventas';


    $this->load->view('reporte_administrador/table_partial_venta_por_fecha', $data);
}
public function filterCompra_fechas()
{
    $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->rpam->compra_lista_Count_por_fecha($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "reporte_compra_por_fecha/", $count, $count );
    
    $data['records'] = $this->rpam->reporte_compra_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' compra';


    $this->load->view('reporte_administrador/table_partial_compra_por_fecha', $data);
}




public function filterVenta_entre_dos_fechas()
{

    $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->rpam->venta_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_venta_por_fecha/", $count, $count);
    
    $data['records'] = $this->rpam->reporte_venta_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' ventas';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte_administrador/table_partial_venta_por_fecha', $data);
}

public function filterCompra_entre_dos_fechas()
{

    $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->rpam->compra_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_compra_por_fecha/", $count, $count);
    
    $data['records'] = $this->rpam->reporte_compra_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' compras';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte_administrador/table_partial_compra_por_fecha', $data);
}


public function filterGanancia_entre_dos_fechas()
{
    $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->rpam->get_detalles_ganancias_sumatorias_entre_dos_fechas_Count($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_ganancias_por_fecha/", $count, $count);
    
    $data['ventas'] = $this->rpam->get_detalles_ganancias_sumatorias_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' ganancias';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte_administrador/table_partial_ganancias_por_fecha', $data);
}



public function generatePDF() {
    // Recupera los datos de la tabla desde la solicitud POST
    $tableData = $this->input->post('tableData');

    // Carga la librería TCPDF
    $this->load->library('pdf');

    // Configura el título y el nombre del autor del PDF
    $this->pdf->SetTitle('Informe de Reparaciones');
    $this->pdf->SetAuthor('Tu Nombre');

    // Inicializa el objeto PDF
    $this->pdf->AddPage();

    // Crea una tabla en el PDF a partir de los datos
    $html = '<table>';
    foreach ($tableData as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . $cell . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table';

    // Agrega la tabla al PDF
    $this->pdf->writeHTML($html, true, false, false, false, '');

    // Genera el PDF y lo muestra en el navegador
    $this->pdf->Output();
}

public function exportToPDF() {
    // Cargar la biblioteca TCPDF

    require_once('assets//TCPDF-main/tcpdf.php');
    // Crear una instancia de TCPDF
    $pdf = new TCPDF();
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->AddPage();

    // Datos de ejemplo (reemplaza esto con tus datos reales)
    $data = array(
        array('ID', 'Nombre', 'Correo'),
        array(1, 'Usuario 1', 'usuario1@example.com'),
        array(2, 'Usuario 2', 'usuario2@example.com'),
        array(3, 'Usuario 3', 'usuario3@example.com'),
    );

    // Crear el contenido HTML del PDF a partir de los datos
    $html = '<table>';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . $cell . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Escribe el contenido HTML en el PDF
    $pdf->writeHTML($html);

    // Guardar el PDF o mostrarlo en el navegador
    $pdf->Output('reporte.pdf', 'I'); // 'I' para mostrar en el navegador
}




    function trasladar_lista()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {

            $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
                        $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
            $this->load->library('pagination');
            
            $count = $this->rpam->traslado_lista_Count($searchText,$id_sucursal);

            $returns = $this->paginationCompress ( "traslado_lista/", $count, $count );
            
            $data['records'] = $this->rpam->traslado_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' lista traslados';
            
            $this->loadViews("reporte_administrador/reporte_traslado_lista", $this->global, $data, NULL);
        }
    }



    public function filterTrasladar()
    {
        $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->rpam->traslado_lista_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "trasladar_lista/", $count, $count );
        
        $data['records'] = $this->rpam->traslado_lista($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = ' lista Traslados';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('reporte_administrador/table_partial', $data);
    }

    function trasladar_lista_Recibidos()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {

 $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
              $data['id_sucursal'] = $id_sucursal; // Nueva línea añadida
            $this->load->library('pagination');
            
            $count = $this->rpam->traslado_lista_recibidos_Count($searchText,$id_sucursal);

            $returns = $this->paginationCompress ( "traslado_lista_recibidos/", $count, $count );
            
            $data['records'] = $this->rpam->traslado_lista_recibidos($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' lista traslados';
            
            $this->loadViews("reporte_administrador/reporte_traslado_lista_recibidos", $this->global, $data, NULL);
        }
    }
    public function filterTrasladarRecibidos()
    {
       $id_sucursal = $this->security->xss_clean($this->input->post('id_sucursal'));
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
    
        }
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->rpam->traslado_lista_recibidos_Count($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "traslado_lista_recibidos/", $count, $count );
        
        $data['records'] = $this->rpam->traslado_lista_recibidos($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = ' lista traslados';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('reporte_administrador/table_partial_recibidos', $data);
    }

}

?>