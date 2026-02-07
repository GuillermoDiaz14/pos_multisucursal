<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:traslados.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Trasladar_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    

    function addNewtraslado($carritoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_traslado', $carritoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    function addNewDetalletraslado($detallesInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_detalle_traslado', $detallesInfo);
        
        $id_traslado = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $id_traslado;
    }
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getEmpleadoInfo($empleadoId)
    {
        $this->db->select('id_empleado, nombre, dni,celular,id_cat');
        $this->db->from('tbl_empleado');
        $this->db->where('id_empleado', $empleadoId);
        $this->db->where('esEliminado', 0);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
 



 


    public function get_productos() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('id_producto, nombre_producto,precio_compra,precio_traslado,stock,imagen,codigo');
        $query = $this->db->get('tbl_producto');

        return $query->result();
    }


public function get_productos_com_stock($id_sucursal) {
    // Recupera los productos de tu tabla de productos (sustituye 'tbl_producto' con el nombre correcto de tu tabla)



    $this->db->select('tbl_producto.*,tbl_producto_stock.stock as stock');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_producto_stock', 'tbl_producto_stock.id_producto = tbl_producto.id_producto ', 'inner');
    $this->db->where('tbl_producto_stock.stock >', 0);
    $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
    $query = $this->db->get();
    return $query->result();
}




  


    public function get_configuracion($id_sucursal) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
 
        $query_configuracion = $this->db->get('tbl_sucursal');
    
        $this->db->select('*');
        $this->db->where('id_sucursal', $id_sucursal);
        $query_metodo_pago = $this->db->get('tbl_metodo_pago');
    
        $result['configuracion'] = $query_configuracion->result();
        $result['metodo_pago'] = $query_metodo_pago->result();
    
        return $result;
    }

    
    
    public function get_traslado($id_traslado) {
     //  $this->db->reset_where();
        $this->db->select('tbl_traslado.*,tbl_sucursal.nombre_sucursal as nombre_sucursal_aumento');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal', 'tbl_sucursal.id_sucursal = tbl_traslado.id_sucursal_aumento', 'inner');
        $this->db->where('tbl_traslado.id_traslado', $id_traslado);
        $query = $this->db->get();
        
        return $query->result();
    }


    public function get_detalle_traslado($id_traslado) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('tbl_detalle_traslado.*, tbl_producto.nombre_producto, tbl_producto.id_producto');
        $this->db->from('tbl_detalle_traslado');
        $this->db->join('tbl_producto', 'tbl_producto.id_producto = tbl_detalle_traslado.id_producto', 'inner');
        $this->db->where('tbl_detalle_traslado.id_traslado', $id_traslado);
        $query = $this->db->get();
        
        return $query->result();
    }


















public function validarStocktrasladoproducto($id_producto, $cantidad_restar,$id_sucursal) {
    // Obtén el stock actual del producto
    $this->db->select('stock');
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    $query = $this->db->get('tbl_producto_stock');

    if ($query->num_rows() === 1) {
        // El producto existe y se encontró un registro
        $row = $query->row();
        $stock_actual = $row->stock;

        // Verifica que haya suficiente stock antes de restar
        if ($stock_actual >= $cantidad_restar) {
            return true; // Stock es mayor o igual a la cantidad
        } else {
            return false; // Stock es menor que la cantidad
        }
    } else {
        return false; // El producto no existe o se encontraron múltiples registros
    }
}






    function traslado_lista_Count($searchText,$id_sucursal)
    {
       $this->db->select('tbl_traslado.*');
       $this->db->from('tbl_traslado');
       $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_aumento = tbl_sucursal.id_sucursal', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
     
       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
           $this->db->or_like('tbl_traslado.id_traslado', $searchText);
           $this->db->group_end();
       }
  $this->db->where('tbl_traslado.id_sucursal_descuento', $id_sucursal);
       $query = $this->db->get();
       
       return $query->num_rows();
    }


    function traslado_lista_recibidos_Count($searchText,$id_sucursal)
    {
       $this->db->select('tbl_traslado.*');
       $this->db->from('tbl_traslado');
       $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_descuento= tbl_sucursal.id_sucursal', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
     
       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
           $this->db->or_like('tbl_traslado.id_traslado', $searchText);
           $this->db->group_end();
       }
  $this->db->where('tbl_traslado.id_sucursal_aumento', $id_sucursal);
       $query = $this->db->get();
       
       return $query->num_rows();
    }
   function traslado_lista_recibidos($searchText,$id_sucursal)
    {
        $this->db->select('tbl_traslado.*, tbl_sucursal.nombre_sucursal as sucursal_traslado');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_descuento = tbl_sucursal.id_sucursal', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
  

       if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
        $this->db->or_like('tbl_traslado.id_traslado', $searchText);
        $this->db->group_end();
       }
       $this->db->where('tbl_traslado.id_sucursal_aumento', $id_sucursal);
       $this->db->order_by('tbl_traslado.id_traslado', 'DESC');
       
       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }
    function traslado_lista($searchText,$id_sucursal)
    {
        $this->db->select('tbl_traslado.*, tbl_sucursal.nombre_sucursal as sucursal_traslado');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_aumento = tbl_sucursal.id_sucursal', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
  

       if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
        $this->db->or_like('tbl_traslado.id_traslado', $searchText);
        $this->db->group_end();
       }
       $this->db->where('tbl_traslado.id_sucursal_descuento', $id_sucursal);
       $this->db->order_by('tbl_traslado.id_traslado', 'DESC');
       
       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }


