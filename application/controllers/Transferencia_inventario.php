<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transferencia_inventario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transferencia_inventario_model');
    }

    /**
     * GET: http://localhost/pos_multisucursal/transferencia_inventario/ejecutar
     */
    public function ejecutar() {
        // Parámetros
        $nombre_origen = 'ropa';
        $nombre_destino = 'zapateria';
        $id_usuario = $this->session->userdata('userId'); // Usuario actual

        if (!$id_usuario) {
            echo json_encode(['status' => false, 'msg' => 'Debe estar autenticado']);
            return;
        }

        $resultado = $this->Transferencia_inventario_model->transferir_inventario_completo(
            $nombre_origen,
            $nombre_destino,
            $id_usuario
        );

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    /**
     * Verifica estado actual de inventario antes/después
     */
    public function verificar_inventario($nombre_sucursal) {
        $this->load->model('Producto_model');
        $this->load->model('Sucursal_model');

        $sucursal = $this->db->where('nombre_sucursal', $nombre_sucursal)->get('tbl_sucursal')->row();

        if (!$sucursal) {
            echo json_encode(['status' => false, 'msg' => 'Sucursal no encontrada']);
            return;
        }

        $productos = $this->db->select('tbl_producto.nombre_producto, tbl_producto_stock.stock')
                              ->from('tbl_producto')
                              ->join('tbl_producto_stock', 'tbl_producto.id_producto = tbl_producto_stock.id_producto', 'inner')
                              ->where('tbl_producto_stock.id_sucursal', $sucursal->id_sucursal)
                              ->where('tbl_producto_stock.stock >', 0)
                              ->get()
                              ->result();

        header('Content-Type: application/json');
        echo json_encode([
            'sucursal' => $nombre_sucursal,
            'total_productos' => count($productos),
            'productos' => $productos
        ]);
    }
}
?>
