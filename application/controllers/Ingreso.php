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
        // Reutilizamos Carrito_model para el manejo de saldo de caja.
        $this->load->model('Carrito_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Ingresos';
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
            
            $this->global['pageTitle'] = 'Ingresos';
            
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
            $this->global['pageTitle'] = 'Agregar ingreso';

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
                $id_usuario  = $this->session->userdata('userId');
                $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $monto = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));

                $ingresoInfo = array('fecha'=>$fecha, 'monto'=>$monto, 'descripcion'=>$descripcion, 'id_sucursal'=>$id_sucursal);

                // Asociamos el ingreso a la caja abierta del cajero (multi-cajero).
                $id_caja_actual = $this->cm->getIdCajaAbierta($id_sucursal, $id_usuario);
                if ($this->db->field_exists('id_caja', 'tbl_ingreso') && $id_caja_actual !== null) {
                    $ingresoInfo['id_caja'] = $id_caja_actual;
                }

                $result = $this->im->addNewIngreso($ingresoInfo);

                if($result > 0) {
                    // Si hay caja abierta del cajero, sumamos el monto al saldo (asumido efectivo).
                    if ($this->cm->hayCajasAbiertas($id_sucursal, $id_usuario) == 1) {
                        $this->cm->aumentarSaldoCajasAbiertas($monto, $id_sucursal, null, $id_usuario);
                    }
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
      

            $this->global['pageTitle'] = 'Editar ingreso';
            
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
                $monto = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));
                $id_usuario  = $this->session->userdata('userId');

                // Capturamos los datos previos para ajustar la caja.
                $ingresoOriginal = $this->im->getIngresoInfo($id_ingreso);
                $monto_anterior = isset($ingresoOriginal->monto) ? (float)$ingresoOriginal->monto : 0;
                $id_sucursal_i = isset($ingresoOriginal->id_sucursal) ? (int)$ingresoOriginal->id_sucursal : 0;

                $ingresoInfo = array('descripcion'=>$descripcion, 'monto'=>$monto,  'fecha'=>$fecha, 'id_ingreso' => $id_ingreso);

                $result = $this->im->editIngreso($ingresoInfo, $id_ingreso);

                if($result == true)
                {
                    // Para ingreso, la diferencia neta a sumar es (monto_nuevo - monto_anterior).
                    // Se aplica a la caja del cajero actual.
                    if ($id_sucursal_i > 0 && $this->cm->hayCajasAbiertas($id_sucursal_i, $id_usuario) == 1) {
                        $diferencia = $monto - $monto_anterior;
                        if ($diferencia != 0) {
                            $this->cm->aumentarSaldoCajasAbiertas($diferencia, $id_sucursal_i, null, $id_usuario);
                        }
                    }
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
        // Antes de eliminar, leemos el ingreso para revertir su efecto en caja.
        $ingreso = $this->im->getIngresoInfo($id);
        $monto = isset($ingreso->monto) ? (float)$ingreso->monto : 0;
        $id_sucursal_i = isset($ingreso->id_sucursal) ? (int)$ingreso->id_sucursal : 0;
        $id_usuario  = $this->session->userdata('userId');

        $this->im->eliminar_ingreso($id);

        // Eliminar un ingreso resta el monto del saldo de la caja del cajero actual.
        if ($id_sucursal_i > 0 && $monto > 0 && $this->cm->hayCajasAbiertas($id_sucursal_i, $id_usuario) == 1) {
            $this->cm->aumentarSaldoCajasAbiertas($monto * -1, $id_sucursal_i, null, $id_usuario);
        }

        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('ingreso/ingreso_lista');
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
    
    $this->global['pageTitle'] = 'Ingresos';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('ingreso/table_partial', $data);
}








}

?>
