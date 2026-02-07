<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Proveedor_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function proveedorListingCount($searchText,$id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_proveedor');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre LIKE '%".$searchText."%')";
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
    function proveedorListing($searchText,$id_sucursal, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_proveedor');
  
        if (!empty($searchText)) {
            $this->db->group_start();
            $this->db->like('tbl_proveedor.nombre', $searchText);
            $this->db->or_like('tbl_proveedor.doc_fiscal', $searchText);
            $this->db->group_end();
           }
           $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('id_proveedor', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewproveedor($proveedorInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_proveedor', $proveedorInfo);
        
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
    function editproveedor($proveedorInfo, $proveedorId)
    {
        $this->db->where('id_proveedor', $proveedorId);
        $this->db->update('tbl_proveedor', $proveedorInfo);
        
        return TRUE;
    }





    public function eliminar_proveedor($id) {
        $this->db->where('id_proveedor', $id);
        $this->db->delete('tbl_proveedor');
    }
    
    function getproveedorInfo($id_proveedor)
    {
        $this->db->select('*');
        $this->db->from('tbl_proveedor');
        $this->db->where('id_proveedor', $id_proveedor);
        $query = $this->db->get();
        
        return $query->row();
    }

          public function importar_proveedores($file_path,$id_sucursal) {
        $csv_file = fopen($file_path, 'r');
        if ($csv_file === FALSE) {
            die('No se pudo abrir el archivo CSV');
        }

        while (($line = fgetcsv($csv_file, 0, ';')) !== FALSE) {
            $data = array(
                'nombre' => $line[0],
                'email' => $line[1],
                'celular' => $line[2],
                'direccion' => $line[3],
                'doc_fiscal' => $line[4],
                'id_sucursal' => $id_sucursal
            );

            $this->db->insert('tbl_proveedor', $data);
        }

        fclose($csv_file);
    }
    
}