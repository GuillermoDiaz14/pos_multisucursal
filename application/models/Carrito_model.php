<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Carrito_model extends CI_Model
{
    private $ventaCashFieldsChecked = false;
    private $ventaHasCashFields = false;
    private $ventaApartadoFieldsChecked = false;
    private $ventaHasApartadoFields = false;

    private function ventaHasCashFields()
    {
        if ($this->ventaCashFieldsChecked) {
            return $this->ventaHasCashFields;
        }

        $this->ventaHasCashFieldsChecked = true;
        $this->ventaHasCashFields =
            $this->db->field_exists('monto_recibido', 'tbl_venta') &&
            $this->db->field_exists('cambio', 'tbl_venta');

        return $this->ventaHasCashFields;
    }

    private function ventaHasApartadoFields()
    {
        if ($this->ventaApartadoFieldsChecked) {
            return $this->ventaHasApartadoFields;
        }

        $this->ventaApartadoFieldsChecked = true;
        $this->ventaHasApartadoFields = $this->db->field_exists('tipo_venta', 'tbl_venta');

        return $this->ventaHasApartadoFields;
    }

    private function getVentaSelectFields($withClient = false)
    {
        $fields = array(
            'tbl_venta.id_venta as id_venta',
            'tbl_venta.impuesto as impuesto',
            'tbl_venta.descuento as descuento',
            'tbl_venta.base_imponible as base_imponible',
            'tbl_venta.fecha_venta as fecha_venta',
            'tbl_venta.total as total',
            'tbl_venta.id_cliente as id_cliente',
            'tbl_venta.saldo as saldo',
            'tbl_venta.tipo_pago as tipo_pago'
        );

        if ($withClient) {
            $fields[] = 'tbl_cliente.nombre as nombre_cliente';
            $fields[] = 'tbl_cliente.id_cliente as id_cliente';
        }

        if ($this->ventaHasCashFields()) {
            $fields[] = 'tbl_venta.monto_recibido as monto_recibido';
            $fields[] = 'tbl_venta.cambio as cambio';
        } else {
            $fields[] = '0 as monto_recibido';
            $fields[] = '0 as cambio';
        }

        if ($this->ventaHasApartadoFields()) {
            $fields[] = 'tbl_venta.tipo_venta as tipo_venta';
            $fields[] = 'tbl_venta.estado_apartado as estado_apartado';
            $fields[] = 'tbl_venta.anticipo as anticipo';
        } else {
            $fields[] = "'normal' as tipo_venta";
            $fields[] = 'NULL as estado_apartado';
            $fields[] = '0 as anticipo';
        }

        $fields[] = '(SELECT `name` FROM tbl_users WHERE tbl_users.userId = tbl_venta.id_usuario LIMIT 1) as nombre_vendedor';

        return implode(',', $fields);
    }
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
    public function get_metodos_pago_sucursal($id_sucursal) {
        $this->db->select('id_metodo_pago, nombre_metodo_pago');
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('nombre_metodo_pago', 'ASC');
        return $this->db->get('tbl_metodo_pago')->result();
    }

    public function get_saldo_cajaabierta($id_sucursal) {
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('id_caja', 'DESC');
        $query = $this->db->get('tbl_caja');
        return $query->result();
    }

    
    
    public function get_venta($id_venta) {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_cliente.id_cliente = tbl_venta.id_cliente', 'left');
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





    /**
     * Verifica si un id_metodo_pago corresponde a "efectivo".
     * El criterio es que el nombre (case-insensitive, sin espacios) sea exactamente 'efectivo'.
     */
    public function esMetodoEfectivo($id_metodo_pago) {
        if (empty($id_metodo_pago)) {
            return false;
        }
        $this->db->select('nombre_metodo_pago');
        $this->db->where('id_metodo_pago', (int)$id_metodo_pago);
        $row = $this->db->get('tbl_metodo_pago')->row();
        if (!$row) {
            return false;
        }
        return strtolower(trim($row->nombre_metodo_pago)) === 'efectivo';
    }

    public function aumentarSaldoCajasAbiertas($monto_aumento, $id_sucursal, $id_metodo_pago = null) {
        // Si se especifica un método de pago y NO es efectivo, no se toca la caja.
        // Pasar null mantiene el comportamiento legacy (afecta la caja siempre) para
        // call sites que aún no fueron migrados.
        if ($id_metodo_pago !== null && !$this->esMetodoEfectivo($id_metodo_pago)) {
            return true;
        }

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
       $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
     
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
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
  

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
       $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

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
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
    

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
       $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

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
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
    

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

    public function ventas_lista_apartado_Count($searchText, $id_sucursal)
    {
        $this->db->select('tbl_venta.id_venta');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_venta', 'apartado');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function ventas_lista_apartado($searchText, $id_sucursal)
    {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_venta', 'apartado');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('tbl_venta.id_venta', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function marcar_entregado_apartado($id_venta)
    {
        $this->db->where('id_venta', $id_venta);
        $this->db->update('tbl_venta', array('estado_apartado' => 'entregado'));
        return TRUE;
    }

    public function cancelar_apartado($id_venta)
    {
        $this->db->where('id_venta', $id_venta);
        $this->db->update('tbl_venta', array('estado_apartado' => 'cancelado'));
        return TRUE;
    }

    public function eliminar_apartado($id_venta)
    {
        $this->eliminar_detalles($id_venta);
        $this->eliminar_venta($id_venta);
        return TRUE;
    }
}
