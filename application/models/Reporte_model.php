<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Reporte_model extends CI_Model
{
    private function parseMoneySql($field)
    {
        return "CAST(REPLACE(REPLACE(REPLACE(REPLACE($field, '$', ''), ',', ''), 'MXN', ''), ' ', '') AS DECIMAL(12,2))";
    }

    private function ventaHasMetodoPagoField()
    {
        return $this->db->field_exists('id_metodo_pago', 'tbl_venta');
    }

    private function ventaHasCashFields()
    {
        return $this->db->field_exists('monto_recibido', 'tbl_venta') && $this->db->field_exists('cambio', 'tbl_venta');
    }

    private function buildDateBucket($fechaInicial, $fechaFinal)
    {
        $bucket = array();
        $inicio = new DateTime($fechaInicial);
        $fin = new DateTime($fechaFinal);
        $fin->modify('+1 day');
        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fin);

        foreach ($periodo as $fecha) {
            $key = $fecha->format('Y-m-d');
            $bucket[$key] = array(
                'fecha' => $key,
                'ventas_efectivo' => 0,
                'ingresos' => 0,
                'gastos' => 0
            );
        }

        return $bucket;
    }
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    


 

    public function hayCajasAbiertas() {
        // Consulta para verificar si existen cajas con estado "abierto"
        $this->db->where('estado', 'abierto');
        $query = $this->db->get('tbl_caja');

        if ($query->num_rows() > 0) {
            return 1; // Si hay al menos una caja abierta, devuelve 1
        } else {
            return 0; // Si no hay cajas abiertas, devuelve 0
        }
    }


    function get_ventas($id_sucursal)
    {
        $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos  
    
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->order_by('id_venta', 'DESC'); // Ajusta el nombre del campo de ID de venta según tu base de datos
    
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function get_detalles_ventas($id_sucursal)
    {
        $this->db->select('tbl_venta.*, tbl_detalle_venta.cantidad as cantidad, tbl_producto.nombre_producto as nombre_producto, tbl_producto.codigo as codigo, tbl_detalle_venta.id_producto as id_producto,tbl_producto_stock.stock');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_detalle_venta', 'tbl_venta.id_venta = tbl_detalle_venta.id_venta', 'left'); // Ajusta el campo de unión según tu estructura de base de datos  
        $this->db->join('tbl_producto', 'tbl_detalle_venta.id_producto = tbl_producto.id_producto', 'left'); // Ajusta el campo de unión según tu estructura de base de datos      
      $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'left'); // Ajusta el campo de unión según tu estructura de base de datos      

        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_sumatoriaPorDia($id_sucursal) {
        // Selecciona la fecha de venta, y la suma de los campos requeridos, agrupados por día
        $this->db->select('fecha_venta, SUM(base_imponible) as suma_base_imponible, SUM(impuesto) as suma_impuesto, SUM(descuento) as suma_descuento, SUM(total) as suma_total');
        $this->db->from('tbl_venta');
        $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
        $this->db->group_by('DATE(fecha_venta)'); // Agrupa por día

        $query = $this->db->get();
        
        $result = $query->result();
        return $result;
    }

   public function get_sumatoriaReparacionPorDia() {
    // Selecciona la fecha de venta, y la suma de los campos requeridos, agrupados por día
    $this->db->select('fecha_ingreso, SUM(costo) as suma_costo, SUM(anticipo) as suma_anticipo, SUM(costo - anticipo) as suma_total', false); 
    // Utiliza 'false' para evitar que CodeIgniter escape la resta

    $this->db->from('tbl_reparacion');
    $this->db->group_by('DATE(fecha_ingreso)'); // Agrupa por día

    $query = $this->db->get();
    
    $result = $query->result();
    return $result;
}


    function get_detalles_ventas_sumatorias($id_sucursal)
    {
        $this->db->select('p.id_producto, p.nombre_producto, p.codigo, 
            SUM(dv.cantidad) AS total_cantidad, 
            SUM(dv.cantidad * p.precio_compra) AS precio_compra_total, 
            SUM(dv.cantidad * p.precio_venta) AS precio_venta_total, 
            SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * p.precio_compra)) AS ganancias_total, DATE(v.fecha_venta) as fecha_venta');
        $this->db->from('tbl_producto p');
        $this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
        $this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner');
   $this->db->where('v.id_sucursal', $id_sucursal);
        $this->db->group_by('p.id_producto, p.nombre_producto');
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $result = $query->result();
            
            // Calcular las sumatorias totales
            $total_cantidad_total = 0;
            $precio_compra_total = 0;
            $precio_venta_total = 0;
            $ganancias_total = 0;
    
            foreach ($result as $row) {
                $total_cantidad_total += $row->total_cantidad;
                $precio_compra_total += $row->precio_compra_total;
                $precio_venta_total += $row->precio_venta_total;
                $ganancias_total += $row->ganancias_total;
            }
    
            return array('result' => $result, 'total_cantidad_total' => $total_cantidad_total, 'precio_compra_total' => $precio_compra_total, 'precio_venta_total' => $precio_venta_total, 'ganancias_total' => $ganancias_total);
        } else {
            return array('result' => array(), 'total_cantidad_total' => 0, 'precio_compra_total' => 0, 'precio_venta_total' => 0, 'ganancias_total' => 0);
        }
    }
    
    function get_detalles_ganancias_sumatorias_entre_dos_fechas($fecha_inicial,$fecha_final,$id_sucursal)
    {
        $this->db->select('p.id_producto, p.nombre_producto,p.codigo, SUM(dv.cantidad) AS total_cantidad, 
        SUM(dv.cantidad * p.precio_compra) AS precio_compra_total, 
        SUM(dv.cantidad * p.precio_venta) AS precio_venta_total, 
        SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * p.precio_compra)) AS ganancias_total,v.fecha_venta');
