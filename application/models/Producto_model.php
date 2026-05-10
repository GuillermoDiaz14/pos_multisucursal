<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Producto_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function productoListingCount($searchText, $id_sucursal, $id_categoria = 0)
    {
        $this->db->from('tbl_producto');
        $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_producto.nombre_producto', $searchText);
            $this->db->or_like('tbl_producto.codigo', $searchText);
            $this->db->group_end();
        }
        if ($id_categoria > 0) {
            $this->db->where('tbl_producto.categoria', $id_categoria);
        }
        $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
        return $this->db->count_all_results();
    }
    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
     
     public function getProductoConStock($id_producto, $id_sucursal)
{
    $this->db->select('tbl_producto.*, tbl_producto_stock.stock');
    $this->db->from('tbl_producto');
    $this->db->join(
        'tbl_producto_stock',
        'tbl_producto.id_producto = tbl_producto_stock.id_producto',
        'left'
    );
    $this->db->where('tbl_producto.id_producto', $id_producto);
    $this->db->where('tbl_producto_stock.id_sucursal', $id_sucursal);
    return $this->db->get()->row();
}

/**
 * Busca producto por EAN-13 (búsqueda principal - super rápida)
 * @param string $ean13: Código EAN-13 del producto
 * @return object|null: Retorna el producto si existe, null si no
 */
public function buscar_por_ean13($ean13)
{
    $this->db->select('id_producto, nombre_producto, codigo, precio_compra, precio_venta, categoria, talla');
    $this->db->from('tbl_producto');
    $this->db->where('codigo', $ean13);
    $resultado = $this->db->get()->row();

    return $resultado;
}

/**
 * Busca producto por ID
 */
public function buscar_por_id($id_producto)
{
    $this->db->select('id_producto, nombre_producto, codigo, precio_compra, precio_venta, categoria, talla');
    $this->db->from('tbl_producto');
    $this->db->where('id_producto', (int)$id_producto);
    return $this->db->get()->row();
}

/**
 * Busca producto por nombre o código parcial
 */
public function buscar_por_nombre($nombre)
{
    $this->db->select('id_producto, nombre_producto, codigo, precio_compra, precio_venta, categoria, talla');
    $this->db->from('tbl_producto');
    $this->db->group_start();
    $this->db->like('nombre_producto', $nombre);
    $this->db->or_like('codigo', $nombre);
    $this->db->group_end();
    $this->db->limit(20);
    return $this->db->get()->result();
}

/**
 * Actualiza precio de compra de un producto
 * @param int $id_producto: ID del producto
 * @param float $precio_compra: Nuevo precio de compra
 * @return bool: true si actualiza, false si no
 */
public function actualizar_precio_compra($id_producto, $precio_compra)
{
    $this->db->where('id_producto', $id_producto);
    return $this->db->update('tbl_producto', array('precio_compra' => $precio_compra));
}

/**
 * Genera un EAN-13 automático ALEATORIO
 * Rango: 5000000000001 a 5999999999999 (reservado para códigos internos)
 * @return string: EAN-13 de 13 dígitos (único y aleatorio)
 */
public function generar_ean13_automatico()
{
    $intentos = 0;
    $max_intentos = 100;
    
    while ($intentos < $max_intentos) {
        // Generar número aleatorio en rango interno: 5000000000001 a 5999999999999
        $numero = mt_rand(5000000000001, 5999999999999);
        
        // Verificar que no exista en BD
        $existe = $this->db->where('codigo', (string)$numero)->get('tbl_producto')->row();
        
        if (!$existe) {
            // Convertir a string para calcular dígito verificador
            $ean12 = substr((string)$numero, 0, 12);
            $suma = 0;
            
            for ($i = 0; $i < 12; $i++) {
                $digito = (int)substr($ean12, $i, 1);
                $suma += $digito * (($i % 2 == 0) ? 1 : 3);
            }
            
            $digito_verificador = (10 - ($suma % 10)) % 10;
            $ean13 = $ean12 . $digito_verificador;
            
            return $ean13;
        }
        
        $intentos++;
    }
    
    // Error: no se pudo generar código único después de 100 intentos
    return false;
}

/**
 * Genera un EAN-13 único considerando códigos reservados en memoria.
 * @param array $codigos_reservados
 * @return string|false
 */
