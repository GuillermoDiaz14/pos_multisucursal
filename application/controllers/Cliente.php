<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Cliente extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cliente_model', 'ccm');
        $this->isLoggedIn();
        $this->module = 'Cliente';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('cliente/cliente_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function cliente_lista()
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
            
            $count = $this->ccm->clienteListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "cliente_lista/", $count, $count );
            
            $data['records'] = $this->ccm->clienteListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' cliente';
            
            $this->loadViews("cliente/cliente_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = ' Agregar nuevo cliente';

            $this->loadViews("cliente/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewcliente()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('correo', 'correo','trim|required|max_length[50]');
            $this->form_validation->set_rules('doc_identidad','doc. de identidad','trim|required|max_length[50]');
            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                   $id_sucursal = $this->session->userdata('id_sucursal');
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $correo = $this->security->xss_clean($this->input->post('correo'));
                $doc_identidad = $this->security->xss_clean($this->input->post('doc_identidad'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                
                $clienteInfo = array('nombre'=>$nombre, 'correo'=>$correo, 'doc_identidad'=>$doc_identidad, 'celular'=>$celular, 'id_sucursal'=>$id_sucursal);
                
                $result = $this->ccm->addNewcliente($clienteInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo cliente agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo cliente');
                }
                
                redirect('cliente/cliente_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($clienteId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($clienteId == null)
            {
                redirect('cliente/cliente_lista');
            }
            
            $data['clienteInfo'] = $this->ccm->getclienteInfo($clienteId);
      

            $this->global['pageTitle'] = ' Editar cliente';
            
            $this->loadViews("cliente/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editcliente()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_cliente = $this->input->post('id_cliente');
            
            $this->form_validation->set_rules('nombre','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('correo','correo','trim|required|max_length[50]');
            $this->form_validation->set_rules('doc_identidad','doc. de identidad','trim|required|max_length[50]');
            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_cliente);
            }
            else
            {
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $correo = $this->security->xss_clean($this->input->post('correo'));
                $doc_identidad = $this->security->xss_clean($this->input->post('doc_identidad'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
             
                
                $clienteInfo = array('nombre'=>$nombre, 'correo'=>$correo,  'doc_identidad'=>$doc_identidad, 'celular' => $celular);
                
                $result = $this->ccm->editcliente($clienteInfo, $id_cliente);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente cliente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion empleado fallo');
                }
                
                redirect('cliente/cliente_lista');
            }
        }
    }





     function confirmar_eliminar_cliente($id) {
        $this->ccm->eliminar_cliente($id);
                            $this->session->set_flashdata('success', 'Eliminado satisfactoriamente');
        redirect('cliente/cliente_lista'); // Redirige a la página de lista de productos
    }


    public function filterclientes()
{
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    $id_sucursal = $this->session->userdata('id_sucursal');
    $count = $this->ccm->clienteListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "cliente_lista/", $count, $count );
    
    $data['records'] = $this->ccm->clienteListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' cliente';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('cliente/table_partial', $data);
}







function importar()
{
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
    }
    else
    {
       
        $this->global['pageTitle'] = ' importar cliente';

        $this->loadViews("cliente/importar", $this->global, NULL, NULL);
    }
}




public function importar_cliente() {
    $config['upload_path'] = './uploads/'; // Carpeta de subida de archivos
    $config['allowed_types'] = 'csv'; // Solo permitir archivos CSV
    $config['max_size'] = 1024; // Tamaño máximo en kilobytes
    $config['overwrite'] = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('archivo')) {
        // Si hay un error en la subida del archivo, muestra un mensaje de error
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error', 'Error al subir el archivo: ' . $error['error']);
        redirect('cliente/importar');
    } else {
        // Procesa el archivo y los datos
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        
        $id_sucursal = $this->session->userdata('id_sucursal');
        // Llama al modelo para importar los datos
        $this->ccm->importar_clientes($file_path,$id_sucursal);

        $this->session->set_flashdata('success', 'Importado clientes correctamente');
        
        // Redirige a la página principal con un mensaje de éxito
        redirect('cliente/importar');
    }
}

}

?>