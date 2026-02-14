<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Configuracion extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion_model', 'conf');
        $this->isLoggedIn();
        $this->module = 'Configuracion';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
          
            
            $data['configuracionInfo'] = $this->conf->getconfiguracionInfo();
      

            $this->global['pageTitle'] = ' Editar configuracion';
            
            $this->loadViews("configuracion/edit", $this->global, $data, NULL);
        }
    }
    

   
    

    
    
    /**
     * This function is used to edit the user information
     */
    function editconfiguracion()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_configuracion = $this->input->post('id_configuracion');
            
            $this->form_validation->set_rules('nombre_empresa','descripcion','trim|required|max_length[200]');
            $this->form_validation->set_rules('telefono', 'telefono','trim|required|max_length[200]');
            $this->form_validation->set_rules('impuesto','impuesto','trim|required|max_length[50]');
            $this->form_validation->set_rules('simbolo_moneda','simbolo','trim|required|max_length[50]');

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_configuracion);
            }
            else
            {
                $nombre_empresa = $this->security->xss_clean($this->input->post('nombre_empresa'));
                $telefono = $this->security->xss_clean($this->input->post('telefono'));
                $impuesto = $this->security->xss_clean($this->input->post('impuesto'));
                $simbolo_moneda = $this->security->xss_clean($this->input->post('simbolo_moneda'));
            
             
                
                $configuracionInfo = array('nombre_empresa'=>$nombre_empresa, 'telefono'=>$telefono,  'impuesto'=>$impuesto,  'simbolo_moneda'=>$simbolo_moneda);
                
                $result = $this->conf->editconfiguracion($configuracionInfo);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente configuracion');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion configuracion fallo');
                }
                
                redirect('configuracion');
            }
        }
    }





}

?>