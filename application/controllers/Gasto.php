<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Gasto extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gasto_model', 'gm');
        $this->isLoggedIn();
        $this->module = 'Gasto';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('gasto/gasto_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function gasto_lista()
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
            $count = $this->gm->gastoListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "gasto_lista/", $count, $count );
            
            $data['records'] = $this->gm->gastoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Tusolutionweb : Gasto';
            
            $this->loadViews("gasto/gasto_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'Tusolutionweb : Agregar nuevo gasto';

            $this->loadViews("gasto/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewGasto()
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
                
                $gastoInfo = array('fecha'=>$fecha, 'monto'=>$monto, 'descripcion'=>$descripcion, 'id_sucursal'=>$id_sucursal);
                
                $result = $this->gm->addNewGasto($gastoInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo gasto agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo gasto');
                }
                
                redirect('gasto/gasto_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($gastoId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($gastoId == null)
            {
                redirect('gasto/gasto_lista');
            }
            
            $data['gastoInfo'] = $this->gm->getGastoInfo($gastoId);
      

            $this->global['pageTitle'] = 'Tusolutionweb : Editar gasto';
            
            $this->loadViews("gasto/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editGasto()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_gasto = $this->input->post('id_gasto');
            
            $this->form_validation->set_rules('descripcion','descripcion','trim|required|max_length[200]');
            $this->form_validation->set_rules('monto', 'Monto', 'trim|required|numeric');
            $this->form_validation->set_rules('fecha','fecha','trim|required|max_length[50]');

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_gasto);
            }
            else
            {
                $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $monto = $this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));
            
             
                
                $gastoInfo = array('descripcion'=>$descripcion, 'monto'=>$monto,  'fecha'=>$fecha, 'id_gasto' => $id_gasto);
                
                $result = $this->gm->editGasto($gastoInfo, $id_gasto);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente gasto');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion gasto fallo');
                }
                
                redirect('gasto/gasto_lista');
            }
        }
    }





     function confirmar_eliminar_gasto($id) {
        $this->gm->eliminar_gasto($id);
        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('gasto/gasto_lista'); // Redirige a la página de lista de productos
    }


    public function filterGastos()
{
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
      $id_sucursal = $this->session->userdata('id_sucursal');
    $count = $this->gm->gastoListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "gasto_lista/", $count, $count );
    
    $data['records'] = $this->gm->gastoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : Gasto';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('gasto/table_partial', $data);
}
}

?>