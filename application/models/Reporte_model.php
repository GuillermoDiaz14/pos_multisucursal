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
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos  
    
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
      $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto AND tbl_producto_stock.id_sucursal = tbl_venta.id_sucursal', 'left'); // Ajusta el campo de unión según tu estructura de base de datos      

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
            SUM(dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra)) AS precio_compra_total,
            SUM(dv.cantidad * p.precio_venta) AS precio_venta_total,
            SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra))) AS ganancias_total, DATE(v.fecha_venta) as fecha_venta');
        $this->db->from('tbl_producto p');
        $this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
        $this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner');
        $this->db->join('tbl_producto_variante pv', 'pv.id_variante = dv.id_variante', 'left');
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
        SUM(dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra)) AS precio_compra_total,
        SUM(dv.cantidad * p.precio_venta) AS precio_venta_total,
        SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra))) AS ganancias_total,v.fecha_venta');
$this->db->from('tbl_producto p');
$this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
$this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner');
$this->db->join('tbl_producto_variante pv', 'pv.id_variante = dv.id_variante', 'left');

$this->db->group_by('p.id_producto, p.nombre_producto');
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
        SUM(dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra)) AS precio_compra_total,
        SUM(dv.cantidad * p.precio_venta) AS precio_venta_total,
        SUM((dv.cantidad * p.precio_venta) - (dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra))) AS ganancias_total,v.fecha_venta');
$this->db->from('tbl_producto p');
$this->db->join('tbl_detalle_venta dv', 'p.id_producto = dv.id_producto', 'inner');
$this->db->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner');
$this->db->join('tbl_producto_variante pv', 'pv.id_variante = dv.id_variante', 'left');

