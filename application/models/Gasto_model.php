<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Gasto_model extends CI_Model
{
    function gastoListingCount($searchText, $id_sucursal)
    {
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_gasto');
        if (!empty($searchText)) {
            $this->db->like('descripcion', $searchText);
        }
        $this->db->where('id_sucursal', (int)$id_sucursal);
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    function gastoListing($searchText, $id_sucursal, $limit = 50, $offset = 0)
    {
        $this->db->select('id_gasto, descripcion, monto, fecha, id_sucursal');
        $this->db->from('tbl_gasto');
        if (!empty($searchText)) {
            $this->db->like('descripcion', $searchText);
        }
        $this->db->where('id_sucursal', (int)$id_sucursal);
        $this->db->order_by('id_gasto', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }

    /**
     * Resumen de gastos para las cards del dashboard.
     * 3 queries simples en vez de calcular sobre filas paginadas.
     */
    public function getGastoStats($id_sucursal)
    {
        $id_sucursal = (int)$id_sucursal;

        // Total registros
        $this->db->select('COUNT(*) as total, COALESCE(SUM(monto), 0) as total_monto', false);
        $this->db->where('id_sucursal', $id_sucursal);
        $row = $this->db->get('tbl_gasto')->row();

        // Monto mes actual
        $this->db->select('COALESCE(SUM(monto), 0) as monto_mes', false);
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->where('MONTH(fecha)', (int)date('n'), false);
        $this->db->where('YEAR(fecha)', (int)date('Y'), false);
        $rowMes = $this->db->get('tbl_gasto')->row();

        return [
            'total'       => $row ? (int)$row->total : 0,
            'total_monto' => $row ? (float)$row->total_monto : 0,
            'monto_mes'   => $rowMes ? (float)$rowMes->monto_mes : 0,
        ];
    }

    function addNewGasto($gastoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_gasto', $gastoInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function editGasto($gastoInfo, $gastoId)
    {
        $this->db->where('id_gasto', (int)$gastoId);
        $this->db->update('tbl_gasto', $gastoInfo);
        return TRUE;
    }

    public function eliminar_gasto($id)
    {
        $this->db->where('id_gasto', (int)$id);
        $this->db->delete('tbl_gasto');
    }

    function getGastoInfo($id_gasto)
    {
        $this->db->select('id_gasto, descripcion, monto, fecha, id_sucursal');
        $this->db->from('tbl_gasto');
        $this->db->where('id_gasto', (int)$id_gasto);
        return $this->db->get()->row();
    }
}
