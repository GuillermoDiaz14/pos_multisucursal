<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Caja_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function cajaListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('tbl_caja');
        if(!empty($searchText)) {
            $likeCriteria = "(fecha_cierre LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
//        $this->db->where('esEliminado', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function cajaListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_caja');
        if(!empty($searchText)) {
            $likeCriteria = "(fecha_cierre LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
//        $this->db->where('esEliminado', 0);
        $this->db->order_by('id_caja', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewCaja($cajaInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_caja', $cajaInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */

    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
 




    
    function getCajaInfo($id_caja)
    {
        $this->db->select('id_caja, fecha_apertura, fecha_cierre,saldo');
        $this->db->from('tbl_caja');
        $this->db->where('id_caja', $id_caja);
        $query = $this->db->get();
        
        return $query->row();
    }
    




    /**
     * Cierra la caja abierta de la sucursal. Si se pasa $id_usuario, solo cierra
     * la caja de ese cajero específico (modo multi-cajero). Si es null, mantiene
     * el comportamiento legacy de cerrar todas las cajas abiertas de la sucursal.
     */
    public function cerrarCaja($id_sucursal, $id_usuario_cierre = null, $id_usuario = null)
    {
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $query = $this->db->get('tbl_caja');

        if ($query->num_rows() === 0) {
            return false;
        }

        $data = array(
            'estado'       => 'cerrado',
            'fecha_cierre' => date('Y-m-d H:i:s')
        );

        if ($id_usuario_cierre !== null) {
            $data['id_usuario_cierre'] = $id_usuario_cierre;
        }

        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('id_usuario', (int)$id_usuario);
        }
        $this->db->update('tbl_caja', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Devuelve la caja abierta del usuario en la sucursal (multi-cajero).
     * Si $id_usuario es null, devuelve la primera caja abierta de la sucursal (legacy).
     */
    public function getCajaAbiertaPorSucursal($id_sucursal, $id_usuario = null)
    {
        $this->db->select('c.*, u.name AS nombre_usuario_apertura');
        $this->db->from('tbl_caja c');
        $this->db->join('tbl_users u', 'u.userId = c.id_usuario', 'left');
        $this->db->where('c.estado', 'abierto');
        $this->db->where('c.id_sucursal', $id_sucursal);
        if ($id_usuario !== null) {
            $this->db->where('c.id_usuario', (int)$id_usuario);
        }
        $this->db->order_by('c.id_caja', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Resumen de movimientos del turno: ventas por método, gastos e ingresos.
     *
     * Si la migración 03 está aplicada (existe id_caja en tbl_venta/cuota/gasto/ingreso),
     * filtra por id_caja directamente — exacto y compatible con multi-cajero.
     *
     * Si la migración aún no está aplicada (legacy), filtra por sucursal + rango
     * [DATE(fecha_apertura), DATE(fecha_cierre/hoy)]. Limitación: las fechas son DATE,
     * por lo que pueden mezclarse turnos del mismo día si hubo más de uno.
     */
    public function getResumenCierre($id_caja)
    {
        $this->db->select('id_sucursal, fecha_apertura, fecha_cierre, monto_apertura, saldo, estado');
        $this->db->where('id_caja', $id_caja);
        $caja = $this->db->get('tbl_caja')->row();

        $resumen = array(
            'ventas_efectivo'      => 0.0,
            'ventas_otros_metodos' => 0.0,
            'gastos'               => 0.0,
            'ingresos'             => 0.0,
            'monto_apertura'       => 0.0,
            'saldo_sistema'        => 0.0,
            'detalle_metodos'      => array(),
        );

        if (!$caja) {
            return $resumen;
        }

        $resumen['monto_apertura'] = (float)$caja->monto_apertura;
        $resumen['saldo_sistema']  = (float)$caja->saldo;

        $useIdCaja = $this->db->field_exists('id_caja', 'tbl_venta')
                  && $this->db->field_exists('id_caja', 'tbl_cuota')
                  && $this->db->field_exists('id_caja', 'tbl_gasto')
                  && $this->db->field_exists('id_caja', 'tbl_ingreso');

        if ($useIdCaja) {
            // Modo preciso: filtrar por id_caja (post-migración 03).
            // Ventas por método (solo contado: crédito/apartado se reciben vía cuotas).
            $sql = "
                SELECT
                    COALESCE(LOWER(TRIM(mp.nombre_metodo_pago)), '') AS metodo,
                    COALESCE(SUM(v.total), 0) AS total
                FROM tbl_venta v
                LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago
                WHERE v.id_caja = ?
                  AND LOWER(TRIM(v.tipo_pago)) = 'contado'
                GROUP BY metodo
            ";
            $rows = $this->db->query($sql, array((int)$id_caja))->result();

            $sqlCuotas = "
                SELECT COALESCE(SUM(cuota), 0) AS total
                FROM tbl_cuota
                WHERE id_caja = ?
            ";
            $rowCuotas = $this->db->query($sqlCuotas, array((int)$id_caja))->row();

            $rowGasto = $this->db->query(
                "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_gasto WHERE id_caja = ?",
                array((int)$id_caja)
            )->row();

            $rowIng = $this->db->query(
                "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_ingreso WHERE id_caja = ?",
                array((int)$id_caja)
            )->row();
        } else {
            // Modo legacy: filtrar por sucursal + rango de fechas.
            $id_sucursal = (int)$caja->id_sucursal;
            $fechaIni    = date('Y-m-d', strtotime($caja->fecha_apertura));
            $fechaFin = (!empty($caja->fecha_cierre) && $caja->estado === 'cerrado')
                ? date('Y-m-d', strtotime($caja->fecha_cierre))
                : date('Y-m-d');

            $sql = "
                SELECT
                    COALESCE(LOWER(TRIM(mp.nombre_metodo_pago)), '') AS metodo,
                    COALESCE(SUM(v.total), 0) AS total
                FROM tbl_venta v
                LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago
                WHERE v.id_sucursal = ?
                  AND LOWER(TRIM(v.tipo_pago)) = 'contado'
                  AND v.fecha_venta >= ?
                  AND v.fecha_venta <= ?
                GROUP BY metodo
            ";
            $rows = $this->db->query($sql, array($id_sucursal, $fechaIni, $fechaFin))->result();

            $sqlCuotas = "
                SELECT COALESCE(SUM(c.cuota), 0) AS total
                FROM tbl_cuota c
                INNER JOIN tbl_venta v ON v.id_venta = c.id_venta
                WHERE v.id_sucursal = ?
                  AND c.fecha_pago >= ?
                  AND c.fecha_pago <= ?
            ";
            $rowCuotas = $this->db->query($sqlCuotas, array($id_sucursal, $fechaIni, $fechaFin))->row();

            $rowGasto = $this->db->query(
                "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_gasto
                 WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?",
                array($id_sucursal, $fechaIni, $fechaFin)
            )->row();

            $rowIng = $this->db->query(
                "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_ingreso
                 WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?",
                array($id_sucursal, $fechaIni, $fechaFin)
            )->row();
        }

        // Procesado común de los resultados
        foreach ($rows as $r) {
            $monto = (float)$r->total;
            $nombre = $r->metodo === '' ? '(sin método)' : ucfirst($r->metodo);
            $resumen['detalle_metodos'][$nombre] = $monto;
            if ($r->metodo === 'efectivo') {
                $resumen['ventas_efectivo'] += $monto;
            } else {
                $resumen['ventas_otros_metodos'] += $monto;
            }
        }

        if ($rowCuotas && (float)$rowCuotas->total > 0) {
            $cuotasTotal = (float)$rowCuotas->total;
            $resumen['ventas_efectivo'] += $cuotasTotal;
            $resumen['detalle_metodos']['Cuotas (apartado/crédito)'] = $cuotasTotal;
        }

        $resumen['gastos']   = $rowGasto ? (float)$rowGasto->total : 0.0;
        $resumen['ingresos'] = $rowIng   ? (float)$rowIng->total   : 0.0;

        return $resumen;
    }

    /**
     * Cierra una caja específica registrando el arqueo (esperado, contado, diferencia,
     * observaciones, usuario y fecha exacta de cierre).
     */
    /**
     * Devuelve la caja con nombres de usuario de apertura y cierre,
     * útil para mostrar/imprimir el ticket de cierre.
     */
    public function getCajaCompleta($id_caja)
    {
        $sql = "
            SELECT c.*,
                   ua.name AS nombre_usuario_apertura,
                   uc.name AS nombre_usuario_cierre,
                   s.nombre_sucursal,
                   s.simbolo_moneda
            FROM tbl_caja c
            LEFT JOIN tbl_users ua ON ua.userId = c.id_usuario
            LEFT JOIN tbl_users uc ON uc.userId = c.id_usuario_cierre
            LEFT JOIN tbl_sucursal s ON s.id_sucursal = c.id_sucursal
            WHERE c.id_caja = ?
            LIMIT 1
        ";
        return $this->db->query($sql, array((int)$id_caja))->row();
    }

    public function cerrarCajaConArqueo($id_caja, $arqueoData)
    {
        $data = array(
            'estado'               => 'cerrado',
            'fecha_cierre'         => date('Y-m-d H:i:s'),
            'efectivo_esperado'    => isset($arqueoData['efectivo_esperado']) ? (float)$arqueoData['efectivo_esperado'] : null,
            'efectivo_contado'     => isset($arqueoData['efectivo_contado']) ? (float)$arqueoData['efectivo_contado'] : null,
            'diferencia'           => isset($arqueoData['diferencia']) ? (float)$arqueoData['diferencia'] : null,
            'observaciones_cierre' => isset($arqueoData['observaciones']) ? $arqueoData['observaciones'] : null,
        );
        if (!empty($arqueoData['id_usuario_cierre'])) {
            $data['id_usuario_cierre'] = (int)$arqueoData['id_usuario_cierre'];
        }
        $this->db->where('id_caja', (int)$id_caja);
        $this->db->where('estado', 'abierto');
        $this->db->update('tbl_caja', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Histórico de cajas con cajero, sucursal y diferencia (faltante/sobrante).
     *
     * Filtros aceptados (todos opcionales):
     *   - id_sucursal: int o null (null = todas las sucursales).
     *   - id_usuario:  int o null (cajero de apertura).
     *   - estado:      'abierto' | 'cerrado' | null (todas).
     *   - fecha_inicial / fecha_final: Y-m-d sobre fecha_apertura.
     */
    public function getHistorialCajas($filters = array())
    {
        $this->db->select('c.id_caja, c.fecha_apertura, c.fecha_cierre, c.monto_apertura, '
            . 'c.saldo, c.estado, c.efectivo_esperado, c.efectivo_contado, c.diferencia, '
            . 'c.observaciones_cierre, c.id_sucursal, c.id_usuario, c.id_usuario_cierre, '
            . 'ua.name AS nombre_usuario_apertura, '
            . 'uc.name AS nombre_usuario_cierre, '
            . 's.nombre_sucursal');
        $this->db->from('tbl_caja c');
        $this->db->join('tbl_users ua', 'ua.userId = c.id_usuario', 'left');
        $this->db->join('tbl_users uc', 'uc.userId = c.id_usuario_cierre', 'left');
        $this->db->join('tbl_sucursal s', 's.id_sucursal = c.id_sucursal', 'left');

        if (!empty($filters['id_sucursal'])) {
            $this->db->where('c.id_sucursal', (int)$filters['id_sucursal']);
        }
        if (!empty($filters['id_usuario'])) {
            $this->db->where('c.id_usuario', (int)$filters['id_usuario']);
        }
        if (!empty($filters['estado'])) {
            $this->db->where('c.estado', $filters['estado']);
        }
        if (!empty($filters['fecha_inicial'])) {
            $this->db->where('DATE(c.fecha_apertura) >=', $filters['fecha_inicial']);
        }
        if (!empty($filters['fecha_final'])) {
            $this->db->where('DATE(c.fecha_apertura) <=', $filters['fecha_final']);
        }

        $this->db->order_by('c.fecha_apertura', 'DESC');
        $this->db->limit(500);

        return $this->db->get()->result();
    }

    /**
     * Resumen agregado del histórico: número de cajas, suma de sobrantes y faltantes,
     * y top de cajeros por descuadre acumulado.
     */
    public function getHistorialCajasResumen($filters = array())
    {
        $rows = $this->getHistorialCajas($filters);

        $resumen = array(
            'total_cajas'      => 0,
            'cerradas'         => 0,
            'abiertas'         => 0,
            'cuadradas'        => 0,
            'con_descuadre'    => 0,
            'suma_sobrante'    => 0.0,
            'suma_faltante'    => 0.0,
            'neto_diferencia'  => 0.0,
            'por_cajero'       => array(),
        );

        foreach ($rows as $r) {
            $resumen['total_cajas']++;
            if ($r->estado === 'cerrado') {
                $resumen['cerradas']++;
            } else {
                $resumen['abiertas']++;
            }

            $diff = (float)$r->diferencia;
            if ($r->estado === 'cerrado') {
                if (abs($diff) < 0.01) {
                    $resumen['cuadradas']++;
                } else {
                    $resumen['con_descuadre']++;
                    if ($diff > 0) {
                        $resumen['suma_sobrante'] += $diff;
                    } else {
                        $resumen['suma_faltante'] += $diff;
                    }
                    $resumen['neto_diferencia'] += $diff;
                }
            }

            $cajero = $r->nombre_usuario_apertura ?: '—';
            if (!isset($resumen['por_cajero'][$cajero])) {
                $resumen['por_cajero'][$cajero] = array(
                    'cajero'        => $cajero,
                    'cajas'         => 0,
                    'sobrante'      => 0.0,
                    'faltante'      => 0.0,
                    'neto'          => 0.0,
                );
            }
            $resumen['por_cajero'][$cajero]['cajas']++;
            if ($r->estado === 'cerrado' && abs($diff) >= 0.01) {
                if ($diff > 0) {
                    $resumen['por_cajero'][$cajero]['sobrante'] += $diff;
                } else {
                    $resumen['por_cajero'][$cajero]['faltante'] += $diff;
                }
                $resumen['por_cajero'][$cajero]['neto'] += $diff;
            }
        }

        $resumen['por_cajero'] = array_values($resumen['por_cajero']);
        usort($resumen['por_cajero'], function ($a, $b) {
            return abs($b['neto']) <=> abs($a['neto']);
        });

        return $resumen;
    }

    /**
     * Devuelve los movimientos detallados de una caja: ventas (contado), cuotas
     * (apartado/crédito), gastos e ingresos. Usa id_caja exacto cuando la migración 03
     * está aplicada; si no, hace fallback a sucursal + rango de fechas.
     */
    public function getMovimientosPorCaja($id_caja)
    {
        $caja = $this->getCajaCompleta($id_caja);
        $detalle = array(
            'ventas'   => array(),
            'cuotas'   => array(),
            'gastos'   => array(),
            'ingresos' => array(),
        );
        if (!$caja) {
            return $detalle;
        }

        $useIdCaja = $this->db->field_exists('id_caja', 'tbl_venta')
                  && $this->db->field_exists('id_caja', 'tbl_cuota')
                  && $this->db->field_exists('id_caja', 'tbl_gasto')
                  && $this->db->field_exists('id_caja', 'tbl_ingreso');

        if ($useIdCaja) {
            $detalle['ventas'] = $this->db->query("
                SELECT v.id_venta, v.fecha_venta, v.total, v.tipo_pago,
                       COALESCE(mp.nombre_metodo_pago, '') AS metodo_pago,
                       COALESCE(cl.nombre, '') AS cliente,
                       COALESCE(u.name, '') AS vendedor
                FROM tbl_venta v
                LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago
                LEFT JOIN tbl_cliente cl ON cl.id_cliente = v.id_cliente
                LEFT JOIN tbl_users u ON u.userId = v.id_usuario
                WHERE v.id_caja = ?
                ORDER BY v.fecha_venta ASC, v.id_venta ASC
            ", array((int)$id_caja))->result();

            $detalle['cuotas'] = $this->db->query("
                SELECT c.id_cuota, c.id_venta, c.cuota, c.fecha_pago,
                       v.tipo_pago, COALESCE(cl.nombre, '') AS cliente
                FROM tbl_cuota c
                LEFT JOIN tbl_venta v ON v.id_venta = c.id_venta
                LEFT JOIN tbl_cliente cl ON cl.id_cliente = v.id_cliente
                WHERE c.id_caja = ?
                ORDER BY c.fecha_pago ASC, c.id_cuota ASC
            ", array((int)$id_caja))->result();

            $detalle['gastos'] = $this->db->query("
                SELECT id_gasto, fecha, monto, descripcion
                FROM tbl_gasto
                WHERE id_caja = ?
                ORDER BY fecha ASC, id_gasto ASC
            ", array((int)$id_caja))->result();

            $detalle['ingresos'] = $this->db->query("
                SELECT id_ingreso, fecha, monto, descripcion
                FROM tbl_ingreso
                WHERE id_caja = ?
                ORDER BY fecha ASC, id_ingreso ASC
            ", array((int)$id_caja))->result();
        } else {
            $id_sucursal = (int)$caja->id_sucursal;
            $fechaIni = date('Y-m-d', strtotime($caja->fecha_apertura));
            $fechaFin = (!empty($caja->fecha_cierre) && $caja->estado === 'cerrado')
                ? date('Y-m-d', strtotime($caja->fecha_cierre))
                : date('Y-m-d');

            $detalle['ventas'] = $this->db->query("
                SELECT v.id_venta, v.fecha_venta, v.total, v.tipo_pago,
                       COALESCE(mp.nombre_metodo_pago, '') AS metodo_pago,
                       COALESCE(cl.nombre, '') AS cliente,
                       COALESCE(u.name, '') AS vendedor
                FROM tbl_venta v
                LEFT JOIN tbl_metodo_pago mp ON mp.id_metodo_pago = v.id_metodo_pago
                LEFT JOIN tbl_cliente cl ON cl.id_cliente = v.id_cliente
                LEFT JOIN tbl_users u ON u.userId = v.id_usuario
                WHERE v.id_sucursal = ?
                  AND v.fecha_venta >= ?
                  AND v.fecha_venta <= ?
                ORDER BY v.fecha_venta ASC, v.id_venta ASC
            ", array($id_sucursal, $fechaIni, $fechaFin))->result();

            $detalle['cuotas'] = $this->db->query("
                SELECT c.id_cuota, c.id_venta, c.cuota, c.fecha_pago,
                       v.tipo_pago, COALESCE(cl.nombre, '') AS cliente
                FROM tbl_cuota c
                INNER JOIN tbl_venta v ON v.id_venta = c.id_venta
                LEFT JOIN tbl_cliente cl ON cl.id_cliente = v.id_cliente
                WHERE v.id_sucursal = ?
                  AND c.fecha_pago >= ?
                  AND c.fecha_pago <= ?
                ORDER BY c.fecha_pago ASC, c.id_cuota ASC
            ", array($id_sucursal, $fechaIni, $fechaFin))->result();

            $detalle['gastos'] = $this->db->query("
                SELECT id_gasto, fecha, monto, descripcion
                FROM tbl_gasto
                WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?
                ORDER BY fecha ASC, id_gasto ASC
            ", array($id_sucursal, $fechaIni, $fechaFin))->result();

            $detalle['ingresos'] = $this->db->query("
                SELECT id_ingreso, fecha, monto, descripcion
                FROM tbl_ingreso
                WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?
                ORDER BY fecha ASC, id_ingreso ASC
            ", array($id_sucursal, $fechaIni, $fechaFin))->result();
        }

        return $detalle;
    }

    /**
     * Helper: usuarios que han tenido caja en una sucursal (para combo del filtro).
     * Si $id_sucursal es null/0, devuelve usuarios de todas las sucursales.
     */
    public function getCajerosConHistorial($id_sucursal = null)
    {
        $this->db->distinct();
        $this->db->select('u.userId, u.name');
        $this->db->from('tbl_caja c');
        $this->db->join('tbl_users u', 'u.userId = c.id_usuario', 'inner');
        if (!empty($id_sucursal)) {
            $this->db->where('c.id_sucursal', (int)$id_sucursal);
        }
        $this->db->order_by('u.name', 'ASC');
        return $this->db->get()->result();
    }
}
