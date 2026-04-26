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
        // Cargamos Carrito_model para reutilizar la lógica de saldo de caja
        // (aumentarSaldoCajasAbiertas / hayCajasAbiertas).
        $this->load->model('Carrito_model', 'cm');
        $this->isLoggedIn();
        $this->module = 'Gastos';
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
            
            $this->global['pageTitle'] = 'Gastos';
            
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
            $this->global['pageTitle'] = 'Agregar gasto';

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
                $monto = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));

                $gastoInfo = array('fecha'=>$fecha, 'monto'=>$monto, 'descripcion'=>$descripcion, 'id_sucursal'=>$id_sucursal);

                $result = $this->gm->addNewGasto($gastoInfo);

                if($result > 0) {
                    // Si hay caja abierta en la sucursal, descontamos el monto del saldo.
                    // Asumimos efectivo (null = legacy): un gasto típicamente sale de la caja.
                    if ($this->cm->hayCajasAbiertas($id_sucursal) == 1) {
                        $this->cm->aumentarSaldoCajasAbiertas($monto * -1, $id_sucursal);
                    }
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
      

            $this->global['pageTitle'] = 'Editar gasto';
            
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
                $monto = (float)$this->security->xss_clean($this->input->post('monto'));
                $fecha = $this->security->xss_clean($this->input->post('fecha'));

                // Capturamos el monto y la sucursal previos para ajustar la caja correctamente.
                $gastoOriginal = $this->gm->getGastoInfo($id_gasto);
                $monto_anterior = isset($gastoOriginal->monto) ? (float)$gastoOriginal->monto : 0;
                $id_sucursal_g = isset($gastoOriginal->id_sucursal) ? (int)$gastoOriginal->id_sucursal : 0;

                $gastoInfo = array('descripcion'=>$descripcion, 'monto'=>$monto,  'fecha'=>$fecha, 'id_gasto' => $id_gasto);

                $result = $this->gm->editGasto($gastoInfo, $id_gasto);

                if($result == true)
                {
                    // Ajuste neto en caja: revertir gasto anterior (sumar) y aplicar el nuevo (restar).
                    // Equivale a sumar la diferencia (monto_anterior - monto_nuevo).
                    if ($id_sucursal_g > 0 && $this->cm->hayCajasAbiertas($id_sucursal_g) == 1) {
                        $diferencia = $monto_anterior - $monto;
                        if ($diferencia != 0) {
                            $this->cm->aumentarSaldoCajasAbiertas($diferencia, $id_sucursal_g);
                        }
                    }
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
        // Antes de eliminar, leemos el gasto para revertir su efecto en la caja.
        $gasto = $this->gm->getGastoInfo($id);
        $monto = isset($gasto->monto) ? (float)$gasto->monto : 0;
        $id_sucursal_g = isset($gasto->id_sucursal) ? (int)$gasto->id_sucursal : 0;

        $this->gm->eliminar_gasto($id);

        // Si hay caja abierta en la sucursal del gasto, devolvemos el monto.
        if ($id_sucursal_g > 0 && $monto > 0 && $this->cm->hayCajasAbiertas($id_sucursal_g) == 1) {
            $this->cm->aumentarSaldoCajasAbiertas($monto, $id_sucursal_g);
        }

        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('gasto/gasto_lista');
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
    
    $this->global['pageTitle'] = 'Gastos';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('gasto/table_partial', $data);
}
}

?>
