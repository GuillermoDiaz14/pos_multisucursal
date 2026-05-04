<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Producto extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Producto_model', 'pm');
        $this->isLoggedIn();
        $this->module = 'Productos';
//        $this->load->library('barcode_manager');

    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('producto/producto_lista');
    }
    
    /**
     * This function is used to load the booking list
     */
    function producto_lista()
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

            $count = $this->pm->productoListingCount($searchText, $id_sucursal);
            $returns = $this->paginationCompress("producto_lista/", $count, $count);

            $data['records']   = $this->pm->productoListing($searchText, $id_sucursal, $returns["page"], $returns["segment"]);
            $data['permisos']  = $this->getProductoPermisos();
            $data['categorias'] = $this->pm->get_categorias();

            $this->global['pageTitle'] = 'Productos';
            $this->loadViews("producto/producto_lista", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add($codigo_prefill = NULL)
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $data['sucursales'] = $this->pm->get_sucursales();
            $data['categorias'] = $this->pm->get_categorias();
            $data['permisos'] = $this->getProductoPermisos();
            $data['codigo_prefill'] = $codigo_prefill ? $this->security->xss_clean(urldecode($codigo_prefill)) : '';

            $this->global['pageTitle'] = 'Agregar producto';

            $this->loadViews("producto/add", $this->global, $data, NULL);
        }
    }
    
    /**
     * Agrega nuevo producto CON soporte para generar EAN-13 automático
     */
    function addNewProducto()
    {
        $isAjax = $this->input->is_ajax_request();

        if(!$this->hasCreateAccess() || !$this->hasProductPermission('gestionar')) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'No tienes permiso para crear productos']);
                return;
            }
            $this->session->set_flashdata('error', 'No tienes permiso para crear productos');
            redirect('producto/producto_lista');
            return;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nombre_producto','nombre','trim|required|max_length[200]');
        $this->form_validation->set_rules('precio_compra', 'precio compra', 'trim|required|numeric');
        $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required|numeric');
        $this->form_validation->set_rules('stock', 'stock', 'trim|required|numeric');
        $this->form_validation->set_rules('id_categoria','categoria','trim|required|max_length[50]');
        $this->form_validation->set_rules('talla','talla','trim|max_length[50]');
        $this->form_validation->set_rules('detalles','detalles','trim|max_length[200]');

        if($this->form_validation->run() == FALSE) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => validation_errors(' ', ' | ')]);
                return;
            }
            $this->add();
        } else {
            // Obtener datos del formulario
                $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
                $precio_compra = (float)$this->security->xss_clean($this->input->post('precio_compra'));
                $precio_venta = (float)$this->security->xss_clean($this->input->post('precio_venta'));
                $categoria = (int)$this->security->xss_clean($this->input->post('id_categoria'));
                $stock = (int)$this->security->xss_clean($this->input->post('stock'));
                $id_sucursal = $this->session->userdata('id_sucursal');
                
                // Determinar EAN-13
                $tipo_codigo = $this->input->post('tipo_codigo'); // 'proveedor' o 'generar'
                
                if ($tipo_codigo === 'generar') {
                    $ean13 = $this->pm->generar_ean13_automatico();
                    if (!$ean13) {
                        echo json_encode(['success' => false, 'message' => 'No se pudo generar el código de barras (rango agotado)']);
                        return;
                    }
                    $codigo_tipo = 'GENERADO';
                } else {
                    $ean13 = $this->security->xss_clean($this->input->post('codigo_proveedor'));
                    if (empty($ean13)) {
                        echo json_encode(['success' => false, 'message' => 'Debe ingresar o escanear un código de barras']);
                        return;
                    }
                    $codigo_tipo = 'PROVEEDOR';
                }

                if ($this->pm->validar_ean13_duplicado($ean13)) {
                    echo json_encode(['success' => false, 'message' => 'Código duplicado: ' . $ean13 . ' ya existe. ¿Es un resurtimiento? Use la opción Resurtir Producto.']);
                    return;
                }

                // Procesar imagen (OPCIONAL)
                $nombre_archivo = '';

                if (!empty($_FILES['imagen']['name'])) {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('imagen')) {
                        $upload_data = $this->upload->data();
                        $nombre_archivo = $upload_data['file_name'];
                        $this->comprimir_imagen('./uploads/' . $nombre_archivo);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al subir imagen: ' . $this->upload->display_errors()]);
                        return;
                    }
                }
                
                // Procesar talla
                $talla = $this->security->xss_clean($this->input->post('talla'));
                $talla = trim($talla);
                $talla = !empty($talla) ? strtoupper($talla) : 'NA';
                
                // Procesar detalles
                $detalles = $this->security->xss_clean($this->input->post('detalles'));
                if (empty($detalles)) {
                    $detalles = 'Sin detalles';
                }
                
                // Preparar datos del producto
                $productoInfo = array(
                    'nombre_producto' => $nombre_producto,
                    'precio_compra' => $precio_compra,
                    'precio_venta' => $precio_venta,
                    'codigo' => $ean13,  // ← EAN-13 como identificador único
                    'categoria' => $categoria,
                    'talla' => $talla,
                    'imagen' => $nombre_archivo,
                    'detalles' => $detalles
                );
                
                // Insertar producto
                $id_producto = $this->pm->addNewProducto($productoInfo);

                if($id_producto > 0) {
                    // Agregar stock solo en la sucursal actual
                    $this->pm->addNewProductoStock(array(
                        'id_producto' => $id_producto,
                        'stock' => $stock,
                        'id_sucursal' => $id_sucursal
                    ));

                    $msg = ($codigo_tipo === 'GENERADO')
                        ? '✓ Producto agregado. Código generado: ' . $ean13
                        : '✓ Producto agregado. Código asignado: ' . $ean13;

                    $producto = $this->pm->getProductoInfo($id_producto);
                    echo json_encode(array(
                        'success' => true,
                        'message' => $msg,
                        'producto' => $producto
                    ));
                    return;
                } else {
                    $this->session->set_flashdata('error', 'Error al crear nuevo producto');
                    echo json_encode(array('success' => false, 'message' => 'Error al crear producto'));
                    return;
                }
        }
    }

    /**
     * Comprime imagen al máximo manteniendo calidad aceptable
     */
