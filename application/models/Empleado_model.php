<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class Empleado_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function empleadoListingCount($searchText,$id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_empleado');
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
    function empleadoListing($searchText,$id_sucursal, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_empleado');
        if(!empty($searchText)) {
            $likeCriteria = "(nombre LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->order_by('id_empleado', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewEmpleado($empleadoInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_empleado', $empleadoInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getEmpleadoInfo($empleadoId)
    {
        $this->db->select('*');
        $this->db->from('tbl_empleado');
        $this->db->where('id_empleado', $empleadoId);

        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
    function editEmpleado($empleadoInfo, $empleadoId)
    {
        $this->db->where('id_empleado', $empleadoId);
        $this->db->update('tbl_empleado', $empleadoInfo);
        
        return TRUE;
    }





    public function eliminar_empleado($id) {
        $this->db->where('id_empleado', $id);
        $this->db->delete('tbl_empleado');
    }
    

    public function get_categorias() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('id_categoria, nombre_categoria');
        $query = $this->db->get('tbl_categoria');

        return $query->result();
    }





       public function importar_empleados($file_path,$id_sucursal) {
        $csv_file = fopen($file_path, 'r');
        if ($csv_file === FALSE) {
            die('No se pudo abrir el archivo CSV');
        }

        while (($line = fgetcsv($csv_file, 0, ';')) !== FALSE) {
            $data = array(
                'nombre' => $line[0],
                'dni' => $line[1],
                'celular' => $line[2],
                'id_sucursal' => $id_sucursal
            );

            $this->db->insert('tbl_empleado', $data);
        }

        fclose($csv_file);
    }
}