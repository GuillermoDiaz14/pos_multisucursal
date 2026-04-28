<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Trasladar extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Trasladar_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Traslados';
    }

    public function index()
    {
        redirect('trasladar/trasladar');
    }

    function trasladar()
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $data['productos']     = $this->tm->get_productos_com_stock($id_sucursal);
            $data['configuracion'] = $this->tm->get_configuracion($id_sucursal);
            $data['sucursales']    = $this->tm->get_sucursales($id_sucursal);
            $data['idusuario']     = $this->vendorId;
            $this->global['pageTitle'] = 'Registrar traslado';
            $this->loadViews("traslado/traslado", $this->global, $data, NULL);
        }
    }

    function addNewTrasladar()
    {
        $id_sucursal_origen  = $this->session->userdata('id_sucursal');
        $id_sucursal_destino = (int)$this->input->post('id_sucursal_destino');
        $comentario          = $this->security->xss_clean((string)$this->input->post('comentario'));
        $productos_raw       = $this->input->post('productos');

        $this->output->set_content_type('application/json');

        if (!$id_sucursal_destino || empty($productos_raw)) {
            $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Datos incompletos']));
            return;
        }
        if ($id_sucursal_destino === (int)$id_sucursal_origen) {
            $this->output->set_output(json_encode(['ok' => false, 'msg' => 'El destino no puede ser la misma sucursal de origen']));
            return;
        }

        $productos = json_decode($productos_raw, true);
        if (!is_array($productos) || empty($productos)) {
            $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Sin productos válidos']));
            return;
        }

        // Validate all stock FIRST before any DB write
        foreach ($productos as &$p) {
            $p['id_producto'] = (int)$p['id_producto'];
            $p['cantidad']    = max(1, (int)$p['cantidad']);
            if (!$this->tm->validarInventarioproducto($p['id_producto'], $p['cantidad'], $id_sucursal_origen)) {
                $this->output->set_output(json_encode([
                    'ok'  => false,
                    'msg' => 'Stock insuficiente para el producto ID ' . $p['id_producto'],
                ]));
                return;
            }
        }
        unset($p);

        $id_traslado = $this->tm->addNewtraslado([
            'fecha_actual'          => date('Y-m-d'),
            'comentario'            => $comentario,
            'id_usuario'            => $this->vendorId,
            'id_sucursal_descuento' => $id_sucursal_origen,
            'id_sucursal_aumento'   => $id_sucursal_destino,
        ]);

        if (!$id_traslado) {
            $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Error al registrar el traslado']));
            return;
        }

        foreach ($productos as $p) {
            $this->tm->addNewDetalletraslado([
                'id_producto' => $p['id_producto'],
                'cantidad'    => $p['cantidad'],
                'id_traslado' => $id_traslado,
            ]);
            $this->tm->actualizarInventarioProductorestar($p['id_producto'], $p['cantidad'], $id_sucursal_origen);
            $this->tm->actualizarInventarioproductosumar($p['id_producto'], $p['cantidad'], $id_sucursal_destino);
        }

        $this->output->set_output(json_encode(['ok' => true, 'msg' => 'Traslado registrado correctamente']));
    }

    function trasladar_lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText  = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count   = $this->tm->traslado_lista_Count($searchText, $id_sucursal);
            $returns = $this->paginationCompress("traslado_lista/", $count, $count);
            $data['records'] = $this->tm->traslado_lista($searchText, $id_sucursal, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Lista de traslados';
            $this->loadViews("traslado/traslado_lista", $this->global, $data, NULL);
        }
    }

    public function filterTrasladar()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = '';
        if (!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }

        $count   = $this->tm->traslado_lista_Count($searchText, $id_sucursal);
        $returns = $this->paginationCompress("traslado_lista/", $count, $count);

        $data['records'] = $this->tm->traslado_lista($searchText, $id_sucursal, $returns["page"], $returns["segment"]);
        $this->load->view('traslado/table_partial', $data);
    }

    function trasladar_lista_Recibidos()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText  = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count   = $this->tm->traslado_lista_recibidos_Count($searchText, $id_sucursal);
            $returns = $this->paginationCompress("traslado_lista_recibidos/", $count, $count);
            $data['records'] = $this->tm->traslado_lista_recibidos($searchText, $id_sucursal, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Traslados recibidos';
            $this->loadViews("traslado/traslado_lista_recibidos", $this->global, $data, NULL);
        }
    }

    public function filterTrasladarRecibidos()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = '';
        if (!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }

        $count   = $this->tm->traslado_lista_recibidos_Count($searchText, $id_sucursal);
        $returns = $this->paginationCompress("traslado_lista_recibidos/", $count, $count);

        $data['records'] = $this->tm->traslado_lista_recibidos($searchText, $id_sucursal, $returns["page"], $returns["segment"]);
        $this->load->view('traslado/table_partial_recibidos', $data);
    }

    public function exportToPDF($id_traslado = NULL)
    {
        $traslados = $this->tm->get_traslado($id_traslado);
        $detalles  = $this->tm->get_detalle_traslado($id_traslado);

        if (empty($traslados)) {
            show_404();
            return;
        }

        require_once('assets//TCPDF-main/tcpdf.php');

        $t   = $traslados[0];
        $pdf = new TCPDF('P', 'mm', array(80, 297));
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(4, 4, 4);
        $pdf->AddPage();

        $total_items = 0;
        foreach ($detalles as $d) { $total_items += (int)$d->cantidad; }

        $html  = '<div style="font-family:helvetica;font-size:9px;">';
        $html .= '<h3 style="text-align:center;margin:0 0 4px">TRASLADO #' . $t->id_traslado . '</h3>';
        $html .= '<hr style="border-top:1px solid #333;margin:2px 0"/>';

        $html .= '<table style="width:100%;font-size:8px;">';
        $html .= '<tr><td><b>Fecha:</b></td><td>' . $t->fecha_actual . '</td></tr>';
        $html .= '<tr><td><b>Origen:</b></td><td>' . htmlspecialchars($t->nombre_sucursal_descuento) . '</td></tr>';
        $html .= '<tr><td><b>Destino:</b></td><td>' . htmlspecialchars($t->nombre_sucursal_aumento) . '</td></tr>';
        $html .= '<tr><td><b>Realizado por:</b></td><td>' . htmlspecialchars($t->nombre_usuario ?? '—') . '</td></tr>';
        if (!empty($t->comentario)) {
            $html .= '<tr><td><b>Comentario:</b></td><td>' . htmlspecialchars($t->comentario) . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<hr style="border-top:1px dashed #333;margin:4px 0"/>';

        $html .= '<table style="width:100%;font-size:8px;border-collapse:collapse;">';
        $html .= '<tr style="background:#eee;">'
               . '<th style="border:1px solid #ccc;padding:2px;text-align:left;">Producto</th>'
               . '<th style="border:1px solid #ccc;padding:2px;text-align:center;">Código</th>'
               . '<th style="border:1px solid #ccc;padding:2px;text-align:center;">Cant.</th>'
               . '</tr>';
        foreach ($detalles as $d) {
            $html .= '<tr>'
                   . '<td style="border:1px solid #ccc;padding:2px;">' . htmlspecialchars($d->nombre_producto) . '</td>'
                   . '<td style="border:1px solid #ccc;padding:2px;text-align:center;">' . htmlspecialchars($d->codigo) . '</td>'
                   . '<td style="border:1px solid #ccc;padding:2px;text-align:center;">' . $d->cantidad . '</td>'
                   . '</tr>';
        }
        $html .= '</table>';

        $html .= '<hr style="border-top:1px dashed #333;margin:4px 0"/>';
        $html .= '<p style="font-size:8px;text-align:right;margin:0"><b>Total unidades: ' . $total_items . '</b></p>';
        $html .= '<p style="font-size:7px;text-align:center;margin:4px 0 0;color:#666;">Generado el ' . date('d/m/Y H:i') . '</p>';
        $html .= '</div>';

        $pdf->writeHTML($html);
        $pdf->Output('traslado_' . $id_traslado . '.pdf', 'I');
    }
}
