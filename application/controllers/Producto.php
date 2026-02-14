<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
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
        $this->module = 'Producto';
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
            
            $count = $this->pm->productoListingCount($searchText,$id_sucursal);

			$returns = $this->paginationCompress ( "producto_lista/", $count, $count );
            
            $data['records'] = $this->pm->productoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' Producto';
            
            $this->loadViews("producto/producto_lista", $this->global, $data, NULL);
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
            $data['sucursales'] = $this->pm->get_sucursales();
            $data['categorias'] = $this->pm->get_categorias();
            //$data['unidades'] = $this->pm->get_unidades();
            $this->global['pageTitle'] = ' Agregar nuevo producto';

            $this->loadViews("producto/add", $this->global, $data, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewProducto()
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
            
            $this->form_validation->set_rules('nombre_producto','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('precio_compra', 'precio compra', 'trim|required|numeric');
            $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required|numeric');
$this->form_validation->set_rules('stock', 'stock', 'trim|required|numeric');
            
//            $this->form_validation->set_rules('stock', 'cantidad', 'trim|required|numeric');
            $this->form_validation->set_rules('codigo','codigo','trim|required|max_length[50]');
            $this->form_validation->set_rules('id_categoria','categoria','trim|required|max_length[50]');
          
            $this->form_validation->set_rules('detalles','detalles','trim|required|max_length[200]');

            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
              //  $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
                $precio_compra = $this->security->xss_clean($this->input->post('precio_compra'));
                $precio_venta = $this->security->xss_clean($this->input->post('precio_venta'));
                $codigo = $this->security->xss_clean($this->input->post('codigo'));
             
                $categoria = $this->security->xss_clean($this->input->post('id_categoria'));
                $imagen = $this->security->xss_clean($nombre_archivo);
                $detalles = $this->security->xss_clean($nombre_archivo);
                
                $detalles = $this->security->xss_clean($this->input->post('detalles'));
                $stock = $this->security->xss_clean($this->input->post('stock'));
//                $id_sucursal_actualizar = $this->security->xss_clean($this->input->post('id_sucursal'));
$id_sucursal_actualizar = $this->session->userdata('id_sucursal');

                
                $productoInfo = array('nombre_producto'=>$nombre_producto, 'precio_compra'=>$precio_compra, 'precio_venta'=>$precio_venta, 'codigo'=>$codigo, 'categoria'=>$categoria, 'imagen'=>$nombre_archivo, 'detalles'=>$detalles);
                $id_producto = $this->pm->addNewProducto($productoInfo);
                
                if($id_producto > 0) {
                    $this->session->set_flashdata('success', 'Nuevo producto agregado satisfactoiramente');
                } else {
                    $this->session->set_flashdata('error', 'error al crear nuevo producto');
                }

$sucursales = $this->pm->get_sucursales();

if (!empty($sucursales)) {
    foreach ($sucursales as $sucursal) {
        $productoInfo = array(
            'id_producto' => $id_producto,
            'stock' => 0,
            'id_sucursal' => $sucursal->id_sucursal
        );

        $this->pm->addNewProductoStock($productoInfo);
    }
}

//actualizar stock del producto incial




                $actualizarStockInfo = array('stock'=>$stock);
                $result4 = $this->pm->actualizarStock($actualizarStockInfo, $id_producto, $id_sucursal_actualizar);
                
                if($result4 > 0) {
                    $this->session->set_flashdata('success', 'actualizado stock');
                } else {
                    $this->session->set_flashdata('error', 'error al actualizar stock');
                }






                
                redirect('producto/producto_lista');
            }
        }
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
            
            $id_sucursal = $this->session->userdata('id_sucursal');
$data['productoInfo'] = $this->pm->getProductoConStock($productoId, $id_sucursal);

            $data['categorias'] = $this->pm->get_categorias();
            //$data['unidades'] = $this->pm->get_unidades();

            $this->global['pageTitle'] = ' Editar producto';
            
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
          

            $this->global['pageTitle'] = ' Editar imagen';
            
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
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('stock','stock','trim|required|numeric');

            
            $id_producto = $this->input->post('id_producto');
            
            $this->form_validation->set_rules('nombre_producto','nombre','trim|required|max_length[200]');
            $this->form_validation->set_rules('precio_compra', 'precio compra', 'trim|required|numeric');
            $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required|numeric');
            $this->form_validation->set_rules('codigo','codigo','trim|required|max_length[200]');
            $this->form_validation->set_rules('detalles','detalles','trim|required|max_length[200]');
            $this->form_validation->set_rules('id_categoria','categoria','trim|required|max_length[200]');
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

              
             
                
                $productoInfo = array('nombre_producto'=>$nombre_producto, 'precio_compra'=>$precio_compra,  'precio_venta'=>$precio_venta, 'codigo' => $codigo, 'detalles' => $detalles, 'categoria' => $categoria);
                
                $result = $this->pm->editProducto($productoInfo, $id_producto);
                $stock = $this->security->xss_clean($this->input->post('stock'));