private function comprimir_imagen($ruta_imagen)
{
    $config['image_library'] = 'gd2';
    $config['source_image'] = $ruta_imagen;
    $config['create_thumb'] = FALSE;
    $config['maintain_ratio'] = TRUE;
    $config['quality'] = '40%'; // 🔥 Compresión máxima (40% de calidad para reducir tamaño)
    $config['width'] = 300;  // Limitar ancho máximo a 300px
    $config['height'] = 300; // Limitar alto máximo a 300px
    
    $this->load->library('image_lib', $config);
    
    if (!$this->image_lib->resize()) {
        log_message('error', 'Error comprimiendo imagen: ' . $this->image_lib->display_errors());
    }
    
    $this->image_lib->clear();
}

    function editProductoImagen()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {

            $config['upload_path'] = './uploads/'; // Ruta donde se guardarán los archivos subidos
            $config['allowed_types'] = 'jpg|jpeg|png|gif'; // Tipos de archivos permitidos
            $config['max_size'] = 2048; // Tamaño máximo en kilobytes
        
            $this->load->library('upload', $config);
        
            if ($this->upload->do_upload('imagen')) {
                // La imagen se ha subido correctamente
                $data = $this->upload->data();
                $nombre_archivo = $data['file_name'];
        
                // Aquí puedes guardar el nombre del archivo en tu base de datos
                // y realizar cualquier otra acción necesaria
        
                // Redirige a una página de éxito o realiza alguna otra acción
              //  redirect('exito');
            } else {
                // La subida de la imagen falló, muestra un mensaje de error
                $error = $this->upload->display_errors();
                echo $error;
            }


            $this->load->library('form_validation');
            
 
            $this->form_validation->set_rules('id_producto','producto','trim|required|max_length[50]');
          
 
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {

                $id_producto = $this->security->xss_clean($this->input->post('id_producto'));



                $productoInfo = array('imagen'=>$nombre_archivo);
                $result = $this->pm->editProducto($productoInfo, $id_producto);


                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Imagen actualizada satisfactoriamente');
                } else {
                    $this->session->set_flashdata('error', 'error al actualizar producto');
                }
                
                redirect('producto/producto_lista');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($productoId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($productoId == null)
            {
                redirect('producto/producto_lista');
            }

            if (!$this->hasProductPermission('gestionar')) {
                $this->session->set_flashdata('error', 'No tienes permiso para editar productos');
                redirect('producto/producto_lista');
            }

            $id_sucursal = $this->session->userdata('id_sucursal');
            $data['productoInfo'] = $this->pm->getProductoConStock($productoId, $id_sucursal);

            $data['categorias'] = $this->pm->get_categorias();
            $data['permisos'] = $this->getProductoPermisos();

            $this->global['pageTitle'] = 'Editar producto';

            $this->loadViews("producto/edit", $this->global, $data, NULL);
        }
    }
    
    function editar_imagen($productoId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($productoId == null)
            {
                redirect('producto/producto_lista');
            }
            
            $data['productoInfo'] = $this->pm->getProductoInfo($productoId);
          

            $this->global['pageTitle'] = 'Editar imagen';
            
            $this->loadViews("producto/editar_imagen", $this->global, $data, NULL);
        }
    }
    
    /**
     * This function is used to edit the user information
     */
    function editProducto()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else if (!$this->hasProductPermission('gestionar')) {
            $this->session->set_flashdata('error', 'No tienes permiso para editar productos');
            redirect('producto/producto_lista');
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('stock','stock','trim|required|numeric');

            
            $id_producto = $this->input->post('id_producto');
            
            $this->form_validation->set_rules('nombre_producto','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('precio_compra', 'precio compra', 'trim|required|numeric');
            $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required|numeric');
            $this->form_validation->set_rules('codigo','codigo','trim|required|max_length[200]');
            $this->form_validation->set_rules('detalles','detalles','trim|max_length[200]');
            $this->form_validation->set_rules('id_categoria','categoria','trim|required|max_length[200]');
            $this->form_validation->set_rules('talla','talla','trim|max_length[50]');
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id_producto);
            }
            else
            {
                $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
                $precio_compra = $this->security->xss_clean($this->input->post('precio_compra'));
                $precio_venta = $this->security->xss_clean($this->input->post('precio_venta'));
                $codigo = $this->security->xss_clean($this->input->post('codigo'));
                $detalles = $this->security->xss_clean($this->input->post('detalles'));
                $categoria = $this->security->xss_clean($this->input->post('id_categoria'));
                $talla = $this->security->xss_clean($this->input->post('talla'));
                $talla = trim($talla);
                $talla = !empty($talla) ? strtoupper($talla) : 'NA';

                // ✅ VALIDAR CÓDIGO DUPLICADO (excluyendo el producto actual)
                $codigo_existente = $this->pm->validar_codigo_duplicado_edit($codigo, $id_producto);
                if ($codigo_existente) {
                    $this->session->set_flashdata('error', 'Error: El código "' . $codigo . '" ya existe en el producto "' . $codigo_existente->nombre_producto . '". Los códigos deben ser únicos.');
                    redirect('producto/edit/' . $id_producto);
                    return;
                }

                $productoInfo = array('nombre_producto'=>$nombre_producto, 'precio_compra'=>$precio_compra,  'precio_venta'=>$precio_venta, 'codigo' => $codigo, 'detalles' => $detalles, 'categoria' => $categoria, 'talla' => $talla);
                
                $stock = $this->security->xss_clean($this->input->post('stock'));
                $id_sucursal = $this->session->userdata('id_sucursal');

                $result = $this->pm->editProducto($productoInfo, $id_producto);

                $this->pm->actualizarStock(
                    array('stock' => $stock),
                    $id_producto,
                    $id_sucursal
                );

                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Actualizado correctamente producto');
                }
                else
                {
                    $this->session->set_flashdata('error', 'actualizacion producto fallo');
                }
                
                redirect('producto/producto_lista');
            }
        }
    }





     function confirmar_eliminar_producto($id) {
        if (!$this->hasProductPermission('gestionar')) {
            $this->session->set_flashdata('error', 'No tienes permiso para eliminar productos');
            redirect('producto/producto_lista');
            return;
        }

        $this->pm->eliminar_producto($id);
        $this->pm->eliminar_producto_stock($id);
        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('producto/producto_lista'); // Redirige a la página de lista de productos
    }


    public function filterProductos()
    {
        $id_sucursal  = $this->session->userdata('id_sucursal');
        $searchText   = $this->security->xss_clean((string)$this->input->post('searchText'));
        $id_categoria = (int)$this->input->post('id_categoria');

        $records = $this->pm->productoListing($searchText, $id_sucursal, 0, 9999, $id_categoria);

        $data['records'] = $records;
        $data['permisos'] = $this->getProductoPermisos();

        // Calcular stats para devolver al JS
        $total     = count($records);
        $sin_stock = 0;
        $stock_bajo = 0;
        foreach ($records as $r) {
            $s = (int)$r->stock;
            if ($s === 0)      $sin_stock++;
            elseif ($s <= 1)   $stock_bajo++;
        }

        $html = $this->load->view('producto/table_partial', $data, TRUE);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'html'       => $html,
            'total'      => $total,
            'sin_stock'  => $sin_stock,
            'stock_bajo' => $stock_bajo,
        ]));
    }