$this->db->from('tbl_producto p');
$this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
$this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner'); // Agregar la relación con tbl_venta

$this->db->group_by('p.id_producto, p.nombre_producto');
 // Agregar el filtro de fechas
 $this->db->where('v.fecha_venta >=', $fecha_inicial);
 $this->db->where('v.fecha_venta <=', $fecha_final);
 $this->db->where('v.id_sucursal', $id_sucursal);
$query = $this->db->get();

if ($query->num_rows() > 0) {
    $result = $query->result();
    
    // Calcular las sumatorias totales
    $total_cantidad_total = 0;
    $precio_compra_total = 0;
    $precio_venta_total = 0;
    $ganancias_total = 0;

    foreach ($result as $row) {
        $total_cantidad_total += $row->total_cantidad;
        $precio_compra_total += $row->precio_compra_total;
        $precio_venta_total += $row->precio_venta_total;
        $ganancias_total += $row->ganancias_total;
    }

    return array('result' => $result, 'total_cantidad_total' => $total_cantidad_total, 'precio_compra_total' => $precio_compra_total, 'precio_venta_total' => $precio_venta_total, 'ganancias_total' => $ganancias_total);
} else {
    return array('result' => array(), 'total_cantidad_total' => 0, 'precio_compra_total' => 0, 'precio_venta_total' => 0, 'ganancias_total' => 0);
}
    }
    function get_detalles_ganancias_sumatorias_entre_dos_fechas_Count($fecha_inicial, $fecha_final,$id_sucursal)
    {
        $this->db->select('p.id_producto, p.nombre_producto,p.codigo, SUM(dv.cantidad) AS total_cantidad, 
        SUM(dv.cantidad * p.precio_compra) AS precio_compra_total, 
        SUM(dv.cantidad * p.precio_venta) AS precio_venta_total, 
        SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * p.precio_compra)) AS ganancias_total,v.fecha_venta');
$this->db->from('tbl_producto p');
$this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
$this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner'); // Agregar la relación con tbl_venta

$this->db->group_by('p.id_producto, p.nombre_producto');
 // Agregar el filtro de fechas
 $this->db->where('v.fecha_venta >=', $fecha_inicial);
 $this->db->where('v.fecha_venta <=', $fecha_final);
 $this->db->where('v.id_sucursal', $id_sucursal); 
        $query = $this->db->get();
    
        return $query->num_rows();
    }






  










function reporte_venta_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal)
{
    $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
    $this->db->from('tbl_venta');
    $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

    // Agrega condiciones para el rango de fechas
    $this->db->where('tbl_venta.fecha_venta >=', $fecha_inicial);
    $this->db->where('tbl_venta.fecha_venta <=', $fecha_final);
    $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
    $this->db->order_by('id_venta', 'DESC');
    $query = $this->db->get();

    $result = $query->result();

    return $result;
}


function reporte_compra_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal)
{
          $this->db->select('tbl_compra.*, tbl_proveedor.id_proveedor as id_proveedor, tbl_proveedor.nombre as nombre_proveedor');
         $this->db->from('tbl_compra');
         $this->db->join('tbl_proveedor', 'tbl_compra.proveedor = tbl_proveedor.id_proveedor', 'left');

    // Agrega condiciones para el rango de fechas
    $this->db->where('tbl_compra.fecha_compra >=', $fecha_inicial);
    $this->db->where('tbl_compra.fecha_compra <=', $fecha_final);
    $this->db->where('tbl_compra.id_sucursal', $id_sucursal);
    $this->db->order_by('id_compra', 'DESC');
    $query = $this->db->get();

    $result = $query->result();

    return $result;
}


function venta_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal)
{
    $this->db->select('tbl_venta.*');
    $this->db->from('tbl_venta');
    $this->db->join('tbl_cliente', 'tbl_venta.cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

    // Agrega condiciones para el rango de fechas
    $this->db->where('tbl_venta.fecha_venta >=', $fecha_inicial);
    $this->db->where('tbl_venta.fecha_venta <=', $fecha_final);
    $this->db->where('tbl_venta.id_sucursal', $id_sucursal);
    $query = $this->db->get();

    return $query->num_rows();
}


function compra_lista_Count_entre_dos_fechas($fecha_inicial, $fecha_final,$id_sucursal)
{
       $this->db->select('tbl_compra.*, tbl_proveedor.id_proveedor as id_proveedor, tbl_proveedor.nombre as nombre_proveedor');
         $this->db->from('tbl_compra');
         $this->db->join('tbl_proveedor', 'tbl_compra.proveedor = tbl_proveedor.id_proveedor', 'left');

    // Agrega condiciones para el rango de fechas
   $this->db->where('tbl_compra.fecha_compra >=', $fecha_inicial);
    $this->db->where('tbl_compra.fecha_compra <=', $fecha_final);
    $this->db->where('tbl_compra.id_sucursal', $id_sucursal);
    $query = $this->db->get();

    return $query->num_rows();
}









function reporte_venta_por_fecha($searchText,$id_sucursal)
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
   $this->db->order_by('id_venta', 'DESC');
//        $this->db->limit($page, $segment);
   $query = $this->db->get();
   
   $result = $query->result();        
   return $result;
}

