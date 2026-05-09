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

        $fields[] = 'tbl_users.name as nombre_vendedor';

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
    $this->db->select('tbl_producto.*,tbl_producto_stock.stock as stock');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_producto_stock', 'tbl_producto_stock.id_producto = tbl_producto.id_producto ', 'inner');
    $this->db->where('tbl_producto_stock.stock >', 0);
    $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
    $query = $this->db->get();
    return $query->result();
}

public function buscar_productos_pos($id_sucursal, $termino, $limit = 20)
{
    if (empty(trim($termino))) {
        return [];
    }
    $this->db->select('tbl_producto.id_producto, tbl_producto.nombre_producto, tbl_producto.codigo, tbl_producto.precio_venta, tbl_producto.imagen, tbl_producto_stock.stock');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_producto_stock', 'tbl_producto_stock.id_producto = tbl_producto.id_producto', 'inner');
    $this->db->where('tbl_producto_stock.stock >', 0);
    $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
    $this->db->group_start();
    $this->db->like('tbl_producto.nombre_producto', $termino);
    $this->db->or_like('tbl_producto.codigo', $termino);
    $this->db->group_end();
    $this->db->limit($limit);
    return $this->db->get()->result();
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
        $this->db->select('*');
        $this->db->where('id_sucursal', $id_sucursal);
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

    /**
     * Devuelve la caja abierta. Si se pasa $id_usuario filtra por cajero (multi-cajero).
     */
    public function get_saldo_cajaabierta($id_sucursal, $id_usuario = null) {
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $this->db->order_by('id_caja', 'DESC');
        $query = $this->db->get('tbl_caja');
        return $query->result();
    }

    /**
     * Devuelve el estado de la caja a la que está asociada una venta.
     * Valores posibles:
     *   - 'abierto'  : la caja sigue abierta y se puede editar/eliminar la venta.
     *   - 'cerrado'  : la caja ya fue cerrada; tocar la venta descuadraría arqueos pasados.
     *   - 'sin_caja' : la venta no tiene id_caja (legacy pre-migración 03 o columna ausente).
     *   - null       : la venta no existe.
     */
    public function getCajaEstadoPorVenta($id_venta) {
        if (!$this->db->field_exists('id_caja', 'tbl_venta')) {
            return 'sin_caja';
        }
        $this->db->select('v.id_caja, c.estado');
        $this->db->from('tbl_venta v');
        $this->db->join('tbl_caja c', 'c.id_caja = v.id_caja', 'left');
        $this->db->where('v.id_venta', (int)$id_venta);
        $row = $this->db->get()->row();
        if (!$row) {
            return null;
        }
        if (empty($row->id_caja)) {
            return 'sin_caja';
        }
        return $row->estado === 'cerrado' ? 'cerrado' : 'abierto';
    }

    /**
     * Devuelve el id_caja abierto del usuario en la sucursal, o null si no tiene.
     * Sirve para asociar movimientos (venta, gasto, ingreso, cuota) a la caja del cajero.
     */
    public function getIdCajaAbierta($id_sucursal, $id_usuario = null) {
        $this->db->select('id_caja');
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $this->db->order_by('id_caja', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get('tbl_caja')->row();
        return $row ? (int)$row->id_caja : null;
    }

    
    
    public function get_venta($id_venta) {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->select('tbl_sucursal.*');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_cliente.id_cliente = tbl_venta.id_cliente', 'left');
        $this->db->join('tbl_sucursal', 'tbl_sucursal.id_sucursal = tbl_venta.id_sucursal', 'left');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_venta.id_usuario', 'left');
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



    /**
     * Verifica si hay caja abierta. Si se pasa $id_usuario, filtra por cajero.
     */
    public function hayCajasAbiertas($id_sucursal, $id_usuario = null) {
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $query = $this->db->get('tbl_caja');

        return $query->num_rows() > 0 ? 1 : 0;
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

    /**
     * Ajusta el saldo de la caja abierta. Si se pasa $id_usuario, solo afecta la caja
     * de ese cajero (multi-cajero). Si es null, mantiene el comportamiento legacy
     * (afecta todas las cajas abiertas de la sucursal).
     *
     * @param float       $monto_aumento  Monto a sumar (negativo para restar).
     * @param int         $id_sucursal
     * @param int|null    $id_metodo_pago Si se pasa y NO es efectivo, no toca la caja.
     * @param int|null    $id_usuario     Si se pasa, solo afecta la caja de ese cajero.
     */
    public function aumentarSaldoCajasAbiertas($monto_aumento, $id_sucursal, $id_metodo_pago = null, $id_usuario = null) {
        if ($id_metodo_pago !== null && !$this->esMetodoEfectivo($id_metodo_pago)) {
            return true;
        }

        $monto = (float)$monto_aumento;
        $this->db->set('saldo', "saldo + ({$monto})", false);
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', (int)$id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $this->db->update('tbl_caja');
        return $this->db->affected_rows() > 0;
    }

    public function aumentarSaldoCredito($id_venta, $monto_aumento) {
        $monto = (float)$monto_aumento;
        $this->db->set('saldo', "saldo + ({$monto})", false);
        $this->db->where('id_venta', (int)$id_venta);
        $this->db->update('tbl_venta');
        return $this->db->affected_rows() > 0;
    }

    public function actualizarInventarioProducto($id_producto, $cantidad_restar, $id_sucursal) {
        $cantidad = (float)$cantidad_restar;
        // cantidad positiva = venta (resta stock con guard); negativa = reversión (suma stock)
        if ($cantidad > 0) {
            $this->db->where('stock >=', $cantidad);
        }
        $this->db->set('stock', "stock - ({$cantidad})", false);
        $this->db->where('id_producto', (int)$id_producto);
        $this->db->where('id_sucursal', (int)$id_sucursal);
        $this->db->update('tbl_producto_stock');
        return $this->db->affected_rows() > 0;
    }






    public function validarInventarioproducto($id_producto, $cantidad_restar, $id_sucursal) {
        $this->db->select('stock');
        $this->db->where('id_producto', (int)$id_producto);
        $this->db->where('id_sucursal', (int)$id_sucursal);
        $row = $this->db->get('tbl_producto_stock')->row();
        return $row && (float)$row->stock >= (float)$cantidad_restar;
    }






    function ventas_lista_Count($searchText, $id_sucursal, $tipo_pago = '')
    {
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        if (!empty($tipo_pago)) {
            $this->db->where('tbl_venta.tipo_pago', $tipo_pago);
        }
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    function ventas_lista($searchText, $id_sucursal, $limit = 50, $offset = 0, $tipo_pago = '')
    {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_venta.id_usuario', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        if (!empty($tipo_pago)) {
            $this->db->where('tbl_venta.tipo_pago', $tipo_pago);
        }
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('tbl_venta.id_venta', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }
    function ventas_lista_contado_Count($searchText, $id_sucursal)
    {
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_pago', 'contado');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    function ventas_lista_contado($searchText, $id_sucursal, $limit = 50, $offset = 0)
    {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_venta.id_usuario', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_pago', 'contado');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('tbl_venta.id_venta', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }
    function ventas_lista_credito_Count($searchText, $id_sucursal)
    {
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_pago', 'credito');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    function ventas_lista_credito($searchText, $id_sucursal, $limit = 50, $offset = 0)
    {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_venta.id_usuario', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_pago', 'credito');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('tbl_venta.id_venta', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
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
        $this->db->select('COUNT(*) as total', false);
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
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    public function ventas_lista_apartado($searchText, $id_sucursal, $limit = 50, $offset = 0)
    {
        $this->db->select($this->getVentaSelectFields(true));
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_venta.id_usuario', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_cliente.nombre', $searchText);
            $this->db->or_like('tbl_venta.id_venta', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_venta.tipo_venta', 'apartado');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('tbl_venta.id_venta', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
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