function importar()
{
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
    }
    else
    {
        $this->global['pageTitle'] = 'Importar productos';
        $this->loadViews("producto/importar", $this->global, NULL, NULL);
    }
}

public function descargar_plantilla() {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=plantilla_productos.csv');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Agregar BOM para UTF-8 en Excel - esto asegura que los acentos se muestren correctamente
    echo "\xEF\xBB\xBF";
    
    // Datos de ejemplo con acentos
    $encabezados = array('Nombre Producto', 'Precio Compra', 'Precio Venta', 'Código', 'ID Categoría', 'Detalles', 'Stock', 'Talla');
    
    $ejemplos = array(
        array('Camiseta Polo de Algodón', '15.00', '25.00', '', '1', 'Camiseta de algodón premium', '50', 'M'),
    );
    
    // Crear contenido CSV
    $output = fopen('php://output', 'w');
    
    // Headers
    fputcsv($output, $encabezados);
    
    // Ejemplo de datos
    foreach ($ejemplos as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

public function importar_producto() {
    header('Content-Type: text/html; charset=UTF-8');
    
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
        return;
    }
    
    // Configuración de subida de archivo
    $config['upload_path']   = FCPATH . 'uploads/'; 
    $config['allowed_types'] = 'csv'; 
    $config['max_size']      = 5120; // 5 MB para inventarios grandes 
    $config['overwrite']     = TRUE;
    $config['file_name']     = 'producto_import_' . time();

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('archivo')) {
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error', 'Error al subir el archivo: ' . $error['error'] . ' - Asegúrese de que la carpeta uploads tenga permisos de escritura.');
        redirect('producto/importar');
    } else {
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        $csv_procesado = $this->input->post('csv_procesado', false);

        if (!empty($csv_procesado)) {
            file_put_contents($file_path, $csv_procesado);
        }
        
        $productos_ids = $this->pm->importar_productos($file_path);

        if (empty($productos_ids)) {
            $warnings = $this->session->flashdata('import_warnings');
            $error_msg = 'No se importaron productos del archivo CSV. ';
            if (!empty($warnings)) {
                $error_msg .= 'Detalles: ' . $warnings;
            } else {
                $error_msg .= 'Por favor verifique que el formato sea correcto, los códigos sean válidos y no estén duplicados.';
            }
            $this->session->set_flashdata('error', $error_msg);
            redirect('producto/importar');
            return;
        }

        $sucursales = $this->pm->get_sucursales();

        if (empty($sucursales)) {
            $this->session->set_flashdata('error', 'No hay sucursales disponibles para asignar stock. Por favor configure las sucursales primero.');
            redirect('producto/importar');
            return;
        }

        $mensaje = 'Se importaron correctamente ' . count($productos_ids) . ' productos.';
        $warnings = $this->session->flashdata('import_warnings');
        if (!empty($warnings)) {
            $mensaje .= ' Advertencias: ' . $warnings;
        }
        
        $this->session->set_flashdata('success', $mensaje);
        redirect('producto/importar');
    }
}


