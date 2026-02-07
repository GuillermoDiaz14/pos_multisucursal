<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Reporte_administrador_model extends CI_Model
{
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








  public function get_sucursales() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
 
        $query = $this->db->get('tbl_sucursal');

        return $query->result();
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

}