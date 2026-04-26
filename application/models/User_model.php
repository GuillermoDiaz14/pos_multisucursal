<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * pagina:ventas.programacionparacompartir.com
 * autor=  Prometeo Service
 * canal youtube= www.youtube.com/channel/UCSDBz3_sEY267ZOpzdzbkZA
 */
class User_model extends CI_Model
{
    private function parseMoneySql($field)
    {
        return "CAST(REPLACE(REPLACE(REPLACE(REPLACE($field, '$', ''), ',', ''), 'MXN', ''), ' ', '') AS DECIMAL(12,2))";
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText,$id_usuario)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.id_sucursal, BaseTbl.createdDtm, Role.role , Sucursal.nombre_sucursal');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
         $this->db->join('tbl_sucursal as Sucursal', 'Sucursal.id_sucursal = BaseTbl.id_sucursal','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
            $this->db->where('BaseTbl.userId !=', $id_usuario);
     //   $this->db->where('BaseTbl.id_sucursal', $id_sucursal);
        // $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText,$id_usuario, $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.createdDtm, 
        Role.role, Role.status as roleStatus, Sucursal.nombre_sucursal');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
               $this->db->join('tbl_sucursal as Sucursal', 'Sucursal.id_sucursal = BaseTbl.id_sucursal','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
           $this->db->where('BaseTbl.userId !=', $id_usuario);
      //  $this->db->where('BaseTbl.id_sucursal', $id_sucursal);
        // $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role, status as roleStatus');
        $this->db->from('tbl_roles');
        $this->db->where('isDeleted', 0);
       // $this->db->where('id_sucursal', $id_sucursal);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    function getSucursalInfo($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from('tbl_sucursal');
        $this->db->where('id_sucursal', $id_sucursal);

         $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getDashboardSummary($id_sucursal, $periods)
    {
        $today = $periods['today'];
        $yesterday = $periods['yesterday'];
        $monthStart = $periods['month_start'];
        $monthEnd = $periods['month_end'];
        $previousMonthStart = $periods['previous_month_start'];
        $previousMonthEnd = $periods['previous_month_end'];

        $ventasSql = "
            SELECT
                COUNT(CASE WHEN fecha_venta = ? THEN 1 END) AS ventas_hoy,
                COALESCE(SUM(CASE WHEN fecha_venta = ? THEN total END), 0) AS total_hoy,
                COUNT(CASE WHEN fecha_venta = ? THEN 1 END) AS ventas_ayer,
                COALESCE(SUM(CASE WHEN fecha_venta = ? THEN total END), 0) AS total_ayer,
                COUNT(CASE WHEN fecha_venta BETWEEN ? AND ? THEN 1 END) AS ventas_mes,
                COALESCE(SUM(CASE WHEN fecha_venta BETWEEN ? AND ? THEN total END), 0) AS total_mes,
                COALESCE(SUM(CASE WHEN fecha_venta BETWEEN ? AND ? THEN descuento END), 0) AS descuento_mes,
                COALESCE(SUM(CASE WHEN fecha_venta BETWEEN ? AND ? THEN impuesto END), 0) AS impuesto_mes,
                COUNT(CASE WHEN fecha_venta BETWEEN ? AND ? AND LOWER(TRIM(tipo_pago)) = 'credito' THEN 1 END) AS ventas_credito_mes,
                COALESCE(SUM(CASE WHEN fecha_venta BETWEEN ? AND ? AND LOWER(TRIM(tipo_pago)) = 'credito' THEN saldo END), 0) AS saldo_credito_mes,
                COUNT(CASE WHEN fecha_venta BETWEEN ? AND ? THEN 1 END) AS ventas_mes_anterior,
                COALESCE(SUM(CASE WHEN fecha_venta BETWEEN ? AND ? THEN total END), 0) AS total_mes_anterior
            FROM tbl_venta
            WHERE id_sucursal = ?
        ";

        $ventasParams = array(
            $today, $today,
            $yesterday, $yesterday,
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $previousMonthStart, $previousMonthEnd,
            $previousMonthStart, $previousMonthEnd,
            $id_sucursal
        );
        $ventas = $this->db->query($ventasSql, $ventasParams)->row_array();

        $comprasSql = "
            SELECT
                COUNT(CASE WHEN fecha_compra BETWEEN ? AND ? THEN 1 END) AS compras_mes,
                COALESCE(SUM(CASE WHEN fecha_compra BETWEEN ? AND ? THEN total END), 0) AS total_compras_mes,
                COUNT(CASE WHEN fecha_compra BETWEEN ? AND ? THEN 1 END) AS compras_mes_anterior,
                COALESCE(SUM(CASE WHEN fecha_compra BETWEEN ? AND ? THEN total END), 0) AS total_compras_mes_anterior
            FROM tbl_compra
            WHERE id_sucursal = ?
        ";

        $comprasParams = array(
            $monthStart, $monthEnd,
            $monthStart, $monthEnd,
            $previousMonthStart, $previousMonthEnd,
            $previousMonthStart, $previousMonthEnd,
            $id_sucursal
        );
        $compras = $this->db->query($comprasSql, $comprasParams)->row_array();

        $precioCompraSql = $this->parseMoneySql('p.precio_compra');
        $precioVentaSql = $this->parseMoneySql('p.precio_venta');

        $inventarioSql = "
            SELECT
                COUNT(DISTINCT p.id_producto) AS productos_registrados,
                COUNT(DISTINCT CASE WHEN ps.stock > 0 THEN p.id_producto END) AS productos_con_stock,
                COALESCE(SUM(ps.stock), 0) AS unidades_inventario,
                COUNT(DISTINCT CASE WHEN ps.stock <= 5 THEN p.id_producto END) AS productos_stock_bajo,
                COALESCE(SUM(ps.stock * $precioCompraSql), 0) AS valor_inventario_costo,
                COALESCE(SUM(ps.stock * $precioVentaSql), 0) AS valor_inventario_venta
            FROM tbl_producto_stock ps
            INNER JOIN tbl_producto p ON p.id_producto = ps.id_producto
            WHERE ps.id_sucursal = ?
        ";
        $inventario = $this->db->query($inventarioSql, array($id_sucursal))->row_array();

        $clientes = $this->db
            ->where('id_sucursal', $id_sucursal)
            ->from('tbl_cliente')
            ->count_all_results();

        $caja = $this->db
            ->select('id_caja, fecha_apertura, fecha_cierre, saldo, estado')
            ->from('tbl_caja')
            ->where('id_sucursal', $id_sucursal)
            ->order_by('id_caja', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        $rentabilidadSql = "
            SELECT
                COALESCE(SUM(dv.cantidad * ($precioVentaSql - $precioCompraSql)), 0) AS utilidad_mes
            FROM tbl_detalle_venta dv
            INNER JOIN tbl_venta v ON v.id_venta = dv.id_venta
            INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
            WHERE v.id_sucursal = ?
              AND v.fecha_venta BETWEEN ? AND ?
        ";
        $rentabilidad = $this->db
            ->query($rentabilidadSql, array($id_sucursal, $monthStart, $monthEnd))
            ->row_array();

        return array(
            'ventas' => $ventas,
            'compras' => $compras,
            'inventario' => $inventario,
            'clientes' => (int) $clientes,
            'caja' => $caja,
            'rentabilidad' => $rentabilidad
        );
    }

    public function getDashboardSalesTrend($id_sucursal, $startDate, $endDate)
    {
        return $this->db
            ->select('fecha_venta, COUNT(*) as ventas, COALESCE(SUM(total), 0) as total', false)
            ->from('tbl_venta')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_venta >=', $startDate)
            ->where('fecha_venta <=', $endDate)
            ->group_by('fecha_venta')
            ->order_by('fecha_venta', 'ASC')
            ->get()
            ->result_array();
    }

    public function getDashboardMonthlyComparison($id_sucursal, $startDate, $endDate)
    {
        $ventas = $this->db
            ->select("DATE_FORMAT(fecha_venta, '%Y-%m') as periodo, COALESCE(SUM(total), 0) as total", false)
            ->from('tbl_venta')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_venta >=', $startDate)
            ->where('fecha_venta <=', $endDate)
            ->group_by("DATE_FORMAT(fecha_venta, '%Y-%m')", false)
            ->order_by('periodo', 'ASC')
            ->get()
            ->result_array();

        $compras = $this->db
            ->select("DATE_FORMAT(fecha_compra, '%Y-%m') as periodo, COALESCE(SUM(total), 0) as total", false)
            ->from('tbl_compra')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_compra >=', $startDate)
            ->where('fecha_compra <=', $endDate)
            ->group_by("DATE_FORMAT(fecha_compra, '%Y-%m')", false)
            ->order_by('periodo', 'ASC')
            ->get()
            ->result_array();

        return array(
            'ventas' => $ventas,
            'compras' => $compras
        );
    }

    public function getDashboardPaymentDistribution($id_sucursal, $startDate, $endDate)
    {
        return $this->db
            ->select("
                CASE
                    WHEN tipo_pago IS NULL OR tipo_pago = '' THEN 'Sin definir'
                    WHEN LOWER(TRIM(tipo_pago)) = 'credito' THEN 'Crédito'
                    WHEN LOWER(TRIM(tipo_pago)) = 'contado' THEN 'Contado'
                    ELSE CONCAT(UCASE(LEFT(tipo_pago, 1)), SUBSTRING(tipo_pago, 2))
                END as tipo_pago,
                COUNT(*) as ventas,
                COALESCE(SUM(total), 0) as total
            ", false)
            ->from('tbl_venta')
            ->where('id_sucursal', $id_sucursal)
            ->where('fecha_venta >=', $startDate)
            ->where('fecha_venta <=', $endDate)
            ->group_by("
                CASE
                    WHEN tipo_pago IS NULL OR tipo_pago = '' THEN 'Sin definir'
                    WHEN LOWER(TRIM(tipo_pago)) = 'credito' THEN 'Crédito'
                    WHEN LOWER(TRIM(tipo_pago)) = 'contado' THEN 'Contado'
                    ELSE CONCAT(UCASE(LEFT(tipo_pago, 1)), SUBSTRING(tipo_pago, 2))
                END
            ", false)
            ->order_by('total', 'DESC')
            ->get()
            ->result_array();
    }

    public function getDashboardTopProducts($id_sucursal, $startDate, $endDate, $limit = 5)
    {
        $precioCompraSql = $this->parseMoneySql('p.precio_compra');

        return $this->db
            ->select("
                p.id_producto,
                p.nombre_producto,
                p.codigo,
                SUM(dv.cantidad) as unidades,
                COALESCE(SUM(dv.sub_total), 0) as total_vendido,
                COALESCE(SUM(dv.cantidad * (dv.precio_venta - $precioCompraSql)), 0) as utilidad_estimada
            ", false)
            ->from('tbl_detalle_venta dv')
            ->join('tbl_venta v', 'v.id_venta = dv.id_venta', 'inner')
            ->join('tbl_producto p', 'p.id_producto = dv.id_producto', 'inner')
            ->where('v.id_sucursal', $id_sucursal)
            ->where('v.fecha_venta >=', $startDate)
            ->where('v.fecha_venta <=', $endDate)
            ->group_by('p.id_producto')
            ->group_by('p.nombre_producto')
            ->group_by('p.codigo')
            ->order_by('unidades', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    public function getDashboardLowStock($id_sucursal, $limit = 8)
    {
        return $this->db
            ->select('p.id_producto, p.nombre_producto, p.codigo, c.nombre_categoria, ps.stock')
            ->from('tbl_producto_stock ps')
            ->join('tbl_producto p', 'p.id_producto = ps.id_producto', 'inner')
            ->join('tbl_categoria c', 'c.id_categoria = p.categoria', 'left')
            ->where('ps.id_sucursal', $id_sucursal)
            ->where('ps.stock <=', 5)
            ->order_by('ps.stock', 'ASC')
            ->order_by('p.nombre_producto', 'ASC')
            ->limit($limit)
            ->get()
            ->result_array();
    }
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);        
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     */
    function loginHistoryCount($userId, $searchText, $fromDate, $toDate)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function loginHistory($userId, $searchText, $fromDate, $toDate, $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        $this->db->from('tbl_last_login as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfoById($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getUserInfoWithRole($userId)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }







    function get_ventas($anio_actual)
    {
        $this->db->select('tbl_venta.*, tbl_cliente.id_cliente as id_cliente, tbl_cliente.nombre as nombre_cliente');
        $this->db->from('tbl_venta');
        $this->db->join('tbl_cliente', 'tbl_venta.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
    
        if (!empty($anio_actual)) {
            // Extraer el año de la fecha_venta y compararlo con el año actual
            $this->db->where('YEAR(tbl_venta.fecha_venta)', $anio_actual);
        }
    
        $this->db->order_by('tbl_venta.id_venta', 'DESC'); // Ajusta el nombre del campo de ID de venta según tu base de datos
    
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    

    function get_reparaciones($anio_actual)
    {
        $this->db->select('tbl_reparacion.*,tbl_cliente.nombre as nombre_cliente,tbl_empleado.nombre as nombre_tecnico');
        $this->db->from('tbl_reparacion');
        $this->db->join('tbl_cliente', 'tbl_reparacion.id_cliente = tbl_cliente.id_cliente', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        $this->db->join('tbl_empleado', 'tbl_reparacion.id_tecnico = tbl_empleado.id_empleado', 'left'); // Ajusta el campo de unión según tu estructura de base de datos
        if (!empty($anio_actual)) {
            // Extraer el año de la fecha_venta y compararlo con el año actual
            $this->db->where('YEAR(tbl_reparacion.fecha_ingreso)', $anio_actual);
        }
    
        $this->db->order_by('tbl_reparacion.id_reparacion', 'DESC'); // Ajusta el nombre del campo de ID de venta según tu base de datos
    
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }



    public function get_sucursal() {
        // Recupera las categorías de tu tabla de categorías (sustituye 'categorias' con el nombre correcto de tu tabla)
        $this->db->select('*');
        $query_configuracion = $this->db->get('tbl_sucursal');
    
  
    
        $result['sucursal'] = $query_configuracion->result();

    
        return $result;
    }



}

  