$id_sucursal = $this->session->userdata('id_sucursal');

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
        $this->pm->eliminar_producto($id);
        $this->pm->eliminar_producto_stock($id);
        $this->session->set_flashdata('success', 'Eliminado correctamente');
        redirect('producto/producto_lista'); // Redirige a la página de lista de productos
    }


    public function filterProductos()
{
    $id_sucursal = $this->session->userdata('id_sucursal');
    $searchText = '';
    if(!empty($this->input->post('searchText'))) {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
    }
    $data['searchText'] = $searchText;
    
    $this->load->library('pagination');
    
    $count = $this->pm->productoListingCount($searchText,$id_sucursal);

    $returns = $this->paginationCompress ( "producto_lista/", $count, $count );
    
    $data['records'] = $this->pm->productoListing($searchText,$id_sucursal, $returns["page"], $returns["segment"]);
    
    $this->global['pageTitle'] = ' Producto';

    // Cargar la vista parcial de la tabla con los resultados filtrados
    $this->load->view('producto/table_partial', $data);
}



function importar()
{
    if(!$this->hasCreateAccess())
    {
        $this->loadThis();
    }
    else
    {
       
        $this->global['pageTitle'] = ' importar producto';

        $this->loadViews("producto/importar", $this->global, NULL, NULL);
    }
}




public function importar_producto() {
    // Configuración de subida de archivo
    $config['upload_path']   = './uploads/'; 
    $config['allowed_types'] = 'csv'; 
    $config['max_size']      = 1024; 
    $config['overwrite']     = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('archivo')) {
        // Error al subir archivo
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error', 'Error al subir el archivo: ' . $error['error']);
        redirect('producto/importar');
    } else {
        // Archivo subido correctamente
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        
        // Importa productos y recibe un array con sus IDs
        $productos_ids = $this->pm->importar_productos($file_path);

        if (empty($productos_ids)) {
            $this->session->set_flashdata('error', 'No se importaron productos del CSV.');
            redirect('producto/importar');
        }

        // Traemos sucursales
        $sucursales = $this->pm->get_sucursales();

        if (empty($sucursales)) {
            $this->session->set_flashdata('error', 'No hay sucursales disponibles para asignar stock');
            redirect('producto/importar');
        }


        $this->session->set_flashdata('success', 'Productos importados y stock agregado correctamente');
        redirect('producto/importar');
    }
}


public function etiqueta()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
    } else {
        $id_sucursal = $this->session->userdata('id_sucursal');
        
        // Capturar búsqueda
        $searchText = '';
        if(!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }
        
        $data['searchText'] = $searchText;
        $data['configuracionInfo'] = $this->pm->getconfiguracionInfo($id_sucursal);
        
        // Si hay búsqueda, filtrar. Si no, traer TODOS los productos
        if (!empty($searchText)) {
            $data['productos'] = $this->pm->get_productos_filtrados($searchText, $id_sucursal);
        } else {
            $data['productos'] = $this->pm->get_productos_sin_sucursal(); // Traer TODOS
        }
        
        $this->global['pageTitle'] = 'Etiquetas';
        $this->loadViews("producto/etiqueta", $this->global, $data, NULL);
    }
}

public function etiqueta_por_categoria()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
    } else {
       // $data['records'] = $this->pm->get_productos();
       $id_sucursal = $this->session->userdata('id_sucursal');
    // Obtener los productos desde el modelo
    $data['configuracionInfo'] = $this->pm->getconfiguracionInfo($id_sucursal);
    $productos = $this->pm->get_productos();
      //$categorias = $this->pm->get_categoriasarray();
      $data['categorias'] = $this->pm->get_categorias();

    // Pasar los productos a la vista
    $data['productos'] = $productos;
    //$data['categorias'] = $categorias;
    $this->global['pageTitle'] = ' etiqueta';
       
}  



$this->loadViews("producto/etiqueta_por_categoria", $this->global,$data, NULL);
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