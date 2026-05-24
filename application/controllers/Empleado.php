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
            
            $this->global['pageTitle'] = 'Empleados';
            
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
            $data['roles'] = $this->em->getRolesAsignables();
            $this->global['pageTitle'] = 'Agregar empleado';

            $this->loadViews("empleado/add", $this->global, $data, NULL);
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
            $this->form_validation->set_rules('celular','Celular','trim|required|max_length[20]');
            $this->form_validation->set_rules('dni','INE','trim|max_length[20]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Contraseña','required|min_length[4]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirmar contraseña','required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('roleId','Rol','required|integer');

            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $id_sucursal = $this->session->userdata('id_sucursal');
                $nombre   = $this->security->xss_clean($this->input->post('nombre'));
                $celular  = $this->security->xss_clean($this->input->post('celular'));
                $dni      = $this->security->xss_clean($this->input->post('dni'));
                $email    = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');

                if ($this->em->emailExists($email)) {
                    $this->session->set_flashdata('error', 'El email ya está registrado como usuario.');
                    redirect('empleado/add');
                    return;
                }

                $roleId = (int) $this->input->post('roleId');
                if (!$this->em->roleExists($roleId)) {
                    $this->session->set_flashdata('error', 'El rol seleccionado no es válido.');
                    redirect('empleado/add');
                    return;
                }

                $empleadoInfo = array(
                    'nombre'      => $nombre,
                    'dni'         => $dni,
                    'celular'     => $celular,
                    'id_sucursal' => $id_sucursal
                );
                $userInfo = array(
                    'email'       => $email,
                    'password'    => getHashedPassword($password),
                    'roleId'      => $roleId,
                    'name'        => $nombre,
                    'mobile'      => $celular,
                    'isAdmin'     => 2,
                    'isDeleted'   => 0,
                    'createdBy'   => $this->vendorId,
                    'createdDtm'  => date('Y-m-d H:i:s'),
                    'id_sucursal' => $id_sucursal
                );

                $result = $this->em->addEmpleadoConUsuario($empleadoInfo, $userInfo);

                if ($result) {
                    $this->session->set_flashdata('success', 'Empleado y usuario creados correctamente.');
                } else {
                    $this->session->set_flashdata('error', 'Error al crear el empleado.');
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
       

            $this->global['pageTitle'] = 'Editar empleado';
            
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
            $this->form_validation->set_rules('dni','INE','trim|max_length[20]');
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
                    $this->session->set_flashdata('success', 'Empleado actualizado correctamente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Error al actualizar el empleado');
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
    
    $this->global['pageTitle'] = 'Empleados';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('empleado/table_partial', $data);
}
}

?>
