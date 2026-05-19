<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Movimientos de Inventario
 *
 * Devuelve la línea de tiempo de entradas/salidas de un producto (con o sin variante)
 * uniendo ventas, compras y traslados (entrada/salida) por sucursal.
 */
class Movimiento_inventario_model extends CI_Model
{
    /**
     * Movimientos del producto/variante en una sucursal y rango de fechas.
     *
     * @return array { rows: [...], totales: {entradas, salidas, neto, saldo_inicial, saldo_final} }
     */
    public function getMovimientos($id_sucursal, $id_producto, $id_variante, $fechaInicial, $fechaFinal)
    {
        $id_sucursal  = (int) $id_sucursal;
        $id_producto  = (int) $id_producto;
        $id_variante  = (int) $id_variante;
        $varFilter    = $id_variante > 0 ? ' AND m.id_variante = ?' : '';
        $varParams    = $id_variante > 0 ? array($id_variante) : array();

        $sql = "
            SELECT * FROM (
                SELECT
                    v.fecha_venta AS fecha,
                    'venta' AS tipo,
                    v.id_venta AS referencia,
                    dv.id_producto,
                    dv.id_variante,
                    -dv.cantidad AS movimiento,
                    v.id_sucursal,
                    COALESCE(u.name, '') AS usuario
                FROM tbl_detalle_venta dv
                INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
                LEFT JOIN tbl_users u ON u.userId = v.id_usuario
                WHERE dv.id_producto = ?

                UNION ALL

                SELECT
                    c.fecha_compra,
                    'compra',
                    c.id_compra,
                    dc.id_producto,
                    dc.id_variante,
                    dc.cantidad,
                    c.id_sucursal,
                    COALESCE(u.name, '')
                FROM tbl_detalle_compra dc
                INNER JOIN tbl_compra c ON c.id_compra = dc.id_compra
                LEFT JOIN tbl_users u ON u.userId = c.id_usuario
                WHERE dc.id_producto = ?

                UNION ALL

                SELECT
                    t.fecha_actual,
                    'traslado_salida',
                    t.id_traslado,
                    dt.id_producto,
                    dt.id_variante,
                    -dt.cantidad,
                    t.id_sucursal_descuento,
                    COALESCE(u.name, '')
                FROM tbl_detalle_traslado dt
                INNER JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
                LEFT JOIN tbl_users u ON u.userId = t.id_usuario
                WHERE dt.id_producto = ?

                UNION ALL

                SELECT
                    t.fecha_actual,
                    'traslado_entrada',
                    t.id_traslado,
                    dt.id_producto,
                    dt.id_variante,
                    dt.cantidad,
                    t.id_sucursal_aumento,
                    COALESCE(u.name, '')
                FROM tbl_detalle_traslado dt
                INNER JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
                LEFT JOIN tbl_users u ON u.userId = t.id_usuario
                WHERE dt.id_producto = ?
            ) m
            WHERE m.id_sucursal = ?
              AND m.fecha BETWEEN ? AND ?
              $varFilter
            ORDER BY m.fecha ASC, m.tipo ASC, m.referencia ASC
        ";

        $params = array($id_producto, $id_producto, $id_producto, $id_producto, $id_sucursal, $fechaInicial, $fechaFinal);
        $params = array_merge($params, $varParams);

        $rows = $this->db->query($sql, $params)->result_array();

        // Saldo inicial = stock actual - (entradas - salidas dentro de rango y posteriores)
        // Más simple y exacto: stock actual menos movimientos POSTERIORES al fechaFinal,
        // luego saldo_inicial = saldo_final_periodo - neto_periodo.
        $stockActual = $this->_stockActual($id_sucursal, $id_producto, $id_variante);

        // Neto posterior a fechaFinal (para reconstruir saldo a fechaFinal)
        $netoPosterior = $this->_netoMovimientos($id_sucursal, $id_producto, $id_variante, $fechaFinal, null, true);

        $saldoFinal = $stockActual - $netoPosterior;

        $entradas = 0.0;
        $salidas  = 0.0;
        foreach ($rows as &$r) {
            $r['cantidad']   = (float) $r['movimiento'];
            $r['referencia'] = (int) $r['referencia'];
            if ($r['cantidad'] > 0) {
                $entradas += $r['cantidad'];
            } else {
                $salidas  += abs($r['cantidad']);
            }
        }
        unset($r);

        $neto         = $entradas - $salidas;
        $saldoInicial = $saldoFinal - $neto;

        // Construir saldo corriente fila por fila
        $saldo = $saldoInicial;
        foreach ($rows as &$r) {
            $saldo += $r['cantidad'];
            $r['saldo'] = $saldo;
        }
        unset($r);

        return array(
            'rows' => $rows,
            'totales' => array(
                'entradas'      => $entradas,
                'salidas'       => $salidas,
                'neto'          => $neto,
                'saldo_inicial' => $saldoInicial,
                'saldo_final'   => $saldoFinal,
            ),
        );
    }

