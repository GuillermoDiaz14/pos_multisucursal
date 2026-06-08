<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Temporada extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('Temporada_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Productos';
    }

    public function index()
    {
        redirect('temporada/lista');
    }
    
    public function lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $data['temporadas'] = $this->tm->getAllIncludingInactive();

            $this->global['pageTitle'] = 'Gestión de Temporadas';
            $this->loadViews("temporada/lista", $this->global, $data, NULL);
        }
    }

    public function add()
    {
        if(!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Agregar temporada';
            $this->loadViews("temporada/add", $this->global, NULL, NULL);
        }
    }

    public function addNewTemporada()
    {
        if(!$this->hasCreateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('temporada/lista');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_temporada', 'Nombre', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|max_length[255]');

        if($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = array(
                'nombre_temporada' => $this->security->xss_clean($this->input->post('nombre_temporada')),
                'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
                'activa' => (int)$this->input->post('activa'),
            );

            $result = $this->tm->insert($data);
            
            if($result > 0) {
                $this->session->set_flashdata('success', 'Temporada creada exitosamente');
            } else {
                $this->session->set_flashdata('error', 'Error al crear temporada');
            }
            
            redirect('temporada/lista');
        }
    }

    public function edit($id_temporada = NULL)
    {
        if(!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if($id_temporada == null) {
                redirect('temporada/lista');
            }
            
            $data['temporadaInfo'] = $this->tm->getById($id_temporada);

            $this->global['pageTitle'] = 'Editar temporada';
            $this->loadViews("temporada/edit", $this->global, $data, NULL);
        }
    }

    public function editTemporada()
    {
        if(!$this->hasUpdateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('temporada/lista');
        }

        $id_temporada = (int)$this->input->post('id_temporada');
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_temporada', 'Nombre', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|max_length[255]');

        if($this->form_validation->run() == FALSE) {
            $this->edit($id_temporada);
        } else {
            $data = array(
                'nombre_temporada' => $this->security->xss_clean($this->input->post('nombre_temporada')),
                'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
                'activa' => (int)$this->input->post('activa'),
            );

            $this->tm->update($id_temporada, $data);
            $this->session->set_flashdata('success', 'Temporada actualizada exitosamente');
            redirect('temporada/lista');
        }
    }

    public function delete($id_temporada)
    {
        if(!$this->hasDeleteAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
        } else {
            $this->tm->deactivate($id_temporada);
            $this->session->set_flashdata('success', 'Temporada eliminada exitosamente');
        }
        redirect('temporada/lista');
    }

    /**
     * AJAX: Create temporada inline
     */
    public function crear_ajax()
    {
        if (!$this->input->is_ajax_request() || !$this->hasCreateAccess()) {
            $this->output->set_status_header(403)->set_output('{}');
            return;
        }

        $nombre = $this->security->xss_clean($this->input->post('nombre'));
        $descripcion = $this->security->xss_clean($this->input->post('descripcion'));

        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'Nombre requerido']);
            return;
        }

        $data = array(
            'nombre_temporada' => $nombre,
            'descripcion' => $descripcion,
            'activa' => 1,
        );

        $id = $this->tm->insert($data);
        
        if ($id > 0) {
            echo json_encode([
                'success' => true,
                'id_temporada' => $id,
                'nombre_temporada' => $nombre,
                'message' => 'Temporada creada'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear']);
        }
    }
}
?>
