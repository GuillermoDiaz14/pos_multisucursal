<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Login extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');

    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }
    
    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $data['sucursal'] = $this->login_model->get_sucursal();

          $this->load->view("users/login", $data);
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    
    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Correo', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|max_length[64]');
        $this->form_validation->set_rules('id_sucursal', 'Sucursal', 'required|integer');
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $email       = strtolower(trim($this->input->post('email', TRUE)));
            $password    = $this->input->post('password');
            $id_sucursal = (int) $this->input->post('id_sucursal', TRUE);
            $result = $this->login_model->loginMe($email, $password,$id_sucursal);

            //pre($result); die;
            
            if (!empty($result))
            {
                if ($result->roleStatus == 2 || $result->isRoleDeleted == 1)
                {
                    $this->session->set_flashdata('error', 'El usuario no tiene ningun rol o la sucursal es incorrecta');
                    redirect('login');
                }

                $lastLogin = $this->login_model->lastLoginInfo($result->userId);

                $accessInfo = $this->accessInfo($result->roleId);

                $accessMatrixRow = $this->db->select('updatedDtm')->from('tbl_access_matrix')->where('roleId', $result->roleId)->get()->row();
                $accessUpdatedAt = $accessMatrixRow ? $accessMatrixRow->updatedDtm : null;

                $sessionArray = array('userId'=>$result->userId,
                                        'role'=>$result->roleId,
                                        'roleText'=>$result->role,
                                        'name'=>$result->name,
                                        'accessInfo'=>$accessInfo,
                                        'accessUpdatedAt'=>$accessUpdatedAt,
                                        'userUpdatedAt'=>$result->updatedDtm,
                                        'lastLogin'=> $lastLogin->createdDtm,
                                        'id_sucursal'=> $result->id_sucursal,
                                        'foto'       => $result->foto       ?? null,
                                        'color_tema' => $result->color_tema ?? '#3c8dbc',
                                        'isLoggedIn' => TRUE
                                );


                                $sessionArray['id_sucursal'] = $id_sucursal;

                // Cargar impresoras Zebra de la sucursal
                $this->load->model('sucursal_model', 'scm');
                $sucInfo = $this->scm->getsucursalInfo($id_sucursal);
                $sessionArray['zebra_ticket_printer'] = ($sucInfo && !empty($sucInfo->zebra_ticket_printer)) ? $sucInfo->zebra_ticket_printer : '';
                $sessionArray['zebra_label_printer']  = ($sucInfo && !empty($sucInfo->zebra_label_printer))  ? $sucInfo->zebra_label_printer  : '';

                $this->session->set_userdata($sessionArray);

                unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin'], $sessionArray['accessInfo']);

                $loginInfo = array("userId"=>$result->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());

                $this->login_model->lastLogin($loginInfo);
                
                redirect('/dashboard');
            }
            else
            {
                $this->session->set_flashdata('error', 'Email/ password o sucursal incorrecto');
                redirect('login');
            }
        }
    }

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('users/forgotPassword');
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','trim|required|valid_email');
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = strtolower($this->security->xss_clean($this->input->post('login_email')));
            
            if($this->login_model->checkEmailExist($email))
            {
                $encoded_email = urlencode($email);
                
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $data1['reset_link'] = base_url() . "resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo->name;
                        $data1["email"] = $userInfo->email;
                        $data1["message"] = "Reset Your Password";
                    }

                    $sendStatus = resetPasswordEmail($data1);
                  $status="estado";
                    /*if($sendStatus){
//                        echo $data1["name"];
  //                      echo $data1["email"];
    //                    echo $data1["message"];
                        $status = "send";
                        setFlashData($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        $status = "notsend";
                        setFlashData($status, "Email has been failed, try again.");
                    }*/
                    $this->load->library('email');

                    $this->email->from("ventas@ventadecodigofuente.com", "tusolutionweb");
                    $this->email->to($data1["email"]);
                    $this->email->subject($data1["message"]);
                    $this->email->message($data1['reset_link']);
                    
                    if ($this->email->send()) {
                     
                        setFlashData($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        show_error($this->email->print_debugger());
                    }   
                }
                else
                {
                    $status = 'unable';
                    setFlashData($status, "It seems an error while sending your details, try again.");
                }
            }
            else
            {
                $status = 'invalid';
                setFlashData($status, "This email is not registered with us.");
            }
            redirect('/forgotPassword');
        }
    }

    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        
        if ($is_correct == 1)
        {
            $this->load->view('users/newPassword', $data);
        }
        else
        {
            redirect('/login');
        }
    }
    
    /**
     * This function used to create new password for user
     */
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = strtolower($this->input->post("email"));
        $activation_id = $this->input->post("activation_code");
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        }
        else
        {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
            
            if($is_correct == 1)
            {                
                $this->login_model->createPasswordUser($email, $password);
                
                $status = 'success';
                $message = 'Password reset successfully';
            }
            else
            {
                $status = 'error';
                $message = 'Password reset failed';
            }
            
            setFlashData($status, $message);

            redirect("/login");
        }
    }

    private function accessInfo($roleId)
    {
        $finalMatrixArray = [];
        $matrix = $this->login_model->getRoleAccessMatrix($roleId);
        
        if(!empty($matrix)) {
            $accessMatrix = json_decode($matrix->access);
            if (is_array($accessMatrix)) {
                foreach($accessMatrix as $moduleMatrix) {
                    if (isset($moduleMatrix->module)) {
                        $moduleName = $moduleMatrix->module;
                        $totalAccess = isset($moduleMatrix->total_access) ? $moduleMatrix->total_access : 0;
                        $finalMatrixArray[$moduleName] = array(
                            'module' => $moduleName,
                            'total_access' => $totalAccess
                        );

                        if (isset($moduleMatrix->scope)) {
                            $finalMatrixArray[$moduleName]['scope'] = $moduleMatrix->scope;
                        }

                        if ($moduleName === 'Productos') {
                            $finalMatrixArray[$moduleName]['ver_precio_compra'] = isset($moduleMatrix->ver_precio_compra) ? (int)$moduleMatrix->ver_precio_compra : 0;
                            $finalMatrixArray[$moduleName]['gestionar'] = isset($moduleMatrix->gestionar) ? (int)$moduleMatrix->gestionar : 0;
                        }

                        if (isset($moduleMatrix->reports) && is_array($moduleMatrix->reports)) {
                            $finalMatrixArray[$moduleName]['reports'] = array();
                            foreach ($moduleMatrix->reports as $reportMatrix) {
                                if (!isset($reportMatrix->key)) {
                                    continue;
                                }
                                $finalMatrixArray[$moduleName]['reports'][$reportMatrix->key] = array(
                                    'key' => $reportMatrix->key,
                                    'allowed' => isset($reportMatrix->allowed) ? (int) $reportMatrix->allowed : 0
                                );
                            }
                        }
                    }
                }
            }
        }
        
        return $finalMatrixArray;
    }
}

?>
