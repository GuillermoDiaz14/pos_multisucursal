<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Subcategoria extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('Subcategoria_model', 'scm');
        $this->load->model('Categoria_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Productos';
    }

    public function index()
    {
        redirect('subcategoria/lista');
    }
    
    public function lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = (int)$this->session->userdata('id_sucursal');
            $id_categoria = (int)$this->input->get('id_categoria');
            
            $data['categorias'] = $this->cm->get_categorias($id_sucursal);
            $data['subcategorias'] = $this->scm->getAll($id_categoria, $id_sucursal);
            $data['id_categoria_filtro'] = $id_categoria;

            $this->global['pageTitle'] = 'Subcategorías';
            $this->loadViews("subcategoria/lista", $this->global, $data, NULL);
        }
    }

    public function add()
    {
        if(!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = (int)$this->session->userdata('id_sucursal');
            $data['categorias'] = $this->cm->get_categorias($id_sucursal);
            $this->global['pageTitle'] = 'Agregar subcategoría';
            $this->loadViews("subcategoria/add", $this->global, $data, NULL);
        }
    }

    public function addNewSubcategoria()
    {
        if(!$this->hasCreateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('subcategoria/lista');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_subcategoria', 'Nombre', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|numeric');

        if($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $id_sucursal = (int)$this->session->userdata('id_sucursal');
            $data = array(
                'id_categoria' => (int)$this->input->post('id_categoria'),
                'id_sucursal' => $id_sucursal,
                'nombre_subcategoria' => $this->security->xss_clean($this->input->post('nombre_subcategoria')),
                'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
                'activa' => 1,
            );

            $result = $this->scm->insert($data);
            
            if($result > 0) {
                $this->session->set_flashdata('success', 'Subcategoría creada exitosamente');
            } else {
                $this->session->set_flashdata('error', 'Error al crear subcategoría');
            }
            
            $id_cat = $data['id_categoria'];
            redirect('subcategoria/lista?id_categoria='.$id_cat);
        }
    }

    public function edit($id_subcategoria = NULL)
    {
        if(!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if($id_subcategoria == null) {
                redirect('subcategoria/lista');
            }
            
            $id_sucursal = (int)$this->session->userdata('id_sucursal');
            $data['subcategoriaInfo'] = $this->scm->getById($id_subcategoria);
            $data['categorias'] = $this->cm->get_categorias($id_sucursal);

            $this->global['pageTitle'] = 'Editar subcategoría';
            $this->loadViews("subcategoria/edit", $this->global, $data, NULL);
        }
    }

    public function editSubcategoria()
    {
        if(!$this->hasUpdateAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
            redirect('subcategoria/lista');
        }

        $id_subcategoria = (int)$this->input->post('id_subcategoria');
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre_subcategoria', 'Nombre', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|numeric');

        if($this->form_validation->run() == FALSE) {
            $this->edit($id_subcategoria);
        } else {
            $data = array(
                'id_categoria' => (int)$this->input->post('id_categoria'),
                'nombre_subcategoria' => $this->security->xss_clean($this->input->post('nombre_subcategoria')),
                'descripcion' => $this->security->xss_clean($this->input->post('descripcion')),
            );

            $this->scm->update($id_subcategoria, $data);
            $this->session->set_flashdata('success', 'Subcategoría actualizada exitosamente');
            redirect('subcategoria/lista?id_categoria='.$data['id_categoria']);
        }
    }

    public function delete($id_subcategoria)
    {
        if(!$this->hasDeleteAccess()) {
            $this->session->set_flashdata('error', 'No tienes permiso');
        } else {
            $subcategoriaInfo = $this->scm->getById($id_subcategoria);
            $this->scm->deactivate($id_subcategoria);
            $this->session->set_flashdata('success', 'Subcategoría eliminada exitosamente');
            redirect('subcategoria/lista?id_categoria='.$subcategoriaInfo->id_categoria);
        }
    }

    /**
     * AJAX: Create subcategory inline
     */
    public function crear_ajax()
    {
        if (!$this->input->is_ajax_request() || !$this->hasCreateAccess()) {
            $this->output->set_status_header(403)->set_output('{}');
            return;
        }

        $id_categoria = (int)$this->input->post('id_categoria');
        $nombre = $this->security->xss_clean($this->input->post('nombre'));

        if (!$id_categoria || empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        $id_sucursal = (int)$this->session->userdata('id_sucursal');
        $data = array(
            'id_categoria' => $id_categoria,
            'id_sucursal' => $id_sucursal,
            'nombre_subcategoria' => $nombre,
            'activa' => 1,
        );

        $id = $this->scm->insert($data);
        
        if ($id > 0) {
            echo json_encode([
                'success' => true,
                'id_subcategoria' => $id,
                'nombre_subcategoria' => $nombre,
                'message' => 'Subcategoría creada'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear']);
        }
    }
}
?>
