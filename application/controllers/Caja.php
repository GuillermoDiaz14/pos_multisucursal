<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Caja extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Caja_model', 'xm');
        $this->load->model('Reporte_model', 'repm');
        $this->isLoggedIn();
        $this->module = 'Caja';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('caja/add');
    }
    
    /**
     * This function is used to load the booking list
     */
    

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasAccessToModule('Caja'))
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            $cajaAbierta = $this->xm->getCajaAbiertaPorSucursal($id_sucursal, $id_usuario);

            $data['caja_abierta'] = $cajaAbierta;

            $this->global['pageTitle'] = $cajaAbierta ? 'Caja abierta' : 'Abrir caja';
            $this->loadViews("caja/add", $this->global, $data, NULL);
        }
    }
    function add_reparacion()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'Abrir caja';

            $this->loadViews("caja/add_reparacion", $this->global, NULL, NULL);
        }
    }
    /**
     * This function is used to add new user to the system
     */
    function addNewCaja()
    {
        if(!$this->hasAccessToModule('Caja'))
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric|greater_than_equal_to[0]');

            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $id_sucursal = $this->session->userdata('id_sucursal');
                $id_usuario  = $this->session->userdata('userId');

                if ($this->xm->getCajaAbiertaPorSucursal($id_sucursal, $id_usuario)) {
                    $this->session->set_flashdata('error', 'Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.');
                    redirect('caja/add');
                    return;
                }

                $saldo = (float)$this->security->xss_clean($this->input->post('saldo'));

                // Registramos monto_apertura y saldo iguales al inicio, y la fecha con hora.
                // saldo se irá moviendo con ventas/gastos/ingresos; monto_apertura queda fijo.
                $cajaInfo = array(
                    'fecha_apertura' => date('Y-m-d H:i:s'),
                    'fecha_cierre'   => null,
                    'monto_apertura' => $saldo,
                    'saldo'          => $saldo,
                    'estado'         => 'abierto',
                    'id_sucursal'    => $id_sucursal,
                    'id_usuario'     => $id_usuario,
                );

                $result = $this->xm->addNewCaja($cajaInfo);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nueva caja abierta satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear abrir caja');
                }

                redirect('carrito/ventas_lista');
            }
        }
    }






    function addNewCajaReparacion()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric|greater_than_equal_to[0]');

            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $id_sucursal = $this->session->userdata('id_sucursal');
                $id_usuario  = $this->session->userdata('userId');

                if ($this->xm->getCajaAbiertaPorSucursal($id_sucursal, $id_usuario)) {
                    $this->session->set_flashdata('error', 'Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.');
                    redirect('caja/add');
                    return;
                }

                $saldo = (float)$this->security->xss_clean($this->input->post('saldo'));

                $cajaInfo = array(
                    'fecha_apertura' => date('Y-m-d H:i:s'),
                    'fecha_cierre'   => null,
                    'monto_apertura' => $saldo,
                    'saldo'          => $saldo,
                    'estado'         => 'abierto',
                    'id_sucursal'    => $id_sucursal,
                    'id_usuario'     => $id_usuario,
                );

                $result = $this->xm->addNewCaja($cajaInfo);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nueva caja abierta satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear abrir caja');
                }

                redirect('reparacion/reparacion_lista_todas');
            }
        }
    }
