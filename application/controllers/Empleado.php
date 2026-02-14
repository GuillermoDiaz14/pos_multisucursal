<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Empleado extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model', 'em');
        $this->isLoggedIn();
        $this->module = 'Empleado';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('empleado/empleadoListing');
    }
    
    /**
     * This function is used to load the booking list
     */

     function importar()
{
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
    }
    else
    {
       
        $this->global['pageTitle'] = ' importar empleado';

        $this->loadViews("empleado/importar", $this->global, NULL, NULL);
    }
}




public function importar_empleado() {
    $config['upload_path'] = './uploads/'; // Carpeta de subida de archivos
    $config['allowed_types'] = 'csv'; // Solo permitir archivos CSV
    $config['max_size'] = 1024; // Tamaño máximo en kilobytes
    $config['overwrite'] = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('archivo')) {
        // Si hay un error en la subida del archivo, muestra un mensaje de error
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error', 'Error al subir el archivo: ' . $error['error']);
        redirect('empleado/importar');
    } else {
        // Procesa el archivo y los datos
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        
        // Llama al modelo para importar los datos
           $id_sucursal = $this->session->userdata('id_sucursal');
        $this->em->importar_empleados($file_path,$id_sucursal);

        $this->session->set_flashdata('success', 'Importado empleados correctamente');
        
        // Redirige a la página principal con un mensaje de éxito
        redirect('empleado/importar');
    }
}

    function empleadoListing()
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
            $count = $this->em->empleadoListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "empleadoListing/", $count, $count );
            
            $data['records'] = $this->em->empleadoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Prometeo service : Empleado';
            
            $this->loadViews("empleado/list", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'Prometeo service : Agregar nuevo empleado';

            $this->loadViews("empleado/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewEmpleado()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[50]');
            $this->form_validation->set_rules('dni','Dni','trim|required|max_length[1024]');
            $this->form_validation->set_rules('celular','Celular','trim|required|max_length[1024]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                   $id_sucursal = $this->session->userdata('id_sucursal');
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $dni = $this->security->xss_clean($this->input->post('dni'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                
                $empleadoInfo = array('nombre'=>$nombre, 'dni'=>$dni, 'celular'=>$celular, 'id_sucursal'=>$id_sucursal);
                
                $result = $this->em->addNewEmpleado($empleadoInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo empleado agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo empleado');
                }
                
                redirect('empleado/empleadoListing');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($bookingId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($bookingId == null)
            {
                redirect('empleado/empleadoListing');
            }
            
            $data['empleadoInfo'] = $this->em->getEmpleadoInfo($bookingId);
       

            $this->global['pageTitle'] = 'Prometeo service : Editar empleado';
            
            $this->loadViews("empleado/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editEmpleado()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $empleadoId = $this->input->post('empleadoId');
            
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[50]');
            $this->form_validation->set_rules('dni','Dni','trim|required|max_length[1024]');
            $this->form_validation->set_rules('celular','Celular','trim|required|max_length[1024]');
         
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($empleadoId);
            }
            else
            {
                $nombre = $this->security->xss_clean($this->input->post('nombre'));
                $dni = $this->security->xss_clean($this->input->post('dni'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                
                
                $empleadoInfo = array('nombre'=>$nombre, 'dni'=>$dni,  'celular'=>$celular);
                
                $result = $this->em->editEmpleado($empleadoInfo, $empleadoId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente empleado');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion empleado fallo');
                }
                
                redirect('empleado/empleadoListing');
            }
        }
    }





     function confirmar_eliminar_empleado($id) {
        $this->em->eliminar_empleado($id);
                                    $this->session->set_flashdata('success', 'Eliminado satisfactoriamente');
        redirect('empleado/empleadoListing'); // Redirige a la página de lista de productos
    }


    public function filterEmployees()
{
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
         $id_sucursal = $this->session->userdata('id_sucursal');
    $count = $this->em->empleadoListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "empleadoListing/", $count, $count );
    
    $data['records'] = $this->em->empleadoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Prometeo service : Empleado';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('empleado/table_partial', $data);
}
}

?>