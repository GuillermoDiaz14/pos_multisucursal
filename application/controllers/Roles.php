<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Roles extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model', 'rm');
        $this->isLoggedIn();   
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('roles/roleListing');
    }
    
    /**
     * This function is used to load the role list
     */
    function roleListing()
    {
        if(!$this->hasModulePermission('Roles'))
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
//              $id_sucursal = $this->session->userdata('id_sucursal');
            $count = $this->rm->roleListingCount($searchText);

			$returns = $this->paginationCompress ( "roles/roleListing/", $count, $count );
            
            $data['roleRecords'] = $this->rm->roleListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Lista de roles';
            
            $this->loadViews("roles/list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasModulePermission('Roles', 'crear'))
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'Agregar rol';

            $this->loadViews("roles/add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkRoleExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewRole()
    {
        if(!$this->hasModulePermission('Roles', 'crear'))
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {

                      
                                 $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));
                
                $roleInfo = array('role'=>$roleText, 'status'=>$status, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->rm->addNewRole($roleInfo);
                
                if($result > 0)
                {
                    $this->addRoleMatrix($result);
                    $this->session->set_flashdata('success', 'Nuevo rol agregado satisfactoriamente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role creation failed');
                }
                
                redirect('roles/roleListing');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $roleId : Optional : This is user id
     */
    function edit($roleId = NULL)
    {
        if(!$this->hasModulePermission('Roles', 'editar'))
        {
            $this->loadThis();
        }
        else
        {
            if($roleId == null)
            {
                redirect('roles/roleListing');
            }
            
            // Forzar recarga del config para evitar listas obsoletas si autoload/opcache se desfasa.
            $this->config->load('modules', TRUE, TRUE);
            $this->config->load('reports', TRUE, TRUE);

            $data['roleInfo'] = $this->rm->getRoleInfo($roleId);

            // Solo un Admin puede editar el rol Admin/Administrador.
            if (!empty($data['roleInfo']) && in_array($data['roleInfo']->role, array('Admin','Administrador'), true) && !$this->isCurrentUserAdminRole()) {
                $this->session->set_flashdata('error', 'No tienes permisos para editar el rol de administrador.');
                redirect('roles/roleListing');
                return;
            }

            $roleAccessMatrix = $this->rm->getRoleAccessMatrix($roleId);
            $data['roleAccessMatrix'] = is_array(json_decode($roleAccessMatrix->access)) ? json_decode($roleAccessMatrix->access) : [];
            $data['moduleList'] = $this->config->item('moduleList');
            $data['reportList'] = $this->config->item('reportList');
            
            $this->global['pageTitle'] = 'Editar rol';
            
            $this->loadViews("roles/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editRole()
    {
        if(!$this->hasModulePermission('Roles', 'editar'))
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $roleId = $this->input->post('roleId');

            // Bloqueo: un no-admin no puede editar el rol Admin/Administrador.
            if (!$this->isCurrentUserAdminRole()) {
                $targetRole = $this->rm->getRoleInfo($roleId);
                if (!empty($targetRole) && in_array($targetRole->role, array('Admin','Administrador'), true)) {
                    $this->session->set_flashdata('error', 'No tienes permisos para editar el rol de administrador.');
                    redirect('roles/roleListing');
                    return;
                }
            }

            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($roleId);
            }
            else
            {
                $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));
                
                $roleInfo = array('role'=>$roleText, 'status'=>$status, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->rm->editRole($roleInfo, $roleId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Role updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role updation failed');
                }
                
                redirect('roles/roleListing');
            }
        }
    }

    private function addRoleMatrix($roleId)
    {
        $this->load->config('modules');

        $modules = $this->config->item('moduleList');

        $accessMatrix = array('roleId'=>$roleId, 'access'=>json_encode($modules), 'createdBy'=> $this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

        $this->rm->insertAccessMatrix($accessMatrix);
    }

    public function storeAccessMatrix()
    {
        if(!$this->hasModulePermission('Roles', 'editar'))
        {
            $this->loadThis();
            return;
        }

        $roleId = $this->input->post('roleIdForMatrix');
        $postParams = $this->input->post('access');

        // Bloqueo: un no-admin no puede modificar la matriz del rol Admin/Administrador.
        if (!$this->isCurrentUserAdminRole()) {
            $targetRole = $this->rm->getRoleInfo($roleId);
            if (!empty($targetRole) && in_array($targetRole->role, array('Admin','Administrador'), true)) {
                $this->session->set_flashdata('error', 'No tienes permisos para modificar la matriz del rol de administrador.');
                redirect('roles/roleListing');
                return;
            }
        }

        log_message('debug', 'storeAccessMatrix roleId=' . $roleId . ' postParams=' . json_encode($postParams));

        $this->load->config('modules');
        $modules = $this->config->item('moduleList');
        $modules2 = [];

        foreach ($modules as $idx => $module) {
            $moduleName = $module['module'];

            // El formulario usa índice numérico como key para evitar que CI3
            // rechace keys con acentos o espacios (ej: "Métodos de Pago").
            $p = isset($postParams[$idx]) && is_array($postParams[$idx]) ? $postParams[$idx] : [];

            $singleModule = ['module' => $moduleName, 'total_access' => 0];

            if (!empty($p['total_access'])) {
                $singleModule['total_access'] = 1;
            }

            if ($moduleName === 'Productos') {
                $singleModule['ver_precio_compra'] = !empty($p['ver_precio_compra']) ? 1 : 0;
                $singleModule['gestionar']         = !empty($p['gestionar'])         ? 1 : 0;
            }

            if ($moduleName === 'Ventas') {
                $singleModule['editar']            = !empty($p['editar'])            ? 1 : 0;
                $singleModule['eliminar']          = !empty($p['eliminar'])          ? 1 : 0;
                $singleModule['configurar_ticket'] = !empty($p['configurar_ticket']) ? 1 : 0;
            }

            if ($moduleName === 'Sucursal') {
                $singleModule['crear']    = !empty($p['crear'])    ? 1 : 0;
                $singleModule['editar']   = !empty($p['editar'])   ? 1 : 0;
                $singleModule['eliminar'] = !empty($p['eliminar']) ? 1 : 0;
            }

            if ($moduleName === 'Usuarios' || $moduleName === 'Roles') {
                $singleModule['crear']    = !empty($p['crear'])    ? 1 : 0;
                $singleModule['editar']   = !empty($p['editar'])   ? 1 : 0;
                $singleModule['eliminar'] = !empty($p['eliminar']) ? 1 : 0;
            }

            if ($moduleName === 'Reportes') {
                $singleModule['scope'] = 'sucursal';
                if (isset($p['scope']) && in_array($p['scope'], array('sucursal', 'todas'), true)) {
                    $singleModule['scope'] = $p['scope'];
                }

                $singleModule['reports'] = array();
                $reportList = $this->config->item('reportList');
                if (is_array($reportList)) {
                    foreach ($reportList as $report) {
                        if (!isset($report['key'])) continue;
                        $reportKey = $report['key'];
                        $allowed = (!empty($p['reports'][$reportKey]['allowed'])) ? 1 : 0;
                        $singleModule['reports'][] = array('key' => $reportKey, 'allowed' => $allowed);
                    }
                }
            }

            $modules2[] = $singleModule;
        }

        $accessMatrix = ['access' => json_encode($modules2), 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s')];
        log_message('debug', 'storeAccessMatrix saving=' . json_encode($modules2));
        $updated = $this->rm->updateAccessMatrix($roleId, $accessMatrix);

        if ($updated) {
            $this->session->set_flashdata('success', 'Permisos guardados correctamente.');
        } else {
            $this->session->set_flashdata('error', 'No hubo cambios o el rol no existe.');
        }

        redirect('roles/roleListing');
    }

    public function filterroles()
    {
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }
        $data['searchText'] = $searchText;
    //   echo $searchText;
        $this->load->library('pagination');
           $id_sucursal = $this->session->userdata('id_sucursal');
        $count = $this->rm->roleListingCount($searchText,$id_sucursal);
    
        $returns = $this->paginationCompress ( "list/", $count, $count );
        
        $data['records'] = $this->rm->roleListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Roles';
    
        // Cargar la vista parcial de la tabla con los resultados filtrados
        $this->load->view('roles/table_partial', $data);
    }
    function confirmar_eliminar_rol($id) {
        if(!$this->hasModulePermission('Roles', 'eliminar'))
        {
            $this->loadThis();
            return;
        }
        // Bloqueo: un no-admin no puede eliminar el rol Admin/Administrador.
        if (!$this->isCurrentUserAdminRole()) {
            $targetRole = $this->rm->getRoleInfo($id);
            if (!empty($targetRole) && in_array($targetRole->role, array('Admin','Administrador'), true)) {
                $this->session->set_flashdata('error', 'No tienes permisos para eliminar el rol de administrador.');
                redirect('roles/roleListing');
                return;
            }
        }
        $this->rm->eliminar_rol($id);
                            $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('roles/roleListing'); // Redirige a la página de lista de productos
    }
}


?>
