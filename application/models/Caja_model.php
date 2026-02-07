<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Caja_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function cajaListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('tbl_caja');
        if(!empty($searchText)) {
            $likeCriteria = "(fecha_cierre LIKE '%".$searchText."%')";
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
    function cajaListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_caja');
        if(!empty($searchText)) {
            $likeCriteria = "(fecha_cierre LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
//        $this->db->where('esEliminado', 0);
        $this->db->order_by('id_caja', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewCaja($cajaInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_caja', $cajaInfo);
        
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
 




    
    function getCajaInfo($id_caja)
    {
        $this->db->select('id_caja, fecha_apertura, fecha_cierre,saldo');
        $this->db->from('tbl_caja');
        $this->db->where('id_caja', $id_caja);
        $query = $this->db->get();
        
        return $query->row();
    }
    




    public function cerrarCaja() {
        // Primero, obtén el saldo actual de todas las cajas abiertas
        $this->db->select('id_caja, saldo');
        $this->db->where('estado', 'abierto');
        $query = $this->db->get('tbl_caja');

        if ($query->num_rows() > 0) {
            // Recorre las cajas abiertas y aumenta su saldo
            foreach ($query->result() as $row) {
                $estado = "cerrado";
    

                // Actualiza el saldo en la base de datos
                $data = array(
                    'estado' => $estado 
                );

      
                $this->db->update('tbl_caja', $data);
            }

            return true; // Se cerra la caja abierta
        } else {
            return false; // No hay cajas abiertas para cerrar
        }
    }
}