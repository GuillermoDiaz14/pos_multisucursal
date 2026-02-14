<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Ingreso extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ingreso_model', 'im');
        $this->isLoggedIn();
        $this->module = 'Ingreso';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('ingreso/ingreso_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function ingreso_lista()
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
               $id_sucursal = $this->session->userdata('id_sucursal');
            $count = $this->im->ingresoListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "ingreso_lista/", $count, $count );
            
            $data['records'] = $this->im->ingresoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' Ingreso';
            
            $this->loadViews("ingreso/ingreso_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = ' Agregar nuevo ingreso';

            $this->loadViews("ingreso/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewIngreso()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('descripcion','descripcion','trim|required|max_length[200]');
            $this->form_validation->set_rules('monto', 'Monto', 'trim|required|numeric');
            $this->form_validation->set_rules('fecha','fecha','trim|required|max_length[50]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                 $id_sucursal = $this->session->userdata('id_sucursal');
                $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $monto = $this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));
                
                $ingresoInfo = array('fecha'=>$fecha, 'monto'=>$monto, 'descripcion'=>$descripcion, 'id_sucursal'=>$id_sucursal);
                
                $result = $this->im->addNewIngreso($ingresoInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo ingreso agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo ingreso');
                }
                
                redirect('ingreso/ingreso_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($id_ingreso = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($id_ingreso == null)
            {
                redirect('ingreso/ingreso_lista');
            }
            
            $data['ingresoInfo'] = $this->im->getIngresoInfo($id_ingreso);
      

            $this->global['pageTitle'] = ' Editar ingreso';
            
            $this->loadViews("ingreso/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editIngreso()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_ingreso = $this->input->post('id_ingreso');
            
            $this->form_validation->set_rules('descripcion','descripcion','trim|required|max_length[200]');
            $this->form_validation->set_rules('monto', 'Monto', 'trim|required|numeric');
            $this->form_validation->set_rules('fecha','fecha','trim|required|max_length[50]');

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_ingreso);
            }
            else
            {
                $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $monto = $this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));
             
                
                $ingresoInfo = array('descripcion'=>$descripcion, 'monto'=>$monto,  'fecha'=>$fecha, 'id_ingreso' => $id_ingreso);
                
                $result = $this->im->editIngreso($ingresoInfo, $id_ingreso);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente ingreso');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion empleado ingreso');
                }
                
                redirect('ingreso/ingreso_lista');
            }
        }
    }





     function confirmar_eliminar_ingreso($id) {
        $this->im->eliminar_ingreso($id);
                $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('ingreso/ingreso_lista'); // Redirige a la página de lista de productos
    }


    public function filterIngresos()
{
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    $id_sucursal = $this->session->userdata('id_sucursal');

    $count = $this->im->ingresoListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "ingreso_lista/", $count, $count );
    
    $data['records'] = $this->im->ingresoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' Ingreso';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('ingreso/table_partial', $data);
}








}

?>