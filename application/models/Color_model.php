<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Color_model extends CI_Model
{
    /**
     * Get all active colors
     * @return array
     */
    public function getAll()
    {
        return $this->db->where('activo', 1)
                        ->order_by('nombre_color', 'ASC')
                        ->get('tbl_color')
                        ->result();
    }

    /**
     * Get all colors including inactive ones (admin only)
     * @return array
     */
    public function getAllIncludingInactive()
    {
        return $this->db->order_by('activo', 'DESC')
                        ->order_by('nombre_color', 'ASC')
                        ->get('tbl_color')
                        ->result();
    }

    /**
     * Get color by ID
     * @param int $id_color
     * @return object|null
     */
    public function getById($id_color)
    {
        return $this->db->where('id_color', $id_color)
                        ->get('tbl_color')
                        ->row();
    }

    /**
     * Get color by name
     * @param string $nombre_color
     * @return object|null
     */
    public function getByName($nombre_color)
    {
        return $this->db->where('nombre_color', $nombre_color)
                        ->get('tbl_color')
                        ->row();
    }

    /**
     * Insert new color
     * @param array $data
     * @return int - ID of inserted color
     */
    public function insert($data)
    {
        if ($this->db->insert('tbl_color', $data)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    /**
     * Update color
     * @param int $id_color
     * @param array $data
     * @return bool
     */
    public function update($id_color, $data)
    {
        $data['updatedDtm'] = date('Y-m-d H:i:s');
        return $this->db->where('id_color', $id_color)
                        ->update('tbl_color', $data);
    }

    /**
     * Deactivate color
     * @param int $id_color
     * @return bool
     */
    public function deactivate($id_color)
    {
        return $this->update($id_color, ['activo' => 0]);
    }

    /**
     * Activate color
     * @param int $id_color
     * @return bool
     */
    public function activate($id_color)
    {
        return $this->update($id_color, ['activo' => 1]);
    }

    /**
     * Count products by color
     * @param int $id_color
     * @return int
     */
    public function countProducts($id_color)
    {
        return $this->db->where('id_color', $id_color)
                        ->count_all_results('tbl_producto');
    }
}
