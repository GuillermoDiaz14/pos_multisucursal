<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Reporte_model', 'repm');
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $previousMonthStart = date('Y-m-01', strtotime('first day of last month'));
        $previousMonthEnd = date('Y-m-t', strtotime('last month'));
        $trendStart = date('Y-m-d', strtotime('-13 days'));
        $monthlyStart = date('Y-m-01', strtotime('-5 months'));

        $periods = array(
            'today' => $today,
            'yesterday' => $yesterday,
            'month_start' => $monthStart,
            'month_end' => $monthEnd,
            'previous_month_start' => $previousMonthStart,
            'previous_month_end' => $previousMonthEnd
        );

        $data['sucursales'] = $this->user_model->getSucursalInfo($id_sucursal);
        $data['dashboardSummary'] = $this->user_model->getDashboardSummary($id_sucursal, $periods);
        $data['salesTrend'] = $this->user_model->getDashboardSalesTrend($id_sucursal, $trendStart, $today);
        $data['monthlyComparison'] = $this->user_model->getDashboardMonthlyComparison($id_sucursal, $monthlyStart, $monthEnd);
        $data['paymentDistribution'] = $this->user_model->getDashboardPaymentDistribution($id_sucursal, $monthStart, $monthEnd);
        $data['topProducts'] = $this->user_model->getDashboardTopProducts($id_sucursal, $monthStart, $monthEnd, 6);
        $data['lowStockProducts'] = $this->user_model->getDashboardLowStock($id_sucursal, 8);
        $data['dashboardPeriods'] = array(
            'today' => $today,
            'month_start' => $monthStart,
            'month_end' => $monthEnd
        );
        $data['dashboardQuickReports'] = array(
            'caja_operativa' => $this->repm->getCajaOperativaResumen($id_sucursal, $today, $today),
            'flujo_total' => $this->repm->getFlujoTotalResumen($id_sucursal, $monthStart, $monthEnd),
            'ventas_por_vendedor' => $this->repm->getVentasPorVendedorResumen($id_sucursal, $monthStart, $monthEnd),
            'compras_por_proveedor' => $this->repm->getComprasPorProveedorResumen($id_sucursal, $monthStart, $monthEnd),
            'stock_actual' => $this->repm->getStockActualResumen($id_sucursal),
            'stock_bajo' => $this->repm->getStockBajoResumen($id_sucursal, 0, '', 5)
        );
        $reportLinks = array();
        foreach ($this->getAccessibleReports() as $report) {
            if (!empty($report['key']) && !empty($report['url'])) {
                $reportLinks[$report['key']] = $report['url'];
            }
        }
        $data['dashboardReportLinks'] = $reportLinks;
        $data['dashboardPermissions'] = array(
            'ventas_diarias' => $this->hasReportAccess('ventas_diarias'),
            'ventas_periodo' => $this->hasReportAccess('ventas_periodo'),
            'ventas_mensuales' => $this->hasReportAccess('ventas_mensuales'),
            'productos_mas_vendidos' => $this->hasReportAccess('productos_mas_vendidos'),
            'utilidad_estimada' => $this->hasReportAccess('utilidad_estimada'),
            'ventas_por_vendedor' => $this->hasReportAccess('ventas_por_vendedor'),
            'compras_periodo' => $this->hasReportAccess('compras_periodo'),
            'compras_mensuales' => $this->hasReportAccess('compras_mensuales'),
            'compras_por_proveedor' => $this->hasReportAccess('compras_por_proveedor'),
            'caja_operativa' => $this->hasReportAccess('caja_operativa'),
            'flujo_total' => $this->hasReportAccess('flujo_total'),
            'stock_actual' => $this->hasReportAccess('stock_actual'),
            'stock_bajo' => $this->hasReportAccess('stock_bajo')
        );

        $this->global['pageTitle'] = 'Panel principal';
        
        $this->loadViews("general/dashboard", $this->global, $data , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {       
            
            $id_usuario=$this->vendorId;
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            //        $id_sucursal = $this->session->userdata('id_sucursal');
            $count = $this->user_model->userListingCount($searchText,$id_usuario);

			$returns = $this->paginationCompress ( "userListing/", $count, $count );
            
            $data['userRecords'] = $this->user_model->userListing($searchText,$id_usuario, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Lista de usuarios';
            
            $this->loadViews("users/users", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {
            $data['sucursal'] = $this->user_model->get_sucursal();
            
            $id_sucursal = $this->session->userdata('id_sucursal');
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles($id_sucursal);
            
            $this->global['pageTitle'] = 'Agregar usuario';

            $this->loadViews("users/addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
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
    function addNewUser()
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','trim|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $id_sucursal = $this->input->post('id_sucursal');
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=> $name, 'mobile'=>$mobile,
                        'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'),'id_sucursal'=>$id_sucursal);
                
                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0){
                    $this->session->set_flashdata('success', 'Usuario creado correctamente');
                } else {
                    $this->session->set_flashdata('error', 'Error al crear el usuario');
                }

                redirect('userListing');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('userListing');
            }
//                     $id_sucursal = $this->session->userdata('id_sucursal');
$data['sucursal'] = $this->user_model->get_sucursal();
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'Editar usuario';
            
            $this->loadViews("users/editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            $changePass = $this->input->post('changePass');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            if($changePass) {
                $this->form_validation->set_rules('password','Password','required|max_length[20]');
                $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]|max_length[20]');
            }
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','trim|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $id_sucursal = $this->input->post('id_sucursal');
                
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));

                $userInfo = array();

                if($changePass && !empty($password))
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>$name, 'mobile'=>$mobile,
                        'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'), 'id_sucursal'=>$id_sucursal);
                }
                else
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name, 'mobile'=>$mobile,
                         'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'), 'id_sucursal'=>$id_sucursal);
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Usuario actualizado correctamente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Error al actualizar el usuario');
                }
                
                redirect('userListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        if(!$this->hasAdminPanelAccess())
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Página no encontrada';
        
        $this->loadViews("general/404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        if(!$this->hasAdminPanelAccess())
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? 0 : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 10, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Historial de inicio de sesión';
            
            $this->loadViews("users/loginHistory", $this->global, $data, NULL);
        }        
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        
        $this->global['pageTitle'] = $active == "details" ? 'Mi perfil' : 'Cambiar contraseña';
        $this->loadViews("users/profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');        
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            
            $userInfo = array('name'=>$name, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
            if($result == true)
            {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'perfil actualizado con exito');
            }
            else
            {
                $this->session->set_flashdata('error', 'actualizado perfil fallo');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Tu antiguo password no es correcto');
                redirect('profile/'.$active);
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Actualizacion de password exitoso'); }
                else { $this->session->set_flashdata('error', 'fallo Actualizacion Password '); }
                
                redirect('profile/'.$active);
            }
        }
    }

    /**
     * AJAX: guarda foto de perfil recortada (base64 canvas de Cropper.js)
     * Comprime a JPEG 75, máx 300×300, sin EXIF (el canvas ya lo corrigió el browser).
     */
    function guardar_foto()
    {
        ob_start();

        if (!$this->input->is_ajax_request()) {
            ob_end_clean();
            show_404(); return;
        }

        if (!function_exists('imagecreatefromstring')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'PHP GD no está habilitado. Activa extension=gd en php.ini y reinicia Apache.']); return;
        }

        if (empty($_FILES['imagen']['tmp_name'])) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Sin imagen']); return;
        }

        $dir_fotos = FCPATH . 'uploads/fotos/';
        if (!is_dir($dir_fotos) && !mkdir($dir_fotos, 0755, true)) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio uploads/fotos/']); return;
        }

        $bytes = @file_get_contents($_FILES['imagen']['tmp_name']);
        if ($bytes === false || strlen($bytes) < 100) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Imagen inválida']); return;
        }

        $src = @imagecreatefromstring($bytes);
        if (!$src) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'No se pudo procesar la imagen']); return;
        }

        // El canvas de Cropper.js ya está orientado correctamente — solo redimensionar
        $orig_w = imagesx($src);
        $orig_h = imagesy($src);
        $size   = 300;

        $dst    = imagecreatetruecolor($size, $size);
        $blanco = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $size, $size, $blanco);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $size, $size, $orig_w, $orig_h);
        imagedestroy($src);

        $userId = $this->vendorId;
        $ruta   = FCPATH . 'uploads/fotos/user_' . $userId . '.jpg';
        $ok     = imagejpeg($dst, $ruta, 75);
        imagedestroy($dst);

        if (!$ok) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen']); return;
        }

        $ts = time();
        $this->user_model->updateFoto($userId, $ts);
        $this->session->set_userdata('foto', $ts);

        ob_end_clean();
        echo json_encode([
            'success' => true,
            'url'     => base_url('uploads/fotos/user_' . $userId . '.jpg?v=' . $ts)
        ]);
    }

    /**
     * AJAX: guarda el color de tema del usuario (hex #rrggbb)
     */
    function guardar_color_tema()
    {
        if (!$this->input->is_ajax_request()) { show_404(); return; }

        $color = trim($this->input->post('color'));

        // Paleta permitida — solo estos 8 valores
        $permitidos = ['#3c8dbc','#1a5276','#283593','#27ae60','#558b2f','#00695c','#16a085','#c0392b','#880e4f','#8e44ad','#4527a0','#d86700','#c2185b','#5d4037','#546e7a','#2c3e50'];
        if (!in_array($color, $permitidos, true)) {
            echo json_encode(['success' => false, 'message' => 'Color no permitido']); return;
        }

        $this->user_model->updateColorTema($this->vendorId, $color);
        $this->session->set_userdata('color_tema', $color);

        echo json_encode(['success' => true]);
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'El  {field} ya está ocupado');
            $return = false;
        }

        return $return;
    }
}

?>
