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
        $this->load->model('Reporte_administrador_model', 'rpam');
        $this->isLoggedIn();
        $this->module = 'Reportes';
    }

    private function authorizeReport($reportKey)
    {
        if (!$this->hasReportAccess($reportKey)) {
            $this->loadThis();
            return false;
        }

        return true;
    }

    private function getReportConfig($reportKey)
    {
        $reportList = $this->config->item('reportList');
        if (!is_array($reportList)) {
            return null;
        }

        foreach ($reportList as $report) {
            if (isset($report['key']) && $report['key'] === $reportKey) {
                return $report;
            }
        }

        return null;
    }

    private function buildReportGroups($accessibleReports)
    {
        $groups = array();
        foreach ($accessibleReports as $report) {
            $category = isset($report['category']) ? $report['category'] : 'General';
            if (!isset($groups[$category])) {
                $groups[$category] = array();
            }
            $groups[$category][] = $report;
        }

        return $groups;
    }

    private function getSelectorTargetAction($reportKey)
    {
        $map = array(
            'ventas_diarias' => 'reporte/reporte_venta_diario',
            'ventas_periodo' => 'reporte/reporte_venta_por_fecha',
            'ventas_mensuales' => 'reporte/reporte_venta_mensual',
            'productos_mas_vendidos' => 'reporte/reporte_venta_productos_mas_vendidos',
            'utilidad_estimada' => 'reporte/reporte_ganancias_por_fecha',
            'ventas_por_vendedor' => 'reporte/ventas_por_vendedor',
            'compras_periodo' => 'reporte/reporte_compra_por_fecha',
            'compras_mensuales' => 'reporte/reporte_compra_mensual',
            'compras_por_proveedor' => 'reporte/compras_por_proveedor',
            'traslados_enviados' => 'reporte_administrador/trasladar_lista',
            'traslados_recibidos' => 'reporte_administrador/trasladar_lista_Recibidos',
            'caja_operativa' => 'reporte/caja_operativa',
            'flujo_total' => 'reporte/flujo_total',
            'stock_actual' => 'reporte/stock_actual',
            'stock_bajo' => 'reporte/stock_bajo',
            'movimientos_inventario' => 'reporte/movimientos_inventario'
        );

        return isset($map[$reportKey]) ? $map[$reportKey] : '';
    }

    private function resolveSucursalId()
    {
        if ($this->canAccessAllBranchesReports()) {
            $idSucursal = $this->input->get('id_sucursal');
            if (empty($idSucursal)) {
                $idSucursal = $this->input->post('id_sucursal');
            }
            if (!empty($idSucursal)) {
                return (int) $idSucursal;
            }
        }

        return (int) $this->session->userdata('id_sucursal');
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $data['accessibleReports'] = $this->getAccessibleReports();
            $data['reportGroups'] = $this->buildReportGroups($data['accessibleReports']);
            $this->global['pageTitle'] = 'Centro de reportes';
            $this->loadViews("reporte/index", $this->global, $data, NULL);
        }
    }

    public function seleccionar_sucursal($reportKey = '')
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->canAccessAllBranchesReports() || !$this->authorizeReport($reportKey))
        {
            return;
        }

        $report = $this->getReportConfig($reportKey);
        $targetAction = $this->getSelectorTargetAction($reportKey);

        if (empty($report) || empty($targetAction)) {
            show_404();
            return;
        }

        $data['report'] = $report;
        $data['targetAction'] = $targetAction;
        $data['sucursales'] = $this->repm->get_sucursales();
        $this->global['pageTitle'] = $report['title'];
        $this->loadViews("reporte/seleccionar_sucursal", $this->global, $data, NULL);
    }

    public function caja_operativa()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('caja_operativa'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-d');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['summary'] = $this->repm->getCajaOperativaResumen($id_sucursal, $fechaInicial, $fechaFinal);
        $data['dailyTrend'] = $this->repm->getCajaOperativaTrend($id_sucursal, $fechaInicial, $fechaFinal);

        $this->global['pageTitle'] = 'Caja operativa';
        $this->loadViews("reporte/caja_operativa", $this->global, $data, NULL);
    }

    public function flujo_total()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('flujo_total'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['summary'] = $this->repm->getFlujoTotalResumen($id_sucursal, $fechaInicial, $fechaFinal);

        $this->global['pageTitle'] = 'Flujo total';
        $this->loadViews("reporte/flujo_total", $this->global, $data, NULL);
    }

    public function ventas_por_vendedor()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('ventas_por_vendedor'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
        $usuarioId = (int) ($this->input->get('usuario_id') ?: 0);

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['usuarioId'] = $usuarioId;
        $data['usuarios'] = $this->repm->getUsuariosPorSucursal($id_sucursal);
        $data['summary'] = $this->repm->getVentasPorVendedorResumen($id_sucursal, $fechaInicial, $fechaFinal, $usuarioId);

        $this->global['pageTitle'] = 'Ventas por vendedor';
        $this->loadViews("reporte/ventas_por_vendedor", $this->global, $data, NULL);
    }

    public function compras_por_proveedor()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('compras_por_proveedor'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
        $proveedorId = (int) ($this->input->get('proveedor_id') ?: 0);

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['proveedorId'] = $proveedorId;
        $data['proveedores'] = $this->repm->getProveedoresPorSucursal($id_sucursal);
        $data['summary'] = $this->repm->getComprasPorProveedorResumen($id_sucursal, $fechaInicial, $fechaFinal, $proveedorId);

        $this->global['pageTitle'] = 'Compras por proveedor';
        $this->loadViews("reporte/compras_por_proveedor", $this->global, $data, NULL);
    }

    public function stock_actual()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('stock_actual'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $categoriaId = (int) ($this->input->get('categoria_id') ?: 0);
        $producto = trim((string) $this->input->get('producto'));

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['categoriaId'] = $categoriaId;
        $data['producto'] = $producto;
        $data['categorias'] = $this->repm->getCategoriasProducto();
        $data['summary'] = $this->repm->getStockActualResumen($id_sucursal, $categoriaId, $producto);

        $this->global['pageTitle'] = 'Stock actual';
        $this->loadViews("reporte/stock_actual", $this->global, $data, NULL);
    }

    public function stock_bajo()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

        if(!$this->authorizeReport('stock_bajo'))
        {
            return;
        }

        $id_sucursal = $this->resolveSucursalId();
        $categoriaId = (int) ($this->input->get('categoria_id') ?: 0);
        $producto = trim((string) $this->input->get('producto'));
        $stockMaximo = max(0, (int) ($this->input->get('stock_maximo') ?: 5));

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['categoriaId'] = $categoriaId;
        $data['producto'] = $producto;
        $data['stockMaximo'] = $stockMaximo;
        $data['categorias'] = $this->repm->getCategoriasProducto();
        $data['summary'] = $this->repm->getStockBajoResumen($id_sucursal, $categoriaId, $producto, $stockMaximo);

        $this->global['pageTitle'] = 'Stock bajo';
        $this->loadViews("reporte/stock_bajo", $this->global, $data, NULL);
    }

    public function movimientos_inventario()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
            return;
        }
        if (!$this->authorizeReport('movimientos_inventario')) {
            return;
        }

        $this->load->model('Movimiento_inventario_model', 'mim');

        $id_sucursal  = $this->resolveSucursalId();
        $id_producto  = (int) $this->input->get('id_producto');
        $id_variante  = (int) $this->input->get('id_variante');
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal   = $this->input->get('fecha_final') ?: date('Y-m-d');

        $producto = $id_producto > 0 ? $this->mim->getProductoConVariantes($id_producto) : null;
        $summary  = null;

        if ($producto) {
            if ((int) $producto['tiene_variantes'] !== 1) {
                $id_variante = 0;
            }
            $summary = $this->mim->getMovimientos($id_sucursal, $id_producto, $id_variante, $fechaInicial, $fechaFinal);
        }

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre']     = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial']       = $fechaInicial;
        $data['fechaFinal']         = $fechaFinal;
        $data['idProducto']         = $id_producto;
        $data['idVariante']         = $id_variante;
        $data['producto']           = $producto;
        $data['summary']            = $summary;

        $this->global['pageTitle'] = 'Movimientos de inventario';
        $this->loadViews('reporte/movimientos_inventario', $this->global, $data, NULL);
    }

    public function movimientos_inventario_buscar_producto()
    {
        $this->output->set_content_type('application/json');
        if (!$this->hasListAccess()) {
            return $this->output->set_output(json_encode(array('items' => array())));
        }
        $this->load->model('Movimiento_inventario_model', 'mim');
        $q = trim((string) $this->input->get('q'));
        if ($q === '') {
            return $this->output->set_output(json_encode(array('items' => array())));
        }
        $items = $this->mim->buscarProductos($q, 25);
        return $this->output->set_output(json_encode(array('items' => $items), JSON_UNESCAPED_UNICODE));
    }




    function reporte_venta_por_fecha()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }
        if(!$this->authorizeReport('ventas_periodo'))
        {
            return;
        }
        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
        $searchText = trim((string) $this->input->get('searchText'));

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['searchText'] = $searchText;
        $data['summary'] = $this->repm->getVentasPorPeriodoResumen($id_sucursal, $fechaInicial, $fechaFinal, $searchText);

        $this->global['pageTitle'] = 'Ventas por periodo';
        $this->loadViews("reporte/reporte_venta_por_fecha", $this->global, $data, NULL);
    }


        function reporte_compra_por_fecha()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }
        if(!$this->authorizeReport('compras_periodo'))
        {
            return;
        }
        $id_sucursal = $this->resolveSucursalId();
        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
        $searchText = trim((string) $this->input->get('searchText'));

        $data['selectedSucursalId'] = $id_sucursal;
        $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['searchText'] = $searchText;
        $data['summary'] = $this->repm->getComprasPorPeriodoResumen($id_sucursal, $fechaInicial, $fechaFinal, $searchText);

        $this->global['pageTitle'] = 'Compras por periodo';
        $this->loadViews("reporte/reporte_compra_por_fecha", $this->global, $data, NULL);
    }




