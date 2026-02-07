<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Ingreso_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function ingresoListingCount($searchText,$id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_ingreso');
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
    function ingresoListing($searchText,$id_sucursal, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_ingreso');
        if(!empty($searchText)) {
            $likeCriteria = "(descripcion LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('id_ingreso', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewIngreso($ingresoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_ingreso', $ingresoInfo);
        
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
    function editIngreso($ingresoInfo, $ingresoId)
    {
        $this->db->where('id_ingreso', $ingresoId);
        $this->db->update('tbl_ingreso', $ingresoInfo);
        
        return TRUE;
    }





    public function eliminar_ingreso($id) {
        $this->db->where('id_ingreso', $id);
        $this->db->delete('tbl_ingreso');
    }
    


    function getIngresoInfo($id_ingreso)
    {
        $this->db->select('id_ingreso, descripcion, monto,fecha');
        $this->db->from('tbl_ingreso');
        $this->db->where('id_ingreso', $id_ingreso);
        $query = $this->db->get();
        
        return $query->row();
    }

}