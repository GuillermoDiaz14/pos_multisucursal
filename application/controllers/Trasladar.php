<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Trasladar extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Trasladar_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Traslados';
    }

    public function index()
    {
        redirect('trasladar/trasladar');
    }

    function trasladar()
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            // Carga inicial: solo los 200 más recientes con stock (orden DESC por id_producto).
            // El resto se busca vía AJAX (Trasladar/buscar_productos).
            $data['productos']     = $this->tm->buscar_productos_traslado($id_sucursal, '', 200);
            $data['configuracion'] = $this->tm->get_configuracion($id_sucursal);
            $data['sucursales']    = $this->tm->get_sucursales($id_sucursal);
            $data['variantes_por_producto'] = $this->tm->get_variantes_por_sucursal($id_sucursal);
            $data['idusuario']     = $this->vendorId;
            $this->global['pageTitle'] = 'Registrar traslado';
            $this->loadViews("traslado/traslado", $this->global, $data, NULL);
        }
    }

    /**
     * AJAX: buscar productos para el traslado.
     * Devuelve hasta 200 filas ordenadas por id_producto DESC.
     */
    public function buscar_productos()
    {
        if (!$this->input->is_ajax_request()) { show_404(); return; }

        $id_sucursal = (int)$this->session->userdata('id_sucursal');
        $texto       = $this->security->xss_clean((string)$this->input->post('texto'));

        $productos = $this->tm->buscar_productos_traslado($id_sucursal, $texto, 200);

        // Adjuntar variantes solo para los productos del resultado (no toda la sucursal).
        $ids_con_var = [];
        foreach ($productos as $p) {
            if ((int)$p->tiene_variantes === 1) $ids_con_var[] = (int)$p->id_producto;
        }
        $variantes = [];
        if (!empty($ids_con_var)) {
            $todas = $this->tm->get_variantes_por_sucursal($id_sucursal);
            foreach ($ids_con_var as $idp) {
                if (isset($todas[$idp])) $variantes[$idp] = $todas[$idp];
            }
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'productos' => $productos,
                'variantes' => $variantes,
            ], JSON_UNESCAPED_UNICODE));
    }

    function addNewTrasladar()
    {
        $this->output->set_content_type('application/json');

        $id_sucursal_origen  = (int)$this->session->userdata('id_sucursal');
        $id_sucursal_destino = (int)$this->input->post('id_sucursal_destino');
        $comentario          = $this->security->xss_clean((string)$this->input->post('comentario'));
        $productos_raw       = $this->input->post('productos');

        if (!$id_sucursal_destino || empty($productos_raw)) {
            return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Datos incompletos']));
        }
        if ($id_sucursal_destino === $id_sucursal_origen) {
            return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'El destino no puede ser la misma sucursal de origen']));
        }

        $productos = json_decode($productos_raw, true);
        if (!is_array($productos) || empty($productos)) {
            return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Sin productos válidos']));
        }

        // Normalizar y validar pertenencia de variante
        $items = [];
        foreach ($productos as $p) {
            $id_producto = (int)($p['id_producto'] ?? 0);
            $id_variante = (int)($p['id_variante'] ?? 0);
            $cantidad    = max(1, (int)($p['cantidad'] ?? 0));
            if ($id_producto <= 0 || $cantidad <= 0) {
                return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Renglón inválido.']));
            }
            if ($id_variante > 0 && !$this->tm->variante_pertenece_producto($id_variante, $id_producto)) {
                return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Talla inválida en uno de los productos.']));
            }
            $items[] = ['id_producto' => $id_producto, 'id_variante' => $id_variante, 'cantidad' => $cantidad];
        }

        $this->db->trans_begin();

        // Insert directo para no anidar el trans_start interno del modelo
        $this->db->insert('tbl_traslado', [
            'fecha_actual'          => date('Y-m-d', now()),
            'comentario'            => $comentario,
            'id_usuario'            => $this->vendorId,
            'id_sucursal_descuento' => $id_sucursal_origen,
            'id_sucursal_aumento'   => $id_sucursal_destino,
        ]);
        $id_traslado = (int)$this->db->insert_id();

        if (!$id_traslado) {
            $this->db->trans_rollback();
            return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Error al registrar el traslado']));
        }

        // Descontar de origen ATÓMICAMENTE y registrar incremento en destino.
        foreach ($items as $it) {
            if ($it['id_variante'] > 0) {
                $aff = $this->tm->decrementar_stock_variante($it['id_variante'], $id_sucursal_origen, $it['cantidad']);
            } else {
                $aff = $this->tm->decrementar_stock_producto($it['id_producto'], $id_sucursal_origen, $it['cantidad']);
            }
            if ($aff !== 1) {
                $this->db->trans_rollback();
                return $this->output->set_output(json_encode([
                    'ok'  => false,
                    'msg' => 'Stock insuficiente en origen para el producto ID ' . $it['id_producto']
                             . ($it['id_variante'] > 0 ? ' (talla)' : ''),
                ]));
            }
            if ($it['id_variante'] > 0) {
                $this->tm->incrementar_stock_variante($it['id_variante'], $id_sucursal_destino, $it['cantidad']);
            } else {
                $this->tm->incrementar_stock_producto($it['id_producto'], $id_sucursal_destino, $it['cantidad']);
            }
        }

        // Insertar detalles en una sola query
        $detalles_batch = [];
        foreach ($items as $it) {
            $detalles_batch[] = [
                'id_traslado' => $id_traslado,
                'id_producto' => $it['id_producto'],
                'id_variante' => $it['id_variante'] > 0 ? $it['id_variante'] : null,
                'cantidad'    => $it['cantidad'],
            ];
        }
        $this->tm->addDetallesTrasladoBatch($detalles_batch);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return $this->output->set_output(json_encode(['ok' => false, 'msg' => 'Error de base de datos durante el traslado.']));
        }

        $this->db->trans_commit();
        return $this->output->set_output(json_encode(['ok' => true, 'msg' => 'Traslado registrado correctamente', 'id_traslado' => $id_traslado]));
    }

    function trasladar_lista()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText  = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count   = $this->tm->traslado_lista_Count($searchText, $id_sucursal);
            $returns = $this->paginationCompress("traslado_lista/", $count, $count);
            $data['records'] = $this->tm->traslado_lista($searchText, $id_sucursal, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Lista de traslados';
            $this->loadViews("traslado/traslado_lista", $this->global, $data, NULL);
        }
    }

    public function filterTrasladar()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = '';
        if (!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }

        $count   = $this->tm->traslado_lista_Count($searchText, $id_sucursal);
        $returns = $this->paginationCompress("traslado_lista/", $count, $count);

        $data['records'] = $this->tm->traslado_lista($searchText, $id_sucursal, $returns["page"], $returns["segment"]);
        $this->load->view('traslado/table_partial', $data);
    }

    function trasladar_lista_Recibidos()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $id_sucursal = $this->session->userdata('id_sucursal');
            $searchText  = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count   = $this->tm->traslado_lista_recibidos_Count($searchText, $id_sucursal);
            $returns = $this->paginationCompress("traslado_lista_recibidos/", $count, $count);
            $data['records'] = $this->tm->traslado_lista_recibidos($searchText, $id_sucursal, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Traslados recibidos';
            $this->loadViews("traslado/traslado_lista_recibidos", $this->global, $data, NULL);
        }
    }

    public function filterTrasladarRecibidos()
    {
        $id_sucursal = $this->session->userdata('id_sucursal');
        $searchText  = '';
        if (!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }

        $count   = $this->tm->traslado_lista_recibidos_Count($searchText, $id_sucursal);
        $returns = $this->paginationCompress("traslado_lista_recibidos/", $count, $count);

        $data['records'] = $this->tm->traslado_lista_recibidos($searchText, $id_sucursal, $returns["page"], $returns["segment"]);
        $this->load->view('traslado/table_partial_recibidos', $data);
    }

        /**
     * Vista HTML de detalle de un traslado.
     * Accesible para origen y destino del traslado.
     */
    public function detalle($id_traslado = NULL)
    {
        $id_traslado = (int)$id_traslado;
        if ($id_traslado <= 0) { show_404(); return; }

        if (!$this->hasListAccess()) {
            $this->loadThis();
            return;
        }

        $traslados = $this->tm->get_traslado($id_traslado);
        if (empty($traslados)) { show_404(); return; }

        $t = $traslados[0];
        $id_sucursal_usuario = (int)$this->session->userdata('id_sucursal');
        // Sólo origen o destino pueden ver el detalle
        if ((int)$t->id_sucursal_descuento !== $id_sucursal_usuario &&
            (int)$t->id_sucursal_aumento   !== $id_sucursal_usuario) {
            show_error('No tienes acceso a este traslado.', 403);
            return;
        }

        $data['traslado'] = $t;
        $data['detalles'] = $this->tm->get_detalle_traslado($id_traslado);
        $data['es_origen']  = ((int)$t->id_sucursal_descuento === $id_sucursal_usuario);
        $data['es_destino'] = ((int)$t->id_sucursal_aumento   === $id_sucursal_usuario);
        $data['recien_registrado'] = (string)$this->input->get('ok') === '1';

        $this->global['pageTitle'] = 'Detalle de traslado #' . $id_traslado;
        $this->loadViews("traslado/detalle", $this->global, $data, NULL);
    }

    /**
     * Genera ZPL del ticket de traslado (80mm, impresora Zebra).
     * @param int $id_traslado
     * Output: JSON {zpl: "..."} o {error: "..."}.
     */
    public function getZPL($id_traslado = NULL)
    {
        $id_traslado = (int)$id_traslado;
        $traslados = $this->tm->get_traslado($id_traslado);

        if (empty($traslados)) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Traslado no encontrado']));
        }
        $t = $traslados[0];

        $id_sucursal_usuario = (int)$this->session->userdata('id_sucursal');
        if ((int)$t->id_sucursal_descuento !== $id_sucursal_usuario &&
            (int)$t->id_sucursal_aumento   !== $id_sucursal_usuario) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Sin acceso a este traslado']));
        }

        $detalles = $this->tm->get_detalle_traslado($id_traslado);

        // Totales
        $total_unidades = 0;
        $total_lineas   = count($detalles);
        foreach ($detalles as $d) { $total_unidades += (int)$d->cantidad; }

        // ── Sanitizer ZPL (UTF-8, sin chars de control) ──
        $clean = function ($s) {
            $s = strip_tags((string)$s);
            $s = str_replace(['^', '~'], ['', ''], $s);
            if (!mb_check_encoding($s, 'UTF-8')) {
                $s = mb_convert_encoding($s, 'UTF-8', 'ISO-8859-1');
            }
            return $s;
        };

        // ── Geometría 80mm @ 203 dpi ──
        $pw      = 640;             // 80mm total
        $margin  = 40;              // ~5mm
        $inner   = $pw - 2 * $margin;
        $sep     = 3;

        // Fuentes
        $fs_tit  = 44;  $fw_tit  = (int)round($fs_tit  * 0.85);
        $fs_sec  = 26;  $fw_sec  = (int)round($fs_sec  * 0.85);  // sección
        $fs_norm = 24;  $fw_norm = (int)round($fs_norm * 0.85);
        $fs_info = 22;  $fw_info = (int)round($fs_info * 0.85);
        $fs_tot  = 32;  $fw_tot  = (int)round($fs_tot  * 0.85);
        $fs_foot = 20;  $fw_foot = (int)round($fs_foot * 0.85);

        // Datos
        $origen   = $clean($t->nombre_sucursal_descuento ?? '—');
        $destino  = $clean($t->nombre_sucursal_aumento   ?? '—');
        $usuario  = $clean($t->nombre_usuario            ?? '—');
        $fecha    = $clean(date('d/m/Y', strtotime($t->fecha_actual)));
        $coment   = $clean(trim((string)($t->comentario ?? '')));

        $body = '';
        $y    = $margin;

        // ── ENCABEZADO ──────────────────────────────────────────────────
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_tit},{$fw_tit}^FDTRASLADO^FS\n";
        $y += $fs_tit + 4;
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_norm},{$fw_norm}^FD# {$t->id_traslado}^FS\n";
        $y += $fs_norm + 6;
        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep},{$sep}^FS\n";
        $y += $sep + 8;

        // ── DATOS ──────────────────────────────────────────────────────
        // Origen / Destino con flecha
        $body .= "^FO{$margin},{$y}^A0N,{$fs_sec},{$fw_sec}^FDORIGEN^FS\n";
        $y += $fs_sec + 2;
        $body .= "^FO{$margin},{$y}^FB{$inner},2,2,L,0^A0N,{$fs_norm},{$fw_norm}^FD{$origen}^FS\n";
        $y += ($fs_norm + 3) * 2 + 2;

        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_tit},{$fw_tit}^FD->^FS\n";
        $y += $fs_tit + 4;

        $body .= "^FO{$margin},{$y}^A0N,{$fs_sec},{$fw_sec}^FDDESTINO^FS\n";
        $y += $fs_sec + 2;
        $body .= "^FO{$margin},{$y}^FB{$inner},2,2,L,0^A0N,{$fs_norm},{$fw_norm}^FD{$destino}^FS\n";
        $y += ($fs_norm + 3) * 2 + 6;

        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep},{$sep}^FS\n";
        $y += $sep + 8;

        // Fecha + responsable (dos columnas)
        $col_r = $pw - $margin - ($fw_norm * 11);
        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDFecha:^FS\n";
        $body .= "^FO{$col_r},{$y}^A0N,{$fs_norm},{$fw_norm}^FD{$fecha}^FS\n";
        $y += $fs_norm + 4;

        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDPor:^FS\n";
        $body .= "^FO{$col_r},{$y}^FB" . ($pw - $margin - $col_r) . ",1,0,L,0^A0N,{$fs_norm},{$fw_norm}^FD{$usuario}^FS\n";
        $y += $fs_norm + 6;

        if ($coment !== '') {
            $body .= "^FO{$margin},{$y}^A0N,{$fs_info},{$fw_info}^FDComentario:^FS\n";
            $y += $fs_info + 2;
            $lines = max(1, min(4, (int)ceil(mb_strlen($coment) / 50)));
            $body .= "^FO{$margin},{$y}^FB{$inner},{$lines},2,L,0^A0N,{$fs_info},{$fw_info}^FD{$coment}^FS\n";
            $y += ($fs_info + 3) * $lines + 4;
        }

        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep},{$sep}^FS\n";
        $y += $sep + 8;

        // ── TABLA PRODUCTOS ────────────────────────────────────────────
        // Columnas (suma = $inner = 560):
        // # 0..40 | Producto 45..330 (285) | Talla 335..435 (100) | Cant 440..560 (120) [derecha]
        $c1 = $margin;            // num
        $c2 = $margin + 45;       // producto
        $c3 = $margin + 330;      // talla
        $c4 = $margin + 440;      // cant
        $col_w_prod = 280;
        $col_w_cant = $inner - 440;

        $body .= "^FO{$c1},{$y}^A0N,{$fs_sec},{$fw_sec}^FD#^FS\n";
        $body .= "^FO{$c2},{$y}^A0N,{$fs_sec},{$fw_sec}^FDPRODUCTO^FS\n";
        $body .= "^FO{$c3},{$y}^A0N,{$fs_sec},{$fw_sec}^FDTALLA^FS\n";
        $body .= "^FO{$c4},{$y}^FB{$col_w_cant},1,0,R,0^A0N,{$fs_sec},{$fw_sec}^FDCANT^FS\n";
        $y += $fs_sec + 2;
        $body .= "^FO{$margin},{$y}^GB{$inner},2,2^FS\n";
        $y += 6;

        $maxChNom = (int)floor($col_w_prod / ($fw_norm * 0.6)); // chars que caben en columna producto
        $i = 0;
        foreach ($detalles as $d) {
            $i++;
            $nom    = $clean($d->nombre_producto);
            $codigo = $clean($d->codigo);
            if (mb_strlen($nom) > $maxChNom) $nom = mb_substr($nom, 0, $maxChNom - 1) . '.';
            $talla  = !empty($d->talla) ? $clean($d->talla) : '-';
            $cant   = (int)$d->cantidad;

            $body .= "^FO{$c1},{$y}^A0N,{$fs_norm},{$fw_norm}^FD{$i}^FS\n";
            $body .= "^FO{$c2},{$y}^A0N,{$fs_norm},{$fw_norm}^FD{$nom}^FS\n";
            $body .= "^FO{$c3},{$y}^A0N,{$fs_norm},{$fw_norm}^FD{$talla}^FS\n";
            $body .= "^FO{$c4},{$y}^FB{$col_w_cant},1,0,R,0^A0N,{$fs_norm},{$fw_norm}^FD{$cant}^FS\n";
            $y += $fs_norm + 2;

            // Segunda línea: código en gris (más pequeño)
            $body .= "^FO{$c2},{$y}^A0N,{$fs_info},{$fw_info}^FD{$codigo}^FS\n";
            $y += $fs_info + 4;
        }

        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep},{$sep}^FS\n";
        $y += $sep + 6;

        // ── TOTALES ────────────────────────────────────────────────────
        $body .= "^FO{$margin},{$y}^A0N,{$fs_norm},{$fw_norm}^FDLineas:^FS\n";
        $body .= "^FO{$margin},{$y}^FB{$inner},1,0,R,0^A0N,{$fs_norm},{$fw_norm}^FD{$total_lineas}^FS\n";
        $y += $fs_norm + 4;

        $body .= "^FO{$margin},{$y}^A0N,{$fs_tot},{$fw_tot}^FDTOTAL UNID:^FS\n";
        $body .= "^FO{$margin},{$y}^FB{$inner},1,0,R,0^A0N,{$fs_tot},{$fw_tot}^FD{$total_unidades}^FS\n";
        $y += $fs_tot + 8;

        $body .= "^FO{$margin},{$y}^GB{$inner},{$sep},{$sep}^FS\n";
        $y += $sep + 10;

        // ── FIRMAS ─────────────────────────────────────────────────────
        // Línea para firma de quien recibe
        $half_w = (int)(($inner - 30) / 2);
        $body .= "^FO{$margin},{$y}^GB{$half_w},2,2^FS\n";
        $body .= "^FO" . ($margin + $half_w + 30) . ",{$y}^GB{$half_w},2,2^FS\n";
        $y += 6;
        $body .= "^FO{$margin},{$y}^FB{$half_w},1,0,C,0^A0N,{$fs_foot},{$fw_foot}^FDENTREGA^FS\n";
        $body .= "^FO" . ($margin + $half_w + 30) . ",{$y}^FB{$half_w},1,0,C,0^A0N,{$fs_foot},{$fw_foot}^FDRECIBE^FS\n";
        $y += $fs_foot + 10;

        // Pie
        $impreso = $clean('Impreso: ' . date('d/m/Y H:i'));
        $body .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,{$fs_foot},{$fw_foot}^FD{$impreso}^FS\n";
        $y += $fs_foot + 20;

        // ── ZPL final ──────────────────────────────────────────────────
        $zpl = "^XA\n^PW{$pw}\n^LL{$y}\n^CI28\n^MNC\n^LH0,0\n" . $body . "^XZ";

        $json = json_encode(['zpl' => $zpl], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            $zpl_safe = preg_replace('/[^\x20-\x7E\n]/', '?', $zpl);
            $json = json_encode(['zpl' => $zpl_safe]);
        }
        return $this->output->set_content_type('application/json')->set_output($json);
    }

    public function exportToPDF($id_traslado = NULL)
    {
        $id_traslado = (int)$id_traslado;
        $traslados = $this->tm->get_traslado($id_traslado);
        $detalles  = $this->tm->get_detalle_traslado($id_traslado);

        if (empty($traslados)) { show_404(); return; }
        $t = $traslados[0];

        // Control de acceso: solo origen o destino
        $id_sucursal_usuario = (int)$this->session->userdata('id_sucursal');
        if ((int)$t->id_sucursal_descuento !== $id_sucursal_usuario &&
            (int)$t->id_sucursal_aumento   !== $id_sucursal_usuario) {
            show_error('No tienes acceso a este traslado.', 403);
            return;
        }

        // Datos de sucursal origen para encabezado (branding)
        $suc_origen = $this->db->select('nombre_sucursal, celular, direccion, ciudad, correo, ticket_logo')
            ->from('tbl_sucursal')
            ->where('id_sucursal', (int)$t->id_sucursal_descuento)
            ->get()->row();

        require_once('assets//TCPDF-main/tcpdf.php');

        $total_items   = 0;
        $total_lineas  = count($detalles);
        foreach ($detalles as $d) { $total_items += (int)$d->cantidad; }

        // PDF con header/footer profesional (clase anónima para evitar tocar TCPDF base)
        $pdf = new class('P', 'mm', 'LETTER') extends TCPDF {
            public $id_traslado_doc = '';
            public $nombre_empresa  = '';
            public $logo_path       = '';
            public function Header() {
                // Barra superior azul oscuro
                $this->SetFillColor(31, 58, 95); // #1f3a5f
                $this->Rect(0, 0, $this->getPageWidth(), 22, 'F');
                // Acento dorado
                $this->SetFillColor(201, 162, 39); // #c9a227
                $this->Rect(0, 22, $this->getPageWidth(), 1.2, 'F');

                // Logo (si existe)
                $logo_drawn = false;
                if ($this->logo_path && file_exists($this->logo_path)) {
                    try {
                        $this->Image($this->logo_path, 12, 5, 0, 12, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                        $logo_drawn = true;
                    } catch (Exception $e) { /* ignore */ }
                }

                // Nombre empresa
                $this->SetFont('helvetica', 'B', 13);
                $this->SetTextColor(255, 255, 255);
                $this->SetXY($logo_drawn ? 30 : 12, 6);
                $this->Cell(140, 6, $this->nombre_empresa, 0, 1, 'L');

                $this->SetFont('helvetica', '', 8);
                $this->SetTextColor(200, 210, 220);
                $this->SetX($logo_drawn ? 30 : 12);
                $this->Cell(140, 5, 'Documento interno · Traslado de inventario', 0, 0, 'L');

                // Título a la derecha
                $this->SetFont('helvetica', 'B', 14);
                $this->SetTextColor(255, 255, 255);
                $this->SetXY(-80, 7);
                $this->Cell(70, 6, 'TRASLADO #' . $this->id_traslado_doc, 0, 1, 'R');
                $this->SetFont('helvetica', '', 8);
                $this->SetTextColor(201, 162, 39);
                $this->SetX(-80);
                $this->Cell(70, 5, date('d/m/Y H:i'), 0, 0, 'R');

                $this->SetTextColor(0, 0, 0);
                $this->SetY(30);
            }
            public function Footer() {
                $this->SetY(-15);
                $this->SetDrawColor(207, 216, 220);
                $this->Line(12, $this->GetY(), $this->getPageWidth() - 12, $this->GetY());
                $this->SetY(-12);
                $this->SetFont('helvetica', 'I', 7);
                $this->SetTextColor(120, 135, 145);
                $this->Cell(0, 5,
                    $this->nombre_empresa . ' · Documento generado el ' . date('d/m/Y H:i') .
                    ' · Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(),
                    0, 0, 'C');
            }
        };

        $pdf->id_traslado_doc = (string)$id_traslado;
        $pdf->nombre_empresa  = $suc_origen->nombre_sucursal ?? 'POS Multisucursal';
        if (!empty($suc_origen->ticket_logo)) {
            $logo_path = FCPATH . 'uploads/logos/' . $suc_origen->ticket_logo;
            if (file_exists($logo_path)) $pdf->logo_path = $logo_path;
        }

        $pdf->SetCreator('POS Multisucursal');
        $pdf->SetTitle('Traslado #' . $id_traslado);
        $pdf->SetMargins(12, 32, 12);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->AddPage();

        $h = function($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };

        // ── ESTILOS ──────────────────────────────────────────────
        $html = '<style>
            .section-title { font-size:10px; font-weight:bold; color:#1f3a5f; letter-spacing:1px; }
            .lbl     { font-size:7.5px; color:#7b8b95; text-transform:uppercase; letter-spacing:.6px; }
            .val     { font-size:11px; font-weight:bold; color:#1f3a5f; }
            .val-sm  { font-size:10px; color:#37474f; }
            .card    { border:1px solid #e0e6e9; border-radius:3px; }
            .card-o  { background:#fdf6e3; border:1px solid #f0d68a; }
            .card-d  { background:#edf7ed; border:1px solid #a5d6a7; }
            .arrow   { font-size:20px; color:#90a4ae; font-weight:bold; }
            th       { background:#1f3a5f; color:#ffffff; font-size:9px; font-weight:bold;
                       padding:6px 5px; letter-spacing:.4px; }
            td.cell      { font-size:10px; padding:6px 5px; border-bottom:1px solid #eceff1; color:#37474f; }
            td.cell-alt  { font-size:10px; padding:6px 5px; border-bottom:1px solid #eceff1;
                           background:#f7f9fa; color:#37474f; }
            .right   { text-align:right; }
            .center  { text-align:center; }
            .totals-card  { background:#f3f6f9; border-left:4px solid #1f3a5f; }
            .totals-lbl   { font-size:10px; color:#546e7a; padding:5px 10px; }
            .totals-val   { font-size:11px; font-weight:bold; color:#1f3a5f;
                            padding:5px 10px; text-align:right; }
            .grand-lbl    { font-size:13px; font-weight:bold; color:#1f3a5f;
                            padding:6px 10px; }
            .grand-val    { font-size:16px; font-weight:bold; color:#c9a227;
                            padding:6px 10px; text-align:right; }
            .firma-line   { border-top:1.5px solid #37474f; height:1px; }
            .firma-lbl    { font-size:9px; color:#37474f; text-align:center; font-weight:bold;
                            letter-spacing:1.5px; }
            .comentario-box { background:#fffde7; border-left:3px solid #c9a227;
                              padding:8px 10px; font-size:10px; color:#37474f; }
        </style>';

        // ── ORIGEN → DESTINO ─────────────────────────────────────
        $html .= '<table cellpadding="9" cellspacing="0"><tr>
            <td width="42%" class="card card-o">
                <span class="lbl">SUCURSAL ORIGEN</span><br>
                <span class="val">' . $h($t->nombre_sucursal_descuento ?? '—') . '</span>
            </td>
            <td width="16%" class="center" style="vertical-align:middle;">
                <span class="arrow">&gt;&gt;</span>
            </td>
            <td width="42%" class="card card-d">
                <span class="lbl">SUCURSAL DESTINO</span><br>
                <span class="val">' . $h($t->nombre_sucursal_aumento ?? '—') . '</span>
            </td>
        </tr></table>';

        // ── META ─────────────────────────────────────────────────
        $html .= '<br><table cellpadding="7" cellspacing="0" border="0">
            <tr style="background:#f7f9fa;">
                <td width="25%" class="card">
                    <span class="lbl">N° TRASLADO</span><br>
                    <span class="val">#' . $id_traslado . '</span>
                </td>
                <td width="25%" class="card">
                    <span class="lbl">FECHA</span><br>
                    <span class="val-sm">" . $h(date('d/m/Y', strtotime($t->fecha_actual))) . "</span>
                </td>
                <td width="30%" class="card">
                    <span class="lbl">REALIZADO POR</span><br>
                    <span class="val-sm">' . $h($t->nombre_usuario ?? '—') . '</span>
                </td>
                <td width="20%" class="card" style="text-align:right;">
                    <span class="lbl">UNIDADES</span><br>
                    <span class="val">' . $total_items . '</span>
                </td>
            </tr>
        </table>';

        if (!empty(trim((string)$t->comentario))) {
            $html .= '<br><div class="comentario-box">
                <span class="lbl">COMENTARIO</span><br>' .
                nl2br($h($t->comentario)) .
            '</div>';
        }

        // ── TABLA DE PRODUCTOS ───────────────────────────────────
        $html .= '<br><br><div class="section-title">PRODUCTOS TRASLADADOS</div>';
        $html .= '<table cellpadding="0" cellspacing="0" border="0">';
        $html .= '<thead><tr>
            <th width="6%" align="center">#</th>
            <th width="46%" align="left">PRODUCTO</th>
            <th width="19%" align="left">CÓDIGO</th>
            <th width="14%" align="center">TALLA</th>
            <th width="15%" align="right">CANTIDAD</th>
        </tr></thead><tbody>';

        if (empty($detalles)) {
            $html .= '<tr><td class="cell center" colspan="5" style="padding:24px;color:#90a4ae;">Sin productos en este traslado.</td></tr>';
        } else {
            $i = 0;
            foreach ($detalles as $d) {
                $i++;
                $tieneVar = (int)($d->tiene_variantes ?? 0) === 1;
                $talla    = $tieneVar && !empty($d->talla) ? $h($d->talla) : '—';
                $cls      = ($i % 2 === 0) ? 'cell-alt' : 'cell';
                $html .= '<tr>
                    <td class="' . $cls . ' center">' . $i . '</td>
                    <td class="' . $cls . '">' . $h($d->nombre_producto) . '</td>
                    <td class="' . $cls . '"><font face="courier" size="-1">' . $h($d->codigo) . '</font></td>
                    <td class="' . $cls . ' center">' . $talla . '</td>
                    <td class="' . $cls . ' right"><b>' . (int)$d->cantidad . '</b></td>
                </tr>';
            }
        }
        $html .= '</tbody></table>';

        // ── TOTALES ──────────────────────────────────────────────
        $html .= '<br><table cellpadding="0" cellspacing="0">
            <tr><td width="60%"></td><td width="40%" class="totals-card">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td class="totals-lbl">Total de líneas</td>
                        <td class="totals-val">' . $total_lineas . '</td>
                    </tr>
                    <tr>
                        <td class="grand-lbl">TOTAL UNIDADES</td>
                        <td class="grand-val">' . $total_items . '</td>
                    </tr>
                </table>
            </td></tr>
        </table>';

        // ── FIRMAS ───────────────────────────────────────────────
        $html .= '<br><br><br><br>';
        $html .= '<table cellpadding="0" cellspacing="0"><tr>
            <td width="42%"><div class="firma-line"></div><div class="firma-lbl">ENTREGA</div></td>
            <td width="16%"></td>
            <td width="42%"><div class="firma-line"></div><div class="firma-lbl">RECIBE</div></td>
        </tr></table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('traslado_' . $id_traslado . '.pdf', 'I');
    }
}
