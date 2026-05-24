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
        if(!$this->hasSucursalPermission('crear'))
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
        if(!$this->hasSucursalPermission('crear'))
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

                    // Logo opcional al crear
                    if (!empty($_FILES['logo_file']['name'])) {
                        $logo = $this->_procesar_logo('logo_file', $result, false);
                        if ($logo) {
                            $this->db->where('id_sucursal', $result)->update('tbl_sucursal', ['ticket_logo' => $logo]);
                        }
                        // si falló, _procesar_logo ya seteó flashdata error; la sucursal queda creada sin logo
                    }

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
        if(!$this->hasSucursalPermission('editar'))
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
        if(!$this->hasSucursalPermission('editar'))
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

                // Logo opcional al editar
                if (!empty($_FILES['logo_file']['name'])) {
                    $logo = $this->_procesar_logo('logo_file', $id_sucursal, true);
                    if ($logo) {
                        $sucursalInfo['ticket_logo'] = $logo;
                    }
                    // si falló, _procesar_logo ya seteó flashdata error; seguimos con los demás campos
                }

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
        if (!$this->hasSucursalPermission('eliminar')) {
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
        if (!$this->hasVentaPermission('configurar_ticket')) { $this->loadThis(); return; }

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
        if (!$this->hasVentaPermission('configurar_ticket')) { $this->loadThis(); return; }

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

        // ── Subida de logo (vía helper unificado) ─────────────────────────
        if (!empty($_FILES['ticket_logo_file']['name'])) {
            $logo = $this->_procesar_logo('ticket_logo_file', $id, true);
            if ($logo === false) {
                redirect('sucursal/ticket_config/' . $id);
                return;
            }
            if ($logo) {
                $fields['ticket_logo'] = $logo;
            }
        }

        $this->db->where('id_sucursal', $id);
        $this->db->update('tbl_sucursal', $fields);

        $this->session->set_flashdata('success', '✔ Configuración del ticket guardada correctamente.');
        redirect('sucursal/ticket_config/' . $id);
    }

    public function ticket_logo_delete($id_sucursal = 0) {
        if (!$this->hasVentaPermission('configurar_ticket')) { show_error('Sin acceso', 403); return; }

        $id  = (int)$id_sucursal;
        $this->_borrar_logo_sucursal($id);

        $this->session->set_flashdata('success', 'Logo eliminado.');
        redirect('sucursal/ticket_config/' . $id);
    }

    /**
     * Eliminar logo (usado desde add/edit). Reutiliza la misma lógica.
     */
    public function logo_delete($id_sucursal = 0) {
        if (!$this->hasSucursalPermission('editar')) { show_error('Sin acceso', 403); return; }
        $id = (int)$id_sucursal;
        $this->_borrar_logo_sucursal($id);
        $this->session->set_flashdata('success', 'Logo eliminado.');
        redirect('sucursal/edit/' . $id);
    }

    /**
     * Borra archivo principal + thumbnail + actualiza BD (ticket_logo = NULL).
     */
    private function _borrar_logo_sucursal($id) {
        $row = $this->db->select('ticket_logo')->where('id_sucursal', $id)->get('tbl_sucursal')->row();
        if ($row && !empty($row->ticket_logo)) {
            $dir   = FCPATH . 'uploads/logos/';
            $file  = $dir . $row->ticket_logo;
            $thumb = $dir . 'thumb_' . $row->ticket_logo;
            if (is_file($file))  { @unlink($file); }
            if (is_file($thumb)) { @unlink($thumb); }
            $this->db->where('id_sucursal', $id)->update('tbl_sucursal', ['ticket_logo' => null]);
        }
    }

    /**
     * Procesa el upload de un logo: valida, redimensiona, comprime, genera thumbnail.
     * Reutiliza la estructura de optimización del proyecto (similar a Producto::comprimir_imagen).
     *
     * @param string $field_name        Nombre del input file ($_FILES key)
     * @param int    $id_sucursal       0 si todavía no existe (add)
     * @param bool   $borrar_anterior   Borrar logo previo de esta sucursal antes de guardar
     * @return string|false  Nombre del archivo guardado (a colocar en BD), o false en error
     *                       (en error setea flashdata 'error' antes de retornar).
     *
     * Parámetros:
     *   - Logo principal: 400px máx, JPEG progresivo q75
     *   - Thumbnail:      80px máx, JPEG progresivo q60 (prefix thumb_)
     */
    private function _procesar_logo($field_name, $id_sucursal = 0, $borrar_anterior = true)
    {
        if (empty($_FILES[$field_name]['name'])) return null; // no se subió nada, no es error

        $uploadPath = FCPATH . 'uploads/logos/';
        if (!is_dir($uploadPath)) @mkdir($uploadPath, 0755, true);

        // 1. Subida con CI Upload (encrypt_name evita colisiones y XSS por nombre)
        $this->load->library('upload', [
            'upload_path'   => $uploadPath,
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 4096,
            'encrypt_name'  => true,
        ], 'upload');

        if (!$this->upload->do_upload($field_name)) {
            $this->session->set_flashdata('error', 'Logo: ' . $this->upload->display_errors('', ''));
            return false;
        }

        $info        = $this->upload->data();
        $tmpUploaded = $uploadPath . $info['file_name'];

        // 2. Validación por cabeceras reales (defensa en profundidad vs encrypt_name)
        $imgInfo = @getimagesize($tmpUploaded);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!$imgInfo || !in_array($imgInfo['mime'], $allowedMimes, true)) {
            @unlink($tmpUploaded);
            $this->session->set_flashdata('error', 'El logo no es una imagen válida.');
            return false;
        }

        // 3. Compresión + thumbnail con GD
        $nombre_final = $this->_comprimir_logo($tmpUploaded);
        if (!$nombre_final) {
            @unlink($tmpUploaded);
            $this->session->set_flashdata('error', 'Error al procesar el logo.');
            return false;
        }

        // 4. Borrar el anterior (si aplica)
        if ($borrar_anterior && $id_sucursal > 0) {
            $old = $this->db->select('ticket_logo')->where('id_sucursal', $id_sucursal)->get('tbl_sucursal')->row();
            if ($old && !empty($old->ticket_logo) && $old->ticket_logo !== $nombre_final) {
                $oldFile  = $uploadPath . $old->ticket_logo;
                $oldThumb = $uploadPath . 'thumb_' . $old->ticket_logo;
                if (is_file($oldFile))  @unlink($oldFile);
                if (is_file($oldThumb)) @unlink($oldThumb);
            }
        }

        return $nombre_final;
    }

    /**
     * Redimensiona + comprime el logo y genera su thumbnail.
     * Devuelve el nombre final (con .jpg) o false.
     */
    private function _comprimir_logo($ruta_original)
    {
        if (!function_exists('imagecreatefromjpeg')) return false;

        $info = @getimagesize($ruta_original);
        if (!$info) return false;
        switch ($info['mime']) {
            case 'image/jpeg': $src = @imagecreatefromjpeg($ruta_original); break;
            case 'image/png':  $src = @imagecreatefrompng($ruta_original);  break;
            case 'image/gif':  $src = @imagecreatefromgif($ruta_original);  break;
            case 'image/webp': $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($ruta_original) : false; break;
            default: return false;
        }
        if (!$src) return false;

        $ow = imagesx($src);
        $oh = imagesy($src);

        // Resize logo principal a 400px máx (manteniendo aspect ratio)
        $max = 400;
        if ($ow > $max || $oh > $max) {
            if ($ow >= $oh) { $nw = $max; $nh = (int) round($oh * ($max / $ow)); }
            else            { $nh = $max; $nw = (int) round($ow * ($max / $oh)); }
        } else {
            $nw = $ow; $nh = $oh;
        }

        $dst = imagecreatetruecolor($nw, $nh);
        $bg  = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $bg);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
        imagedestroy($src);

        // Salida siempre como .jpg para consistencia
        $target_jpg = preg_replace('/\.[^.]+$/', '.jpg', $ruta_original);
        imageinterlace($dst, true);
        $ok = @imagejpeg($dst, $target_jpg, 75);
        if (!$ok) { imagedestroy($dst); return false; }

        // Si el original era png/gif/webp, borrar el archivo viejo de otra extensión
        if (realpath($ruta_original) && realpath($target_jpg) && realpath($ruta_original) !== realpath($target_jpg)) {
            @unlink($ruta_original);
        }

        // Thumbnail 80px máx, q60
        $tmax = 80;
        if ($nw > $tmax || $nh > $tmax) {
            if ($nw >= $nh) { $tw = $tmax; $th = (int) round($nh * ($tmax / $nw)); }
            else            { $th = $tmax; $tw = (int) round($nw * ($tmax / $nh)); }
        } else { $tw = $nw; $th = $nh; }

        $thumb_path = dirname($target_jpg) . DIRECTORY_SEPARATOR . 'thumb_' . basename($target_jpg);
        $t  = imagecreatetruecolor($tw, $th);
        $bg2 = imagecolorallocate($t, 255, 255, 255);
        imagefilledrectangle($t, 0, 0, $tw, $th, $bg2);
        imagecopyresampled($t, $dst, 0, 0, 0, 0, $tw, $th, $nw, $nh);
        imageinterlace($t, true);
        @imagejpeg($t, $thumb_path, 60);
        imagedestroy($t);
        imagedestroy($dst);

        return basename($target_jpg);
    }
}

?>
