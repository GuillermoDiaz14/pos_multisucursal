<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Proveedor extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Proveedor_model', 'prm');
        $this->isLoggedIn();
        $this->module = 'Proveedor';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('proveedor/proveedor_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function proveedor_lista()
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
            
            $count = $this->prm->proveedorListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "proveedor_lista/", $count, $count );
            
            $data['records'] = $this->prm->proveedorListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' proveedor';
            
            $this->loadViews("proveedor/proveedor_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = ' Agregar nuevo proveedor';

            $this->loadViews("proveedor/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewproveedor()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('email','email','trim|required|max_length[50]');

            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[200]');
            $this->form_validation->set_rules('doc_fiscal','doc fiscal','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                 $id_sucursal = $this->session->userdata('id_sucursal');
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $doc_fiscal = $this->security->xss_clean($this->input->post('doc_fiscal'));
                
                $proveedorInfo = array('nombre'=>$nombre, 'email'=>$email, 'celular'=>$celular, 'direccion'=>$direccion, 'doc_fiscal'=>$doc_fiscal, 'id_sucursal'=>$id_sucursal);
                
                $result = $this->prm->addNewproveedor($proveedorInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo proveedor agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo proveedor');
                }
                
                redirect('proveedor/proveedor_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($proveedorId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($proveedorId == null)
            {
                redirect('proveedor/proveedor_lista');
            }
            
            $data['proveedorInfo'] = $this->prm->getproveedorInfo($proveedorId);
      

            $this->global['pageTitle'] = ' Editar proveedor';
            
            $this->loadViews("proveedor/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editproveedor()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_proveedor = $this->input->post('id_proveedor');
            
            $this->form_validation->set_rules('nombre','nombre','trim|required|max_length[200]');
  
            $this->form_validation->set_rules('email','email','trim|required|max_length[50]');
            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[50]');
            $this->form_validation->set_rules('doc_fiscal','doc_fiscal','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_proveedor);
            }
            else
            {
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $doc_fiscal = $this->security->xss_clean($this->input->post('doc_fiscal'));
            
             
                
                $proveedorInfo = array('nombre'=>$nombre, 'email'=>$email,  'celular'=>$celular, 'direccion' => $direccion, 'doc_fiscal' => $doc_fiscal);
                
                $result = $this->prm->editproveedor($proveedorInfo, $id_proveedor);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente proveedor');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion proveedor fallo');
                }
                
                redirect('proveedor/proveedor_lista');
            }
        }
    }





     function confirmar_eliminar_proveedor($id) {
        $this->prm->eliminar_proveedor($id);
                    $this->session->set_flashdata('success', 'Eliminado correctamente');

        redirect('proveedor/proveedor_lista'); // Redirige a la página de lista de productos
    }


    public function filterproveedors()
{
     $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->prm->proveedorListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "proveedor_lista/", $count, $count );
    
    $data['records'] = $this->prm->proveedorListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' proveedor';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('proveedor/table_partial', $data);
}


function importar()
{
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
    }
    else
    {
       
        $this->global['pageTitle'] = ' importar proveedor';

        $this->loadViews("proveedor/importar", $this->global, NULL, NULL);
    }
}




public function importar_proveedor() {

     $id_sucursal = $this->session->userdata('id_sucursal');
    $config['upload_path'] = './uploads/'; // Carpeta de subida de archivos
    $config['allowed_types'] = 'csv'; // Solo permitir archivos CSV
    $config['max_size'] = 1024; // Tamaño máximo en kilobytes
    $config['overwrite'] = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('archivo')) {
        // Si hay un error en la subida del archivo, muestra un mensaje de error
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error', 'Error al subir el archivo: ' . $error['error']);
        redirect('proveedor/importar');
    } else {
        // Procesa el archivo y los datos
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        
        // Llama al modelo para importar los datos
        $this->prm->importar_proveedores($file_path,$id_sucursal);

        $this->session->set_flashdata('success', 'Importado proveedor correctamente');
        
        // Redirige a la página principal con un mensaje de éxito
        redirect('proveedor/importar');
    }
}







}

?>