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
    




    public function cerrarCaja($id_sucursal, $id_usuario_cierre = null)
    {
        $this->db->where('estado', 'abierto');
        $this->db->where('id_sucursal', $id_sucursal);
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
        $this->db->update('tbl_caja', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Devuelve la caja abierta de la sucursal con datos del cajero que la abrió.
     */
    public function getCajaAbiertaPorSucursal($id_sucursal)
    {
        $this->db->select('c.*, u.name AS nombre_usuario_apertura');
        $this->db->from('tbl_caja c');
        $this->db->join('tbl_users u', 'u.userId = c.id_usuario', 'left');
        $this->db->where('c.estado', 'abierto');
        $this->db->where('c.id_sucursal', $id_sucursal);
        $this->db->order_by('c.id_caja', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Resumen de movimientos del turno actual: ventas por método, gastos e ingresos.
     * Filtra por sucursal y por el rango [DATE(fecha_apertura), CURDATE()].
     *
     * Limitación conocida: tbl_venta/tbl_gasto/tbl_ingreso usan DATE en sus fechas,
     * por lo que no se puede acotar por hora. Si una sucursal solo abre una caja
     * por día, esto es exacto; en otros casos puede mezclar movimientos de
     * turnos previos del mismo día.
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

        $id_sucursal = (int)$caja->id_sucursal;
        $fechaIni    = date('Y-m-d', strtotime($caja->fecha_apertura));
        // Si la caja está cerrada, acotamos el rango con su fecha_cierre real.
        // Si sigue abierta, usamos hoy.
        $fechaFin = (!empty($caja->fecha_cierre) && $caja->estado === 'cerrado')
            ? date('Y-m-d', strtotime($caja->fecha_cierre))
            : date('Y-m-d');

        // Ventas agrupadas por método (solo tipo_pago=contado afecta caja directamente;
        // crédito/apartado se reciben vía cuotas y no las contamos aquí).
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

        // Cuotas cobradas en el rango (apartado/crédito). No tenemos método por cuota,
        // así que se asumen efectivo (consistente con la lógica de aumentarSaldo legacy).
        $sqlCuotas = "
            SELECT COALESCE(SUM(c.cuota), 0) AS total
            FROM tbl_cuota c
            INNER JOIN tbl_venta v ON v.id_venta = c.id_venta
            WHERE v.id_sucursal = ?
              AND c.fecha_pago >= ?
              AND c.fecha_pago <= ?
        ";
        $rowCuotas = $this->db->query($sqlCuotas, array($id_sucursal, $fechaIni, $fechaFin))->row();
        if ($rowCuotas && (float)$rowCuotas->total > 0) {
            $cuotasTotal = (float)$rowCuotas->total;
            $resumen['ventas_efectivo'] += $cuotasTotal;
            $resumen['detalle_metodos']['Cuotas (apartado/crédito)'] = $cuotasTotal;
        }

        // Gastos
        $rowGasto = $this->db->query(
            "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_gasto
             WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?",
            array($id_sucursal, $fechaIni, $fechaFin)
        )->row();
        $resumen['gastos'] = $rowGasto ? (float)$rowGasto->total : 0.0;

        // Ingresos
        $rowIng = $this->db->query(
            "SELECT COALESCE(SUM(monto),0) AS total FROM tbl_ingreso
             WHERE id_sucursal = ? AND fecha >= ? AND fecha <= ?",
            array($id_sucursal, $fechaIni, $fechaFin)
        )->row();
        $resumen['ingresos'] = $rowIng ? (float)$rowIng->total : 0.0;

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
}
