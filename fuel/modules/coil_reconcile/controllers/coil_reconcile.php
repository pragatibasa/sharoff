<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class coil_reconcile extends Fuel_base_controller {
	private $data;
	public $nav_selected = 'coil_reconcile';
	public $view_location = 'coil_reconcile';

	function __construct() {
		parent::__construct();
		$this->load->module_model(COIL_RECONCILE_FOLDER, 'coil_reconcile_model');
	}

	function index() {
		$this->_render('coil_reconcile');
	}
}

/* End of file */
/* Location: ./fuel/modules/controllers*/