public function etiqueta()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
    } else {
        $id_sucursal = $this->session->userdata('id_sucursal');
        
        $searchText = '';
        if(!empty($this->input->get('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->get('searchText'));
        }
        
        $data['searchText'] = $searchText;
        $data['configuracionInfo'] = $this->pm->getconfiguracionInfo($id_sucursal);
        $data['categorias'] = $this->pm->get_categorias();
        $data['productos'] = $this->pm->get_productos_para_etiquetas($id_sucursal, $searchText);
        
        $this->global['pageTitle'] = 'Impresión de etiquetas';
        $this->loadViews("producto/etiqueta", $this->global, $data, NULL);
    }
}

/**
 * Formulario rápido: Agregar producto e imprimir etiqueta
 */
public function quick_add_label()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
    } else {
        $id_sucursal = $this->session->userdata('id_sucursal');

        $data['configuracionInfo'] = $this->pm->getconfiguracionInfo($id_sucursal);
        $data['categorias'] = $this->pm->get_categorias();

        $this->global['pageTitle'] = 'Agregar y Etiquetar';
        $this->loadViews("producto/quick_add_label", $this->global, $data, NULL);
    }
}

/**
 * Página de Resurtimiento de Productos
 */
public function resurtir()
{
    if(!$this->hasCreateAccess()) {
        $this->loadThis();
    } else {
        $this->global['pageTitle'] = 'Resurtir Producto';
        $this->loadViews("producto/resurtir", $this->global, array(), NULL);
    }
}

