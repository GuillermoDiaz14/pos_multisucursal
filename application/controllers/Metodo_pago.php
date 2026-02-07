<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Metodo_pago extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Metodo_pago_model', 'mpm');
        $this->isLoggedIn();
        $this->module = 'Metodo_pago';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('metodo_pago/metodo_pago_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function metodo_pago_lista()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
               $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->mpm->metodo_pagoListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "metodo_pago_lista/", $count, $count );
            
            $data['records'] = $this->mpm->metodo_pagoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Tusolutionweb : metodo_pago';
            
            $this->loadViews("metodo_pago/metodo_pago_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'Tusolutionweb : Agregar nuevo metodo_pago';

            $this->loadViews("metodo_pago/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewMetodo_pago()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre_metodo_pago','nombre metodo pago','trim|required|max_length[200]');
       
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                 $id_sucursal = $this->session->userdata('id_sucursal');
                $nombre_metodo_pago = $this->security->xss_clean($this->input->post('nombre_metodo_pago'));
           
                
                $metodo_pagoInfo = array('nombre_metodo_pago'=>$nombre_metodo_pago,'id_sucursal'=>$id_sucursal);
                
                $result = $this->mpm->addNewmetodo_pago($metodo_pagoInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo metodo pago agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo metodo pago');
                }
                
                redirect('metodo_pago/metodo_pago_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($metodo_pagoId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($metodo_pagoId == null)
            {
                redirect('metodo_pago/metodo_pago_lista');
            }
            
            $data['metodo_pagoInfo'] = $this->mpm->getmetodo_pagoInfo($metodo_pagoId);
      

            $this->global['pageTitle'] = 'Tusolutionweb : Editar metodo_pago';
            
            $this->loadViews("metodo_pago/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editMetodo_pago()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_metodo_pago = $this->input->post('id_metodo_pago');
            
            $this->form_validation->set_rules('nombre_metodo_pago','nombre metodo pago','trim|required|max_length[200]');
        

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_metodo_pago);
            }
            else
            {
                $nombre_metodo_pago = $this->security->xss_clean($this->input->post('nombre_metodo_pago'));
       
            
             
                
                $metodo_pagoInfo = array('nombre_metodo_pago'=>$nombre_metodo_pago);
                
                $result = $this->mpm->editmetodo_pago($metodo_pagoInfo, $id_metodo_pago);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente metodo pago');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion metodo pago fallo');
                }
                
                redirect('metodo_pago/metodo_pago_lista');
            }
        }
    }





     function confirmar_eliminar_metodo_pago($id) {
        $this->mpm->eliminar_metodo_pago($id);
                $this->session->set_flashdata('success', 'Eliminado correctamente');
        
        redirect('metodo_pago/metodo_pago_lista'); // Redirige a la página de lista de productos
    }


    public function filtermetodo_pagos()
{
     $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->mpm->metodo_pagoListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "metodo_pago_lista/", $count, $count );
    
    $data['records'] = $this->mpm->metodo_pagoListing($searchText,$id_sucursal, $returns["page"], $returns["sempment"]);
    
    $this->global['pageTitle'] = 'Tusolutionweb : metodo_pago';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('metodo_pago/table_partial', $data);
}
}

?>