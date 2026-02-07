<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Metodo_pago_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function metodo_pagoListingCount($searchText,$id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_metodo_pago');
        if(!empty($searchText)) {
            $likeCriteria = "(descripcion LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('id_sucursal', $id_sucursal);
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
    function metodo_pagoListing($searchText,$id_sucursal, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_metodo_pago');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre_metodo_pago LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('id_metodo_pago', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewmetodo_pago($metodo_pagoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_metodo_pago', $metodo_pagoInfo);
        
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
    function editMetodo_pago($metodo_pagoInfo, $metodo_pagoId)
    {
        $this->db->where('id_metodo_pago', $metodo_pagoId);
        $this->db->update('tbl_metodo_pago', $metodo_pagoInfo);
        
        return TRUE;
    }





    public function eliminar_metodo_pago($id) {
        $this->db->where('id_metodo_pago', $id);
        $this->db->delete('tbl_metodo_pago');
    }
    
    function getmetodo_pagoInfo($id_metodo_pago)
    {
        $this->db->select('*');
        $this->db->from('tbl_metodo_pago');
        $this->db->where('id_metodo_pago', $id_metodo_pago);
        $query = $this->db->get();
        
        return $query->row();
    }
    
}