/**
 * AJAX: Buscar producto por EAN-13 y retornar stock actual
 */
public function buscar_por_codigo()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }
    
    $codigo = $this->security->xss_clean($this->input->post('codigo'));
    
    if (empty($codigo)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Código vacío')));
    }
    
    $producto = $this->pm->buscar_por_ean13($codigo);
    
    if (!$producto) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Código no encontrado')));
    }
    
    $id_sucursal = $this->session->userdata('id_sucursal');
    $stock = $this->pm->obtener_stock_sucursal($producto->id_producto, $id_sucursal);
    
    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => true,
            'producto' => $producto,
            'stock_sucursal' => $stock
        )));
}

/**
 * Procesa resurtimiento de producto
 */
public function resurtir_producto()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }
    
    $codigo = $this->security->xss_clean($this->input->post('codigo'));
    $stock_nuevo = (int)$this->security->xss_clean($this->input->post('stock_nuevo'));
    $id_sucursal = $this->session->userdata('id_sucursal');
    
    if (empty($codigo) || $stock_nuevo <= 0) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => false,
                'message' => 'Datos inválidos'
            )));
    }
    
    $producto = $this->pm->buscar_por_ean13($codigo);
    
    if (!$producto) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => false,
                'message' => 'Producto no encontrado'
            )));
    }
    
    // Obtener stock actual
    $stock_actual = $this->pm->obtener_stock_sucursal($producto->id_producto, $id_sucursal);
    $stock_total = $stock_actual + $stock_nuevo;
    
    // Actualizar stock
    $this->pm->actualizar_stock($producto->id_producto, $id_sucursal, $stock_total);
    
    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => true,
            'message' => 'Stock actualizado',
            'stock_anterior' => $stock_actual,
            'stock_nuevo' => $stock_total,
            'cantidad_agregada' => $stock_nuevo
        )));
}

/**
 * Busca producto por código o nombre (flexible)
 */
public function buscar_producto()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }

    $busqueda = $this->security->xss_clean($this->input->post('busqueda'));

    if (empty($busqueda)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Búsqueda vacía')));
    }

    $id_sucursal = $this->session->userdata('id_sucursal');

    // Si es numérico (cualquier longitud), buscar por código exacto primero
    if (is_numeric($busqueda)) {
        $producto = $this->pm->buscar_por_ean13($busqueda);
        if ($producto) {
            $stock = $this->pm->obtener_stock_sucursal($producto->id_producto, $id_sucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => true,
                    'productos' => array($producto),
                    'stock_sucursal' => $stock
                )));
        }
    }

    // Buscar por nombre (también intenta por código parcial si es numérico)
    $productos = $this->pm->buscar_por_nombre($busqueda);

    if (empty($productos)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'No encontrado')));
    }

    // Agregar stock a cada producto
    foreach ($productos as &$prod) {
        $prod->stock_sucursal = $this->pm->obtener_stock_sucursal($prod->id_producto, $id_sucursal);
    }

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => true,
            'productos' => $productos
        )));
}

