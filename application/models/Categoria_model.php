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
    function categoriaListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('tbl_categoria');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre_categoria LIKE '%".$searchText."%')";
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
    function categoriaListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_categoria');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre_categoria LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
//        $this->db->where('esEliminado', 0);
        $this->db->order_by('id_categoria', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
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