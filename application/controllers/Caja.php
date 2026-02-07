<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Caja extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Caja_model', 'xm');
        $this->isLoggedIn();
        $this->module = 'Caja';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('caja/add');
    }
    
    /**
     * This function is used to load the booking list
     */
    

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
            $this->global['pageTitle'] = 'Tusolutionweb : Abrir caja';

            $this->loadViews("caja/add", $this->global, NULL, NULL);
        }
    }
    function add_reparacion()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'Tusolutionweb : Abrir caja';

            $this->loadViews("caja/add_reparacion", $this->global, NULL, NULL);
        }
    }
    /**
     * This function is used to add new user to the system
     */
    function addNewCaja()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('saldo','saldo','trim|required|max_length[200]');
            
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                        $id_sucursal = $this->session->userdata('id_sucursal');
                $saldo = $this->security->xss_clean($this->input->post('saldo'));
              
                
                $cajaInfo = array('fecha_apertura'=>date('Y-m-d'),'fecha_cierre'=>"",'saldo'=>$saldo,'estado'=>"abierto",'id_sucursal'=>$id_sucursal);
                
                $result = $this->xm->addNewCaja($cajaInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nueva caja abierta satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear abrir caja');
                }
                
                redirect('carrito/ventas_lista');
            }
        }
    }






    function addNewCajaReparacion()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('saldo','saldo','trim|required|max_length[200]');
            
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $id_sucursal = $this->session->userdata('id_sucursal');
                $saldo = $this->security->xss_clean($this->input->post('saldo'));
              
                
                $cajaInfo = array('fecha_apertura'=>date('Y-m-d'),'fecha_cierre'=>"",'saldo'=>$saldo,'estado'=>"abierto",'id_sucursal'=>$id_sucursal);
                
                $result = $this->xm->addNewCaja($cajaInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nueva caja abierta satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear abrir caja');
                }
                
                redirect('reparacion/reparacion_lista_todas');
            }
        }
    }
//cerrarCaja
    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */

    
    
    /**
     * This function is used to edit the user information
     */
    function cerrarCaja()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
     
             // Itera sobre los productos e imprime cada valor
        
     //actualizar caja
     $validacioncaja = $this->xm->cerrarCaja();
     if($validacioncaja == true) {
         $this->session->set_flashdata('success', 'caja cerrrada');
     } else {
         $this->session->set_flashdata('error', 'error cerrando caja');
     }

        }
    }

    function cerrarCajaReparacion()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
     
             // Itera sobre los productos e imprime cada valor
        
     //actualizar caja
     $validacioncaja = $this->xm->cerrarCaja();
     if($validacioncaja == true) {
         $this->session->set_flashdata('success', 'caja cerrrada');
     } else {
         $this->session->set_flashdata('error', 'error cerrando caja');
     }

        }
    }





    


}

?>