$this->db->group_by('p.id_producto, p.nombre_producto');
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
    $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

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
    $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

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
    $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos

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

    public function getSubcategoriasProducto($categoriaId = 0)
    {
        $this->db->select('id_subcategoria, id_categoria, nombre_subcategoria')
                 ->from('tbl_subcategoria')
                 ->where('activa', 1);
        if ((int) $categoriaId > 0) {
            $this->db->where('id_categoria', (int) $categoriaId);
        }
        return $this->db->order_by('nombre_subcategoria', 'ASC')->get()->result();
    }

    public function getTemporadasProducto()
    {
        return $this->db
            ->select('id_temporada, nombre_temporada')
            ->from('tbl_temporada')
            ->where('activa', 1)
            ->order_by('nombre_temporada', 'ASC')
            ->get()
            ->result();
    }

    public function getColoresProducto()
    {
        return $this->db
            ->select('id_color, nombre_color, codigo_hex')
            ->from('tbl_color')
            ->where('activo', 1)
            ->order_by('nombre_color', 'ASC')
            ->get()
            ->result();
    }

    public function getGenerosProducto()
    {
        // Distintos géneros presentes en productos (campo varchar)
        $rows = $this->db
            ->select("DISTINCT genero", false)
            ->from('tbl_producto')
            ->where("genero IS NOT NULL", null, false)
            ->where("genero <> ''", null, false)
            ->where("genero <> 'NA'", null, false)
            ->order_by('genero', 'ASC')
            ->get()
            ->result();
        return $rows;
    }

    public function getVentasPorVendedorResumen($id_sucursal, $fechaInicial, $fechaFinal, $usuarioId = 0)
    {
        $precioCompraSql = $this->parseMoneySql('COALESCE(pv.precio_compra, p.precio_compra)');

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
            ->join('tbl_producto_variante pv', 'pv.id_variante = dv.id_variante', 'left')
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

    public function getStockActualResumen($id_sucursal, $categoriaId = 0, $producto = '', $filtros = array())
    {
        $pcProd = $this->parseMoneySql('p.precio_compra');
        $pcVar  = $this->parseMoneySql('COALESCE(pv.precio_compra, p.precio_compra)');

        $subcategoriaId = (int) ($filtros['subcategoria_id'] ?? 0);
        $temporadaId    = (int) ($filtros['temporada_id']    ?? 0);
        $colorId        = (int) ($filtros['color_id']        ?? 0);
        $genero         = trim((string) ($filtros['genero']  ?? ''));
        $soloStockBajo  = !empty($filtros['solo_stock_bajo']);
        $umbralBajo     = (int) ($filtros['umbral_stock_bajo'] ?? 5);

        // Construye un WHERE compartido por ambas ramas (parámetros se duplican)
        $buildWhere = function (&$params, $sucursalParamPlaceholder = true) use (
            $id_sucursal, $categoriaId, $producto,
            $subcategoriaId, $temporadaId, $colorId, $genero
        ) {
            $w = '';
            if ($sucursalParamPlaceholder) {
                $params[] = (int) $id_sucursal;
            }
            if ($categoriaId > 0)    { $w .= ' AND p.categoria = ? ';        $params[] = (int) $categoriaId; }
            if ($subcategoriaId > 0) { $w .= ' AND p.id_subcategoria = ? ';  $params[] = (int) $subcategoriaId; }
            if ($temporadaId > 0)    { $w .= ' AND p.id_temporada = ? ';     $params[] = (int) $temporadaId; }
            if ($colorId > 0)        { $w .= ' AND p.id_color = ? ';         $params[] = (int) $colorId; }
            if ($genero !== '')      { $w .= ' AND p.genero = ? ';           $params[] = $genero; }
            if ($producto !== '') {
                $like = '%' . $producto . '%';
                $w .= ' AND (p.nombre_producto LIKE ? OR p.codigo LIKE ? ) ';
                $params[] = $like; $params[] = $like;
            }
            return $w;
        };

        $paramsSimples = array();
        $whereSimples  = $buildWhere($paramsSimples);

        $paramsVar = array();
        $whereVar  = $buildWhere($paramsVar);

        // Rama 1: productos SIN variantes
        $sqlSimples = "
            SELECT
                p.id_producto,
                NULL AS id_variante,
                NULL AS talla,
                p.codigo,
                p.nombre_producto,
                COALESCE(c.nombre_categoria, 'Sin categoría') AS nombre_categoria,
                COALESCE(sc.nombre_subcategoria, '—')         AS nombre_subcategoria,
                COALESCE(t.nombre_temporada, '—')             AS nombre_temporada,
                COALESCE(co.nombre_color, '—')                AS nombre_color,
                COALESCE(co.codigo_hex, '')                   AS color_hex,
                COALESCE(NULLIF(NULLIF(p.genero, ''), 'NA'), '—') AS genero,
                COALESCE(p.tiene_variantes, 0)                AS tiene_variantes,
                COALESCE(ps.stock, 0)                          AS stock,
                COALESCE(ps.stock, 0) * $pcProd                AS valor_inventario,
                $pcProd                                        AS precio_compra_unitario
            FROM tbl_producto_stock ps
            INNER JOIN tbl_producto p          ON p.id_producto = ps.id_producto
            LEFT JOIN tbl_categoria c          ON c.id_categoria = p.categoria
            LEFT JOIN tbl_subcategoria sc      ON sc.id_subcategoria = p.id_subcategoria
            LEFT JOIN tbl_temporada t          ON t.id_temporada = p.id_temporada
            LEFT JOIN tbl_color co             ON co.id_color = p.id_color
            WHERE ps.id_sucursal = ?
              AND COALESCE(p.tiene_variantes, 0) = 0
              $whereSimples
        ";

        // Rama 2: productos CON variantes
        $sqlVariantes = "
            SELECT
                p.id_producto,
                pv.id_variante,
                pv.talla,
                p.codigo,
                p.nombre_producto,
                COALESCE(c.nombre_categoria, 'Sin categoría') AS nombre_categoria,
                COALESCE(sc.nombre_subcategoria, '—')         AS nombre_subcategoria,
                COALESCE(t.nombre_temporada, '—')             AS nombre_temporada,
                COALESCE(co.nombre_color, '—')                AS nombre_color,
                COALESCE(co.codigo_hex, '')                   AS color_hex,
                COALESCE(NULLIF(NULLIF(p.genero, ''), 'NA'), '—') AS genero,
                1                                              AS tiene_variantes,
                COALESCE(sv.stock, 0)                          AS stock,
                COALESCE(sv.stock, 0) * $pcVar                 AS valor_inventario,
                $pcVar                                         AS precio_compra_unitario
            FROM tbl_producto p
            INNER JOIN tbl_producto_variante pv
                ON pv.id_producto = p.id_producto AND pv.activo = 1
            LEFT JOIN tbl_stock_variante sv
                ON sv.id_variante = pv.id_variante AND sv.id_sucursal = ?
            LEFT JOIN tbl_categoria c          ON c.id_categoria = p.categoria
            LEFT JOIN tbl_subcategoria sc      ON sc.id_subcategoria = p.id_subcategoria
            LEFT JOIN tbl_temporada t          ON t.id_temporada = p.id_temporada
            LEFT JOIN tbl_color co             ON co.id_color = p.id_color
            WHERE COALESCE(p.tiene_variantes, 0) = 1
              $whereVar
        ";

        $sql = "($sqlSimples) UNION ALL ($sqlVariantes)
                ORDER BY nombre_producto ASC, talla ASC";

        $rows = $this->db->query($sql, array_merge($paramsSimples, $paramsVar))->result_array();

        // Filtro opcional "solo stock bajo" (post-query para no duplicar SQL)
        if ($soloStockBajo) {
            $rows = array_values(array_filter($rows, function ($r) use ($umbralBajo) {
                return (float) $r['stock'] <= $umbralBajo;
            }));
        }

        $totales = array(
            'productos'        => 0,
            'unidades'         => 0,
            'valor_inventario' => 0,
            'stock_bajo'       => 0,
            'sin_stock'        => 0
        );

        $productosUnicos = array();
        $porCategoria    = array();
        $porSubcategoria = array();
        $porTemporada    = array();
        $porGenero       = array();

        foreach ($rows as &$row) {
            $row['stock']                  = (float) $row['stock'];
            $row['valor_inventario']       = (float) $row['valor_inventario'];
            $row['precio_compra_unitario'] = (float) $row['precio_compra_unitario'];
            $row['tiene_variantes']        = (int) $row['tiene_variantes'];

            $totales['unidades']         += $row['stock'];
            $totales['valor_inventario'] += $row['valor_inventario'];
            if ($row['stock'] <= 0)             { $totales['sin_stock']++; }
            elseif ($row['stock'] <= $umbralBajo){ $totales['stock_bajo']++; }

            $productosUnicos[$row['id_producto']] = true;

            $agruparEn = function (&$bucket, $clave) use ($row) {
                if (!isset($bucket[$clave])) {
                    $bucket[$clave] = array('label' => $clave, 'unidades' => 0, 'valor' => 0, 'productos' => array());
                }
                $bucket[$clave]['unidades'] += $row['stock'];
                $bucket[$clave]['valor']    += $row['valor_inventario'];
                $bucket[$clave]['productos'][$row['id_producto']] = true;
            };
            $agruparEn($porCategoria,    $row['nombre_categoria']);
            $agruparEn($porSubcategoria, $row['nombre_subcategoria']);
            $agruparEn($porTemporada,    $row['nombre_temporada']);
            $agruparEn($porGenero,       $row['genero']);
        }
        unset($row);

        $totales['productos'] = count($productosUnicos);

        $finalizarBucket = function ($bucket) {
            $out = array();
            foreach ($bucket as $b) {
                $out[] = array(
                    'label'     => $b['label'],
                    'unidades'  => (float) $b['unidades'],
                    'valor'     => (float) $b['valor'],
                    'productos' => count($b['productos'])
                );
            }
            usort($out, function ($a, $b) { return $b['valor'] <=> $a['valor']; });
            return $out;
        };

        return array(
            'rows'    => $rows,
            'totales' => $totales,
            'breakdowns' => array(
                'categoria'    => $finalizarBucket($porCategoria),
                'subcategoria' => $finalizarBucket($porSubcategoria),
                'temporada'    => $finalizarBucket($porTemporada),
                'genero'       => $finalizarBucket($porGenero)
            )
        );
    }

    public function getStockBajoResumen($id_sucursal, $categoriaId = 0, $producto = '', $stockMaximo = 5, $filtros = array())
    {
        // Optimización (cientos de miles de productos):
        //  - Filtro primario por (id_sucursal, stock) usa idx_stock_sucursal_activo / idx_stock_sucursal.
        //  - Detalle se LIMITA (parámetro $limite), default 500.
        //  - Totales y breakdowns se calculan en SQL aparte para reflejar TODO el universo filtrado, no sólo el LIMIT.
        $pcProd = $this->parseMoneySql('p.precio_compra');
        $pcVar  = $this->parseMoneySql('COALESCE(pv.precio_compra, p.precio_compra)');
        $stockMaximo = (int) $stockMaximo;

        $subcategoriaId = (int) ($filtros['subcategoria_id'] ?? 0);
        $temporadaId    = (int) ($filtros['temporada_id']    ?? 0);
        $colorId        = (int) ($filtros['color_id']        ?? 0);
        $genero         = trim((string) ($filtros['genero']  ?? ''));
        $limite         = max(1, (int) ($filtros['limite']   ?? 500));

        // Constructor de WHERE compartido (sin id_sucursal ni stockMax, que se incluyen aparte por rama)
        $buildWhereExtra = function (&$params) use ($categoriaId, $subcategoriaId, $temporadaId, $colorId, $genero, $producto) {
            $w = '';
            if ($categoriaId > 0)    { $w .= ' AND p.categoria = ? ';        $params[] = (int) $categoriaId; }
            if ($subcategoriaId > 0) { $w .= ' AND p.id_subcategoria = ? ';  $params[] = (int) $subcategoriaId; }
            if ($temporadaId > 0)    { $w .= ' AND p.id_temporada = ? ';     $params[] = (int) $temporadaId; }
            if ($colorId > 0)        { $w .= ' AND p.id_color = ? ';         $params[] = (int) $colorId; }
            if ($genero !== '')      { $w .= ' AND p.genero = ? ';           $params[] = $genero; }
            if ($producto !== '') {
                $like = '%' . $producto . '%';
                $w .= ' AND (p.nombre_producto LIKE ? OR p.codigo LIKE ? ) ';
                $params[] = $like; $params[] = $like;
            }
            return $w;
        };

        // --- DETALLE (LIMIT) ---
        $paramsSimples = array((int) $id_sucursal, $stockMaximo);
        $whereSimples  = $buildWhereExtra($paramsSimples);

        $paramsVar = array((int) $id_sucursal, $stockMaximo);
        $whereVar  = $buildWhereExtra($paramsVar);

        $sqlSimples = "
            SELECT
                p.id_producto, NULL AS id_variante, NULL AS talla,
                p.codigo, p.nombre_producto,
                COALESCE(c.nombre_categoria, 'Sin categoría')     AS nombre_categoria,
                COALESCE(sc.nombre_subcategoria, '—')             AS nombre_subcategoria,
                COALESCE(t.nombre_temporada, '—')                 AS nombre_temporada,
                COALESCE(co.nombre_color, '—')                    AS nombre_color,
                COALESCE(co.codigo_hex, '')                       AS color_hex,
                COALESCE(NULLIF(NULLIF(p.genero,''),'NA'), '—')   AS genero,
                COALESCE(p.tiene_variantes, 0)                    AS tiene_variantes,
                COALESCE(ps.stock, 0)                              AS stock,
                COALESCE(ps.stock, 0) * $pcProd                    AS valor_inventario
            FROM tbl_producto_stock ps
            INNER JOIN tbl_producto p     ON p.id_producto = ps.id_producto
            LEFT JOIN tbl_categoria c     ON c.id_categoria = p.categoria
            LEFT JOIN tbl_subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
            LEFT JOIN tbl_temporada t     ON t.id_temporada = p.id_temporada
            LEFT JOIN tbl_color co        ON co.id_color = p.id_color
            WHERE ps.id_sucursal = ?
              AND ps.stock <= ?
              AND COALESCE(p.tiene_variantes, 0) = 0
              $whereSimples
        ";

        $sqlVariantes = "
            SELECT
                p.id_producto, pv.id_variante, pv.talla,
                p.codigo, p.nombre_producto,
                COALESCE(c.nombre_categoria, 'Sin categoría')     AS nombre_categoria,
                COALESCE(sc.nombre_subcategoria, '—')             AS nombre_subcategoria,
                COALESCE(t.nombre_temporada, '—')                 AS nombre_temporada,
                COALESCE(co.nombre_color, '—')                    AS nombre_color,
                COALESCE(co.codigo_hex, '')                       AS color_hex,
                COALESCE(NULLIF(NULLIF(p.genero,''),'NA'), '—')   AS genero,
                1                                                  AS tiene_variantes,
                COALESCE(sv.stock, 0)                              AS stock,
                COALESCE(sv.stock, 0) * $pcVar                     AS valor_inventario
            FROM tbl_producto p
            INNER JOIN tbl_producto_variante pv
                ON pv.id_producto = p.id_producto AND pv.activo = 1
            LEFT JOIN tbl_stock_variante sv
                ON sv.id_variante = pv.id_variante AND sv.id_sucursal = ?
            LEFT JOIN tbl_categoria c     ON c.id_categoria = p.categoria
            LEFT JOIN tbl_subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
            LEFT JOIN tbl_temporada t     ON t.id_temporada = p.id_temporada
            LEFT JOIN tbl_color co        ON co.id_color = p.id_color
            WHERE COALESCE(p.tiene_variantes, 0) = 1
              AND COALESCE(sv.stock, 0) <= ?
              $whereVar
        ";

        $sql = "($sqlSimples) UNION ALL ($sqlVariantes)
                ORDER BY stock ASC, nombre_producto ASC, talla ASC
                LIMIT " . (int) $limite;

        $rows = $this->db->query($sql, array_merge($paramsSimples, $paramsVar))->result_array();
        foreach ($rows as &$row) {
            $row['stock']            = (float) $row['stock'];
            $row['valor_inventario'] = (float) $row['valor_inventario'];
            $row['tiene_variantes']  = (int) $row['tiene_variantes'];
        }
        unset($row);

        // --- TOTALES & BREAKDOWNS (siempre sobre el universo completo) ---
        $resumen = $this->_stockBajoAgregados($id_sucursal, $stockMaximo, $buildWhereExtra, $pcProd, $pcVar);

        return array(
            'rows'         => $rows,
            'totales'      => $resumen['totales'],
            'breakdowns'   => $resumen['breakdowns'],
            'limite'       => (int) $limite,
            'mostrandoMax' => count($rows) >= (int) $limite
        );
    }

    /**
     * Totales y breakdowns para stock bajo computados en SQL.
     * Una sola consulta UNION + 4 consultas GROUP BY (por categoría/subcategoría/temporada/género).
     */
    private function _stockBajoAgregados($id_sucursal, $stockMaximo, $buildWhereExtra, $pcProd, $pcVar)
    {
        // Totales globales: COUNT productos únicos, SUM unidades y valor
        $paramsS = array((int) $id_sucursal, (int) $stockMaximo);
        $wS = $buildWhereExtra($paramsS);
        $paramsV = array((int) $id_sucursal, (int) $stockMaximo);
        $wV = $buildWhereExtra($paramsV);

        $sqlTotales = "
            SELECT
                COUNT(DISTINCT id_producto) AS productos,
                COALESCE(SUM(stock), 0)     AS unidades,
                COALESCE(SUM(valor), 0)     AS valor_inventario,
                COALESCE(SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END), 0) AS sin_stock
            FROM (
                SELECT p.id_producto, COALESCE(ps.stock,0) AS stock, COALESCE(ps.stock,0)*$pcProd AS valor
                FROM tbl_producto_stock ps
                INNER JOIN tbl_producto p ON p.id_producto = ps.id_producto
                WHERE ps.id_sucursal = ? AND ps.stock <= ? AND COALESCE(p.tiene_variantes,0) = 0 $wS
                UNION ALL
                SELECT p.id_producto, COALESCE(sv.stock,0) AS stock, COALESCE(sv.stock,0)*$pcVar AS valor
                FROM tbl_producto p
                INNER JOIN tbl_producto_variante pv ON pv.id_producto = p.id_producto AND pv.activo = 1
                LEFT JOIN tbl_stock_variante sv ON sv.id_variante = pv.id_variante AND sv.id_sucursal = ?
                WHERE COALESCE(p.tiene_variantes,0) = 1 AND COALESCE(sv.stock,0) <= ? $wV
            ) u
        ";
        $tot = $this->db->query($sqlTotales, array_merge($paramsS, $paramsV))->row_array();

        $totales = array(
            'productos'        => (int) ($tot['productos'] ?? 0),
            'unidades'         => (float) ($tot['unidades'] ?? 0),
            'valor_inventario' => (float) ($tot['valor_inventario'] ?? 0),
            'sin_stock'        => (int) ($tot['sin_stock'] ?? 0)
        );

        // Breakdowns: una consulta por dimensión, agregada en SQL
        $breakdownPorDim = function ($selectExpr, $groupExpr, $joinExtra = '') use ($buildWhereExtra, $id_sucursal, $stockMaximo, $pcProd, $pcVar) {
            $pS = array((int) $id_sucursal, (int) $stockMaximo);
            $wS = $buildWhereExtra($pS);
            $pV = array((int) $id_sucursal, (int) $stockMaximo);
            $wV = $buildWhereExtra($pV);

            $sql = "
                SELECT label, SUM(unidades) AS unidades, SUM(valor) AS valor, COUNT(DISTINCT id_producto) AS productos
                FROM (
                    SELECT $selectExpr AS label, p.id_producto, COALESCE(ps.stock,0) AS unidades, COALESCE(ps.stock,0)*$pcProd AS valor
                    FROM tbl_producto_stock ps
                    INNER JOIN tbl_producto p ON p.id_producto = ps.id_producto
                    $joinExtra
                    WHERE ps.id_sucursal = ? AND ps.stock <= ? AND COALESCE(p.tiene_variantes,0) = 0 $wS
                    UNION ALL
                    SELECT $selectExpr AS label, p.id_producto, COALESCE(sv.stock,0) AS unidades, COALESCE(sv.stock,0)*$pcVar AS valor
                    FROM tbl_producto p
                    INNER JOIN tbl_producto_variante pv ON pv.id_producto = p.id_producto AND pv.activo = 1
                    LEFT JOIN tbl_stock_variante sv ON sv.id_variante = pv.id_variante AND sv.id_sucursal = ?
                    $joinExtra
                    WHERE COALESCE(p.tiene_variantes,0) = 1 AND COALESCE(sv.stock,0) <= ? $wV
                ) u
                GROUP BY label
                ORDER BY valor DESC
                LIMIT 30
            ";
            $rows = $this->db->query($sql, array_merge($pS, $pV))->result_array();
            $out = array();
            foreach ($rows as $r) {
                $out[] = array(
                    'label'     => (string) $r['label'],
                    'unidades'  => (float) $r['unidades'],
                    'valor'     => (float) $r['valor'],
                    'productos' => (int) $r['productos']
                );
            }
            return $out;
        };

        $breakdowns = array(
            'categoria'    => $breakdownPorDim("COALESCE(c.nombre_categoria,'Sin categoría')",
                                "c.nombre_categoria", " LEFT JOIN tbl_categoria c ON c.id_categoria = p.categoria "),
            'subcategoria' => $breakdownPorDim("COALESCE(sc.nombre_subcategoria,'—')",
                                "sc.nombre_subcategoria", " LEFT JOIN tbl_subcategoria sc ON sc.id_subcategoria = p.id_subcategoria "),
            'temporada'    => $breakdownPorDim("COALESCE(t.nombre_temporada,'—')",
                                "t.nombre_temporada", " LEFT JOIN tbl_temporada t ON t.id_temporada = p.id_temporada "),
            'genero'       => $breakdownPorDim("COALESCE(NULLIF(NULLIF(p.genero,''),'NA'),'—')",
                                "p.genero", "")
        );

        return array('totales' => $totales, 'breakdowns' => $breakdowns);
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

        $precioCompraSql = $this->parseMoneySql('COALESCE(pv.precio_compra, p.precio_compra)');
        $utilidadSql = "
            SELECT COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)), 0) AS utilidad_estimada
            FROM tbl_detalle_venta dv
            INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
            INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
            LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
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
        $this->db->join('tbl_cliente c', 'v.id_cliente = c.id_cliente', 'left');
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

    public function getProductosMasVendidosResumen($id_sucursal, $fechaInicial, $fechaFinal, $limit = 10, $desglosarPorTalla = false, $filtros = array())
    {
        // Filtros adicionales: subcategoría, temporada, color, género, categoría
        // Performance: WHERE primario sobre (id_sucursal, fecha_venta) usa idx_venta_sucursal_fecha.
        // Los filtros de producto se aplican en el JOIN para reducir el set lo antes posible.
        $categoriaId    = (int) ($filtros['categoria_id']    ?? 0);
        $subcategoriaId = (int) ($filtros['subcategoria_id'] ?? 0);
        $temporadaId    = (int) ($filtros['temporada_id']    ?? 0);
        $colorId        = (int) ($filtros['color_id']        ?? 0);
        $genero         = trim((string) ($filtros['genero']  ?? ''));

        $this->db
            ->select('p.id_producto, p.codigo, p.nombre_producto,
                      COALESCE(c.nombre_categoria, "Sin categoría") AS nombre_categoria,
                      COALESCE(sc.nombre_subcategoria, "—")         AS nombre_subcategoria,
                      COALESCE(t.nombre_temporada, "—")             AS nombre_temporada,
                      COALESCE(co.nombre_color, "—")                AS nombre_color,
                      COALESCE(NULLIF(NULLIF(p.genero,""),"NA"), "—") AS genero,
                      COALESCE(p.tiene_variantes,0)                 AS tiene_variantes,
                      SUM(dv.cantidad)                              AS unidades,
                      COALESCE(SUM(dv.cantidad * dv.precio_venta),0) AS total_vendido', false)
            ->from('tbl_detalle_venta dv')
            ->join('tbl_venta v',     'v.id_venta = dv.id_venta', 'inner')
            ->join('tbl_producto p',  'p.id_producto = dv.id_producto', 'inner')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->join('tbl_subcategoria sc', 'sc.id_subcategoria = p.id_subcategoria', 'left')
            ->join('tbl_temporada t', 't.id_temporada = p.id_temporada', 'left')
            ->join('tbl_color co',    'co.id_color = p.id_color', 'left')
            ->where('v.id_sucursal', $id_sucursal)
            ->where('v.fecha_venta >=', $fechaInicial)
            ->where('v.fecha_venta <=', $fechaFinal);

        if ($categoriaId > 0)    { $this->db->where('p.categoria', $categoriaId); }
        if ($subcategoriaId > 0) { $this->db->where('p.id_subcategoria', $subcategoriaId); }
        if ($temporadaId > 0)    { $this->db->where('p.id_temporada', $temporadaId); }
        if ($colorId > 0)        { $this->db->where('p.id_color', $colorId); }
        if ($genero !== '')      { $this->db->where('p.genero', $genero); }

        $rows = $this->db
            ->group_by(array('dv.id_producto','p.codigo','p.nombre_producto','c.nombre_categoria','sc.nombre_subcategoria','t.nombre_temporada','co.nombre_color','p.genero','p.tiene_variantes'))
            ->order_by('unidades', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();

        $totales = array('productos' => count($rows), 'unidades' => 0, 'total_vendido' => 0);
        foreach ($rows as &$row) {
            $row['id_producto']     = (int) $row['id_producto'];
            $row['tiene_variantes'] = (int) $row['tiene_variantes'];
            $row['unidades']        = (float) $row['unidades'];
            $row['total_vendido']   = (float) $row['total_vendido'];
            $row['variantes']       = array();
            $totales['unidades']      += $row['unidades'];
            $totales['total_vendido'] += $row['total_vendido'];
        }
        unset($row);

        // Desglose por talla (solo para productos con variantes del top)
        if ($desglosarPorTalla && !empty($rows)) {
            $ids = array();
            foreach ($rows as $r) {
                if ($r['tiene_variantes'] === 1) { $ids[] = $r['id_producto']; }
            }

            if (!empty($ids)) {
                $idsIn = implode(',', array_map('intval', $ids));
                // Incluye filas con id_variante NULL (legacy) agrupadas como "Sin talla"
                // para que la suma del desglose coincida con el total del producto.
                $sqlDesglose = "
                    SELECT
                        dv.id_producto,
                        dv.id_variante,
                        COALESCE(pv.talla, 'Sin talla') AS talla,
                        SUM(dv.cantidad) AS unidades,
                        COALESCE(SUM(dv.cantidad * dv.precio_venta), 0) AS total_vendido
                    FROM tbl_detalle_venta dv
                    INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
                    LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
                    WHERE v.id_sucursal = ?
                      AND v.fecha_venta >= ?
                      AND v.fecha_venta <= ?
                      AND dv.id_producto IN ($idsIn)
                    GROUP BY dv.id_producto, dv.id_variante, pv.talla
                    ORDER BY dv.id_producto, unidades DESC
                ";
                $desglose = $this->db->query($sqlDesglose, array($id_sucursal, $fechaInicial, $fechaFinal))->result_array();

                $porProducto = array();
                foreach ($desglose as $d) {
                    $pid = (int) $d['id_producto'];
                    $porProducto[$pid][] = array(
                        'id_variante'   => (int) $d['id_variante'],
                        'talla'         => $d['talla'],
                        'unidades'      => (float) $d['unidades'],
                        'total_vendido' => (float) $d['total_vendido']
                    );
                }

                foreach ($rows as &$row) {
                    if (isset($porProducto[$row['id_producto']])) {
                        $row['variantes'] = $porProducto[$row['id_producto']];
                    }
                }
                unset($row);
            }
        }

        // --- Totales y breakdowns SOBRE EL UNIVERSO COMPLETO (no sólo el top) ---
        $aggregados = $this->_ventasAgregados(
            $id_sucursal, $fechaInicial, $fechaFinal, $filtros,
            'SUM(dv.cantidad) AS valor_unidades, COALESCE(SUM(dv.cantidad * dv.precio_venta), 0) AS valor'
        );

        $totalesGlobales = $this->db->query(
            "SELECT
                COUNT(DISTINCT p.id_producto) AS productos,
                COALESCE(SUM(dv.cantidad),0) AS unidades,
                COALESCE(SUM(dv.cantidad * dv.precio_venta),0) AS total_vendido
             FROM tbl_detalle_venta dv
             INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
             INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
             WHERE v.id_sucursal = ? AND v.fecha_venta >= ? AND v.fecha_venta <= ?"
             . $this->_ventasFiltroExtraSql($filtros, $extraParams = array()),
            array_merge(array($id_sucursal, $fechaInicial, $fechaFinal), $extraParams)
        )->row_array();

        $totales['productos']     = (int) ($totalesGlobales['productos'] ?? 0);
        $totales['unidades']      = (float) ($totalesGlobales['unidades'] ?? 0);
        $totales['total_vendido'] = (float) ($totalesGlobales['total_vendido'] ?? 0);

        return array(
            'rows'       => $rows,
            'totales'    => $totales,
            'breakdowns' => $aggregados
        );
    }

    /**
     * Devuelve la cláusula WHERE adicional (string) y append de params para los filtros
     * de producto en consultas de ventas. Usa pass-by-reference para los params.
     */
    private function _ventasFiltroExtraSql($filtros, &$params)
    {
        $sql = '';
        $categoriaId    = (int) ($filtros['categoria_id']    ?? 0);
        $subcategoriaId = (int) ($filtros['subcategoria_id'] ?? 0);
        $temporadaId    = (int) ($filtros['temporada_id']    ?? 0);
        $colorId        = (int) ($filtros['color_id']        ?? 0);
        $genero         = trim((string) ($filtros['genero']  ?? ''));
        if ($categoriaId > 0)    { $sql .= ' AND p.categoria = ? ';        $params[] = $categoriaId; }
        if ($subcategoriaId > 0) { $sql .= ' AND p.id_subcategoria = ? ';  $params[] = $subcategoriaId; }
        if ($temporadaId > 0)    { $sql .= ' AND p.id_temporada = ? ';     $params[] = $temporadaId; }
        if ($colorId > 0)        { $sql .= ' AND p.id_color = ? ';         $params[] = $colorId; }
        if ($genero !== '')      { $sql .= ' AND p.genero = ? ';           $params[] = $genero; }
        return $sql;
    }

    /**
     * Breakdowns por dimensión (categoría/subcategoría/temporada/género) para ventas.
     * $expr indica las métricas: por defecto unidades + total vendido.
     */
    private function _ventasAgregados($id_sucursal, $fechaInicial, $fechaFinal, $filtros, $exprMetrica)
    {
        $dims = array(
            'categoria'    => array("COALESCE(c.nombre_categoria,'Sin categoría') AS label",
                                    " LEFT JOIN tbl_categoria c ON c.id_categoria = p.categoria "),
            'subcategoria' => array("COALESCE(sc.nombre_subcategoria,'—') AS label",
                                    " LEFT JOIN tbl_subcategoria sc ON sc.id_subcategoria = p.id_subcategoria "),
            'temporada'    => array("COALESCE(t.nombre_temporada,'—') AS label",
                                    " LEFT JOIN tbl_temporada t ON t.id_temporada = p.id_temporada "),
            'genero'       => array("COALESCE(NULLIF(NULLIF(p.genero,''),'NA'),'—') AS label", "")
        );

        $out = array();
        foreach ($dims as $key => $cfg) {
            $params = array((int) $id_sucursal, $fechaInicial, $fechaFinal);
            $whereExtra = $this->_ventasFiltroExtraSql($filtros, $params);

            $sql = "
                SELECT {$cfg[0]}, $exprMetrica
                FROM tbl_detalle_venta dv
                INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
                INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
                {$cfg[1]}
                WHERE v.id_sucursal = ? AND v.fecha_venta >= ? AND v.fecha_venta <= ?
                $whereExtra
                GROUP BY label
                ORDER BY valor DESC
                LIMIT 30
            ";
            $rs = $this->db->query($sql, $params)->result_array();
            $arr = array();
            foreach ($rs as $r) {
                $arr[] = array(
                    'label'    => (string) $r['label'],
                    'unidades' => (float) ($r['valor_unidades'] ?? 0),
                    'valor'    => (float) ($r['valor'] ?? 0)
                );
            }
            $out[$key] = $arr;
        }
        return $out;
    }

    public function getUtilidadEstimadaResumen($id_sucursal, $fechaInicial, $fechaFinal, $filtros = array())
    {
        // Performance:
        //  - WHERE primario sobre idx_venta_sucursal_fecha (id_sucursal, fecha_venta).
        //  - Detalle LIMITADO (default 200 productos por utilidad). Totales en SQL aparte.
        //  - Tendencia diaria agregada en SQL (no en PHP) para soportar millones de filas.
        $precioCompraSql = $this->parseMoneySql('COALESCE(pv.precio_compra, p.precio_compra)');
        $limite = max(50, min(2000, (int) ($filtros['limite'] ?? 200)));

        $params = array((int) $id_sucursal, $fechaInicial, $fechaFinal);
        $whereExtra = $this->_ventasFiltroExtraSql($filtros, $params);

        // --- DETALLE (Top N por utilidad) ---
        $rows = $this->db->query(
            "SELECT
                p.id_producto,
                p.codigo,
                p.nombre_producto,
                COALESCE(c.nombre_categoria, 'Sin categoría')     AS nombre_categoria,
                COALESCE(sc.nombre_subcategoria, '—')             AS nombre_subcategoria,
                COALESCE(t.nombre_temporada, '—')                 AS nombre_temporada,
                COALESCE(co.nombre_color, '—')                    AS nombre_color,
                COALESCE(NULLIF(NULLIF(p.genero,''),'NA'),'—')    AS genero,
                SUM(dv.cantidad)                                  AS cantidad,
                SUM(dv.cantidad * $precioCompraSql)               AS costo_total,
                SUM(dv.cantidad * dv.precio_venta)                AS venta_total,
                SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)) AS utilidad_estimada
            FROM tbl_detalle_venta dv
            INNER JOIN tbl_venta v             ON v.id_venta = dv.id_venta
            INNER JOIN tbl_producto p          ON p.id_producto = dv.id_producto
            LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
            LEFT JOIN tbl_categoria c          ON c.id_categoria = p.categoria
            LEFT JOIN tbl_subcategoria sc      ON sc.id_subcategoria = p.id_subcategoria
            LEFT JOIN tbl_temporada t          ON t.id_temporada = p.id_temporada
            LEFT JOIN tbl_color co             ON co.id_color = p.id_color
            WHERE v.id_sucursal = ?
              AND v.fecha_venta >= ?
              AND v.fecha_venta <= ?
              $whereExtra
            GROUP BY p.id_producto, p.codigo, p.nombre_producto, c.nombre_categoria,
                     sc.nombre_subcategoria, t.nombre_temporada, co.nombre_color, p.genero
            ORDER BY utilidad_estimada DESC
            LIMIT " . (int) $limite,
            $params
        )->result_array();

        foreach ($rows as &$row) {
            $row['cantidad']          = (float) $row['cantidad'];
            $row['costo_total']       = (float) $row['costo_total'];
            $row['venta_total']       = (float) $row['venta_total'];
            $row['utilidad_estimada'] = (float) $row['utilidad_estimada'];
            $row['margen_pct']        = $row['venta_total'] > 0
                ? round(($row['utilidad_estimada'] / $row['venta_total']) * 100, 2)
                : 0;
        }
        unset($row);

        // --- TOTALES GLOBALES (sobre el universo filtrado completo) ---
        $paramsT = array((int) $id_sucursal, $fechaInicial, $fechaFinal);
        $whereExtraT = $this->_ventasFiltroExtraSql($filtros, $paramsT);

        $totRow = $this->db->query(
            "SELECT
                COUNT(DISTINCT p.id_producto) AS productos,
                COALESCE(SUM(dv.cantidad),0) AS cantidad,
                COALESCE(SUM(dv.cantidad * $precioCompraSql),0) AS costo_total,
                COALESCE(SUM(dv.cantidad * dv.precio_venta),0) AS venta_total,
                COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)),0) AS utilidad_estimada
             FROM tbl_detalle_venta dv
             INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
             INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
             LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
             WHERE v.id_sucursal = ? AND v.fecha_venta >= ? AND v.fecha_venta <= ? $whereExtraT",
            $paramsT
        )->row_array();

        $totales = array(
            'productos'         => (int) ($totRow['productos'] ?? 0),
            'cantidad'          => (float) ($totRow['cantidad'] ?? 0),
            'costo_total'       => (float) ($totRow['costo_total'] ?? 0),
            'venta_total'       => (float) ($totRow['venta_total'] ?? 0),
            'utilidad_estimada' => (float) ($totRow['utilidad_estimada'] ?? 0),
            'margen_pct'        => 0
        );
        if ($totales['venta_total'] > 0) {
            $totales['margen_pct'] = round(($totales['utilidad_estimada'] / $totales['venta_total']) * 100, 2);
        }

        // --- TENDENCIA DIARIA (agregada en SQL) ---
        $paramsTr = array((int) $id_sucursal, $fechaInicial, $fechaFinal);
        $whereExtraTr = $this->_ventasFiltroExtraSql($filtros, $paramsTr);
        $trendRows = $this->db->query(
            "SELECT DATE(v.fecha_venta) AS fecha,
                    COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)),0) AS utilidad,
                    COALESCE(SUM(dv.cantidad * dv.precio_venta),0) AS venta
             FROM tbl_detalle_venta dv
             INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
             INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
             LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
             WHERE v.id_sucursal = ? AND v.fecha_venta >= ? AND v.fecha_venta <= ? $whereExtraTr
             GROUP BY DATE(v.fecha_venta)
             ORDER BY DATE(v.fecha_venta) ASC",
            $paramsTr
        )->result_array();

        $trend = array();
        foreach ($trendRows as $tr) {
            $trend[] = array(
                'fecha'    => $tr['fecha'],
                'venta'    => (float) $tr['venta'],
                'utilidad' => (float) $tr['utilidad']
            );
        }

        // --- BREAKDOWNS por dimensión (utilidad y venta) ---
        $breakdowns = $this->_utilidadAgregados($id_sucursal, $fechaInicial, $fechaFinal, $filtros, $precioCompraSql);

        return array(
            'rows'         => $rows,
            'totales'      => $totales,
            'trend'        => $trend,
            'breakdowns'   => $breakdowns,
            'limite'       => (int) $limite,
            'mostrandoMax' => count($rows) >= (int) $limite
        );
    }

    private function _utilidadAgregados($id_sucursal, $fechaInicial, $fechaFinal, $filtros, $precioCompraSql)
    {
        $dims = array(
            'categoria'    => array("COALESCE(c.nombre_categoria,'Sin categoría') AS label",
                                    " LEFT JOIN tbl_categoria c ON c.id_categoria = p.categoria "),
            'subcategoria' => array("COALESCE(sc.nombre_subcategoria,'—') AS label",
                                    " LEFT JOIN tbl_subcategoria sc ON sc.id_subcategoria = p.id_subcategoria "),
            'temporada'    => array("COALESCE(t.nombre_temporada,'—') AS label",
                                    " LEFT JOIN tbl_temporada t ON t.id_temporada = p.id_temporada "),
            'genero'       => array("COALESCE(NULLIF(NULLIF(p.genero,''),'NA'),'—') AS label", "")
        );
        $out = array();
        foreach ($dims as $key => $cfg) {
            $params = array((int) $id_sucursal, $fechaInicial, $fechaFinal);
            $whereExtra = $this->_ventasFiltroExtraSql($filtros, $params);
            $sql = "
                SELECT {$cfg[0]},
                       COALESCE(SUM(dv.cantidad),0) AS unidades,
                       COALESCE(SUM(dv.cantidad * dv.precio_venta),0) AS venta,
                       COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)),0) AS utilidad
                FROM tbl_detalle_venta dv
                INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
                INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
                LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
                {$cfg[1]}
                WHERE v.id_sucursal = ? AND v.fecha_venta >= ? AND v.fecha_venta <= ? $whereExtra
                GROUP BY label
                ORDER BY utilidad DESC
                LIMIT 30
            ";
            $rs = $this->db->query($sql, $params)->result_array();
            $arr = array();
            foreach ($rs as $r) {
                $venta = (float) $r['venta'];
                $util  = (float) $r['utilidad'];
                $arr[] = array(
                    'label'      => (string) $r['label'],
                    'unidades'   => (float) $r['unidades'],
                    'venta'      => $venta,
                    'utilidad'   => $util,
                    'margen_pct' => $venta > 0 ? round(($util / $venta) * 100, 2) : 0
                );
            }
            $out[$key] = $arr;
        }
        return $out;
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
