<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Reporte extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reporte_model', 'repm');
        $this->isLoggedIn();
        $this->module = 'Reporte';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('general/dashboard');
    }




    function reporte_venta_por_fecha()
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
            
            $count = $this->repm->venta_lista_Count_por_fecha($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "reporte_venta_por_fecha/", $count, $count );
            
            $data['records'] = $this->repm->reporte_venta_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'tusolutionweb : reporte lista venta';
            
            $this->loadViews("reporte/reporte_venta_por_fecha", $this->global, $data, NULL);
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
             $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->repm->compra_lista_Count_por_fecha($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "reporte_compra_por_fecha/", $count, $count );
            
            $data['records'] = $this->repm->reporte_compra_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'tusolutionweb : reporte lista compra';
            
            $this->loadViews("reporte/reporte_compra_por_fecha", $this->global, $data, NULL);
        }
    }




public function reporte_venta_mensual()
{


   $id_sucursal = $this->session->userdata('id_sucursal');
$data['ventas'] = $this->repm->get_ventas($id_sucursal);
$this->global['pageTitle'] = 'Prometeo Service  : Dashboard';

$this->loadViews("reporte/reporte_venta_mensual", $this->global, $data , NULL);

}
public function reporte_compra_mensual()
{

 $id_sucursal = $this->session->userdata('id_sucursal');

$data['compras'] = $this->repm->get_compras($id_sucursal);
$this->global['pageTitle'] = 'tusolutionweb : Dashboard';

$this->loadViews("reporte/reporte_compra_mensual", $this->global, $data , NULL);

}

public function reporte_venta_productos_mas_vendidos()
{


$id_sucursal = $this->session->userdata('id_sucursal');
$data['ventas'] = $this->repm->get_detalles_ventas($id_sucursal);
$this->global['pageTitle'] = 'Tusolutionweb : Productos mas vendidos';

$this->loadViews("reporte/reporte_venta_productos_mas_vendidos", $this->global, $data , NULL);

}


public function reporte_ganancias_por_fecha()
{

$id_sucursal = $this->session->userdata('id_sucursal');

$data['ventas'] = $this->repm->get_detalles_ventas_sumatorias($id_sucursal);
$this->global['pageTitle'] = 'Tusolutionweb : Reporte ganancias';

$this->loadViews("reporte/reporte_ganancias_por_fecha", $this->global, $data , NULL);

}




public function reporte_venta_diario()
{


$id_sucursal = $this->session->userdata('id_sucursal');
$data['ventas'] = $this->repm->get_sumatoriaPorDia($id_sucursal);

$this->global['pageTitle'] = 'Tusolutionweb : Reporte diario';
$totalesPorDia = $this->organizarTotalesPorDia($data['ventas']);
$data['totalesPorDia'] = $totalesPorDia;

$this->loadViews("reporte/reporte_venta_diario", $this->global, $data , NULL);

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
    $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->repm->venta_lista_Count_por_fecha($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "reporte_venta_por_fecha/", $count, $count );
    
    $data['records'] = $this->repm->reporte_venta_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : Ventas';


    $this->load->view('reporte/table_partial_venta_por_fecha', $data);
}
public function filterCompra_fechas()
{
       $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->repm->compra_lista_Count_por_fecha($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "reporte_compra_por_fecha/", $count, $count );
    
    $data['records'] = $this->repm->reporte_compra_por_fecha($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : compra';


    $this->load->view('reporte/table_partial_compra_por_fecha', $data);
}




public function filterVenta_entre_dos_fechas()
{

    $id_sucursal = $this->session->userdata('id_sucursal');
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->repm->venta_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_venta_por_fecha/", $count, $count);
    
    $data['records'] = $this->repm->reporte_venta_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : ventas';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte/table_partial_venta_por_fecha', $data);
}

public function filterCompra_entre_dos_fechas()
{

      $id_sucursal = $this->session->userdata('id_sucursal');
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->repm->compra_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_compra_por_fecha/", $count, $count);
    
    $data['records'] = $this->repm->reporte_compra_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : compras';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte/table_partial_compra_por_fecha', $data);
}


public function filterGanancia_entre_dos_fechas()
{
       $id_sucursal = $this->session->userdata('id_sucursal');
    $fecha_inicial = $this->security->xss_clean($this->input->post('fechaInicial'));
    $fecha_final = $this->security->xss_clean($this->input->post('fechaFinal'));

    $data['fecha_inicial'] = $fecha_inicial;
    $data['fecha_final'] = $fecha_final;
    
    $this->load->library('pagination');
    
    // Modificar tu lógica de consulta para usar ambas fechas
    $count = $this->repm->get_detalles_ganancias_sumatorias_entre_dos_fechas_Count($fecha_inicial, $fecha_final,$id_sucursal);

    $returns = $this->paginationCompress("reporte_ganancias_por_fecha/", $count, $count);
    
    $data['ventas'] = $this->repm->get_detalles_ganancias_sumatorias_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : ganancias';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('reporte/table_partial_ganancias_por_fecha', $data);
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

}

?>