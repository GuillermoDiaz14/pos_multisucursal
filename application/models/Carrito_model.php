<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Carrito_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    

    function addNewVenta($carritoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_venta', $carritoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    function addNewDetalleVenta($detallesInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_detalle_venta', $detallesInfo);
        
        $id_venta = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $id_venta;
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
    function editEmpleado($empleadoInfo, $empleadoId)
    {
        $this->db->where('id_empleado', $empleadoId);
        $this->db->update('tbl_empleado', $empleadoInfo);
        
        return TRUE;
    }



 

    public function eliminar_detalles($id_venta) {
        $this->db->where('id_venta', $id_venta);
        $this->db->delete('tbl_detalle_venta');
    }
   public function eliminar_venta($id_venta) {
        $this->db->where('id_venta', $id_venta);
        $this->db->delete('tbl_venta');
    }
    public function get_productos() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('id_producto, nombre_producto,precio_compra,precio_venta,stock,imagen,codigo');
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



    public function get_clientes($id_sucursal) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
        $this->db->from('tbl_cliente');
        $this->db->where('id_sucursal', $id_sucursal);
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
    public function get_saldo_cajaabierta($id_sucursal) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
        $query = $this->db->get('tbl_caja');
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        return $query->result();
    }

    
    
    public function get_venta($id_venta) {
     //  $this->db->reset_where();
        $this->db->select('tbl_venta.id_venta as id_venta,tbl_venta.impuesto as impuesto,tbl_venta.descuento as descuento,tbl_venta.base_imponible as base_imponible,tbl_venta.fecha_venta as fecha_venta,tbl_venta.total as total,tbl_venta.cliente as cliente, tbl_cliente.nombre as nombre_cliente, tbl_cliente.id_cliente as id_cliente, tbl_venta.saldo as saldo');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_cliente.id_cliente = tbl_venta.cliente', 'inner');
        $this->db->where('tbl_venta.id_venta', $id_venta);
        $query = $this->db->get();
        
        return $query->result();
    }
    public function get_cuota($id_venta) {
        //  $this->db->reset_where();
           $this->db->select('*');
           $this->db->from('tbl_cuota');

           $this->db->where('id_venta', $id_venta);
           $query = $this->db->get();
           
           return $query->result();
       }

    public function get_detalle_venta($id_venta) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('tbl_detalle_venta.precio_venta as precio_individual,tbl_detalle_venta.cantidad as cantidad,tbl_detalle_venta.sub_total as sub_total,tbl_detalle_venta.id_venta as id_venta, tbl_producto.nombre_producto, tbl_producto.id_producto');
        $this->db->from('tbl_detalle_venta');
        $this->db->join('tbl_producto', 'tbl_producto.id_producto = tbl_detalle_venta.id_producto', 'inner');
        $this->db->where('tbl_detalle_venta.id_venta', $id_venta);
        $query = $this->db->get();
        
        return $query->result();
    }



    public function hayCajasAbiertas($id_sucursal) {
        // Consulta para verificar si existen cajas con estado "abierto"
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get('tbl_caja');

        if ($query->num_rows() > 0) {
            return 1; // Si hay al menos una caja abierta, devuelve 1
        } else {
            return 0; // Si no hay cajas abiertas, devuelve 0
        }
    }





    public function aumentarSaldoCajasAbiertas($monto_aumento,$id_sucursal) {
        // Primero, obtén el saldo actual de todas las cajas abiertas
        $this->db->select('id_caja, saldo');
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get('tbl_caja');

        if ($query->num_rows() > 0) {
            // Recorre las cajas abiertas y aumenta su saldo
            foreach ($query->result() as $row) {
                $id_caja = $row->id_caja;
                $saldo_actual = $row->saldo;
                $nuevo_saldo = $saldo_actual + $monto_aumento;

                // Actualiza el saldo en la base de datos
                $data = array(
                    'saldo' => $nuevo_saldo
                );

                $this->db->where('id_caja', $id_caja);
                $this->db->update('tbl_caja', $data);
            }

            return true; // Se aumentó el saldo de al menos una caja abierta
        } else {
            return false; // No hay cajas abiertas para aumentar el saldo
        }
    }

  public function aumentarSaldoCredito($id_venta,$monto_aumento) {
        // Primero, obtén el saldo actual de todas las cajas abiertas
        $this->db->select('id_venta, saldo');
        $this->db->where('id_venta', $id_venta);
        $query = $this->db->get('tbl_venta');

        if ($query->num_rows() > 0) {
            // Recorre las cajas abiertas y aumenta su saldo
            foreach ($query->result() as $row) {
                $id_venta = $row->id_venta;
                $saldo_actual = $row->saldo;
                $nuevo_saldo = $saldo_actual + $monto_aumento;

                // Actualiza el saldo en la base de datos
                $data = array(
                    'saldo' => $nuevo_saldo
                );

                $this->db->where('id_venta', $id_venta);
                $this->db->update('tbl_venta', $data);
            }

            return true; // Se aumentó el saldo de al menos una caja abierta
        } else {
            return false; // No hay cajas abiertas para aumentar el saldo
        }
    }

    public function actualizarInventarioProducto($id_producto, $cantidad_restar,$id_sucursal) {
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






public function validarInventarioproducto($id_producto, $cantidad_restar,$id_sucursal) {
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






    function ventas_lista_Count($searchText,$id_sucursal)
    {
       $this->db->select('tbl_venta.*');
       $this->db->from('tbl_venta');
       $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
     
       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_cliente.nombre', $searchText);
           $this->db->or_like('tbl_venta.id_venta', $searchText);
           $this->db->group_end();
       }
  $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $query = $this->db->get();
       
       return $query->num_rows();
    }

    function ventas_lista($searchText,$id_sucursal)
    {
        $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
  

       if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_cliente.nombre', $searchText);
        $this->db->or_like('tbl_venta.id_venta', $searchText);
        $this->db->group_end();
       }
       $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $this->db->order_by('tbl_venta.id_venta', 'DESC');
       
       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }
    function ventas_lista_contado_Count($searchText, $id_sucursal)
    {
       $this->db->select('tbl_venta.*');
       $this->db->from('tbl_venta');
       $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_cliente.nombre', $searchText);
           $this->db->or_like('tbl_venta.id_venta', $searchText);
           $this->db->group_end();
       }
       $this->db->where('tipo_pago', 'contado');
       $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $query = $this->db->get();
       
       return $query->num_rows();
    }
    function ventas_lista_contado($searchText, $id_sucursal)
    {
        $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
    

       if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_cliente.nombre', $searchText);
        $this->db->or_like('tbl_venta.id_venta', $searchText);
        
        $this->db->group_end();
       }
       $this->db->where('tipo_pago', 'contado');
       $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $this->db->order_by('id_venta', 'DESC');

       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }
    function ventas_lista_credito_Count($searchText, $id_sucursal)
    {
       $this->db->select('tbl_venta.*');
       $this->db->from('tbl_venta');
       $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

       if (!empty($searchText)) {
           $this->db->group_start();
           $this->db->like('tbl_cliente.nombre', $searchText);
           $this->db->or_like('tbl_venta.id_venta', $searchText);
           $this->db->group_end();
       }
       $this->db->where('tipo_pago', 'credito');
       $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $query = $this->db->get();
       
       return $query->num_rows();
    }
    function ventas_lista_credito($searchText, $id_sucursal)
    {
        $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
    

       if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_cliente.nombre', $searchText);
        $this->db->or_like('tbl_venta.id_venta', $searchText);
        
        $this->db->group_end();
       }
       $this->db->where('tipo_pago', 'credito');
       $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
       $this->db->order_by('id_venta', 'DESC');

       $query = $this->db->get();
       
       $result = $query->result();        
       return $result;
    }
    public function get_metodos($id_sucursal) {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
        $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get('tbl_metodo_pago');

        return $query->result();
    }
    public function get_met() {
    
        $this->db->select('*');
        $this->db->from('tbl_metodo_pago');

        $query = $this->db->get();
        
        return $query->result();
    }


        function edit_venta($ventaInfo, $id_venta)
    {
        $this->db->where('id_venta', $id_venta);
        $this->db->update('tbl_venta', $ventaInfo);
        
        return TRUE;
    }


        function addNewcuota($cuotaInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_cuota', $cuotaInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
}