public function reporte_venta_mensual()
{
if(!$this->hasListAccess())
{
    $this->loadThis();
    return;
}
if(!$this->authorizeReport('ventas_mensuales'))
{
    return;
}


   $id_sucursal = $this->resolveSucursalId();
   $year = (int) ($this->input->get('year') ?: date('Y'));
   $data['selectedSucursalId'] = $id_sucursal;
   $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
   $data['year'] = $year;
   $data['summary'] = $this->repm->getVentasMensualesResumen($id_sucursal, $year);
   $this->global['pageTitle'] = 'Ventas mensuales';

   $this->loadViews("reporte/reporte_venta_mensual", $this->global, $data , NULL);

}
public function reporte_compra_mensual()
{
if(!$this->hasListAccess())
{
    $this->loadThis();
    return;
}
if(!$this->authorizeReport('compras_mensuales'))
{
    return;
}

 $id_sucursal = $this->resolveSucursalId();
 $year = (int) ($this->input->get('year') ?: date('Y'));
 $data['selectedSucursalId'] = $id_sucursal;
 $data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
 $data['year'] = $year;
 $data['summary'] = $this->repm->getComprasMensualesResumen($id_sucursal, $year);
 $this->global['pageTitle'] = 'Compras mensuales';

 $this->loadViews("reporte/reporte_compra_mensual", $this->global, $data , NULL);

}

