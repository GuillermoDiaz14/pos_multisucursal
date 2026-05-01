<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Transferencia_inventario_model extends CI_Model {

    /**
     * Transfiere TODO el inventario de una sucursal a otra
     * @param string $nombre_sucursal_origen: nombre de sucursal origen
     * @param string $nombre_sucursal_destino: nombre de sucursal destino
     * @param int $id_usuario: usuario que realiza la transferencia
     * @return array: resultado con status y detalles
     */
    public function transferir_inventario_completo($nombre_sucursal_origen, $nombre_sucursal_destino, $id_usuario) {

        $this->db->trans_start();

        // Obtener IDs de sucursales
        $id_origen = $this->_obtener_id_sucursal($nombre_sucursal_origen);
        $id_destino = $this->_obtener_id_sucursal($nombre_sucursal_destino);

        if (!$id_origen || !$id_destino) {
            return ['status' => false, 'msg' => 'Sucursal no encontrada'];
        }

        // Obtener productos con stock en origen
        $productos = $this->_obtener_productos_con_stock($id_origen);

        if (empty($productos)) {
            return ['status' => false, 'msg' => 'No hay inventario para transferir'];
        }

        // Crear registro de traslado
        $traslado_data = [
            'fecha_actual' => date('Y-m-d'),
            'comentario' => 'Transferencia completa de inventario',
            'id_usuario' => $id_usuario,
            'id_sucursal_descuento' => $id_origen,
            'id_sucursal_aumento' => $id_destino
        ];

        $this->db->insert('tbl_traslado', $traslado_data);
        $id_traslado = $this->db->insert_id();

        $total_items = 0;

        // Transferir cada producto
        foreach ($productos as $producto) {
            $cantidad = (int)$producto->stock;

            // Registrar detalle de traslado
            $detalle_data = [
                'id_producto' => $producto->id_producto,
                'cantidad' => $cantidad,
                'id_traslado' => $id_traslado
            ];
            $this->db->insert('tbl_detalle_traslado', $detalle_data);

            // Restar stock de origen
            $this->db->set('stock', 'stock - ' . $cantidad, FALSE);
            $this->db->where('id_producto', $producto->id_producto);
            $this->db->where('id_sucursal', $id_origen);
            $this->db->update('tbl_producto_stock');

            // Sumar stock en destino
            $existe_en_destino = $this->db->where('id_producto', $producto->id_producto)
                                          ->where('id_sucursal', $id_destino)
                                          ->get('tbl_producto_stock')
                                          ->num_rows();

            if ($existe_en_destino > 0) {
                $this->db->set('stock', 'stock + ' . $cantidad, FALSE);
                $this->db->where('id_producto', $producto->id_producto);
                $this->db->where('id_sucursal', $id_destino);
                $this->db->update('tbl_producto_stock');
            } else {
                $insert_data = [
                    'id_producto' => $producto->id_producto,
                    'stock' => $cantidad,
                    'id_sucursal' => $id_destino
                ];
                $this->db->insert('tbl_producto_stock', $insert_data);
            }

            $total_items++;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => false, 'msg' => 'Error en la transferencia'];
        }

        return [
            'status' => true,
            'msg' => "Transferencia completada: $total_items productos",
            'id_traslado' => $id_traslado
        ];
    }

    private function _obtener_id_sucursal($nombre) {
        $query = $this->db->where('nombre_sucursal', $nombre)->get('tbl_sucursal');
        return $query->num_rows() > 0 ? $query->row()->id_sucursal : false;
    }

    private function _obtener_productos_con_stock($id_sucursal) {
        return $this->db->where('id_sucursal', $id_sucursal)
                       ->where('stock >', 0)
                       ->get('tbl_producto_stock')
                       ->result();
    }
}
?>
