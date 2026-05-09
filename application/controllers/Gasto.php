<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Gasto extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gasto_model', 'gm');
        $this->load->model('Carrito_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Gastos';
    }

    public function index()
    {
        redirect('gasto/gasto_lista');
    }

    function gasto_lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText  = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }

            $data['searchText']  = $searchText;
            $data['per_page']    = 50;
            $data['page']        = 1;
            $data['total_count'] = $this->gm->gastoListingCount($searchText, $id_sucursal);
            $data['records']     = $this->gm->gastoListing($searchText, $id_sucursal, 50, 0);
            $data['stats']       = $this->gm->getGastoStats($id_sucursal);

            $this->global['pageTitle'] = 'Gastos';
            $this->loadViews("gasto/gasto_lista", $this->global, $data, NULL);
        }
    }

    function add()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Agregar gasto';
            $this->loadViews("gasto/add", $this->global, NULL, NULL);
        }
    }

    function addNewGasto()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('monto', 'Monto', 'trim|required|numeric');
            $this->form_validation->set_rules('fecha', 'fecha', 'trim|required|max_length[50]');

            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                $id_sucursal = $this->session->userdata('id_sucursal');
                $id_usuario  = $this->session->userdata('userId');
                $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $monto       = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha       = $this->security->xss_clean($this->input->post('fecha'));

                $gastoInfo = [
                    'fecha'       => $fecha,
                    'monto'       => $monto,
                    'descripcion' => $descripcion,
                    'id_sucursal' => $id_sucursal,
                ];

                $id_caja_actual = $this->cm->getIdCajaAbierta($id_sucursal, $id_usuario);
                if ($this->db->field_exists('id_caja', 'tbl_gasto') && $id_caja_actual !== null) {
                    $gastoInfo['id_caja'] = $id_caja_actual;
                }

                $result = $this->gm->addNewGasto($gastoInfo);

                if ($result > 0) {
                    if ($this->cm->hayCajasAbiertas($id_sucursal, $id_usuario) == 1) {
                        $this->cm->aumentarSaldoCajasAbiertas($monto * -1, $id_sucursal, null, $id_usuario);
                    }
                    $this->session->set_flashdata('success', 'Gasto registrado correctamente');
                } else {
                    $this->session->set_flashdata('error', 'Error al registrar el gasto');
                }

                redirect('gasto/gasto_lista');
            }
        }
    }

    function edit($gastoId = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if ($gastoId == null) {
                redirect('gasto/gasto_lista');
            }

            $data['gastoInfo'] = $this->gm->getGastoInfo($gastoId);
            $this->global['pageTitle'] = 'Editar gasto';
            $this->loadViews("gasto/edit", $this->global, $data, NULL);
        }
    }

    function editGasto()
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id_gasto = (int)$this->input->post('id_gasto');

            $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('monto', 'Monto', 'trim|required|numeric');
            $this->form_validation->set_rules('fecha', 'fecha', 'trim|required|max_length[50]');

            if ($this->form_validation->run() == FALSE) {
                $this->edit($id_gasto);
            } else {
                $descripcion    = $this->security->xss_clean($this->input->post('descripcion'));
                $monto          = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha          = $this->security->xss_clean($this->input->post('fecha'));
                $id_usuario     = $this->session->userdata('userId');

                $gastoOriginal  = $this->gm->getGastoInfo($id_gasto);
                $monto_anterior = isset($gastoOriginal->monto) ? (float)$gastoOriginal->monto : 0;
                $id_sucursal_g  = isset($gastoOriginal->id_sucursal) ? (int)$gastoOriginal->id_sucursal : 0;

                $gastoInfo = [
                    'descripcion' => $descripcion,
                    'monto'       => $monto,
                    'fecha'       => $fecha,
                ];

                $result = $this->gm->editGasto($gastoInfo, $id_gasto);

                if ($result == true) {
                    if ($id_sucursal_g > 0 && $this->cm->hayCajasAbiertas($id_sucursal_g, $id_usuario) == 1) {
                        $diferencia = $monto_anterior - $monto;
                        if ($diferencia != 0) {
                            $this->cm->aumentarSaldoCajasAbiertas($diferencia, $id_sucursal_g, null, $id_usuario);
                        }
                    }
                    $this->session->set_flashdata('success', 'Gasto actualizado correctamente');
                } else {
                    $this->session->set_flashdata('error', 'Error al actualizar el gasto');
                }

                redirect('gasto/gasto_lista');
            }
        }
    }

    function confirmar_eliminar_gasto($id)
    {
        if (!$this->hasDeleteAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso para eliminar gastos.');
            redirect('gasto/gasto_lista');
            return;
        }

        $id          = (int)$id;
        $gasto       = $this->gm->getGastoInfo($id);
        $monto       = isset($gasto->monto) ? (float)$gasto->monto : 0;
        $id_sucursal_g = isset($gasto->id_sucursal) ? (int)$gasto->id_sucursal : 0;
        $id_usuario  = $this->session->userdata('userId');

        $this->gm->eliminar_gasto($id);

        if ($id_sucursal_g > 0 && $monto > 0 && $this->cm->hayCajasAbiertas($id_sucursal_g, $id_usuario) == 1) {
            $this->cm->aumentarSaldoCajasAbiertas($monto, $id_sucursal_g, null, $id_usuario);
        }

        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('gasto/gasto_lista');
    }

    public function filterGastos()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = $this->security->xss_clean($this->input->post('searchText') ?? '');
        $page        = max(1, (int)$this->input->post('page'));
        $limit       = 50;
        $offset      = ($page - 1) * $limit;

        $total   = $this->gm->gastoListingCount($searchText, $id_sucursal);
        $data    = [
            'records' => $this->gm->gastoListing($searchText, $id_sucursal, $limit, $offset),
        ];

        $html = $this->load->view('gasto/table_partial', $data, true);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'html'  => $html,
            'total' => $total,
            'page'  => $page,
            'pages' => (int)ceil($total / $limit),
            'limit' => $limit,
        ]);
    }
}