function venta_lista_Count_por_fecha($searchText,$id_sucursal)
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
   $query = $this->db->get();
   
   return $query->num_rows();
}

     function reporte_compra_por_fecha($searchText,$id_sucursal)
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

     function compra_lista_Count_por_fecha($searchText,$id_sucursal)
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



  public function get_compras($id_sucursal) {
    
        $this->db->select('tbl_compra.id_compra as id_compra,tbl_compra.fecha_compra as fecha_compra,tbl_compra.total as total,tbl_proveedor.nombre as proveedor, tbl_proveedor.id_proveedor as id_proveedor,tbl_compra.nota as nota');
        $this->db->from('tbl_compra');
        $this->db->join('tbl_proveedor', 'tbl_proveedor.id_proveedor = tbl_compra.proveedor', 'inner');
        $this->db->where('tbl_compra.id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->result();
    }

    public function get_sucursales()
    {
        return $this->db->order_by('nombre_sucursal', 'ASC')->get('tbl_sucursal')->result();
    }

    public function getSucursalNombre($id_sucursal)
    {
        $row = $this->db
            ->select('nombre_sucursal')
            ->from('tbl_sucursal')
            ->where('id_sucursal', $id_sucursal)
            ->get()
            ->row_array();

        return !empty($row['nombre_sucursal']) ? $row['nombre_sucursal'] : 'Sucursal';
    }

    public function getUsuariosPorSucursal($id_sucursal)
    {
        return $this->db
            ->select('userId, name')
            ->from('tbl_users')
            ->where('isDeleted', 0)
            ->where('id_sucursal', $id_sucursal)
            ->order_by('name', 'ASC')
            ->get()
            ->result();
    }

    public function getProveedoresPorSucursal($id_sucursal)
    {
        return $this->db
            ->select('id_proveedor, nombre')
            ->from('tbl_proveedor')
            ->where('id_sucursal', $id_sucursal)
            ->order_by('nombre', 'ASC')
            ->get()
            ->result();
    }

    public function getCategoriasProducto()
    {
        return $this->db
            ->select('id_categoria, nombre_categoria')
            ->from('tbl_categoria')
            ->order_by('nombre_categoria', 'ASC')
            ->get()
            ->result();
    }

    public function getVentasPorVendedorResumen($id_sucursal, $fechaInicial, $fechaFinal, $usuarioId = 0)
    {
        $precioCompraSql = $this->parseMoneySql('p.precio_compra');

        $this->db
            ->select("
                u.userId,
                u.name AS vendedor,
                COUNT(v.id_venta) AS tickets,
                COALESCE(SUM(v.total), 0) AS total_vendido,
                COALESCE(AVG(v.total), 0) AS ticket_promedio
            ", false)
            ->from('tbl_venta v')
            ->join('tbl_users u', 'u.userId = v.id_usuario', 'inner')
            ->where('v.id_sucursal', $id_sucursal)
            ->where('v.fecha_venta >=', $fechaInicial)
            ->where('v.fecha_venta <=', $fechaFinal);

        if ($usuarioId > 0) {
            $this->db->where('u.userId', $usuarioId);
        }

        $ventasRows = $this->db
            ->group_by('u.userId')
            ->group_by('u.name')
            ->order_by('total_vendido', 'DESC')
            ->get()
            ->result_array();

        $this->db
            ->select("
                u.userId,
                COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)), 0) AS utilidad_estimada
            ", false)
            ->from('tbl_venta v')
            ->join('tbl_users u', 'u.userId = v.id_usuario', 'inner')
            ->join('tbl_detalle_venta dv', 'dv.id_venta = v.id_venta', 'inner')
            ->join('tbl_producto p', 'p.id_producto = dv.id_producto', 'inner')
            ->where('v.id_sucursal', $id_sucursal)
            ->where('v.fecha_venta >=', $fechaInicial)
            ->where('v.fecha_venta <=', $fechaFinal);

        if ($usuarioId > 0) {
            $this->db->where('u.userId', $usuarioId);
        }

        $utilidadRows = $this->db
            ->group_by('u.userId')
            ->get()
            ->result_array();

        $utilidadMap = array();
        foreach ($utilidadRows as $utilidadRow) {
            $utilidadMap[(int) $utilidadRow['userId']] = (float) $utilidadRow['utilidad_estimada'];
        }

        $rows = array();
        foreach ($ventasRows as $ventasRow) {
            $ventasRow['utilidad_estimada'] = isset($utilidadMap[(int) $ventasRow['userId']]) ? $utilidadMap[(int) $ventasRow['userId']] : 0;
            $rows[] = $ventasRow;
        }

        $totales = array(
            'tickets' => 0,
            'total_vendido' => 0,
            'utilidad_estimada' => 0
        );

        foreach ($rows as &$row) {
            $row['tickets'] = (int) $row['tickets'];
            $row['total_vendido'] = (float) $row['total_vendido'];
            $row['ticket_promedio'] = (float) $row['ticket_promedio'];
            $row['utilidad_estimada'] = (float) $row['utilidad_estimada'];
            $totales['tickets'] += $row['tickets'];
            $totales['total_vendido'] += $row['total_vendido'];
            $totales['utilidad_estimada'] += $row['utilidad_estimada'];
        }
        unset($row);

        $totales['vendedores'] = count($rows);
        $totales['ticket_promedio'] = $totales['tickets'] > 0 ? $totales['total_vendido'] / $totales['tickets'] : 0;

        return array(
            'rows' => $rows,
            'totales' => $totales
        );
    }

    public function getComprasPorProveedorResumen($id_sucursal, $fechaInicial, $fechaFinal, $proveedorId = 0)
    {
        $this->db
            ->select("
                p.id_proveedor,
                p.nombre AS proveedor,
                COUNT(c.id_compra) AS ordenes,
                COALESCE(SUM(c.total), 0) AS total_comprado,
                COALESCE(AVG(c.total), 0) AS compra_promedio
            ", false)
            ->from('tbl_compra c')
            ->join('tbl_proveedor p', 'p.id_proveedor = c.proveedor', 'left')
            ->where('c.id_sucursal', $id_sucursal)
            ->where('c.fecha_compra >=', $fechaInicial)
            ->where('c.fecha_compra <=', $fechaFinal);

        if ($proveedorId > 0) {
            $this->db->where('p.id_proveedor', $proveedorId);
        }

        $rows = $this->db
            ->group_by('p.id_proveedor')
            ->group_by('p.nombre')
            ->order_by('total_comprado', 'DESC')
            ->get()
            ->result_array();

        $totales = array(
            'ordenes' => 0,
            'total_comprado' => 0
        );

        foreach ($rows as &$row) {
            $row['ordenes'] = (int) $row['ordenes'];
            $row['total_comprado'] = (float) $row['total_comprado'];
            $row['compra_promedio'] = (float) $row['compra_promedio'];
            $totales['ordenes'] += $row['ordenes'];
            $totales['total_comprado'] += $row['total_comprado'];
        }
        unset($row);

        $totales['proveedores'] = count($rows);
        $totales['compra_promedio'] = $totales['ordenes'] > 0 ? $totales['total_comprado'] / $totales['ordenes'] : 0;

        return array(
            'rows' => $rows,
            'totales' => $totales
        );
    }

    public function getStockActualResumen($id_sucursal, $categoriaId = 0, $producto = '')
    {
        $precioCompraSql = $this->parseMoneySql('p.precio_compra');

        $this->db
            ->select("
                ps.id_producto,
                p.nombre_producto,
                p.codigo,
                c.nombre_categoria,
                COALESCE(ps.stock, 0) AS stock,
                COALESCE(ps.stock, 0) * $precioCompraSql AS valor_inventario
            ", false)
            ->from('tbl_producto_stock ps')
            ->join('tbl_producto p', 'p.id_producto = ps.id_producto', 'inner')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->where('ps.id_sucursal', $id_sucursal);

        if ($categoriaId > 0) {
            $this->db->where('p.categoria', $categoriaId);
        }

        if ($producto !== '') {
            $this->db->group_start()
                ->like('p.nombre_producto', $producto)
                ->or_like('p.codigo', $producto)
            ->group_end();
        }

        $rows = $this->db
            ->order_by('p.nombre_producto', 'ASC')
            ->get()
            ->result_array();

        $totales = array(
            'productos' => count($rows),
            'unidades' => 0,
            'valor_inventario' => 0,
            'stock_bajo' => 0
        );

        foreach ($rows as &$row) {
            $row['stock'] = (float) $row['stock'];
            $row['valor_inventario'] = (float) $row['valor_inventario'];
            $totales['unidades'] += $row['stock'];
            $totales['valor_inventario'] += $row['valor_inventario'];
            if ($row['stock'] <= 5) {
                $totales['stock_bajo']++;
            }
        }
        unset($row);

        return array(
            'rows' => $rows,
            'totales' => $totales
        );
    }

    public function getStockBajoResumen($id_sucursal, $categoriaId = 0, $producto = '', $stockMaximo = 5)
    {
        $precioCompraSql = $this->parseMoneySql('p.precio_compra');

        $this->db
            ->select("
                ps.id_producto,
                p.nombre_producto,
                p.codigo,
                c.nombre_categoria,
                COALESCE(ps.stock, 0) AS stock,
                COALESCE(ps.stock, 0) * $precioCompraSql AS valor_inventario
            ", false)
            ->from('tbl_producto_stock ps')
            ->join('tbl_producto p', 'p.id_producto = ps.id_producto', 'inner')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->where('ps.id_sucursal', $id_sucursal)
            ->where('ps.stock <=', $stockMaximo);

        if ($categoriaId > 0) {
            $this->db->where('p.categoria', $categoriaId);
        }

        if ($producto !== '') {
            $this->db->group_start()
                ->like('p.nombre_producto', $producto)
                ->or_like('p.codigo', $producto)
            ->group_end();
        }

        $rows = $this->db
            ->order_by('ps.stock', 'ASC')
            ->order_by('p.nombre_producto', 'ASC')
            ->get()
            ->result_array();

        $totales = array(
            'productos' => count($rows),
            'unidades' => 0,
            'valor_inventario' => 0
        );

        foreach ($rows as &$row) {
            $row['stock'] = (float) $row['stock'];
            $row['valor_inventario'] = (float) $row['valor_inventario'];
            $totales['unidades'] += $row['stock'];
            $totales['valor_inventario'] += $row['valor_inventario'];
        }
        unset($row);

        return array(
            'rows' => $rows,
            'totales' => $totales
        );
    }

    public function getCajaOperativaResumen($id_sucursal, $fechaInicial, $fechaFinal)
    {
        $ventaFields = $this->ventaHasCashFields()
            ? 'COALESCE(v.monto_recibido, 0) - COALESCE(v.cambio, 0)'
            : 'COALESCE(v.total, 0)';

        $joinMetodoPago = '';
        $metodoEfectivo = '1 = 1';

        if ($this->ventaHasMetodoPagoField()) {
            $joinMetodoPago = ' LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago ';
            $metodoEfectivo = "LOWER(TRIM(COALESCE(mp.nombre_metodo_pago, ''))) = 'efectivo'";
        }

        $sql = "
            SELECT
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'contado' AND $metodoEfectivo THEN $ventaFields ELSE 0 END), 0) AS ventas_efectivo
            FROM tbl_venta v
            $joinMetodoPago
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
        ";

        $ventas = $this->db->query($sql, array($id_sucursal, $fechaInicial, $fechaFinal))->row_array();

        $ingresos = $this->db
            ->select('COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_ingreso')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->get()
            ->row_array();

        $gastos = $this->db
            ->select('COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_gasto')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->get()
            ->row_array();

        $caja = $this->db
            ->select('saldo, estado')
            ->from('tbl_caja')
            ->where('id_sucursal', $id_sucursal)
            ->order_by('id_caja', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        $ventasEfectivo = isset($ventas['ventas_efectivo']) ? (float) $ventas['ventas_efectivo'] : 0;
        $ingresosTotal = isset($ingresos['total']) ? (float) $ingresos['total'] : 0;
        $gastosTotal = isset($gastos['total']) ? (float) $gastos['total'] : 0;

        return array(
            'ventas_efectivo' => $ventasEfectivo,
            'ingresos' => $ingresosTotal,
            'gastos' => $gastosTotal,
            'saldo_caja_actual' => !empty($caja['saldo']) ? (float) $caja['saldo'] : 0,
            'estado_caja' => !empty($caja['estado']) ? ucfirst($caja['estado']) : 'Sin caja',
            'neto_operativo' => $ventasEfectivo + $ingresosTotal - $gastosTotal
        );
    }

    public function getCajaOperativaTrend($id_sucursal, $fechaInicial, $fechaFinal)
    {
        $bucket = $this->buildDateBucket($fechaInicial, $fechaFinal);

        $ventaFields = $this->ventaHasCashFields()
            ? 'COALESCE(v.monto_recibido, 0) - COALESCE(v.cambio, 0)'
            : 'COALESCE(v.total, 0)';

        $joinMetodoPago = '';
        $metodoEfectivo = '1 = 1';
        if ($this->ventaHasMetodoPagoField()) {
            $joinMetodoPago = ' LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago ';
            $metodoEfectivo = "LOWER(TRIM(COALESCE(mp.nombre_metodo_pago, ''))) = 'efectivo'";
        }

        $ventasSql = "
            SELECT
                v.fecha_venta AS fecha,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'contado' AND $metodoEfectivo THEN $ventaFields ELSE 0 END), 0) AS total
            FROM tbl_venta v
            $joinMetodoPago
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
            GROUP BY v.fecha_venta
            ORDER BY v.fecha_venta ASC
        ";
        $ventas = $this->db->query($ventasSql, array($id_sucursal, $fechaInicial, $fechaFinal))->result_array();

        foreach ($ventas as $row) {
            if (isset($bucket[$row['fecha']])) {
                $bucket[$row['fecha']]['ventas_efectivo'] = (float) $row['total'];
            }
        }

        $ingresos = $this->db
            ->select('fecha, COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_ingreso')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->group_by('fecha')
            ->order_by('fecha', 'ASC')
            ->get()
            ->result_array();

        foreach ($ingresos as $row) {
            if (isset($bucket[$row['fecha']])) {
                $bucket[$row['fecha']]['ingresos'] = (float) $row['total'];
            }
        }

        $gastos = $this->db
            ->select('fecha, COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_gasto')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->group_by('fecha')
            ->order_by('fecha', 'ASC')
            ->get()
            ->result_array();

        foreach ($gastos as $row) {
            if (isset($bucket[$row['fecha']])) {
                $bucket[$row['fecha']]['gastos'] = (float) $row['total'];
            }
        }

        return array_values($bucket);
    }

    public function getFlujoTotalResumen($id_sucursal, $fechaInicial, $fechaFinal)
    {
        $joinMetodoPago = '';
        $metodoEfectivo = '1 = 1';
        $metodoTransferencia = '0 = 1';

        if ($this->ventaHasMetodoPagoField()) {
            $joinMetodoPago = ' LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago ';
            $metodoEfectivo = "LOWER(TRIM(COALESCE(mp.nombre_metodo_pago, ''))) = 'efectivo'";
            $metodoTransferencia = "LOWER(TRIM(COALESCE(mp.nombre_metodo_pago, ''))) = 'transferencia'";
        }

        $ventasSql = "
            SELECT
                COALESCE(SUM(v.total), 0) AS ventas_total,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'contado' THEN v.total ELSE 0 END), 0) AS ventas_contado,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'credito' THEN v.total ELSE 0 END), 0) AS ventas_credito,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'apartado' THEN v.total ELSE 0 END), 0) AS ventas_apartado,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'contado' AND $metodoEfectivo THEN v.total ELSE 0 END), 0) AS cobrado_efectivo,
                COALESCE(SUM(CASE WHEN LOWER(TRIM(v.tipo_pago)) = 'contado' AND $metodoTransferencia THEN v.total ELSE 0 END), 0) AS cobrado_transferencia
            FROM tbl_venta v
            $joinMetodoPago
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
        ";
        $ventas = $this->db->query($ventasSql, array($id_sucursal, $fechaInicial, $fechaFinal))->row_array();

        $compras = $this->db
            ->select('COALESCE(SUM(total), 0) AS total', false)
            ->from('tbl_compra')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_compra >=', $fechaInicial)
            ->where('fecha_compra <=', $fechaFinal)
            ->get()
            ->row_array();

        $ingresos = $this->db
            ->select('COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_ingreso')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->get()
            ->row_array();

        $gastos = $this->db
            ->select('COALESCE(SUM(monto), 0) AS total', false)
            ->from('tbl_gasto')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha >=', $fechaInicial)
            ->where('fecha <=', $fechaFinal)
            ->get()
            ->row_array();

        $precioCompraSql = $this->parseMoneySql('p.precio_compra');
        $utilidadSql = "
            SELECT COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)), 0) AS utilidad_estimada
            FROM tbl_detalle_venta dv
            INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
            INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
        ";
        $utilidad = $this->db->query($utilidadSql, array($id_sucursal, $fechaInicial, $fechaFinal))->row_array();

        $ventasTotal = isset($ventas['ventas_total']) ? (float) $ventas['ventas_total'] : 0;
        $cobradoEfectivo = isset($ventas['cobrado_efectivo']) ? (float) $ventas['cobrado_efectivo'] : 0;
        $cobradoTransferencia = isset($ventas['cobrado_transferencia']) ? (float) $ventas['cobrado_transferencia'] : 0;
        $comprasTotal = isset($compras['total']) ? (float) $compras['total'] : 0;
        $ingresosTotal = isset($ingresos['total']) ? (float) $ingresos['total'] : 0;
        $gastosTotal = isset($gastos['total']) ? (float) $gastos['total'] : 0;

        return array(
            'ventas_total' => $ventasTotal,
            'ventas_contado' => isset($ventas['ventas_contado']) ? (float) $ventas['ventas_contado'] : 0,
            'ventas_credito' => isset($ventas['ventas_credito']) ? (float) $ventas['ventas_credito'] : 0,
            'ventas_apartado' => isset($ventas['ventas_apartado']) ? (float) $ventas['ventas_apartado'] : 0,
            'cobrado_efectivo' => $cobradoEfectivo,
            'cobrado_transferencia' => $cobradoTransferencia,
            'compras_total' => $comprasTotal,
            'ingresos' => $ingresosTotal,
            'gastos' => $gastosTotal,
            'flujo_neto' => ($cobradoEfectivo + $cobradoTransferencia + $ingresosTotal) - ($gastosTotal + $comprasTotal),
            'utilidad_estimada' => isset($utilidad['utilidad_estimada']) ? (float) $utilidad['utilidad_estimada'] : 0
        );
    }

    public function getVentasDiariasResumen($id_sucursal, $year, $month)
    {
        $fechaInicial = sprintf('%04d-%02d-01', $year, $month);
        $fechaFinal = date('Y-m-t', strtotime($fechaInicial));
        $rows = $this->db
            ->select('DATE(fecha_venta) AS fecha, COUNT(*) AS tickets, COALESCE(SUM(base_imponible), 0) AS subtotal, COALESCE(SUM(impuesto), 0) AS impuesto, COALESCE(SUM(descuento), 0) AS descuento, COALESCE(SUM(total), 0) AS total', false)
            ->from('tbl_venta')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_venta >=', $fechaInicial)
            ->where('fecha_venta <=', $fechaFinal)
            ->group_by('DATE(fecha_venta)')
            ->order_by('DATE(fecha_venta)', 'ASC')
            ->get()
            ->result_array();

        $bucket = array();
        $cursor = new DateTime($fechaInicial);
        $end = new DateTime($fechaFinal);
        while ($cursor <= $end) {
            $key = $cursor->format('Y-m-d');
            $bucket[$key] = array(
                'fecha' => $key,
                'tickets' => 0,
                'subtotal' => 0,
                'impuesto' => 0,
                'descuento' => 0,
                'total' => 0
            );
            $cursor->modify('+1 day');
        }

        $totales = array(
            'tickets' => 0,
            'subtotal' => 0,
            'impuesto' => 0,
            'descuento' => 0,
            'total' => 0,
            'ticket_promedio' => 0
        );

        foreach ($rows as $row) {
            $fecha = $row['fecha'];
            $bucket[$fecha] = array(
                'fecha' => $fecha,
                'tickets' => (int) $row['tickets'],
                'subtotal' => (float) $row['subtotal'],
                'impuesto' => (float) $row['impuesto'],
                'descuento' => (float) $row['descuento'],
                'total' => (float) $row['total']
            );
            $totales['tickets'] += (int) $row['tickets'];
            $totales['subtotal'] += (float) $row['subtotal'];
            $totales['impuesto'] += (float) $row['impuesto'];
            $totales['descuento'] += (float) $row['descuento'];
            $totales['total'] += (float) $row['total'];
        }

        $totales['ticket_promedio'] = $totales['tickets'] > 0 ? $totales['total'] / $totales['tickets'] : 0;

        return array(
            'rows' => array_values($bucket),
            'totales' => $totales,
            'fecha_inicial' => $fechaInicial,
            'fecha_final' => $fechaFinal
        );
    }

    public function getVentasPorPeriodoResumen($id_sucursal, $fechaInicial, $fechaFinal, $searchText = '')
    {
        $this->db->select('v.id_venta, DATE(v.fecha_venta) AS fecha_venta, COALESCE(c.nombre, "Público general") AS nombre_cliente, v.base_imponible, v.impuesto, v.descuento, v.total');
        $this->db->from('tbl_venta v');
        $this->db->join('tbl_cliente c', 'v.cliente = c.id_cliente', 'left');
        $this->db->where('v.id_sucursal', $id_sucursal);
        $this->db->where('v.fecha_venta >=', $fechaInicial);
        $this->db->where('v.fecha_venta <=', $fechaFinal);
        if ($searchText !== '') {
            $this->db->group_start();
            $this->db->like('c.nombre', $searchText);
            if (ctype_digit($searchText)) {
                $this->db->or_where('v.id_venta', (int) $searchText);
            }
            $this->db->group_end();
        }
        $rows = $this->db
            ->order_by('v.fecha_venta', 'DESC')
            ->order_by('v.id_venta', 'DESC')
            ->get()
            ->result_array();

        $totales = array(
            'tickets' => count($rows),
            'subtotal' => 0,
            'impuesto' => 0,
            'descuento' => 0,
            'total' => 0,
            'ticket_promedio' => 0
        );
        $trend = array();

        foreach ($rows as &$row) {
            $row['base_imponible'] = (float) $row['base_imponible'];
            $row['impuesto'] = (float) $row['impuesto'];
            $row['descuento'] = (float) $row['descuento'];
            $row['total'] = (float) $row['total'];
            $totales['subtotal'] += $row['base_imponible'];
            $totales['impuesto'] += $row['impuesto'];
            $totales['descuento'] += $row['descuento'];
            $totales['total'] += $row['total'];
            if (!isset($trend[$row['fecha_venta']])) {
                $trend[$row['fecha_venta']] = 0;
            }
            $trend[$row['fecha_venta']] += $row['total'];
        }
        unset($row);

        ksort($trend);
        $totales['ticket_promedio'] = $totales['tickets'] > 0 ? $totales['total'] / $totales['tickets'] : 0;

        return array(
            'rows' => $rows,
            'totales' => $totales,
            'trend' => $trend
        );
    }

    public function getVentasMensualesResumen($id_sucursal, $year)
    {
        $fechaInicial = $year . '-01-01';
        $fechaFinal = $year . '-12-31';
        $rows = $this->db
            ->select('MONTH(fecha_venta) AS mes, COUNT(*) AS tickets, COALESCE(SUM(base_imponible), 0) AS subtotal, COALESCE(SUM(impuesto), 0) AS impuesto, COALESCE(SUM(descuento), 0) AS descuento, COALESCE(SUM(total), 0) AS total', false)
            ->from('tbl_venta')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_venta >=', $fechaInicial)
            ->where('fecha_venta <=', $fechaFinal)
            ->group_by('MONTH(fecha_venta)')
            ->order_by('MONTH(fecha_venta)', 'ASC')
            ->get()
            ->result_array();

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = array(
                'mes' => $i,
                'tickets' => 0,
                'subtotal' => 0,
                'impuesto' => 0,
                'descuento' => 0,
                'total' => 0
            );
        }

        $totales = array('tickets' => 0, 'subtotal' => 0, 'impuesto' => 0, 'descuento' => 0, 'total' => 0);
        foreach ($rows as $row) {
            $mes = (int) $row['mes'];
            $months[$mes] = array(
                'mes' => $mes,
                'tickets' => (int) $row['tickets'],
                'subtotal' => (float) $row['subtotal'],
                'impuesto' => (float) $row['impuesto'],
                'descuento' => (float) $row['descuento'],
                'total' => (float) $row['total']
            );
            $totales['tickets'] += (int) $row['tickets'];
            $totales['subtotal'] += (float) $row['subtotal'];
            $totales['impuesto'] += (float) $row['impuesto'];
            $totales['descuento'] += (float) $row['descuento'];
            $totales['total'] += (float) $row['total'];
        }

        return array('rows' => array_values($months), 'totales' => $totales);
    }

    public function getProductosMasVendidosResumen($id_sucursal, $fechaInicial, $fechaFinal, $limit = 10)
    {
        $rows = $this->db
            ->select('p.codigo, p.nombre_producto, COALESCE(c.nombre_categoria, "Sin categoría") AS nombre_categoria, SUM(dv.cantidad) AS unidades, COALESCE(SUM(dv.cantidad * dv.precio_venta), 0) AS total_vendido', false)
            ->from('tbl_detalle_venta dv')
            ->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner')
            ->join('tbl_producto p', 'p.id_producto = dv.id_producto', 'inner')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->where('v.id_sucursal', $id_sucursal)
            ->where('v.fecha_venta >=', $fechaInicial)
            ->where('v.fecha_venta <=', $fechaFinal)
            ->group_by(array('dv.id_producto', 'p.codigo', 'p.nombre_producto', 'c.nombre_categoria'))
            ->order_by('unidades', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();

        $totales = array('productos' => count($rows), 'unidades' => 0, 'total_vendido' => 0);
        foreach ($rows as &$row) {
            $row['unidades'] = (float) $row['unidades'];
            $row['total_vendido'] = (float) $row['total_vendido'];
            $totales['unidades'] += $row['unidades'];
            $totales['total_vendido'] += $row['total_vendido'];
        }
        unset($row);

        return array('rows' => $rows, 'totales' => $totales);
    }

    public function getUtilidadEstimadaResumen($id_sucursal, $fechaInicial, $fechaFinal)
    {
        $precioCompraSql = $this->parseMoneySql('p.precio_compra');
        $rows = $this->db->query(
            "SELECT
                p.codigo,
                p.nombre_producto,
                SUM(dv.cantidad) AS cantidad,
                SUM(dv.cantidad * $precioCompraSql) AS costo_total,
                SUM(dv.cantidad * dv.precio_venta) AS venta_total,
                SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)) AS utilidad_estimada
            FROM tbl_detalle_venta dv
            INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
            INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
            GROUP BY p.id_producto, p.codigo, p.nombre_producto
            ORDER BY utilidad_estimada DESC",
            array($id_sucursal, $fechaInicial, $fechaFinal)
        )->result_array();

        $totales = array('cantidad' => 0, 'costo_total' => 0, 'venta_total' => 0, 'utilidad_estimada' => 0);
        foreach ($rows as &$row) {
            $row['cantidad'] = (float) $row['cantidad'];
            $row['costo_total'] = (float) $row['costo_total'];
            $row['venta_total'] = (float) $row['venta_total'];
            $row['utilidad_estimada'] = (float) $row['utilidad_estimada'];
            $totales['cantidad'] += $row['cantidad'];
            $totales['costo_total'] += $row['costo_total'];
            $totales['venta_total'] += $row['venta_total'];
            $totales['utilidad_estimada'] += $row['utilidad_estimada'];
        }
        unset($row);

        return array('rows' => $rows, 'totales' => $totales);
    }

    public function getComprasPorPeriodoResumen($id_sucursal, $fechaInicial, $fechaFinal, $searchText = '')
    {
        $this->db->select('cp.id_compra, DATE(cp.fecha_compra) AS fecha_compra, COALESCE(pr.nombre, "Sin proveedor") AS nombre_proveedor, cp.nota, cp.total');
        $this->db->from('tbl_compra cp');
        $this->db->join('tbl_proveedor pr', 'cp.proveedor = pr.id_proveedor', 'left');
        $this->db->where('cp.id_sucursal', $id_sucursal);
        $this->db->where('cp.fecha_compra >=', $fechaInicial);
        $this->db->where('cp.fecha_compra <=', $fechaFinal);
        if ($searchText !== '') {
            $this->db->group_start();
            $this->db->like('pr.nombre', $searchText);
            $this->db->like('cp.nota', $searchText);
            if (ctype_digit($searchText)) {
                $this->db->or_where('cp.id_compra', (int) $searchText);
            }
            $this->db->group_end();
        }
        $rows = $this->db
            ->order_by('cp.fecha_compra', 'DESC')
            ->order_by('cp.id_compra', 'DESC')
            ->get()
            ->result_array();

        $totales = array('ordenes' => count($rows), 'total' => 0, 'promedio' => 0);
        $trend = array();

        foreach ($rows as &$row) {
            $row['total'] = (float) $row['total'];
            $totales['total'] += $row['total'];
            if (!isset($trend[$row['fecha_compra']])) {
                $trend[$row['fecha_compra']] = 0;
            }
            $trend[$row['fecha_compra']] += $row['total'];
        }
        unset($row);

        ksort($trend);
        $totales['promedio'] = $totales['ordenes'] > 0 ? $totales['total'] / $totales['ordenes'] : 0;

        return array('rows' => $rows, 'totales' => $totales, 'trend' => $trend);
    }

    public function getComprasMensualesResumen($id_sucursal, $year)
    {
        $fechaInicial = $year . '-01-01';
        $fechaFinal = $year . '-12-31';
        $rows = $this->db
            ->select('MONTH(fecha_compra) AS mes, COUNT(*) AS ordenes, COALESCE(SUM(total), 0) AS total', false)
            ->from('tbl_compra')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_compra >=', $fechaInicial)
            ->where('fecha_compra <=', $fechaFinal)
            ->group_by('MONTH(fecha_compra)')
            ->order_by('MONTH(fecha_compra)', 'ASC')
            ->get()
            ->result_array();

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = array('mes' => $i, 'ordenes' => 0, 'total' => 0);
        }

        $totales = array('ordenes' => 0, 'total' => 0, 'promedio' => 0);
        foreach ($rows as $row) {
            $mes = (int) $row['mes'];
            $months[$mes] = array(
                'mes' => $mes,
                'ordenes' => (int) $row['ordenes'],
                'total' => (float) $row['total']
            );
            $totales['ordenes'] += (int) $row['ordenes'];
            $totales['total'] += (float) $row['total'];
        }

        $totales['promedio'] = $totales['ordenes'] > 0 ? $totales['total'] / $totales['ordenes'] : 0;

        return array('rows' => array_values($months), 'totales' => $totales);
    }

}
