<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Sucursal_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */


 


 public function Get_cantidad_productos() {
    // Recupera las categorías de tu tabla de categorías (sustituye 'tbl_sucursal' con el nombre correcto de tu tabla)
    $query = $this->db->get('tbl_producto');
    
    // Obtén la cantidad de registros
    $num_rows = $query->num_rows();

    // Devuelve tanto la cantidad de registros como los resultados
    return array(
        'num_rows' => $num_rows,
        'result_array' => $query->result_array()
    );
}


    function sucursalListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre_sucursal LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
  
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
    function sucursalListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
  
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_sucursal.nombre_sucursal', $searchText);
        
            $this->db->group_end();
           }
   
        $this->db->order_by('id_sucursal', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */


        function addNewProductoStock($productoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_producto_stock', $productoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    function addNewsucursal($sucursalInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_sucursal', $sucursalInfo);
        
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
    function editsucursal($sucursalInfo, $sucursalId)
    {
        $this->db->where('id_sucursal', $sucursalId);
        $this->db->update('tbl_sucursal', $sucursalInfo);
        
        return TRUE;
    }





    public function eliminar_producto_stock($id_sucursal) {
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->delete('tbl_producto_stock');
    }

       public function eliminar_sucursal($id) {
        $this->db->where('id_sucursal', $id);
        $this->db->delete('tbl_sucursal');
    }
    
    
    function getsucursalInfo($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
        $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->row();
    }

    
}