<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Sucursal extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sucursal_model', 'scm');
        $this->isLoggedIn();
        $this->module = 'Sucursal';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('sucursal/sucursal_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function sucursal_lista()
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
            
            $count = $this->scm->sucursalListingCount($searchText);

			$returns = $this->paginationCompress ( "sucursal_lista/", $count, $count );
            
            $data['records'] = $this->scm->sucursalListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Sucursales';
            
            $this->loadViews("sucursal/sucursal_lista", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'Agregar sucursal';

            $this->loadViews("sucursal/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewsucursal()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre_sucursal','nombre sucursal','trim|required|max_length[200]');
            $this->form_validation->set_rules('impuesto','impuesto','trim|required|max_length[50]');

            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[200]');
            $this->form_validation->set_rules('ciudad','ciudad','trim|required|max_length[50]');
            $this->form_validation->set_rules('simbolo_moneda','simbolo moneda','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
 
                $nombre_sucursal = $this->security->xss_clean($this->input->post('nombre_sucursal'));
                $impuesto = $this->security->xss_clean($this->input->post('impuesto'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $ciudad = $this->security->xss_clean($this->input->post('ciudad'));
                $simbolo_moneda = $this->security->xss_clean($this->input->post('simbolo_moneda'));
                
                $sucursalInfo = array('nombre_sucursal'=>$nombre_sucursal, 'impuesto'=>$impuesto, 'celular'=>$celular, 'direccion'=>$direccion, 'ciudad'=>$ciudad, 'simbolo_moneda'=>$simbolo_moneda);
                
                $result = $this->scm->addNewsucursal($sucursalInfo);
                
                if($result > 0) {
                    $this->scm->inicializarStockSucursal($result);
                    $this->session->set_flashdata('success', 'Nuevo sucursal agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo sucursal');
                }
                


              


                

                redirect('sucursal/sucursal_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($sucursalId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($sucursalId == null)
            {
                redirect('sucursal/sucursal_lista');
            }
            
            $data['sucursalInfo'] = $this->scm->getsucursalInfo($sucursalId);
      

            $this->global['pageTitle'] = 'Editar sucursal';
            
            $this->loadViews("sucursal/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editsucursal()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id_sucursal = $this->input->post('id_sucursal');
            
            $this->form_validation->set_rules('nombre_sucursal','nombre sucursal','trim|required|max_length[200]');
            $this->form_validation->set_rules('impuesto','impuesto','trim|required|max_length[50]');

            $this->form_validation->set_rules('celular','celular','trim|required|max_length[50]');
            $this->form_validation->set_rules('direccion','direccion','trim|required|max_length[200]');
            $this->form_validation->set_rules('ciudad','ciudad','trim|required|max_length[50]');
            $this->form_validation->set_rules('simbolo_moneda','simbolo moneda','trim|required|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_sucursal);
            }
            else
            {
                $nombre_sucursal = $this->security->xss_clean($this->input->post('nombre_sucursal'));
                $impuesto = $this->security->xss_clean($this->input->post('impuesto'));
                $celular = $this->security->xss_clean($this->input->post('celular'));
                $direccion = $this->security->xss_clean($this->input->post('direccion'));
                $ciudad = $this->security->xss_clean($this->input->post('ciudad'));
                $correo = $this->security->xss_clean($this->input->post('correo'));

                $simbolo_moneda          = $this->security->xss_clean($this->input->post('simbolo_moneda'));
                $zebra_ticket_printer    = $this->security->xss_clean($this->input->post('zebra_ticket_printer'));
                $zebra_label_printer     = $this->security->xss_clean($this->input->post('zebra_label_printer'));
                $allowed_media           = ['^MNN', '^MNC'];
                $zebra_ticket_media_type = in_array($this->input->post('zebra_ticket_media_type'), $allowed_media) ? $this->input->post('zebra_ticket_media_type') : '^MNC';
                $zebra_label_media_type  = in_array($this->input->post('zebra_label_media_type'),  $allowed_media) ? $this->input->post('zebra_label_media_type')  : '^MNN';

                $sucursalInfo = array(
                    'nombre_sucursal'         => $nombre_sucursal,
                    'impuesto'                => $impuesto,
                    'celular'                 => $celular,
                    'direccion'               => $direccion,
                    'ciudad'                  => $ciudad,
                    'correo'                  => $correo,
                    'simbolo_moneda'          => $simbolo_moneda,
                    'zebra_ticket_printer'    => $zebra_ticket_printer ?: NULL,
                    'zebra_label_printer'     => $zebra_label_printer  ?: NULL,
                    'zebra_ticket_media_type' => $zebra_ticket_media_type,
                    'zebra_label_media_type'  => $zebra_label_media_type,
                );

                $result = $this->scm->editsucursal($sucursalInfo, $id_sucursal);

                // Actualizar sesión si es la sucursal activa del usuario
                if ($result && $this->session->userdata('id_sucursal') == $id_sucursal) {
                    $this->session->set_userdata('zebra_ticket_printer',    $zebra_ticket_printer    ?: '');
                    $this->session->set_userdata('zebra_label_printer',     $zebra_label_printer     ?: '');
                    $this->session->set_userdata('zebra_ticket_media_type', $zebra_ticket_media_type);
                    $this->session->set_userdata('zebra_label_media_type',  $zebra_label_media_type);
                }
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente sucursal');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion sucursal fallo');
                }
                
                redirect('sucursal/sucursal_lista');
            }
        }
    }





    public function validar_contrasena_eliminar() {
        if (!$this->hasDeleteAccess()) {
            echo json_encode(['success' => false, 'message' => 'Sin permisos']);
            return;
        }

        $password = $this->input->post('password');
        $id_sucursal = $this->input->post('id_sucursal');

        if (empty($password) || empty($id_sucursal)) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        $userId = $this->session->userdata('userId');
        $this->load->model('Login_model', 'lm');

        $this->db->select('password');
        $this->db->from('tbl_users');
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        $user = $query->row();

        if ($user && verifyHashedPassword($password, $user->password)) {
            $this->scm->eliminar_producto_stock($id_sucursal);
            $this->scm->eliminar_sucursal($id_sucursal);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        }
    }

    function confirmar_eliminar_sucursal($id) {
        redirect('sucursal/sucursal_lista');
    }


    public function filtersucursal()
{
 
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->scm->sucursalListingCount($searchText,);

    $returns = $this->paginationCompress ( "sucursal_lista/", $count, $count );
    
    $data['records'] = $this->scm->sucursalListing($searchText, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = 'Sucursales';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('sucursal/table_partial', $data);
}











    // ── Configuración del ticket por sucursal ─────────────────────────────
    public function ticket_config($id_sucursal = NULL) {
        if (!$this->hasUpdateAccess()) { $this->loadThis(); return; }

        if ($id_sucursal === NULL) {
            $id_sucursal = $this->session->userdata('id_sucursal');
        }

        $this->db->where('id_sucursal', $id_sucursal);
        $sucursal = $this->db->get('tbl_sucursal')->row();

        if (!$sucursal) { show_error('Sucursal no encontrada', 404); return; }

        $data['sucursal']   = $sucursal;
        $this->global['pageTitle'] = 'Configurar Ticket';
        $this->loadViews('sucursal/ticket_config', $this->global, $data, NULL);
    }

    public function ticket_config_save() {
        if (!$this->hasUpdateAccess()) { $this->loadThis(); return; }

        $id  = (int)$this->input->post('id_sucursal');
        $p   = $this->input->post();

        $c = function($v, $min, $max) { return max($min, min($max, (int)$v)); };
        $xss = function($v) { return $this->security->xss_clean((string)$v); };

        $fields = [
            // Visibilidad
            'ticket_mostrar_logo'    => isset($p['ticket_mostrar_logo'])    ? 1 : 0,
            'ticket_mostrar_tel'     => isset($p['ticket_mostrar_tel'])     ? 1 : 0,
            'ticket_mostrar_dir'     => isset($p['ticket_mostrar_dir'])     ? 1 : 0,
            'ticket_mostrar_ciudad'  => isset($p['ticket_mostrar_ciudad'])  ? 1 : 0,
            'ticket_mostrar_correo'  => isset($p['ticket_mostrar_correo'])  ? 1 : 0,
            'ticket_mostrar_num'     => isset($p['ticket_mostrar_num'])     ? 1 : 0,
            'ticket_mostrar_fecha'   => isset($p['ticket_mostrar_fecha'])   ? 1 : 0,
            'ticket_mostrar_cliente' => isset($p['ticket_mostrar_cliente']) ? 1 : 0,
            'ticket_mostrar_desc'    => isset($p['ticket_mostrar_desc'])    ? 1 : 0,
            'ticket_mostrar_cambio'  => isset($p['ticket_mostrar_cambio'])  ? 1 : 0,
            // Textos
            'ticket_subtitulo'       => $xss($p['ticket_subtitulo']   ?? ''),
            'ticket_msg_gracias'     => $xss($p['ticket_msg_gracias'] ?? '¡Gracias por su compra!'),
            'ticket_politica'        => $xss($p['ticket_politica']    ?? ''),
            // Logo
            'ticket_logo_opacidad'   => $c($p['ticket_logo_opacidad'] ?? 30, 5, 80),
            'ticket_logo_ancho'      => $c($p['ticket_logo_ancho']    ?? 70, 30, 78),
            // Diseño
            'ticket_margen'          => $c($p['ticket_margen']        ?? 5,  3, 15),
            'ticket_separador'       => $c($p['ticket_separador']     ?? 3,  1,  6),
            // Fuentes (ZPL dots)
            'ticket_fs_titulo'       => $c($p['ticket_fs_titulo']     ?? 48, 32, 72),
            'ticket_fs_info'         => $c($p['ticket_fs_info']       ?? 22, 16, 36),
            'ticket_fs_normal'       => $c($p['ticket_fs_normal']     ?? 24, 18, 40),
            'ticket_fs_total'        => $c($p['ticket_fs_total']      ?? 40, 28, 60),
            'ticket_fs_gracias'      => $c($p['ticket_fs_gracias']    ?? 28, 18, 44),
        ];

        // ── Subida de logo ────────────────────────────────────────────────
        if (!empty($_FILES['ticket_logo_file']['name'])) {
            $uploadPath = FCPATH . 'uploads/logos/';
            if (!is_dir($uploadPath)) { mkdir($uploadPath, 0755, true); }

            $this->load->library('upload', [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true,
            ]);

            if ($this->upload->do_upload('ticket_logo_file')) {
                $info = $this->upload->data();
                // borrar logo anterior
                $old = $this->db->select('ticket_logo')->where('id_sucursal', $id)->get('tbl_sucursal')->row();
                if ($old && !empty($old->ticket_logo)) {
                    $oldFile = $uploadPath . $old->ticket_logo;
                    if (is_file($oldFile)) { @unlink($oldFile); }
                }
                $fields['ticket_logo'] = $info['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('sucursal/ticket_config/' . $id);
                return;
            }
        }

        $this->db->where('id_sucursal', $id);
        $this->db->update('tbl_sucursal', $fields);

        $this->session->set_flashdata('success', '✔ Configuración del ticket guardada correctamente.');
        redirect('sucursal/ticket_config/' . $id);
    }

    public function ticket_logo_delete($id_sucursal = 0) {
        if (!$this->hasUpdateAccess()) { show_error('Sin acceso', 403); return; }

        $id  = (int)$id_sucursal;
        $row = $this->db->select('ticket_logo')->where('id_sucursal', $id)->get('tbl_sucursal')->row();
        if ($row && !empty($row->ticket_logo)) {
            $file = FCPATH . 'uploads/logos/' . $row->ticket_logo;
            if (is_file($file)) { @unlink($file); }
            $this->db->where('id_sucursal', $id)->update('tbl_sucursal', ['ticket_logo' => null]);
        }

        $this->session->set_flashdata('success', 'Logo eliminado.');
        redirect('sucursal/ticket_config/' . $id);
    }
}

?>
