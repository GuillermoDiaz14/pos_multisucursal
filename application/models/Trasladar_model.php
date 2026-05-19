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
    // Mantengo el método para compatibilidad: devuelve TODOS los productos con stock.
    // Nuevo flujo: usar buscar_productos_traslado() con paginación.
    return $this->buscar_productos_traslado($id_sucursal, '', 200);
}

/**
 * Búsqueda paginada de productos con stock en la sucursal.
 * Ordena por id_producto DESC (lo más nuevo arriba).
 * Stock combinado producto simple / suma de variantes.
 *
 * @param int    $id_sucursal
 * @param string $texto       texto a buscar en nombre o código (LIKE)
 * @param int    $limit       máximo de filas a devolver (default 200)
 * @return array
 */
public function buscar_productos_traslado($id_sucursal, $texto = '', $limit = 200)
{
    $limit  = max(1, min((int)$limit, 500));
    $params = [(int)$id_sucursal, (int)$id_sucursal];

    $where = '';
    $texto = trim((string)$texto);
    if ($texto !== '') {
        // Búsqueda exacta por código si es numérico, o LIKE en ambos campos
        if (ctype_digit($texto)) {
            $where = " AND (p.codigo = ? OR p.nombre_producto LIKE ? OR p.codigo LIKE ?)";
            $like  = '%' . $texto . '%';
            array_push($params, $texto, $like, $like);
        } else {
            $where = " AND (p.nombre_producto LIKE ? OR p.codigo LIKE ?)";
            $like  = '%' . $texto . '%';
            array_push($params, $like, $like);
        }
    }
    $params[] = $limit;

    $sql = "
        SELECT p.id_producto, p.nombre_producto, p.codigo, p.tiene_variantes,
               CASE
                   WHEN p.tiene_variantes = 1
                       THEN COALESCE((SELECT SUM(sv.stock)
                                        FROM tbl_stock_variante sv
                                        INNER JOIN tbl_producto_variante v ON v.id_variante = sv.id_variante
                                       WHERE v.id_producto = p.id_producto
                                         AND sv.id_sucursal = ?), 0)
                   ELSE COALESCE(ps.stock, 0)
               END AS stock
          FROM tbl_producto p
          LEFT JOIN tbl_producto_stock ps
                 ON ps.id_producto = p.id_producto AND ps.id_sucursal = ?
         WHERE 1=1 $where
         HAVING stock > 0
         ORDER BY p.id_producto DESC
         LIMIT ?
    ";
    return $this->db->query($sql, $params)->result();
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
        $this->db->select('tbl_traslado.*,
            s_destino.nombre_sucursal as nombre_sucursal_aumento,
            s_origen.nombre_sucursal  as nombre_sucursal_descuento,
            tbl_users.name            as nombre_usuario');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal as s_destino', 's_destino.id_sucursal = tbl_traslado.id_sucursal_aumento',   'left');
        $this->db->join('tbl_sucursal as s_origen',  's_origen.id_sucursal  = tbl_traslado.id_sucursal_descuento', 'left');
        $this->db->join('tbl_users',                 'tbl_users.userId      = tbl_traslado.id_usuario',            'left');
        $this->db->where('tbl_traslado.id_traslado', $id_traslado);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_detalle_traslado($id_traslado) {
        $this->db->select('tbl_detalle_traslado.*,
            tbl_producto.nombre_producto, tbl_producto.codigo, tbl_producto.id_producto,
            tbl_producto.tiene_variantes,
            tbl_producto_variante.talla');
        $this->db->from('tbl_detalle_traslado');
        $this->db->join('tbl_producto', 'tbl_producto.id_producto = tbl_detalle_traslado.id_producto', 'inner');
        $this->db->join('tbl_producto_variante',
            'tbl_producto_variante.id_variante = tbl_detalle_traslado.id_variante', 'left');
        $this->db->where('tbl_detalle_traslado.id_traslado', $id_traslado);
        $this->db->order_by('tbl_detalle_traslado.id_detalle_traslado', 'ASC');
        return $this->db->get()->result();
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
    function traslado_lista_recibidos($searchText, $id_sucursal)
    {
        $this->db->select('tbl_traslado.*, tbl_sucursal.nombre_sucursal as sucursal_traslado, tbl_users.name as nombre_usuario');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_descuento = tbl_sucursal.id_sucursal', 'left');
        $this->db->join('tbl_users',    'tbl_users.userId = tbl_traslado.id_usuario', 'left');

        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
            $this->db->or_like('tbl_traslado.id_traslado', $searchText);
            $this->db->or_like('tbl_users.name', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_traslado.id_sucursal_aumento', $id_sucursal);
        $this->db->order_by('tbl_traslado.id_traslado', 'DESC');
        return $this->db->get()->result();
    }

    function traslado_lista($searchText, $id_sucursal)
    {
        $this->db->select('tbl_traslado.*, tbl_sucursal.nombre_sucursal as sucursal_traslado, tbl_users.name as nombre_usuario');
        $this->db->from('tbl_traslado');
        $this->db->join('tbl_sucursal', 'tbl_traslado.id_sucursal_aumento = tbl_sucursal.id_sucursal', 'left');
        $this->db->join('tbl_users',    'tbl_users.userId = tbl_traslado.id_usuario', 'left');

        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
            $this->db->or_like('tbl_traslado.id_traslado', $searchText);
            $this->db->or_like('tbl_users.name', $searchText);
            $this->db->group_end();
        }
        $this->db->where('tbl_traslado.id_sucursal_descuento', $id_sucursal);
        $this->db->order_by('tbl_traslado.id_traslado', 'DESC');
        return $this->db->get()->result();
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

// ─────────────────────────────────────────────────────────────────
// Fase 4: helpers atómicos para traslados con/sin variantes
// ─────────────────────────────────────────────────────────────────

public function decrementar_stock_producto($id_producto, $id_sucursal, $cantidad)
{
    $this->db->query(
        "UPDATE tbl_producto_stock
            SET stock = stock - ?
          WHERE id_producto = ? AND id_sucursal = ? AND stock >= ?",
        [(int)$cantidad, (int)$id_producto, (int)$id_sucursal, (int)$cantidad]
    );
    return (int)$this->db->affected_rows();
}

public function decrementar_stock_variante($id_variante, $id_sucursal, $cantidad)
{
    $this->db->query(
        "UPDATE tbl_stock_variante
            SET stock = stock - ?
          WHERE id_variante = ? AND id_sucursal = ? AND stock >= ?",
        [(int)$cantidad, (int)$id_variante, (int)$id_sucursal, (int)$cantidad]
    );
    return (int)$this->db->affected_rows();
}

public function incrementar_stock_producto($id_producto, $id_sucursal, $cantidad)
{
    return $this->db->query(
        "INSERT INTO tbl_producto_stock (id_producto, id_sucursal, stock)
              VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE stock = stock + VALUES(stock)",
        [(int)$id_producto, (int)$id_sucursal, (int)$cantidad]
    );
}

public function incrementar_stock_variante($id_variante, $id_sucursal, $cantidad)
{
    return $this->db->query(
        "INSERT INTO tbl_stock_variante (id_variante, id_sucursal, stock)
              VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE stock = stock + VALUES(stock)",
        [(int)$id_variante, (int)$id_sucursal, (int)$cantidad]
    );
}

public function addDetallesTrasladoBatch(array $detalles)
{
    if (empty($detalles)) return true;
    return $this->db->insert_batch('tbl_detalle_traslado', $detalles);
}

public function variante_pertenece_producto($id_variante, $id_producto)
{
    $row = $this->db
        ->select('id_variante')
        ->from('tbl_producto_variante')
        ->where('id_variante', (int)$id_variante)
        ->where('id_producto', (int)$id_producto)
        ->where('activo', 1)
        ->get()->row();
    return (bool)$row;
}

public function get_variantes_por_sucursal($id_sucursal)
{
    $sql = "SELECT v.id_producto, v.id_variante, v.talla, v.orden,
                   COALESCE(sv.stock, 0) AS stock
              FROM tbl_producto_variante v
              INNER JOIN tbl_producto p ON p.id_producto = v.id_producto
              LEFT JOIN tbl_stock_variante sv
                     ON sv.id_variante = v.id_variante AND sv.id_sucursal = ?
             WHERE v.activo = 1 AND p.tiene_variantes = 1
             ORDER BY v.id_producto, v.orden, v.id_variante";
    $rows = $this->db->query($sql, [(int)$id_sucursal])->result();
    $out = [];
    foreach ($rows as $r) {
        $out[(int)$r->id_producto][] = $r;
    }
    return $out;
}

}