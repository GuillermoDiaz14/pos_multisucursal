<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Producto_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function productoListingCount($searchText,$id_sucursal)
    {
        $this->db->select('tbl_producto.*,tbl_categoria.nombre_categoria as nombre_categoria,tbl_producto_stock.stock as stock');
        $this->db->from('tbl_producto');
        $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_producto.nombre_producto', $searchText);
            $this->db->or_like('tbl_producto.codigo', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
     
     public function getProductoConStock($id_producto, $id_sucursal)
{
    $this->db->select('tbl_producto.*, tbl_producto_stock.stock');
    $this->db->from('tbl_producto');
    $this->db->join(
        'tbl_producto_stock',
        'tbl_producto.id_producto = tbl_producto_stock.id_producto',
        'left'
    );
    $this->db->where('tbl_producto.id_producto', $id_producto);
    $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
    return $this->db->get()->row();
}


    function productoListing($searchText,$id_sucursal, $page, $segment)
    {
        $this->db->select('tbl_producto.*,tbl_categoria.nombre_categoria as nombre_categoria,tbl_producto_stock.stock as stock');
       $this->db->from('tbl_producto');
       $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
       $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'left');
       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_producto.nombre_producto', $searchText);
           $this->db->or_like('tbl_producto.codigo', $searchText);
           $this->db->group_end();
       }

       $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
       $this->db->order_by('id_producto', 'DESC');

       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }

    public function get_productos_sin_sucursal() {
    $this->db->select('tbl_producto.*, tbl_categoria.nombre_categoria as nombre_categoria');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    
    // Ordenar más recientes primero
    $this->db->order_by('tbl_producto.id_producto', 'DESC');
    
    $query = $this->db->get();
    return $query->result_array(); // Devuelve array para la vista
}
public function get_productos_filtrados($searchText = '', $id_sucursal = NULL) {
    $this->db->select('tbl_producto.*, tbl_categoria.nombre_categoria as nombre_categoria');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    
    // Búsqueda
    if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_producto.nombre_producto', $searchText);
        $this->db->or_like('tbl_producto.codigo', $searchText);
        $this->db->group_end();
    }
    
    // Ordenar más recientes primero
    $this->db->order_by('tbl_producto.id_producto', 'DESC');
    
    $query = $this->db->get();
    return $query->result_array(); // Devuelve array para la vista
}
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewProducto($productoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_producto', $productoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

          function addNewProductoStock($productoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_producto_stock', $productoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getProductoInfo($productoId)
    {
        $this->db->select('tbl_producto.*,tbl_categoria.nombre_categoria as nombre_categoria');
        $this->db->from('tbl_producto');
        $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        $this->db->where('id_producto', $productoId);

        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
    function editProducto($productoInfo, $productoId)
    {
        $this->db->where('id_producto', $productoId);
        $this->db->update('tbl_producto', $productoInfo);
        
        return TRUE;
    }





    public function eliminar_producto($id) {
        $this->db->where('id_producto', $id);
        $this->db->delete('tbl_producto');
    }
    
   public function eliminar_producto_stock($id) {
        $this->db->where('id_producto', $id);
        $this->db->delete('tbl_producto_stock');
    }
    
    public function get_productos() {
        $query = $this->db->get('tbl_producto');
        return $query->result_array(); // Devuelve un array asociativo
    }


    
    public function get_categorias() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('id_categoria, nombre_categoria');
        $query = $this->db->get('tbl_categoria');

        return $query->result();
    }
 public function get_sucursales() {
    $query = $this->db->get('tbl_sucursal');
    return $query->result(); // 👈 devuelve objetos
}


    
    public function get_categoriasarray() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $query = $this->db->get('tbl_categoria');
        return $query->result_array(); // Devuelve un array asociativo
    }

public function importar_productos($file_path) {
    $csv_file = fopen($file_path, 'r');
    if ($csv_file === FALSE) {
        die('No se pudo abrir el archivo CSV');
    }

    $productos_ids = array();
    $row = 0;

    while (($line = fgetcsv($csv_file, 0, ';')) !== FALSE) {
        
        $line = array_map(function($value) {
    return mb_convert_encoding($value, 'UTF-8', 'auto');
}, $line);


        // Saltar encabezado
        if ($row == 0) {
            $row++;
            continue;
        }

        $stock = isset($line[6]) ? (int)$line[6] : 0; // 👈 TOMAMOS EL STOCK DEL CSV

        $data = array(
            'nombre_producto' => $line[0],
            'precio_compra'   => $line[1],
            'precio_venta'    => $line[2],
            'codigo'          => $line[3],
            'categoria'       => $line[4],
            'imagen'          => '',
            'detalles'        => $line[5]
        );

        $this->db->insert('tbl_producto', $data);
        $id_producto = $this->db->insert_id();

        if ($id_producto) {
            $productos_ids[] = $id_producto;

            // 🔥 INSERTAR STOCK REAL
            $this->db->insert('tbl_producto_stock', array(
                'id_producto' => $id_producto,
                'id_sucursal' => $this->session->userdata('id_sucursal'),
                'stock'       => $stock
            ));
        }

        $row++;
    }

    fclose($csv_file);
    return $productos_ids;
}



    function getconfiguracionInfo($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
    $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->row();
    }
    public function actualizarStock($data, $id_producto, $id_sucursal)
{
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    return $this->db->update('tbl_producto_stock', $data);
}

    
}