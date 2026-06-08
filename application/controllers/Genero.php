<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Genero extends BaseController
{
    private $generos_predefinidos = array('NA', 'Hombre', 'Mujer', 'Unisex', 'Niño');

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('Genero_model', 'gm');
        $this->isLoggedIn();
        $this->module = 'Productos';
    }

    public function index()
    {
        redirect('genero/lista');
    }

    private function tableReady()
    {
        return $this->db->table_exists('tbl_genero');
    }

    private function fallbackRows()
    {
        $rows = array();
        $i = 1;
        foreach ($this->generos_predefinidos as $genero) {
            $rows[] = (object) array(
                'id_genero' => $i++,
                'nombre_genero' => $genero,
                'descripcion' => $genero === 'NA' ? 'Sin género especificado' : NULL,
                'activa' => 1,
            );
        }
        return $rows;
    }

    private function getRows($includeInactive = true)
    {
        if (!$this->tableReady()) {
            return $this->fallbackRows();
        }

        return $includeInactive ? $this->gm->getAllIncludingInactive() : $this->gm->getAll();
    }

    public function lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
            return;
        }

        $data['generos'] = $this->getRows(true);
        $data['table_ready'] = $this->tableReady();

        $this->global['pageTitle'] = 'Gestión de Géneros';
        $this->loadViews("genero/lista", $this->global, $data, NULL);
    }

    public function add()
    {
        if(!$this->hasCreateAccess()) {
            $this->loadThis();
            return;
        }

        if (!$this->tableReady()) {
            $this->session->set_flashdata('error', 'Debes ejecutar la migración de géneros para habilitar este catálogo.');
            redirect('genero/lista');
            return;
        }

        $this->global['pageTitle'] = 'Agregar género';
        $this->loadViews("genero/add", $this->global, NULL, NULL);
    }

    public function addNewGenero()
    {
        if(!$this->hasCreateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('genero/lista');
            return;
        }

        if (!$this->tableReady()) {
            $this->session->set_flashdata('error', 'Debes ejecutar la migración de géneros para habilitar este catálogo.');
            redirect('genero/lista');
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_genero', 'Nombre', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|max_length[255]');

        if($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $data = array(
            'nombre_genero' => $this->security->xss_clean($this->input->post('nombre_genero')),
            'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
            'activa' => 1,
        );

        $result = $this->gm->insert($data);
        $this->session->set_flashdata($result > 0 ? 'success' : 'error', $result > 0 ? 'Género creado exitosamente' : 'Error al crear género');
        redirect('genero/lista');
    }

    public function edit($id_genero = NULL)
    {
        if(!$this->hasUpdateAccess()) {
            $this->loadThis();
            return;
        }

        if (!$this->tableReady()) {
            $this->session->set_flashdata('error', 'Debes ejecutar la migración de géneros para habilitar este catálogo.');
            redirect('genero/lista');
            return;
        }

        if($id_genero == null) {
            redirect('genero/lista');
            return;
        }

        $data['generoInfo'] = $this->gm->getById($id_genero);
        if (empty($data['generoInfo'])) {
            $this->session->set_flashdata('error', 'Género no encontrado');
            redirect('genero/lista');
            return;
        }

        $this->global['pageTitle'] = 'Editar género';
        $this->loadViews("genero/edit", $this->global, $data, NULL);
    }

    public function editGenero()
    {
        if(!$this->hasUpdateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('genero/lista');
            return;
        }

        if (!$this->tableReady()) {
            $this->session->set_flashdata('error', 'Debes ejecutar la migración de géneros para habilitar este catálogo.');
            redirect('genero/lista');
            return;
        }

        $id_genero = (int)$this->input->post('id_genero');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_genero', 'Nombre', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|max_length[255]');

        if($this->form_validation->run() == FALSE) {
            $this->edit($id_genero);
            return;
        }

        $data = array(
            'nombre_genero' => $this->security->xss_clean($this->input->post('nombre_genero')),
            'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
            'activa' => (int)$this->input->post('activa'),
        );

        $this->gm->update($id_genero, $data);
        $this->session->set_flashdata('success', 'Género actualizado exitosamente');
        redirect('genero/lista');
    }

    public function delete($id_genero)
    {
        if(!$this->hasDeleteAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('genero/lista');
            return;
        }

        if (!$this->tableReady()) {
            $this->session->set_flashdata('error', 'Debes ejecutar la migración de géneros para habilitar este catálogo.');
            redirect('genero/lista');
            return;
        }

        $this->gm->deactivate($id_genero);
        $this->session->set_flashdata('success', 'Género eliminado exitosamente');
        redirect('genero/lista');
    }

    public function get_all_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            $this->output->set_status_header(403)->set_output('[]');
            return;
        }

        $rows = $this->getRows(false);
        $generos = array();
        foreach ($rows as $row) {
            $generos[] = isset($row->nombre_genero) ? $row->nombre_genero : $row;
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($generos, JSON_UNESCAPED_UNICODE));
    }
}
?>