public function generar_ean13_automatico_unico($codigos_reservados = array())
{
    $codigos_reservados = array_map('strval', (array)$codigos_reservados);
    $intentos = 0;
    $max_intentos = 200;

    while ($intentos < $max_intentos) {
        $ean13 = $this->generar_ean13_automatico();

        if ($ean13 === false) {
            return false;
        }

        if (!in_array((string)$ean13, $codigos_reservados, true)) {
            return $ean13;
        }

        $intentos++;
    }

    return false;
}

/**
 * Valida formato EAN-13: exactamente 13 dígitos.
 * @param string $codigo
 * @return bool
 */
public function validar_formato_ean13($codigo)
{
    return (bool) preg_match('/^\d{13}$/', (string) $codigo);
}

/**
 * Valida el checksum de un código EAN-13.
 * @param string $codigo
 * @return bool
 */
public function validar_checksum_ean13($codigo)
{
    $codigo = (string) $codigo;

    if (!$this->validar_formato_ean13($codigo)) {
        return false;
    }

    $ean12 = substr($codigo, 0, 12);
    $digito_verificador_esperado = (int) substr($codigo, 12, 1);
    $suma = 0;

    for ($i = 0; $i < 12; $i++) {
        $digito = (int) substr($ean12, $i, 1);
        $suma += $digito * (($i % 2 === 0) ? 1 : 3);
    }

    $digito_verificador_calculado = (10 - ($suma % 10)) % 10;

    return $digito_verificador_calculado === $digito_verificador_esperado;
}

/**
 * Valida si un EAN-13 está duplicado
 * @param string $ean13: Código EAN-13
 * @param int $id_producto_actual: ID del producto actual (para excluir en ediciones)
 * @return bool: true si existe, false si está disponible
 */
public function validar_ean13_duplicado($ean13, $id_producto_actual = null)
{
    $this->db->select('id_producto');
    $this->db->from('tbl_producto');
    $this->db->where('codigo', $ean13);
    
    if ($id_producto_actual) {
        $this->db->where('id_producto !=', $id_producto_actual);
    }
    
    $resultado = $this->db->get()->row();
    
    return ($resultado !== null);
}

/**
 * Obtiene el stock actual de un producto en una sucursal específica
 * @param int $id_producto: ID del producto
 * @param int $id_sucursal: ID de la sucursal
 * @return int: Stock actual o 0 si no existe
 */
public function obtener_stock_sucursal($id_producto, $id_sucursal)
{
    $this->db->select('stock');
    $this->db->from('tbl_producto_stock');
    $this->db->where('id_producto', $id_producto);
    $this->db->where('id_sucursal', $id_sucursal);
    $resultado = $this->db->get()->row();
    
    return $resultado ? (int)$resultado->stock : 0;
}

/**
 * Actualiza el stock de un producto en una sucursal
 * @param int $id_producto: ID del producto
 * @param int $id_sucursal: ID de la sucursal
 * @param int $stock_nuevo: Nuevo stock
 * @return bool: true si se actualizó
 */
public function actualizar_stock($id_producto, $id_sucursal, $stock_nuevo)
{
    $existe = $this->db
        ->where('id_producto', $id_producto)
        ->where('id_sucursal', $id_sucursal)
        ->get('tbl_producto_stock')
        ->row();
    
    if ($existe) {
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->update('tbl_producto_stock', array('stock' => $stock_nuevo));
        return true;
    } else {
        $this->db->insert('tbl_producto_stock', array(
            'id_producto' => $id_producto,
            'id_sucursal' => $id_sucursal,
            'stock' => $stock_nuevo
        ));
        return true;
    }
}

/**
 * Valida si un EAN-13 está duplicado (SIMPLE - solo EAN-13)
 * @param string $codigo: Código EAN-13 del producto
 * @return object|null: Retorna el producto si el EAN-13 ya existe
 */
public function validar_codigo_duplicado($codigo)
{
    $this->db->select('id_producto, nombre_producto, codigo');
    $this->db->from('tbl_producto');
    $this->db->where('codigo', $codigo);
    $resultado = $this->db->get()->row();
    
    return $resultado;
}

