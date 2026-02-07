<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Entrada_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    

     function addNewCompra($carritoInfo)
     {
         $this->db->trans_start();
         $this->db->insert('tbl_compra', $carritoInfo);
         
         $insert_id = $this->db->insert_id();
         
         $this->db->trans_complete();
         
         return $insert_id;
     }
     function addNewDetalleCompra($detallesInfo)
     {
         $this->db->trans_start();
         $this->db->insert('tbl_detalle_compra', $detallesInfo);
         
         $id_compra = $this->db->insert_id();
         
         $this->db->trans_complete();
         
         return $id_compra;
     }
 
 
     
 
     function editCompra($empleadoInfo, $empleadoId)
     {
         $this->db->where('id_empleado', $empleadoId);
         $this->db->update('tbl_compra', $empleadoInfo);
         
         return TRUE;
     }
 
 
 
      public function eliminar_detalles($id_compra) {
        $this->db->where('id_compra', $id_compra);
        $this->db->delete('tbl_detalle_compra');
    }
   public function eliminar_compra($id_compra) {
        $this->db->where('id_compra', $id_compra);
        $this->db->delete('tbl_compra');
    }
 
  
 
     public function get_productos($id_sucursal) {

         ////

          $this->db->select('tbl_producto.*,tbl_producto_stock.stock as stock');
        $this->db->from('tbl_producto');
        $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'inner');
        $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->result();
     }
 
 
     public function get_proveedores($id_sucursal) {
         // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
         $this->db->select('*');
         $this->db->where('id_sucursal', $id_sucursal);
         $query = $this->db->get('tbl_proveedor');
 
         return $query->result();
     }
     public function get_configuracion() {
         // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
         $this->db->select('*');
         $query = $this->db->get('tbl_configuracion');
 
         return $query->result();
     }
 
     
     
     public function get_compra($id_compra) {
    
        $this->db->select('tbl_compra.id_compra as id_compra,tbl_compra.fecha_compra as fecha_compra,tbl_compra.total as total,tbl_proveedor.nombre as proveedor, tbl_proveedor.id_proveedor as id_proveedor,tbl_compra.nota as nota');
        $this->db->from('tbl_compra');
        $this->db->join('tbl_proveedor', 'tbl_proveedor.id_proveedor = tbl_compra.proveedor', 'inner');
        $this->db->where('tbl_compra.id_compra', $id_compra);
        $query = $this->db->get();
        
        return $query->result();
    }


    public function get_detalle_compra($id_compra) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('tbl_detalle_compra.precio_compra as precio_individual,tbl_detalle_compra.cantidad as cantidad,tbl_detalle_compra.sub_total as sub_total,tbl_detalle_compra.id_compra as id_compra, tbl_producto.nombre_producto, tbl_producto.id_producto');
        $this->db->from('tbl_detalle_compra');
        $this->db->join('tbl_producto', 'tbl_producto.id_producto = tbl_detalle_compra.id_producto', 'inner');
        $this->db->where('tbl_detalle_compra.id_compra', $id_compra);
        $query = $this->db->get();
        
        return $query->result();
    }
 
 
 
  
       function edit_compra($compraInfo, $id_compra)
    {
        $this->db->where('id_compra', $id_compra);
        $this->db->update('tbl_compra', $compraInfo);
        
        return TRUE;
    }
 
 
 
 
 
 
 
     public function actualizarInventarioProducto($id_producto, $cantidad_sumar,$id_sucursal) {
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
            
                 // Calcula el nuevo stock restando la cantidad
                 $nuevo_stock = $stock_actual + $cantidad_sumar;
 
                 // Actualiza el stock en la base de datos
                 $data = array(
                     'stock' => $nuevo_stock
                 );
 
                 $this->db->where('id_producto', $id_producto);
                 $this->db->where('id_sucursal', $id_sucursal);
                 $this->db->update('tbl_producto_stock', $data);
 
                 return true; // El stock se actualizó correctamente
          
         } else {
             return false; // El producto no existe o se encontraron múltiples registros
         }
     }
 
 
 
     function compras_lista_Count($searchText,$id_sucursal)
     {
        $this->db->select('tbl_compra.*');
        $this->db->from('tbl_compra');
        $this->db->join('tbl_proveedor', 'tbl_compra.proveedor = tbl_proveedor.id_proveedor', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
      
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_proveedor.nombre', $searchText);
            $this->db->or_like('tbl_compra.id_compra', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_compra.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->num_rows();
     }
 
     function compras_lista($searchText,$id_sucursal)
     {
         $this->db->select('tbl_compra.*, tbl_proveedor.id_proveedor as id_proveedor, tbl_proveedor.nombre as nombre_proveedor');
         $this->db->from('tbl_compra');
         $this->db->join('tbl_proveedor', 'tbl_compra.proveedor = tbl_proveedor.id_proveedor', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
   
 
        if (!empty($searchText)) {
         $this->db->group_start();
         $this->db->like('tbl_proveedor.nombre', $searchText);
         $this->db->or_like('tbl_compra.id_compra', $searchText);
         $this->db->group_end();
        }
        $this->db->where('tbl_compra.id_sucursal', $id_sucursal);
        $this->db->order_by('id_compra', 'DESC');
        
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
     }
 
}