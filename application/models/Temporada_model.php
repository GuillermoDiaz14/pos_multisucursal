<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temporada_model extends CI_Model
{
    /**
     * Get all active seasons
     * @return array
     */
    public function getAll()
    {
        return $this->db->where('activa', 1)
                        ->order_by('nombre_temporada', 'ASC')
                        ->get('tbl_temporada')
                        ->result();
    }

    /**
     * Get all seasons including inactive ones (admin only)
     * @return array
     */
    public function getAllIncludingInactive()
    {
        return $this->db->order_by('activa', 'DESC')
                        ->order_by('nombre_temporada', 'ASC')
                        ->get('tbl_temporada')
                        ->result();
    }

    /**
     * Get season by ID
     * @param int $id_temporada
     * @return object|null
     */
    public function getById($id_temporada)
    {
        return $this->db->where('id_temporada', $id_temporada)
                        ->get('tbl_temporada')
                        ->row();
    }

    /**
     * Get current active season (by date range)
     * @return object|null
     */
    public function getCurrentSeason()
    {
        $today = date('Y-m-d');
        $this->db->where('activa', 1);
        $this->db->where('(fecha_inicio IS NULL OR fecha_inicio <= "' . $today . '")');
        $this->db->where('(fecha_fin IS NULL OR fecha_fin >= "' . $today . '")');
        
        return $this->db->get('tbl_temporada')->row();
    }

    /**
     * Insert new season
     * @param array $data
     * @return int - ID of inserted season
     */
    public function insert($data)
    {
        if ($this->db->insert('tbl_temporada', $data)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    /**
     * Update season
     * @param int $id_temporada
     * @param array $data
     * @return bool
     */
    public function update($id_temporada, $data)
    {
        $data['updatedDtm'] = date('Y-m-d H:i:s');
        return $this->db->where('id_temporada', $id_temporada)
                        ->update('tbl_temporada', $data);
    }

    /**
     * Deactivate season
     * @param int $id_temporada
     * @return bool
     */
    public function deactivate($id_temporada)
    {
        return $this->update($id_temporada, ['activa' => 0]);
    }

    /**
     * Activate season
     * @param int $id_temporada
     * @return bool
     */
    public function activate($id_temporada)
    {
        return $this->update($id_temporada, ['activa' => 1]);
    }

    /**
     * Count products by season
     * @param int $id_temporada
     * @return int
     */
    public function countProducts($id_temporada)
    {
        return $this->db->where('id_temporada', $id_temporada)
                        ->count_all_results('tbl_producto');
    }
}
