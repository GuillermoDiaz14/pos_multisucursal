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
        $this->db->select('tbl_producto.*, COALESCE(ps.stock, 0) AS stock');
        $this->db->from('tbl_producto');
        $this->db->join(
            'tbl_producto_stock ps',
            'ps.id_producto = tbl_producto.id_producto AND ps.id_sucursal = ' . (int)$id_sucursal,
            'left'
        );
        $this->db->order_by('tbl_producto.nombre_producto', 'ASC');
        return $this->db->get()->result();
     }

     /**
      * Variantes activas con stock por sucursal para todos los productos con tiene_variantes=1.
      * Usa una sola query para evitar N+1.
      * @return array<int,array<object>> indexado por id_producto
      */
     public function get_variantes_por_sucursal($id_sucursal) {
        $sql = "SELECT v.id_producto, v.id_variante, v.talla, v.orden,
                       COALESCE(v.precio_compra, p.precio_compra) AS precio_compra,
                       COALESCE(sv.stock, 0) AS stock
                FROM tbl_producto_variante v
                INNER JOIN tbl_producto p ON p.id_producto = v.id_producto
                LEFT JOIN tbl_stock_variante sv
                       ON sv.id_variante = v.id_variante AND sv.id_sucursal = ?
                WHERE v.activo = 1 AND p.tiene_variantes = 1
                ORDER BY v.id_producto, v.orden, v.id_variante";
        $rows = $this->db->query($sql, [(int)$id_sucursal])->result();
        $by_prod = [];
        foreach ($rows as $r) {
            $by_prod[(int)$r->id_producto][] = $r;
        }
        return $by_prod;
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
 
 
 
 
 
 
 
     public function actualizarInventarioProducto($id_producto, $cantidad_sumar, $id_sucursal) {
         // Upsert atómico: si la fila no existe, la crea; si existe, suma.
         $sql = "INSERT INTO tbl_producto_stock (id_producto, id_sucursal, stock)
                 VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE stock = stock + VALUES(stock)";
         return $this->db->query($sql, [(int)$id_producto, (int)$id_sucursal, (int)$cantidad_sumar]);
     }

     /**
      * Suma stock a una variante en una sucursal (upsert atómico).
      */
     public function actualizarInventarioVariante($id_variante, $cantidad_sumar, $id_sucursal) {
         $sql = "INSERT INTO tbl_stock_variante (id_variante, id_sucursal, stock)
                 VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE stock = stock + VALUES(stock)";
         return $this->db->query($sql, [(int)$id_variante, (int)$id_sucursal, (int)$cantidad_sumar]);
     }

     /**
      * Inserta múltiples detalles de compra en una sola query (escalable).
      */
     public function addDetallesCompraBatch(array $detalles) {
         if (empty($detalles)) return true;
         return $this->db->insert_batch('tbl_detalle_compra', $detalles);
     }

     /**
      * Verifica que un id_variante pertenezca a un id_producto y esté activo.
      * @return bool
      */
     public function variante_pertenece_producto($id_variante, $id_producto) {
         $row = $this->db
             ->select('id_variante')
             ->from('tbl_producto_variante')
             ->where('id_variante', (int)$id_variante)
             ->where('id_producto', (int)$id_producto)
             ->where('activo', 1)
             ->get()->row();
         return (bool)$row;
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