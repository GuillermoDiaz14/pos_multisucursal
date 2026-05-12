<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseController extends CI_Controller {
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $accessInfo = [];
	protected $global = array ();
	protected $lastLogin = '';
	protected $module = '';

	protected function defaultReportPermissions($allowed = 0) {
		$reportList = $this->config->item('reportList');
		$reports = array();

		if (!is_array($reportList)) {
			return $reports;
		}

		foreach ($reportList as $report) {
			if (!isset($report['key'])) {
				continue;
			}
			$reports[$report['key']] = array(
				'key' => $report['key'],
				'allowed' => (int) $allowed
			);
		}

		return $reports;
	}

	/**
	 * This is default constructor
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn() {
		// Sesión de emergencia activa: no requiere sesión normal
		if ($this->isAdmin()) {
			$this->_setupEmergencyGlobals();
			return;
		}

		$isLoggedIn = $this->session->userdata ( 'isLoggedIn' );

		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			redirect ( 'login' );
		} else {
			$now          = time();
			$loginTime    = (int) $this->session->userdata('login_time');
			$lastActivity = (int) $this->session->userdata('last_activity');

			// Sesión expirada: 12h absolutas desde login o 2h sin actividad
			if (($loginTime && ($now - $loginTime) > 43200) ||
			    ($lastActivity && ($now - $lastActivity) > 7200)) {
				$this->session->sess_destroy();
				$this->session->set_flashdata('error', 'Tu sesión expiró por seguridad. Por favor inicia sesión nuevamente.');
				redirect('login');
				return;
			}

			// Renovar marca de actividad
			$this->session->set_userdata('last_activity', $now);
			$this->role       = $this->session->userdata('role');
			$this->vendorId   = $this->session->userdata('userId');
			$this->name       = $this->session->userdata('name');
			$this->roleText   = $this->session->userdata('roleText');
			$this->lastLogin  = $this->session->userdata('lastLogin');
			$this->accessInfo = $this->session->userdata('accessInfo');

			// Refrescar rol y permisos si el usuario fue modificado por un admin
			if (!empty($this->role)) {
				$this->_refreshRoleIfNeeded();
				$this->_refreshAccessInfoIfNeeded();
			}

			$this->global['name']             = $this->name;
			$this->global['role']             = $this->role;
			$this->global['role_text']        = $this->roleText;
			$this->global['last_login']       = $this->lastLogin;
			$this->global['is_admin']         = $this->isAdmin() ? 1 : 0;
			$this->global['access_info']      = $this->accessInfo;
			$this->global ['accessible_reports'] = $this->getAccessibleReports();
			$this->global ['report_scope_all'] = $this->canAccessAllBranchesReports();
		}
	}

	private function _setupEmergencyGlobals() {
		// Obtener la primera sucursal disponible para operaciones que la requieran
		if (!$this->session->userdata('id_sucursal')) {
			$suc = $this->db->select('id_sucursal')->from('tbl_sucursal')
				->limit(1)->get()->row();
			if ($suc) {
				$this->session->set_userdata('id_sucursal', $suc->id_sucursal);
			}
		}

		$this->name     = 'Administrador (emergencia)';
		$this->roleText = 'Emergencia';
		$this->accessInfo = [];

		$this->global['name']              = $this->name;
		$this->global['role']              = 0;
		$this->global['role_text']         = $this->roleText;
		$this->global['last_login']        = '';
		$this->global['is_admin']          = 1;
		$this->global['access_info']       = [];
		$this->global['accessible_reports'] = [];
		$this->global['report_scope_all']  = true;
	}

	private function _refreshRoleIfNeeded() {
		$row = $this->db
			->select('tbl_users.roleId, tbl_users.updatedDtm, tbl_roles.role as roleText, tbl_roles.isDeleted as roleDeleted, tbl_roles.status as roleStatus')
			->from('tbl_users')
			->join('tbl_roles', 'tbl_roles.roleId = tbl_users.roleId', 'left')
			->where('tbl_users.userId', $this->vendorId)
			->where('tbl_users.isDeleted', 0)
			->get()->row();

		if (!$row) {
			$this->session->sess_destroy();
			redirect('login');
			return;
		}

		// Si el rol fue eliminado o desactivado, cerrar sesión inmediatamente
		if (!empty($row->roleDeleted) || (int)$row->roleStatus !== 1) {
			$this->session->sess_destroy();
			redirect('login');
			return;
		}

		$sessionUpdatedAt = $this->session->userdata('userUpdatedAt');
		if ($row->updatedDtm === $sessionUpdatedAt) return;

		// El rol del usuario cambió: actualizar sesión y forzar recarga de permisos
		$this->role     = $row->roleId;
		$this->roleText = $row->roleText;

		$this->session->set_userdata([
			'role'            => $row->roleId,
			'roleText'        => $row->roleText,
			'userUpdatedAt'   => $row->updatedDtm,
			'accessUpdatedAt' => null,
		]);
	}

	private function _refreshAccessInfoIfNeeded() {
		$row = $this->db
			->select('access, updatedDtm')
			->from('tbl_access_matrix')
			->where('roleId', $this->role)
			->get()->row();

		if (!$row) return;

		$dbUpdated     = $row->updatedDtm;
		$sessionUpdated = $this->session->userdata('accessUpdatedAt');

		// Siempre refrescar si no hay accessUpdatedAt en sesión, o si cambió
		if ($dbUpdated !== $sessionUpdated) {
			$accessMatrix = json_decode($row->access);
			if (!is_array($accessMatrix)) return;

			$finalMatrixArray = [];
			foreach ($accessMatrix as $moduleMatrix) {
				if (!isset($moduleMatrix->module)) continue;
				$moduleName  = $moduleMatrix->module;
				$totalAccess = isset($moduleMatrix->total_access) ? (int)$moduleMatrix->total_access : 0;

				$finalMatrixArray[$moduleName] = [
					'module'       => $moduleName,
					'total_access' => $totalAccess,
				];

				if (isset($moduleMatrix->scope)) {
					$finalMatrixArray[$moduleName]['scope'] = $moduleMatrix->scope;
				}

				if ($moduleName === 'Productos') {
					$finalMatrixArray[$moduleName]['ver_precio_compra'] = isset($moduleMatrix->ver_precio_compra) ? (int)$moduleMatrix->ver_precio_compra : 0;
					$finalMatrixArray[$moduleName]['gestionar']         = isset($moduleMatrix->gestionar) ? (int)$moduleMatrix->gestionar : 0;
				}

				if ($moduleName === 'Ventas') {
					$finalMatrixArray[$moduleName]['editar']   = isset($moduleMatrix->editar)   ? (int)$moduleMatrix->editar   : 0;
					$finalMatrixArray[$moduleName]['eliminar'] = isset($moduleMatrix->eliminar) ? (int)$moduleMatrix->eliminar : 0;
				}

				if (isset($moduleMatrix->reports) && is_array($moduleMatrix->reports)) {
					$finalMatrixArray[$moduleName]['reports'] = [];
					foreach ($moduleMatrix->reports as $rpt) {
						if (!isset($rpt->key)) continue;
						$finalMatrixArray[$moduleName]['reports'][$rpt->key] = [
							'key'     => $rpt->key,
							'allowed' => isset($rpt->allowed) ? (int)$rpt->allowed : 0,
						];
					}
				}
			}

			$this->accessInfo = $finalMatrixArray;
			$this->session->set_userdata([
				'accessInfo'      => $finalMatrixArray,
				'accessUpdatedAt' => $dbUpdated,
			]);
		}
	}
	
	/**
	 * Sesión de emergencia activa (token temporal, sin sesión normal requerida).
	 */
	function isAdmin() {
		$active  = $this->session->userdata('emergency_admin');
		$expires = (int) $this->session->userdata('emergency_expires');

		if ($active && $expires > time()) {
			return true;
		}

		if ($active) {
			$this->session->unset_userdata(['emergency_admin', 'emergency_expires']);
		}

		return false;
	}

	/**
	 * El usuario tiene acceso al panel de administración (usuarios, roles).
	 * Se deriva del módulo "Configuracion" en la matriz de acceso de su rol.
	 * La sesión de emergencia también lo concede.
	 */
	protected function hasAdminPanelAccess() {
		if ($this->isAdmin()) return true;
		return isset($this->accessInfo['Configuracion'])
			&& (int)$this->accessInfo['Configuracion']['total_access'] === 1;
	}

	/**
	 * This function is used to check the user having list access or not
	 */
	protected function hasListAccess() {
		if ($this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo) 
			&& ($this->accessInfo[$this->module]['total_access'] == 1)))
		{
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having create access or not
	 */
	protected function hasCreateAccess() {
		if ($this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo) 
			&& ($this->accessInfo[$this->module]['total_access'] == 1)))
		{
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having update access or not
	 */
	protected function hasUpdateAccess() {
		if ($this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo) 
			&& ($this->accessInfo[$this->module]['total_access'] == 1)))
		{
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having delete access or not
	 */
	protected function hasDeleteAccess() {
		if ($this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo) 
			&& ($this->accessInfo[$this->module]['total_access'] == 1)))
		{
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having access to a specific module
	 */
	protected function hasAccessToModule($moduleName) {
		if ($this->isAdmin() ||
			(array_key_exists($moduleName, $this->accessInfo) 
			&& ($this->accessInfo[$moduleName]['total_access'] == 1)))
		{
			return true;
		}
		return false;
	}

	protected function getProductoPermisos() {
		if ($this->isAdmin()) {
			return array('total_access' => 1, 'ver_precio_compra' => 1, 'gestionar' => 1);
		}
		return isset($this->accessInfo['Productos'])
			? $this->accessInfo['Productos']
			: array('total_access' => 0, 'ver_precio_compra' => 0, 'gestionar' => 0);
	}

	protected function hasProductPermission($permissionName) {
		if ($this->isAdmin()) {
			return true;
		}

		if (!array_key_exists('Productos', $this->accessInfo)) {
			return false;
		}

		$productoModule = $this->accessInfo['Productos'];
		if (!isset($productoModule['total_access']) || (int) $productoModule['total_access'] !== 1) {
			return false;
		}

		return isset($productoModule[$permissionName]) && (int) $productoModule[$permissionName] === 1;
	}

	protected function hasVentaPermission($permissionName) {
		if ($this->isAdmin()) {
			return true;
		}

		if (!array_key_exists('Ventas', $this->accessInfo)) {
			return false;
		}

		$ventasModule = $this->accessInfo['Ventas'];
		if (!isset($ventasModule['total_access']) || (int) $ventasModule['total_access'] !== 1) {
			return false;
		}

		return isset($ventasModule[$permissionName]) && (int) $ventasModule[$permissionName] === 1;
	}

	protected function hasReportAccess($reportKey) {
		if ($this->isAdmin()) {
			return true;
		}

		if (!array_key_exists('Reportes', $this->accessInfo)) {
			return false;
		}

		$reportModule = $this->accessInfo['Reportes'];
		if (!isset($reportModule['total_access']) || (int) $reportModule['total_access'] !== 1) {
			return false;
		}

		if (!isset($reportModule['reports']) || !is_array($reportModule['reports'])) {
			return true;
		}

		if (!array_key_exists($reportKey, $reportModule['reports'])) {
			return false;
		}

		return isset($reportModule['reports'][$reportKey]['allowed']) && (int) $reportModule['reports'][$reportKey]['allowed'] === 1;
	}

	protected function canAccessAllBranchesReports() {
		if ($this->isAdmin()) {
			return true;
		}

		if (!array_key_exists('Reportes', $this->accessInfo)) {
			return false;
		}

		$scope = isset($this->accessInfo['Reportes']['scope']) ? $this->accessInfo['Reportes']['scope'] : 'sucursal';
		return $scope === 'todas';
	}

	protected function getAccessibleReports() {
		$reportList = $this->config->item('reportList');
		$accessible = array();
		$canAccessAll = $this->canAccessAllBranchesReports();

		if (!is_array($reportList)) {
			return $accessible;
		}

		foreach ($reportList as $report) {
			if (!isset($report['key']) || !$this->hasReportAccess($report['key'])) {
				continue;
			}

			$url = '';
			if ($canAccessAll && !empty($report['multi_url'])) {
				$url = base_url() . $report['multi_url'];
			} elseif (!empty($report['single_url'])) {
				$url = base_url() . $report['single_url'];
			} else {
				continue;
			}

			$report['url'] = $url;
			$accessible[] = $report;
		}

		return $accessible;
	}

	/**
	 * This function is used to load the set of views
	 */
	function loadThis() {
		$this->global ['pageTitle'] = 'CodeInsect : Access Denied';
		
		$this->load->view ( 'includes/header', $this->global );
		$this->load->view ( 'general/access' );
		$this->load->view ( 'includes/footer' );
	}
	
	/**
	 * This function is used to logged out user from system
	 */
	function logout() {
		$this->session->sess_destroy ();
		redirect ( 'login' );
	}

	/**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
		// pre($this->global); die;
        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }
	
	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT) {
		$this->load->library ( 'pagination' );

		$config ['base_url'] = base_url () . $link;
		$config ['total_rows'] = $count;
		$config ['uri_segment'] = $segment;
		$config ['per_page'] = $perPage;
		$config ['num_links'] = 5;
		$config ['full_tag_open'] = '<nav><ul class="pagination">';
		$config ['full_tag_close'] = '</ul></nav>';
		$config ['first_tag_open'] = '<li class="arrow">';
		$config ['first_link'] = 'First';
		$config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<li class="arrow">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<li class="arrow">';
		$config ['next_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="active"><a href="#">';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="arrow">';
		$config ['last_link'] = 'Last';
		$config ['last_tag_close'] = '</li>';
	
		$this->pagination->initialize ( $config );
		$page = $config ['per_page'];
		$segment = $this->uri->segment ( $segment );
	
		return array (
			"page" => $page,
			"segment" => $segment
		);
	}
}
