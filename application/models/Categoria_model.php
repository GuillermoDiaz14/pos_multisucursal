<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Categoria_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function categoriaListingCount($searchText, $id_sucursal = null)
    {
        $this->db->select('COUNT(*) as total', false);
        $this->db->from('tbl_categoria');
        if ($id_sucursal) {
            $this->db->where('id_sucursal', (int)$id_sucursal);
        }
        if (!empty($searchText)) {
            $this->db->like('nombre_categoria', $searchText);
        }
        $row = $this->db->get()->row();
        return $row ? (int)$row->total : 0;
    }

    function categoriaListing($searchText, $limit = 50, $offset = 0, $id_sucursal = null)
    {
        $this->db->select('id_categoria, nombre_categoria');
        $this->db->from('tbl_categoria');
        if ($id_sucursal) {
            $this->db->where('id_sucursal', (int)$id_sucursal);
        }
        if (!empty($searchText)) {
            $this->db->like('nombre_categoria', $searchText);
        }
        $this->db->order_by('id_categoria', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }

    /**
     * Get active categories for selects
     * @param int|null $id_sucursal
     * @return array
     */
    public function get_categorias($id_sucursal = null)
    {
        $this->db->select('id_categoria, nombre_categoria');
        $this->db->from('tbl_categoria');
        if ($id_sucursal) {
            $this->db->where('id_sucursal', (int)$id_sucursal);
        }
        $this->db->order_by('nombre_categoria', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewCategoria($gastoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_categoria', $gastoInfo);
        
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
    function editCategoria($categoriaInfo, $categoriaId)
    {
        $this->db->where('id_categoria', $categoriaId);
        $this->db->update('tbl_categoria', $categoriaInfo);
        
        return TRUE;
    }





    public function eliminar_categoria($id) {
        $this->db->where('id_categoria', $id);
        $this->db->delete('tbl_categoria');
    }
    
    function getCategoriaInfo($id_gasto)
    {
        $this->db->select('id_categoria, nombre_categoria');
        $this->db->from('tbl_categoria');
        $this->db->where('id_categoria', $id_gasto);
        $query = $this->db->get();
        
        return $query->row();
    }
    
}