public function reporte_venta_productos_mas_vendidos()
{
if(!$this->hasListAccess())
{
    $this->loadThis();
    return;
}
if(!$this->authorizeReport('productos_mas_vendidos'))
{
    return;
}

$id_sucursal = $this->resolveSucursalId();
$fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
$fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
$desglosarTalla = (int) $this->input->get('desglosar_talla') === 1;
$data['selectedSucursalId'] = $id_sucursal;
$data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
$data['fechaInicial'] = $fechaInicial;
$data['fechaFinal'] = $fechaFinal;
$data['desglosarTalla'] = $desglosarTalla;
$data['summary'] = $this->repm->getProductosMasVendidosResumen($id_sucursal, $fechaInicial, $fechaFinal, 10, $desglosarTalla);
$this->global['pageTitle'] = 'Productos más vendidos';

$this->loadViews("reporte/reporte_venta_productos_mas_vendidos", $this->global, $data , NULL);

}


public function reporte_ganancias_por_fecha()
{
if(!$this->hasListAccess())
{
    $this->loadThis();
    return;
}
if(!$this->authorizeReport('utilidad_estimada'))
{
    return;
}
$id_sucursal = $this->resolveSucursalId();
$fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
$fechaFinal = $this->input->get('fecha_final') ?: date('Y-m-d');
$data['selectedSucursalId'] = $id_sucursal;
$data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
$data['fechaInicial'] = $fechaInicial;
$data['fechaFinal'] = $fechaFinal;
$data['summary'] = $this->repm->getUtilidadEstimadaResumen($id_sucursal, $fechaInicial, $fechaFinal);
$this->global['pageTitle'] = 'Utilidad estimada';

$this->loadViews("reporte/reporte_ganancias_por_fecha", $this->global, $data , NULL);

}