//cerrarCaja
    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */

    
    
    /**
     * This function is used to edit the user information
     */
    function cerrarCaja()
    {
        if(!$this->hasAccessToModule('Caja'))
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            // Filtramos por cajero (3er param) para no cerrar cajas de otros usuarios.
            $validacioncaja = $this->xm->cerrarCaja($id_sucursal, $id_usuario, $id_usuario);

            if($validacioncaja == true) {
                $this->session->set_flashdata('success', 'caja cerrada');
            } else {
                $this->session->set_flashdata('error', 'error cerrando caja');
            }

            echo json_encode(array('success' => $validacioncaja));
        }
    }

    function cerrarCajaReparacion()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $id_usuario  = $this->session->userdata('userId');
            $validacioncaja = $this->xm->cerrarCaja($id_sucursal, $id_usuario, $id_usuario);

            if($validacioncaja == true) {
                $this->session->set_flashdata('success', 'caja cerrada');
            } else {
                $this->session->set_flashdata('error', 'error cerrando caja');
            }

            echo json_encode(array('success' => $validacioncaja));
        }
    }

    /**
     * Pantalla de cierre de caja con arqueo: muestra desglose del turno (apertura,
     * ventas por método, gastos, ingresos, esperado) y pide al cajero contar el efectivo.
     */
    function cierre_arqueo()
    {
        if(!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $id_sucursal = $this->session->userdata('id_sucursal');
        $id_usuario  = $this->session->userdata('userId');
        $caja = $this->xm->getCajaAbiertaPorSucursal($id_sucursal, $id_usuario);

        if (!$caja) {
            $this->session->set_flashdata('error', 'No tienes una caja abierta en esta sucursal.');
            redirect('caja/add');
            return;
        }

        $data['caja']    = $caja;
        $data['resumen'] = $this->xm->getResumenCierre($caja->id_caja);

        $this->global['pageTitle'] = 'Cierre de caja';
        $this->loadViews('caja/cierre', $this->global, $data, NULL);
    }

    /**
     * Procesa el formulario de cierre con arqueo: guarda esperado, contado, diferencia
     * y observaciones; cierra la caja registrando hora exacta y usuario.
     */
    function confirmar_cierre()
    {
        if(!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_caja', 'id_caja', 'trim|required|integer');
        $this->form_validation->set_rules('efectivo_contado', 'Efectivo contado', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|max_length[1000]');

        if ($this->form_validation->run() == FALSE) {
            $this->cierre_arqueo();
            return;
        }

        $id_caja           = (int)$this->input->post('id_caja');
        $efectivo_contado  = (float)$this->input->post('efectivo_contado');
        $observaciones     = trim((string)$this->security->xss_clean($this->input->post('observaciones')));

        $id_sucursal = $this->session->userdata('id_sucursal');
        $id_usuario  = $this->session->userdata('userId');
        $caja = $this->xm->getCajaAbiertaPorSucursal($id_sucursal, $id_usuario);

        if (!$caja || (int)$caja->id_caja !== $id_caja) {
            $this->session->set_flashdata('error', 'La caja indicada no está abierta o no pertenece a este cajero.');
            redirect('caja/cierre_arqueo');
            return;
        }

        $efectivo_esperado = (float)$caja->saldo;
        $diferencia        = round($efectivo_contado - $efectivo_esperado, 2);

        // Si hay descuadre, exigimos justificación.
        if (abs($diferencia) > 0.009 && $observaciones === '') {
            $this->session->set_flashdata('error', 'Se detectó una diferencia de $' . number_format($diferencia, 2)
                . '. Debes registrar una observación que la justifique.');
            redirect('caja/cierre_arqueo');
            return;
        }

        $arqueoData = array(
            'efectivo_esperado' => $efectivo_esperado,
            'efectivo_contado'  => $efectivo_contado,
            'diferencia'        => $diferencia,
            'observaciones'     => $observaciones,
            'id_usuario_cierre' => $this->session->userdata('userId'),
        );

        $ok = $this->xm->cerrarCajaConArqueo($id_caja, $arqueoData);

        if ($ok) {
            // Mensaje con link directo al ticket PDF (la vista de caja/add muestra el HTML del flashdata).
            $linkTicket = base_url() . 'caja/ticket_cierre/' . $id_caja;
            $msg = 'Caja cerrada correctamente. Diferencia: $' . number_format($diferencia, 2)
                 . '. <a href="' . $linkTicket . '" target="_blank" class="btn btn-xs btn-primary" style="margin-left:8px;">'
                 . '<i class="fa fa-print"></i> Imprimir ticket de cierre</a>';
            $this->session->set_flashdata('success', $msg);
            redirect('caja/add');
        } else {
            $this->session->set_flashdata('error', 'No fue posible cerrar la caja. Intenta nuevamente.');
            redirect('caja/cierre_arqueo');
        }
    }

    /**
     * Genera el ticket PDF de cierre de caja con desglose por método de pago,
     * gastos, ingresos, esperado vs contado, diferencia y observaciones.
     * Reimprimible: se puede invocar para cualquier id_caja cerrada.
     */
    function ticket_cierre($id_caja = null)
    {
        if(!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $id_caja = (int)$id_caja;
        if ($id_caja <= 0) {
            show_error('id_caja inválido', 400);
            return;
        }

        $caja = $this->xm->getCajaCompleta($id_caja);
        if (!$caja) {
            show_error('Caja no encontrada', 404);
            return;
        }

        // Verificación de acceso por sucursal (igual que detalle/reporte_cierre).
        if (!$this->canAccessAllBranchesReports()) {
            $sesionSucursal = (int)$this->session->userdata('id_sucursal');
            if ((int)$caja->id_sucursal !== $sesionSucursal) {
                show_error('No tienes acceso a esta caja.', 403);
                return;
            }
        }

        $resumen = $this->xm->getResumenCierre($id_caja);

        require_once('assets//TCPDF-main/tcpdf.php');

        $pdf = new TCPDF('P', 'mm', array(80, 250));
        $pdf->SetMargins(2, 2, 2);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        $image_path = FCPATH . 'assets/dist/img/logodemo.png';
        if (file_exists($image_path) && is_readable($image_path)) {
            try {
                $pageWidth  = 80;
                $imageWidth = 40;
                $x = ($pageWidth - $imageWidth) / 2;
                $pdf->Image($image_path, $x, 5, $imageWidth, 0, 'PNG');
            } catch (Exception $e) {
                log_message('error', 'Error al insertar logo en ticket cierre: ' . $e->getMessage());
            }
        }

        $simbolo = !empty($caja->simbolo_moneda) ? $caja->simbolo_moneda : '$';
        $fmt = function ($v) use ($simbolo) {
            return $simbolo . number_format((float)$v, 2);
        };

        $efectivo_esperado = isset($caja->efectivo_esperado) ? (float)$caja->efectivo_esperado : (float)$resumen['saldo_sistema'];
        $efectivo_contado  = isset($caja->efectivo_contado)  ? (float)$caja->efectivo_contado  : 0;
        $diferencia        = isset($caja->diferencia)        ? (float)$caja->diferencia        : 0;
        $estado_cierre     = ($caja->estado === 'cerrado') ? 'CIERRE DE CAJA' : 'PRE-CIERRE (caja aún abierta)';

        $html  = '<div style="text-align:center; font-size:11px; font-weight:bold;">'
               . htmlspecialchars($caja->nombre_sucursal ?: 'Sucursal', ENT_QUOTES) . '</div>';
        $html .= '<div style="text-align:center; font-size:10px; font-weight:bold;">'
               . $estado_cierre . '</div>';
        $html .= '<div style="text-align:center; font-size:8px;">Caja #' . (int)$caja->id_caja . '</div>';
        $html .= '<hr>';

        $html .= '<table width="100%" style="font-size:8px;">'
               . '<tr><td><b>Apertura</b></td><td align="right">' . htmlspecialchars($caja->fecha_apertura, ENT_QUOTES) . '</td></tr>'
               . '<tr><td>Cajero apertura</td><td align="right">' . htmlspecialchars($caja->nombre_usuario_apertura ?: '—', ENT_QUOTES) . '</td></tr>'
               . '<tr><td>Monto apertura</td><td align="right">' . $fmt($caja->monto_apertura) . '</td></tr>';

        if ($caja->estado === 'cerrado') {
            $html .= '<tr><td><b>Cierre</b></td><td align="right">' . htmlspecialchars($caja->fecha_cierre, ENT_QUOTES) . '</td></tr>'
                   . '<tr><td>Cajero cierre</td><td align="right">' . htmlspecialchars($caja->nombre_usuario_cierre ?: '—', ENT_QUOTES) . '</td></tr>';
        }
        $html .= '</table><hr>';

        // Movimientos del turno
        $html .= '<div style="font-size:9px; font-weight:bold;">Movimientos del turno</div>';
        $html .= '<table width="100%" style="font-size:8px;">';
        if (!empty($resumen['detalle_metodos'])) {
            foreach ($resumen['detalle_metodos'] as $nombre => $monto) {
                $html .= '<tr><td>' . htmlspecialchars($nombre, ENT_QUOTES) . '</td>'
                       . '<td align="right">' . $fmt($monto) . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2"><i>Sin ventas registradas</i></td></tr>';
        }
        $html .= '<tr><td>Ingresos extra</td><td align="right">+ ' . $fmt($resumen['ingresos']) . '</td></tr>';
        $html .= '<tr><td>Gastos</td><td align="right">- ' . $fmt($resumen['gastos']) . '</td></tr>';
        $html .= '</table><hr>';

        // Arqueo
        $html .= '<table width="100%" style="font-size:9px;">'
               . '<tr><td><b>Efectivo esperado</b></td><td align="right"><b>' . $fmt($efectivo_esperado) . '</b></td></tr>';

        if ($caja->estado === 'cerrado') {
            $html .= '<tr><td><b>Efectivo contado</b></td><td align="right"><b>' . $fmt($efectivo_contado) . '</b></td></tr>';
            $etiqueta = ($diferencia == 0) ? 'CUADRADA'
                : (($diferencia > 0) ? 'SOBRANTE' : 'FALTANTE');
            $html .= '<tr><td><b>Diferencia (' . $etiqueta . ')</b></td>'
                   . '<td align="right"><b>' . $fmt($diferencia) . '</b></td></tr>';
        }
        $html .= '</table>';

        if ($caja->estado === 'cerrado' && !empty($caja->observaciones_cierre)) {
            $html .= '<hr><div style="font-size:8px;"><b>Observaciones:</b><br>'
                   . nl2br(htmlspecialchars($caja->observaciones_cierre, ENT_QUOTES)) . '</div>';
        }

        if ((float)$resumen['ventas_otros_metodos'] > 0) {
            $html .= '<hr><div style="font-size:7px; text-align:center;"><i>Las ventas en métodos no efectivo ('
                   . $fmt($resumen['ventas_otros_metodos']) . ') no afectan el efectivo en caja.</i></div>';
        }

        $html .= '<hr><div style="text-align:center; font-size:7px;">Firma cajero</div>';
        $html .= '<div style="text-align:center; font-size:7px;">_____________________________</div>';
        $html .= '<div style="text-align:center; font-size:7px;">Generado: ' . date('Y-m-d H:i:s') . '</div>';

        $pdf->writeHTML($html);
        $pdf->Output('cierre_caja_' . (int)$caja->id_caja . '.pdf', 'I');
    }

    /**
     * Histórico de cajas con diferencia (faltante/sobrante) por cajero.
     * Sucursal: respeta el alcance del usuario (todas vs su sucursal).
     */
    function historial()
    {
        if (!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $puedeVerTodas = $this->canAccessAllBranchesReports();
        $sesionSucursal = (int)$this->session->userdata('id_sucursal');

        // id_sucursal=0 con permiso = "todas"; sin permiso, siempre la suya.
        $idSucursalGet = $this->input->get('id_sucursal');
        if ($puedeVerTodas) {
            $id_sucursal = ($idSucursalGet === '' || $idSucursalGet === null) ? 0 : (int)$idSucursalGet;
        } else {
            $id_sucursal = $sesionSucursal;
        }

        $fechaInicial = $this->input->get('fecha_inicial') ?: date('Y-m-01');
        $fechaFinal   = $this->input->get('fecha_final')   ?: date('Y-m-d');
        $estado       = $this->input->get('estado') ?: '';
        $id_usuario_f = (int)($this->input->get('id_usuario') ?: 0);

        $filters = array(
            'id_sucursal'   => $id_sucursal ?: null,
            'id_usuario'    => $id_usuario_f ?: null,
            'estado'        => in_array($estado, array('abierto', 'cerrado'), true) ? $estado : null,
            'fecha_inicial' => $fechaInicial,
            'fecha_final'   => $fechaFinal,
        );

        $data['canViewAll']      = $puedeVerTodas;
        $data['sucursales']      = $puedeVerTodas ? $this->repm->get_sucursales() : array();
        $data['cajeros']         = $this->xm->getCajerosConHistorial($id_sucursal ?: null);
        $data['selectedSucursal']= $id_sucursal;
        $data['fechaInicial']    = $fechaInicial;
        $data['fechaFinal']      = $fechaFinal;
        $data['estadoFiltro']    = $estado;
        $data['idUsuarioFiltro'] = $id_usuario_f;
        $data['records']         = $this->xm->getHistorialCajas($filters);
        $data['resumen']         = $this->xm->getHistorialCajasResumen($filters);

        $this->global['pageTitle'] = 'Histórico de cajas';
        $this->loadViews('caja/historial', $this->global, $data, NULL);
    }

    /**
     * Detalle de movimientos de una caja: ventas, cuotas, gastos e ingresos.
     * Reutiliza getResumenCierre para los KPIs y getMovimientosPorCaja para el detalle.
     */
    function detalle($id_caja = null)
    {
        if (!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $id_caja = (int)$id_caja;
        if ($id_caja <= 0) {
            show_error('id_caja inválido', 400);
            return;
        }

        $caja = $this->xm->getCajaCompleta($id_caja);
        if (!$caja) {
            show_error('Caja no encontrada', 404);
            return;
        }

        // Si el usuario no puede ver todas las sucursales, restringimos a la suya.
        if (!$this->canAccessAllBranchesReports()) {
            $sesionSucursal = (int)$this->session->userdata('id_sucursal');
            if ((int)$caja->id_sucursal !== $sesionSucursal) {
                $this->session->set_flashdata('error', 'No tienes acceso a esta caja.');
                redirect('caja/historial');
                return;
            }
        }

        $data['caja']        = $caja;
        $data['resumen']     = $this->xm->getResumenCierre($id_caja);
        $data['movimientos'] = $this->xm->getMovimientosPorCaja($id_caja);

        $this->global['pageTitle'] = 'Detalle de caja #' . $id_caja;
        $this->loadViews('caja/detalle', $this->global, $data, NULL);
    }

    /**
     * Reporte de cierre en HTML (consolidado del turno) reimprimible para cualquier
     * caja cerrada o pre-cierre para una abierta. Complementa al PDF (ticket_cierre).
     */
    function reporte_cierre($id_caja = null)
    {
        if (!$this->hasAccessToModule('Caja')) {
            $this->loadThis();
            return;
        }

        $id_caja = (int)$id_caja;
        if ($id_caja <= 0) {
            show_error('id_caja inválido', 400);
            return;
        }

        $caja = $this->xm->getCajaCompleta($id_caja);
        if (!$caja) {
            show_error('Caja no encontrada', 404);
            return;
        }

        if (!$this->canAccessAllBranchesReports()) {
            $sesionSucursal = (int)$this->session->userdata('id_sucursal');
            if ((int)$caja->id_sucursal !== $sesionSucursal) {
                $this->session->set_flashdata('error', 'No tienes acceso a esta caja.');
                redirect('caja/historial');
                return;
            }
        }

        $data['caja']    = $caja;
        $data['resumen'] = $this->xm->getResumenCierre($id_caja);

        $this->global['pageTitle'] = 'Reporte de cierre — Caja #' . $id_caja;
        $this->loadViews('caja/reporte_cierre', $this->global, $data, NULL);
    }



    


}

?>
