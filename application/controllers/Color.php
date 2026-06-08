<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Color extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('Color_model', 'colm');
        $this->isLoggedIn();
        $this->module = 'Productos';
    }

    public function index()
    {
        redirect('color/lista');
    }
    
    public function lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $data['colores'] = $this->colm->getAllIncludingInactive();

            $this->global['pageTitle'] = 'Gestión de Colores';
            $this->loadViews("color/lista", $this->global, $data, NULL);
        }
    }

    public function add()
    {
        if(!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Agregar color';
            $this->loadViews("color/add", $this->global, NULL, NULL);
        }
    }

    public function addNewColor()
    {
        if(!$this->hasCreateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('color/lista');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_color', 'Nombre del color', 'trim|required|max_length[50]');

        if($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = array(
                'nombre_color' => $this->security->xss_clean($this->input->post('nombre_color')),
                'codigo_hex' => NULL,
                'activo' => 1,
            );

            $result = $this->colm->insert($data);
            
            if($result > 0) {
                $this->session->set_flashdata('success', 'Color creado exitosamente');
            } else {
                $this->session->set_flashdata('error', 'Error al crear color');
            }
            
            redirect('color/lista');
        }
    }

    public function edit($id_color = NULL)
    {
        if(!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if($id_color == null) {
                redirect('color/lista');
            }
            
            $data['colorInfo'] = $this->colm->getById($id_color);

            $this->global['pageTitle'] = 'Editar color';
            $this->loadViews("color/edit", $this->global, $data, NULL);
        }
    }

    public function editColor()
    {
        if(!$this->hasUpdateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('color/lista');
        }

        $id_color = (int)$this->input->post('id_color');
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_color', 'Nombre del color', 'trim|required|max_length[50]');

        if($this->form_validation->run() == FALSE) {
            $this->edit($id_color);
        } else {
            $data = array(
                'nombre_color' => $this->security->xss_clean($this->input->post('nombre_color')),
            );

            $this->colm->update($id_color, $data);
            $this->session->set_flashdata('success', 'Color actualizado exitosamente');
            redirect('color/lista');
        }
    }

    public function delete($id_color)
    {
        if(!$this->hasDeleteAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
        } else {
            $this->colm->deactivate($id_color);
            $this->session->set_flashdata('success', 'Color eliminado exitosamente');
        }
        redirect('color/lista');
    }

    /**
     * AJAX: Create color inline
     */
    public function crear_ajax()
    {
        if (!$this->input->is_ajax_request() || !$this->hasCreateAccess()) {
            $this->output->set_status_header(403)->set_output('{}');
            return;
        }

        $nombre = $this->security->xss_clean($this->input->post('nombre'));

        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'Nombre requerido']);
            return;
        }

        $data = array(
            'nombre_color' => $nombre,
            'codigo_hex' => NULL,
            'activo' => 1,
        );

        $id = $this->colm->insert($data);
        
        if ($id > 0) {
            echo json_encode([
                'success' => true,
                'id_color' => $id,
                'nombre_color' => $nombre,
                'message' => 'Color creado'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear']);
        }
    }
}
?>