/**
 * Valida EAN-13 duplicado en edición (excluye el producto actual)
 * @param string $codigo: Código EAN-13
 * @param int $id_producto_actual: ID del producto actual (para excluirlo)
 * @return object|null: Retorna el producto si hay conflicto
 */
public function validar_codigo_duplicado_edit($codigo, $id_producto_actual, $unused = null)
{
    $this->db->select('id_producto, nombre_producto, codigo');
    $this->db->from('tbl_producto');
    $this->db->where('codigo', $codigo);
    $this->db->where('id_producto !=', $id_producto_actual);
    $resultado = $this->db->get()->row();
    
    return $resultado;
}


    function productoListing($searchText, $id_sucursal, $limit = 100, $offset = 0, $id_categoria = 0)
    {
        $this->db->select('tbl_producto.*,tbl_categoria.nombre_categoria as nombre_categoria,tbl_producto_stock.stock as stock');
        $this->db->from('tbl_producto');
        $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
        $this->db->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'left');
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_producto.nombre_producto', $searchText);
            $this->db->or_like('tbl_producto.codigo', $searchText);
            $this->db->group_end();
        }
        if ($id_categoria > 0) {
            $this->db->where('tbl_producto.categoria', $id_categoria);
        }
        $this->db->where('tbl_producto_stock.id_sucursal', (int)$id_sucursal);
        $this->db->order_by('tbl_producto.id_producto', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }

    public function get_productos_sin_sucursal($limit = 200) {
    $this->db->select('tbl_producto.*, tbl_categoria.nombre_categoria as nombre_categoria');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    
    // Ordenar más recientes primero y limitar a 200
    $this->db->order_by('tbl_producto.id_producto', 'DESC');
    $this->db->limit($limit); // ← LÍMITE
    
    $query = $this->db->get();
    return $query->result_array();
}

public function get_productos_filtrados($searchText = '', $id_sucursal = NULL) {
    $this->db->select('tbl_producto.*, tbl_categoria.nombre_categoria as nombre_categoria');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    
    // Búsqueda
    if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_producto.nombre_producto', $searchText);
        $this->db->or_like('tbl_producto.codigo', $searchText);
        $this->db->group_end();
    }
    
    // Ordenar más recientes primero
    $this->db->order_by('tbl_producto.id_producto', 'DESC');
    
    $query = $this->db->get();
    return $query->result_array(); // Devuelve array para la vista
}

public function get_productos_para_etiquetas($id_sucursal, $searchText = '')
{
    $this->db->select('
        tbl_producto.id_producto,
        tbl_producto.nombre_producto,
        tbl_producto.codigo,
        tbl_producto.precio_venta,
        tbl_producto.categoria,
        tbl_categoria.nombre_categoria,
        COALESCE(tbl_producto_stock.stock, 0) as stock
    ');
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    $this->db->join(
        'tbl_producto_stock',
        'tbl_producto.id_producto = tbl_producto_stock.id_producto AND tbl_producto_stock.id_sucursal = ' . (int)$id_sucursal,
        'left'
    );

    if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_producto.nombre_producto', $searchText);
        $this->db->or_like('tbl_producto.codigo', $searchText);
        $this->db->group_end();
    }

    $this->db->order_by('tbl_producto.nombre_producto', 'ASC');

    return $this->db->get()->result_array();
}

/** AJAX search con filtros de categoría y stock; limitado a 400 filas */
public function get_productos_para_etiquetas_ajax($id_sucursal, $searchText = '', $categoria = 0, $stock_mode = 'all', $limit = 400)
{
    $select = 'tbl_producto.id_producto, tbl_producto.nombre_producto,
        tbl_producto.codigo, tbl_producto.precio_venta, tbl_producto.categoria,
        tbl_categoria.nombre_categoria,
        COALESCE(tbl_producto_stock.stock, 0) as stock';

    $this->db->select($select, false);
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    $this->db->join(
        'tbl_producto_stock',
        'tbl_producto.id_producto = tbl_producto_stock.id_producto AND tbl_producto_stock.id_sucursal = ' . (int)$id_sucursal,
        'left'
    );

    if (!empty($searchText)) {
        $this->db->group_start();
        $this->db->like('tbl_producto.nombre_producto', $searchText);
        $this->db->or_like('tbl_producto.codigo', $searchText);
        $this->db->group_end();
    }

    if ($categoria > 0) {
        $this->db->where('tbl_producto.categoria', $categoria);
    }

    if ($stock_mode === 'with_stock') {
        $this->db->where('COALESCE(tbl_producto_stock.stock, 0) > 0', null, false);
    } elseif ($stock_mode === 'without_stock') {
        $this->db->where('COALESCE(tbl_producto_stock.stock, 0) <= 0', null, false);
    }

    $this->db->order_by('tbl_producto.id_producto', 'DESC');
    $this->db->limit((int)$limit);

    return $this->db->get()->result_array();
}

