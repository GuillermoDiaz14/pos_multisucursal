<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Sucursal extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sucursal_model', 'scm');
        $this->isLoggedIn();
        $this->module = 'Sucursal';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('sucursal/sucursal_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function sucursal_lista()
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
            
            $count = $this->scm->sucursalListingCount($searchText);

			$returns = $this->paginationCompress ( "sucursal_lista/", $count, $count );
            
            $data['records'] = $this->scm->sucursalListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Tusolutionweb : sucursal';
            
            $this->loadViews("sucursal/sucursal_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'Tusolutionweb : Agregar nuevo sucursal';

            $this->loadViews("sucursal/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewsucursal()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre_sucursal','nombre sucursal','trim|required|max_length[200]');
            $this->form_validation->set_rules('impuesto','impuesto','trim|required|max_length[50]');

            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[200]');
            $this->form_validation->set_rules('ciudad','ciudad','trim|required|max_length[50]');
            $this->form_validation->set_rules('simbolo_moneda','simbolo moneda','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
 
                $nombre_sucursal = $this->security->xss_clean($this->input->post('nombre_sucursal'));
                $impuesto = $this->security->xss_clean($this->input->post('impuesto'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $ciudad = $this->security->xss_clean($this->input->post('ciudad'));
                $simbolo_moneda = $this->security->xss_clean($this->input->post('simbolo_moneda'));
                
                $sucursalInfo = array('nombre_sucursal'=>$nombre_sucursal, 'impuesto'=>$impuesto, 'celular'=>$celular, 'direccion'=>$direccion, 'ciudad'=>$ciudad, 'simbolo_moneda'=>$simbolo_moneda);
                
                $result = $this->scm->addNewsucursal($sucursalInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo sucursal agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo sucursal');
                }
                


              


                

                redirect('sucursal/sucursal_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($sucursalId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($sucursalId == null)
            {
                redirect('sucursal/sucursal_lista');
            }
            
            $data['sucursalInfo'] = $this->scm->getsucursalInfo($sucursalId);
      

            $this->global['pageTitle'] = 'Tusolutionweb : Editar sucursal';
            
            $this->loadViews("sucursal/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editsucursal()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_sucursal = $this->input->post('id_sucursal');
            
            $this->form_validation->set_rules('nombre_sucursal','nombre sucursal','trim|required|max_length[200]');
            $this->form_validation->set_rules('impuesto','impuesto','trim|required|max_length[50]');

            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[200]');
            $this->form_validation->set_rules('ciudad','ciudad','trim|required|max_length[50]');
            $this->form_validation->set_rules('simbolo_moneda','simbolo moneda','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_sucursal);
            }
            else
            {
                $nombre_sucursal = $this->security->xss_clean($this->input->post('nombre_sucursal'));
                $impuesto = $this->security->xss_clean($this->input->post('impuesto'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $ciudad = $this->security->xss_clean($this->input->post('ciudad'));
            
                $simbolo_moneda = $this->security->xss_clean($this->input->post('simbolo_moneda'));
                
                $sucursalInfo = array('nombre_sucursal'=>$nombre_sucursal, 'impuesto'=>$impuesto,  'celular'=>$celular, 'direccion' => $direccion, 'ciudad' => $ciudad, 'correo' => $correo, 'simbolo_moneda' => $simbolo_moneda);
                
                $result = $this->scm->editsucursal($sucursalInfo, $id_sucursal);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente sucursal');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion sucursal fallo');
                }
                
                redirect('sucursal/sucursal_lista');
            }
        }
    }





     function confirmar_eliminar_sucursal($id) {
      //  $id_sucursal = $this->session->userdata('id_sucursal');

        $this->scm->eliminar_producto_stock($id);
        $this->scm->eliminar_sucursal($id);
                    $this->session->set_flashdata('success', 'Eliminado correctamente');

        redirect('sucursal/sucursal_lista'); // Redirige a la página de lista de productos
    }


    public function filtersucursal()
{
 
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->scm->sucursalListingCount($searchText,);

    $returns = $this->paginationCompress ( "sucursal_lista/", $count, $count );
    
    $data['records'] = $this->scm->sucursalListing($searchText, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : sucursal';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('sucursal/table_partial', $data);
}















}

?>