public function reporte_venta_diario()
{
if(!$this->hasListAccess())
{
    $this->loadThis();
    return;
}
if(!$this->authorizeReport('ventas_diarias'))
{
    return;
}

$id_sucursal = $this->resolveSucursalId();
$year = (int) ($this->input->get('year') ?: date('Y'));
$month = (int) ($this->input->get('month') ?: date('n'));
$data['selectedSucursalId'] = $id_sucursal;
$data['sucursalNombre'] = $this->repm->getSucursalNombre($id_sucursal);
$data['year'] = $year;
$data['month'] = $month;
$data['summary'] = $this->repm->getVentasDiariasResumen($id_sucursal, $year, $month);

$this->global['pageTitle'] = 'Ventas diarias';
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
    
    $this->global['pageTitle'] = 'Ventas';


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
    
    $this->global['pageTitle'] = 'Compras';


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
    
    $this->global['pageTitle'] = 'Ventas';

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
    
    $this->global['pageTitle'] = 'Compras';

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
    
    $this->global['pageTitle'] = 'Ganancias';

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

public function exportar_pdf_reporte()
{
    if(!$this->hasListAccess())
    {
        $this->loadThis();
        return;
    }

    require_once('assets//TCPDF-main/tcpdf.php');

    $title = trim((string) $this->input->post('title'));
    $subtitle = trim((string) $this->input->post('subtitle'));
    $filtersHtml = (string) $this->input->post('filters_html');
    $kpisHtml = (string) $this->input->post('kpis_html');
    $tableHtml = (string) $this->input->post('table_html');
    $orientation = strtoupper((string) $this->input->post('orientation')) === 'L' ? 'L' : 'P';

    $pdf = new TCPDF($orientation, 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('POS Multisucursal');
    $pdf->SetAuthor('POS Multisucursal');
    $pdf->SetTitle($title ?: 'Reporte');
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    $html = '<style>
        h1{font-size:18px;color:#16324f;margin:0 0 4px;}
        .subtitle{font-size:10px;color:#5b6b7f;margin-bottom:10px;}
        .meta{font-size:9px;color:#34495e;margin-bottom:10px;}
        table{width:100%;border-collapse:collapse;font-size:9px;}
        th{background-color:#16324f;color:#ffffff;font-weight:bold;padding:6px;border:1px solid #d8e1eb;}
        td{padding:5px;border:1px solid #d8e1eb;}
        .kpi-table td{width:50%;}
    </style>';
    $html .= '<h1>' . htmlspecialchars($title ?: 'Reporte', ENT_QUOTES, 'UTF-8') . '</h1>';
    if ($subtitle !== '') {
        $html .= '<div class="subtitle">' . htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    if ($filtersHtml !== '') {
        $html .= '<div class="meta">' . $filtersHtml . '</div>';
    }
    if ($kpisHtml !== '') {
        $html .= $kpisHtml;
    }
    if ($tableHtml !== '') {
        $html .= $tableHtml;
    }

    $pdf->writeHTML($html, true, false, true, false, '');
    $filename = url_title($title ?: 'reporte', '_', true) . '.pdf';
    $pdf->Output($filename, 'I');
}

}

?>