/** Polling: sólo productos con id > $since_id */
public function get_productos_nuevos_para_etiquetas($id_sucursal, $since_id)
{
    $select = 'tbl_producto.id_producto, tbl_producto.nombre_producto,
        tbl_producto.codigo, tbl_producto.precio_venta, tbl_producto.categoria,
        tbl_categoria.nombre_categoria,
        COALESCE(tbl_producto_stock.stock, 0) as stock';

    $this->db->select($select, false);
    $this->db->from('tbl_producto');
    $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left');
    $this->db->join(
        'tbl_producto_stock',
        'tbl_producto.id_producto = tbl_producto_stock.id_producto AND tbl_producto_stock.id_sucursal = ' . (int)$id_sucursal,
        'left'
    );
    $this->db->where('tbl_producto.id_producto >', (int)$since_id);
    $this->db->order_by('tbl_producto.id_producto', 'ASC');
    $this->db->limit(200);

    return $this->db->get()->result_array();
}

/** Max id_producto global (índice PK — muy rápido) */
public function get_max_producto_id()
{
    $row = $this->db->select_max('id_producto', 'max_id')->from('tbl_producto')->get()->row();
    return $row ? (int)$row->max_id : 0;
}
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewProducto($productoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_producto', $productoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

          function addNewProductoStock($productoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_producto_stock', $productoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getProductoInfo($productoId)
    {
        $this->db->select('tbl_producto.*,tbl_categoria.nombre_categoria as nombre_categoria');
        $this->db->from('tbl_producto');
        $this->db->join('tbl_categoria', 'tbl_producto.categoria = tbl_categoria.id_categoria', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        $this->db->where('id_producto', $productoId);

        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
    function editProducto($productoInfo, $productoId)
    {
        $this->db->where('id_producto', $productoId);
        $this->db->update('tbl_producto', $productoInfo);
        
        return TRUE;
    }





    public function eliminar_producto($id) {
        $this->db->where('id_producto', $id);
        $this->db->delete('tbl_producto');
    }
    
   public function eliminar_producto_stock($id) {
        $this->db->where('id_producto', $id);
        $this->db->delete('tbl_producto_stock');
    }
    
    public function get_productos() {
        $query = $this->db->get('tbl_producto');
        return $query->result_array(); // Devuelve un array asociativo
    }


    
    public function get_categorias() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('id_categoria, nombre_categoria');
        $query = $this->db->get('tbl_categoria');

        return $query->result();
    }
 public function get_sucursales() {
    $query = $this->db->get('tbl_sucursal');
    return $query->result(); // 👈 devuelve objetos
}


    
    public function get_categoriasarray() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $query = $this->db->get('tbl_categoria');
        return $query->result_array(); // Devuelve un array asociativo
    }

public function importar_productos($file_path) {
    $csv_file = fopen($file_path, 'r');
    if ($csv_file === FALSE) {
        return array();
    }

    // ✅ DETECTAR AUTOMÁTICAMENTE EL SEPARADOR
    $first_line = fgets($csv_file);
    $separador = $this->detectar_separador($first_line);
    rewind($csv_file);
    
    // Limpiar BOM de UTF-8 si existe
    if (fgets($csv_file, 4) !== "\xEF\xBB\xBF") {
        rewind($csv_file);
    }
    
    $productos_validados = array();
    $errores = array();
    $row = 0;

    // ✅ PASO 1: VALIDAR TODOS LOS DATOS ANTES DE INSERTAR
    while (($line = fgetcsv($csv_file, 0, $separador)) !== FALSE) {
        $line = array_map(array($this, 'limpiar_campo_csv'), $line);

        // Saltar encabezado
        if ($row == 0) {
            $row++;
            continue;
        }

        // Validar mínimo campos
        if (count($line) < 7) {
            $errores[] = 'Línea ' . ($row + 1) . ' incompleta: necesita 7 campos';
            $row++;
            continue;
        }

        // Validar campos requeridos vacíos
        if (empty($line[0]) || empty($line[1]) || empty($line[2]) || empty($line[4])) {
            $errores[] = 'Línea ' . ($row + 1) . ' con campos requeridos vacíos';
            $row++;
            continue;
        }

        // Validar precios numéricos
        if (!is_numeric($line[1]) || !is_numeric($line[2])) {
            $errores[] = 'Línea ' . ($row + 1) . ' tiene precios no numéricos';
            $row++;
            continue;
        }

        // Validar stock numérico
        if (!empty($line[6]) && !is_numeric($line[6])) {
            $errores[] = 'Línea ' . ($row + 1) . ' tiene stock no numérico';
            $row++;
            continue;
        }

        // Preparar datos
        $nombre_producto = $this->security->xss_clean($line[0]);
        $precio_compra = (float)$line[1];
        $precio_venta = (float)$line[2];
        $codigo = $this->security->xss_clean($line[3]);
        $id_categoria = (int)$line[4];
        $detalles = !empty($line[5]) ? $this->security->xss_clean($line[5]) : 'Sin detalles';
        $stock = isset($line[6]) && is_numeric($line[6]) ? (int)$line[6] : 0;
        $talla = isset($line[7]) && !empty($line[7]) ? strtoupper($this->security->xss_clean($line[7])) : 'NA';

        // ✅ GESTIONAR CÓDIGO DE BARRAS
        if (empty($codigo)) {
            // Si no hay código, generar EAN-13 automáticamente
            $codigos_reservados = array_column($productos_validados, 'codigo');
            $codigo = $this->generar_ean13_automatico_unico($codigos_reservados);
            if (!$codigo) {
                $errores[] = 'Línea ' . ($row + 1) . ' no se pudo generar código (rango agotado)';
                $row++;
                continue;
            }
        } else {
            // Validar formato EAN-13 (13 dígitos)
            if (!$this->validar_formato_ean13($codigo)) {
                $errores[] = 'Línea ' . ($row + 1) . ' código inválido: "' . $codigo . '" debe ser EAN-13 (13 dígitos)';
                $row++;
                continue;
            }
            
            // Validar checksum EAN-13
            if (!$this->validar_checksum_ean13($codigo)) {
                $errores[] = 'Línea ' . ($row + 1) . ' código "' . $codigo . '" tiene checksum incorrecto';
                $row++;
                continue;
            }
        }

        // Validar categoría existe
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_categoria');
        $this->db->where('id_categoria', $id_categoria);
        $cat_row   = $this->db->get()->row();
        $cat_check = $cat_row ? (int)$cat_row->total : 0;

        if ($cat_check === 0) {
            $errores[] = 'Línea ' . ($row + 1) . ' tiene categoría inexistente (ID: ' . $id_categoria . ')';
            $row++;
            continue;
        }

        // ✅ Validar códigos duplicados DENTRO del CSV
        foreach ($productos_validados as $prod_check) {
            if ($prod_check['codigo'] === $codigo) {
                $errores[] = 'Línea ' . ($row + 1) . ' código duplicado: "' . $codigo . '" ya existe en esta importación (línea ' . $prod_check['row'] . ')';
                $row++;
                continue 2; // Salir del while externo
            }
        }

        // ✅ Validar que código NO EXISTA YA EN LA BASE DE DATOS
        $existe_en_bd = $this->validar_ean13_duplicado($codigo);
        if ($existe_en_bd) {
            $errores[] = 'Línea ' . ($row + 1) . ' código "' . $codigo . '" ya existe en la base de datos';
            $row++;
            continue;
        }

        // Guardar producto validado
        $productos_validados[] = array(
            'row' => $row + 1,
            'nombre_producto' => $nombre_producto,
            'precio_compra' => $precio_compra,
            'precio_venta' => $precio_venta,
            'codigo' => $codigo,
            'id_categoria' => $id_categoria,
            'detalles' => $detalles,
            'stock' => $stock,
            'talla' => $talla
        );

        $row++;
    }

    fclose($csv_file);

    // ✅ SI HAY ERRORES, NO INSERTAR NADA
    if (!empty($errores)) {
        $this->session->set_flashdata('import_warnings', implode('<br>', $errores));
        return array();
    }

    // ✅ PASO 2: INSERTAR/ACTUALIZAR PRODUCTOS (Sin errores previos)
    $productos_ids = array();
    $this->db->trans_start();

    foreach ($productos_validados as $prod) {
        // ✅ NUEVA LÓGICA: Buscar SOLO por EAN-13 (código)
        $producto_existente = $this->buscar_por_ean13($prod['codigo']);

        if ($producto_existente) {
            // RESURTIMIENTO: Producto existe → Solo aumentar stock
            $id_sucursal = $this->session->userdata('id_sucursal');
            
            $stock_actual = $this->obtener_stock_sucursal($producto_existente->id_producto, $id_sucursal);
            
            if ($stock_actual > 0) {
                // Actualizar stock existente
                $nuevo_stock = $stock_actual + $prod['stock'];
                $this->db->where('id_producto', $producto_existente->id_producto);
                $this->db->where('id_sucursal', $id_sucursal);
                $this->db->update('tbl_producto_stock', array('stock' => $nuevo_stock));
            } else {
                // Crear registro de stock si no existe
                $this->db->insert('tbl_producto_stock', array(
                    'id_producto' => $producto_existente->id_producto,
                    'id_sucursal' => $id_sucursal,
                    'stock' => $prod['stock']
                ));
            }

            $productos_ids[] = $producto_existente->id_producto;
        } else {
            // NUEVO PRODUCTO: Insertar
            $data = array(
                'nombre_producto' => $prod['nombre_producto'],
                'precio_compra' => $prod['precio_compra'],
                'precio_venta' => $prod['precio_venta'],
                'codigo' => $prod['codigo'],  // ← EAN-13 único
                'categoria' => $prod['id_categoria'],
                'imagen' => '',
                'detalles' => $prod['detalles'],
                'talla' => $prod['talla']
            );

            $this->db->insert('tbl_producto', $data);
            $id_producto = $this->db->insert_id();

            if ($id_producto) {
                $productos_ids[] = $id_producto;
                $id_sucursal = $this->session->userdata('id_sucursal');

                // Asignar stock a sucursal actual
                $this->db->insert('tbl_producto_stock', array(
                    'id_producto' => $id_producto,
                    'id_sucursal' => $id_sucursal,
                    'stock' => $prod['stock']
                ));
            }
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        return array();
    }

    return $productos_ids;
}

/**
 * ✅ Detecta automáticamente el separador del CSV
 */
private function detectar_separador($primera_linea) {
    $separadores = array(',', ';', "\t", '|');
    
    foreach ($separadores as $sep) {
        $campos = explode($sep, $primera_linea);
        if (count($campos) >= 7) {
            return $sep;
        }
    }
    
    return ',';
}

/**
 * Limpia un valor proveniente del CSV sin conservar comillas envolventes.
 * @param string $value
 * @return string
 */
private function limpiar_campo_csv($value)
{
    if (!mb_check_encoding($value, 'UTF-8')) {
        $value = mb_convert_encoding($value, 'UTF-8', 'auto');
    }

    $value = trim($value);

    if (strlen($value) >= 2 && $value[0] === '"' && substr($value, -1) === '"') {
        $value = substr($value, 1, -1);
    }

    return str_replace('""', '"', trim($value));
}



    function getconfiguracionInfo($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
    $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->row();
    }
    public function actualizarStock($data, $id_producto, $id_sucursal)
    {
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_sucursal', $id_sucursal);
        return $this->db->update('tbl_producto_stock', $data);
    }

    public function incrementar_stock_sucursal($id_producto, $id_sucursal, $cantidad)
    {
        $sql = "INSERT INTO tbl_producto_stock (id_producto, id_sucursal, stock)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE stock = stock + VALUES(stock)";
        return $this->db->query($sql, [(int)$id_producto, (int)$id_sucursal, (int)$cantidad]);
    }

}