/**
 * Actualiza precio de compra de un producto
 */
public function actualizar_precio_compra()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }

    if (!$this->hasProductPermission('ver_precio_compra')) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'No tienes permiso para ver o editar precios de compra')));
    }

    $id_producto = (int)$this->security->xss_clean($this->input->post('id_producto'));
    $precio_compra = (float)$this->security->xss_clean($this->input->post('precio_compra'));

    if ($id_producto <= 0 || $precio_compra < 0) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Datos inválidos')));
    }

    if ($this->pm->actualizar_precio_compra($id_producto, $precio_compra)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => true, 'message' => 'Precio actualizado')));
    }

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('success' => false, 'message' => 'Error al actualizar')));
}

/**
 * AJAX: Generar EAN-13 automático (para nueva consulta antes de insertar)
 */
public function generar_ean13_ajax()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }
    
    $ean13 = $this->pm->generar_ean13_automatico();
    
    if (!$ean13) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => false,
                'message' => 'Error: No se pudo generar código (rango agotado)'
            )));
    }
    
    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => true,
            'ean13' => $ean13
        )));
}

/**
 * AJAX: Generar varios EAN-13 únicos para completar filas vacías en importación CSV
 */
public function generar_ean13_lote_ajax()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }

    $cantidad = (int)$this->input->post('cantidad');
    $codigos_existentes = $this->input->post('codigos_existentes');

    if (!is_array($codigos_existentes)) {
        $codigos_existentes = array();
    }

    if ($cantidad <= 0) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => false,
                'message' => 'Cantidad inválida'
            )));
    }

    $codigos_generados = array();
    $reservados = array_map('strval', $codigos_existentes);

    for ($i = 0; $i < $cantidad; $i++) {
        $ean13 = $this->pm->generar_ean13_automatico_unico(array_merge($reservados, $codigos_generados));

        if ($ean13 === false) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'No se pudieron generar suficientes códigos únicos'
                )));
        }

        $codigos_generados[] = $ean13;
    }

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => true,
            'codigos' => $codigos_generados
        )));
}

/**
 * Actualiza stock de un producto en una sucursal
 */
public function actualizar_stock($id_producto, $stock, $id_sucursal)
{
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    $this->db->update('tbl_producto_stock', array('stock' => $stock));
    return true;
}


public function etiqueta_por_categoria()
{
    redirect('producto/etiqueta');
}


public function generar_etiquetas() {
    // Carga la biblioteca 'Zend Barcode'
    $this->load->library('zend');

    // Datos de ejemplo (puedes cargar datos desde tu base de datos)
    $productos = array(
        array('nombre' => 'Producto 1', 'precio' => '$10.99', 'codigo' => '123456789'),
        array('nombre' => 'Producto 2', 'precio' => '$15.99', 'codigo' => '987654321'),
        // Agrega más productos según sea necesario
    );

    // Genera las etiquetas y las almacena en una variable
    $etiquetas = array();
    foreach ($productos as $producto) {
        $barcodeOptions = array(
            'text' => $producto['codigo'],
        );

        $barcodeRenderer = new Zend\Barcode\Renderer\Html();
        $codigo_barras = new Zend\Barcode\Barcode($barcodeOptions, $barcodeRenderer);
        $codigo_barras_html = $codigo_barras->render();

        $etiqueta = array(
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'codigo_barras' => $codigo_barras_html,
        );
        $etiquetas[] = $etiqueta;
    }

    // Carga la vista con las etiquetas generadas
    $data['etiquetas'] = $etiquetas;
    $this->loadViews("producto/etiqueta", $this->global, $data, NULL);

}



}

?>
