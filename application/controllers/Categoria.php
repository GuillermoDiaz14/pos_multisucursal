<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Categoria extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categoria_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Categoria';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('categoria/categoria_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function categoria_lista()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->cm->categoriaListingCount($searchText);

			$returns = $this->paginationCompress ( "categoria_lista/", $count, $count );
            
            $data['records'] = $this->cm->categoriaListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' Categoria';
            
            $this->loadViews("categoria/categoria_lista", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = ' Agregar nueva categoria';

            $this->loadViews("categoria/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewCategoria()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre_categoria','nombre','trim|required|max_length[200]');
     
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $nombre_categoria = $this->security->xss_clean($this->input->post('nombre_categoria'));
              
                
                $categoriaInfo = array('nombre_categoria'=>$nombre_categoria);
                
                $result = $this->cm->addNewCategoria($categoriaInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nueva categoria agregado satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nueva categoria');
                }
                
                redirect('categoria/categoria_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($id_categoria = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($id_categoria == null)
            {
                redirect('categoria/categoria_lista');
            }
            
            $data['categoriaInfo'] = $this->cm->getCategoriaInfo($id_categoria);
      

            $this->global['pageTitle'] = ' Editar categoria';
            
            $this->loadViews("categoria/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editCategoria()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_categoria = $this->input->post('id_categoria');
            
            $this->form_validation->set_rules('nombre_categoria','nombre','trim|required|max_length[200]');
     

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_categoria);
            }
            else
            {
                $nombre_categoria = $this->security->xss_clean($this->input->post('nombre_categoria'));
           
            
             
                
                $categoriaInfo = array('nombre_categoria'=>$nombre_categoria, 'id_categoria' => $id_categoria);
                
                $result = $this->cm->editCategoria($categoriaInfo, $id_categoria);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente categoria');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion categoria fallo');
                }
                
                redirect('categoria/categoria_lista');
            }
        }
    }





     function confirmar_eliminar_categoria($id) {
        $this->cm->eliminar_categoria($id);
        $this->session->set_flashdata('success', 'eliminado correctamente');  
        redirect('categoria/categoria_lista'); // Redirige a la página de lista de productos
    }


    public function filterCategorias()
{
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->cm->categoriaListingCount($searchText);

    $returns = $this->paginationCompress ( "categoria_lista/", $count, $count );
    
    $data['records'] = $this->cm->categoriaListing($searchText, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' Categoria';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('categoria/table_partial', $data);
}
}

?>