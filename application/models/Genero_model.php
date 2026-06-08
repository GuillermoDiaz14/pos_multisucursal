<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Genero_model extends CI_Model
{
    public function getAll()
    {
        return $this->db->where('activa', 1)
                        ->order_by('nombre_genero', 'ASC')
                        ->get('tbl_genero')
                        ->result();
    }

    public function getAllIncludingInactive()
    {
        return $this->db->order_by('activa', 'DESC')
                        ->order_by('nombre_genero', 'ASC')
                        ->get('tbl_genero')
                        ->result();
    }

    public function getById($id_genero)
    {
        return $this->db->where('id_genero', $id_genero)
                        ->get('tbl_genero')
                        ->row();
    }

    public function insert($data)
    {
        if ($this->db->insert('tbl_genero', $data)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    public function update($id_genero, $data)
    {
        $data['updatedDtm'] = date('Y-m-d H:i:s');
        return $this->db->where('id_genero', $id_genero)
                        ->update('tbl_genero', $data);
    }

    public function deactivate($id_genero)
    {
        return $this->update($id_genero, ['activa' => 0]);
    }

    public function activate($id_genero)
    {
        return $this->update($id_genero, ['activa' => 1]);
    }
}
?>