    private function _stockActual($id_sucursal, $id_producto, $id_variante)
    {
        if ($id_variante > 0) {
            $row = $this->db->select('stock')
                ->where('id_variante', $id_variante)
                ->where('id_sucursal', $id_sucursal)
                ->get('tbl_stock_variante')->row();
            return $row ? (float) $row->stock : 0.0;
        }

        $tieneVar = (int) $this->db->select('tiene_variantes')
            ->where('id_producto', $id_producto)
            ->get('tbl_producto')->row()->tiene_variantes;

        if ($tieneVar === 1) {
            $row = $this->db->query(
                "SELECT COALESCE(SUM(sv.stock),0) AS total
                 FROM tbl_producto_variante v
                 LEFT JOIN tbl_stock_variante sv ON sv.id_variante = v.id_variante AND sv.id_sucursal = ?
                 WHERE v.id_producto = ? AND v.activo = 1",
                array($id_sucursal, $id_producto)
            )->row();
            return $row ? (float) $row->total : 0.0;
        }

        $row = $this->db->select('stock')
            ->where('id_producto', $id_producto)
            ->where('id_sucursal', $id_sucursal)
            ->get('tbl_producto_stock')->row();
        return $row ? (float) $row->stock : 0.0;
    }

    /**
     * Calcula el neto (entradas - salidas) en un rango. Si $strictlyAfter=true,
     * usa fecha > $fechaDesde sin tope superior (movimientos posteriores).
     */
    private function _netoMovimientos($id_sucursal, $id_producto, $id_variante, $fechaDesde, $fechaHasta, $strictlyAfter = false)
    {
        $cmp = $strictlyAfter ? '>' : '>=';
        $varFilter = $id_variante > 0 ? ' AND m.id_variante = ?' : '';
        $varParams = $id_variante > 0 ? array($id_variante) : array();
        $hastaFilter = $fechaHasta !== null ? ' AND m.fecha <= ?' : '';
        $hastaParams = $fechaHasta !== null ? array($fechaHasta) : array();

        $sql = "
            SELECT COALESCE(SUM(m.movimiento), 0) AS neto FROM (
                SELECT v.fecha_venta AS fecha, dv.id_producto, dv.id_variante, -dv.cantidad AS movimiento, v.id_sucursal
                FROM tbl_detalle_venta dv INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
                WHERE dv.id_producto = ?
                UNION ALL
                SELECT c.fecha_compra, dc.id_producto, dc.id_variante, dc.cantidad, c.id_sucursal
                FROM tbl_detalle_compra dc INNER JOIN tbl_compra c ON c.id_compra = dc.id_compra
                WHERE dc.id_producto = ?
                UNION ALL
                SELECT t.fecha_actual, dt.id_producto, dt.id_variante, -dt.cantidad, t.id_sucursal_descuento
                FROM tbl_detalle_traslado dt INNER JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
                WHERE dt.id_producto = ?
                UNION ALL
                SELECT t.fecha_actual, dt.id_producto, dt.id_variante, dt.cantidad, t.id_sucursal_aumento
                FROM tbl_detalle_traslado dt INNER JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
                WHERE dt.id_producto = ?
            ) m
            WHERE m.id_sucursal = ?
              AND m.fecha $cmp ?
              $hastaFilter
              $varFilter
        ";

        $params = array($id_producto, $id_producto, $id_producto, $id_producto, $id_sucursal, $fechaDesde);
        $params = array_merge($params, $hastaParams, $varParams);

        $row = $this->db->query($sql, $params)->row();
        return $row ? (float) $row->neto : 0.0;
    }

    public function getProductoConVariantes($id_producto)
    {
        $producto = $this->db->select('p.id_producto, p.codigo, p.nombre_producto, COALESCE(p.tiene_variantes,0) AS tiene_variantes, c.nombre_categoria')
            ->from('tbl_producto p')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->where('p.id_producto', (int) $id_producto)
            ->get()->row_array();

        if (!$producto) return null;

        $producto['variantes'] = array();
        if ((int) $producto['tiene_variantes'] === 1) {
            $producto['variantes'] = $this->db->select('id_variante, talla, orden')
                ->where('id_producto', (int) $id_producto)
                ->where('activo', 1)
                ->order_by('orden ASC, id_variante ASC')
                ->get('tbl_producto_variante')->result_array();
        }
        return $producto;
    }

    public function buscarProductos($termino, $limit = 25)
    {
        $like = '%' . $termino . '%';
        return $this->db->select('id_producto, codigo, nombre_producto, COALESCE(tiene_variantes,0) AS tiene_variantes')
            ->from('tbl_producto')
            ->group_start()
              ->like('nombre_producto', $termino)
              ->or_like('codigo', $termino)
            ->group_end()
            ->where('activo', 1)
            ->limit((int) $limit)
            ->get()->result_array();
    }
}
