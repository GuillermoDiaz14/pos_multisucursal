<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subcategoria_model extends CI_Model
{
    /**
     * Get all active subcategories with optional filters
     * @param int $id_categoria - Filter by category (optional)
     * @param int $id_sucursal - Filter by branch (optional)
     * @return array
     */
    public function getAll($id_categoria = 0, $id_sucursal = 0)
    {
        $this->db->select('s.*, c.nombre_categoria');
        $this->db->from('tbl_subcategoria s');
        $this->db->join('tbl_categoria c', 'c.id_categoria = s.id_categoria', 'left');
        $this->db->where('s.activa', 1);
        
        if ($id_categoria > 0) {
            $this->db->where('s.id_categoria', $id_categoria);
        }
        if ($id_sucursal > 0) {
            $this->db->where('s.id_sucursal', $id_sucursal);
        }
        
        $this->db->order_by('c.nombre_categoria', 'ASC');
        $this->db->order_by('s.nombre_subcategoria', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get subcategories by category
     * @param int $id_categoria
     * @param int $id_sucursal
     * @return array
     */
    public function getByCategoria($id_categoria, $id_sucursal = 0)
    {
        $this->db->select('id_subcategoria, nombre_subcategoria');
        $this->db->from('tbl_subcategoria');
        $this->db->where('id_categoria', $id_categoria);
        $this->db->where('activa', 1);
        
        if ($id_sucursal > 0) {
            $this->db->where('id_sucursal', $id_sucursal);
        }
        
        $this->db->order_by('nombre_subcategoria', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get subcategory by ID
     * @param int $id_subcategoria
     * @return object|null
     */
    public function getById($id_subcategoria)
    {
        return $this->db->where('id_subcategoria', $id_subcategoria)
                        ->get('tbl_subcategoria')
                        ->row();
    }

    /**
     * Insert new subcategory
     * @param array $data
     * @return int - ID of inserted subcategory
     */
    public function insert($data)
    {
        if ($this->db->insert('tbl_subcategoria', $data)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    /**
     * Update subcategory
     * @param int $id_subcategoria
     * @param array $data
     * @return bool
     */
    public function update($id_subcategoria, $data)
    {
        $data['updatedDtm'] = date('Y-m-d H:i:s');
        return $this->db->where('id_subcategoria', $id_subcategoria)
                        ->update('tbl_subcategoria', $data);
    }

    /**
     * Soft delete subcategory (deactivate)
     * @param int $id_subcategoria
     * @return bool
     */
    public function deactivate($id_subcategoria)
    {
        return $this->update($id_subcategoria, ['activa' => 0]);
    }

    /**
     * Count subcategories by category
     * @param int $id_categoria
     * @return int
     */
    public function countByCategoria($id_categoria)
    {
        return $this->db->where('id_categoria', $id_categoria)
                        ->where('activa', 1)
                        ->count_all_results('tbl_subcategoria');
    }
}