//que liste todas las sucursales exeppto id sucrsal

    public function get_sucursales($id_sucursal) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
          $this->db->where('id_sucursal !=', $id_sucursal);
        $query = $this->db->get('tbl_sucursal');

        return $query->result();
    }



    public function actualizarInventarioProductorestar($id_producto, $cantidad_restar,$id_sucursal) {
        // Obtén el stock actual del producto
        $this->db->select('stock');
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get('tbl_producto_stock');

        if ($query->num_rows() === 1) {
            // El producto existe y se encontró un registro
            $row = $query->row();
            $stock_actual = $row->stock;

            // Verifica que haya suficiente stock antes de restar
            if ($stock_actual >= $cantidad_restar) {
                // Calcula el nuevo stock restando la cantidad
                $nuevo_stock = $stock_actual - $cantidad_restar;

                // Actualiza el stock en la base de datos
                $data = array(
                    'stock' => $nuevo_stock
                );

                $this->db->where('id_producto', $id_producto);
                $this->db->where('id_sucursal', $id_sucursal);
                $this->db->update('tbl_producto_stock', $data);

                return true; // El stock se actualizó correctamente
            } else {
                return false; // No hay suficiente stock para restar
            }
        } else {
            return false; // El producto no existe o se encontraron múltiples registros
        }
    }


 public function actualizarInventarioproductosumar($id_producto, $cantidad, $id_sucursal)
{
    // Buscar si existe el producto en esa sucursal
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    $query = $this->db->get('tbl_producto_stock');

    // SI EXISTE → SUMAR
    if ($query->num_rows() === 1) {

        $row = $query->row();
        $stock_actual = $row->stock;

        $nuevo_stock = $stock_actual + $cantidad;

        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->update('tbl_producto_stock', [
            'stock' => $nuevo_stock
        ]);

        return true;

    } 
    // SI NO EXISTE → CREAR REGISTRO
    else {

        $data = [
            'id_producto' => $id_producto,
            'id_sucursal' => $id_sucursal,
            'stock'       => $cantidad
        ];

        $this->db->insert('tbl_producto_stock', $data);

        return true;
    }
}



public function validarInventarioproducto($id_producto, $cantidad_restar, $id_sucursal) {
    // Obtén el stock actual del producto
    $this->db->select('stock');
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    $query = $this->db->get('tbl_producto_stock');

    // Verifica si la consulta tiene al menos una fila
    if ($query->num_rows() > 0) {
        // El producto existe y se encontró un registro
        $row = $query->row();
        $stock_actual = $row->stock;

        // Verifica que haya suficiente stock antes de restar
        if ($stock_actual >= $cantidad_restar) {
            return true; // Stock es mayor o igual a la cantidad
        } else {
            return false; // Stock es menor que la cantidad
        }
    } else {
        // No se encontraron resultados, el producto no existe
        return false;
    }
}

}