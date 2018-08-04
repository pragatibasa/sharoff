<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class search extends Fuel_base_controller {
	private $data;
	public $nav_selected = '';
	public $view_location = 'search';
	private $search;

	function __construct() {
		parent::__construct();
		$this->config->load('search');
		$this->load->language('search');
		$this->search = $this->config->item('search');
		$this->load->module_model(SEARCH_FOLDER, 'search_model');
	}

	function index() {
		$searchType  = $_REQUEST['searchType'];
		$searchValue = $_REQUEST['searchValue'];
		if($searchType == 'bill') {
			$this->displayBillDetails($searchValue);
		} elseif ($searchType == 'coil') {
			$this->displayCoilDetails($searchValue);
		}
	}

	function displayBillDetails($billNo) {
		$vars['billDetails'] = $this->search_model->getBillDetails($billNo);
		if(count($vars['billDetails']) == 0) {
			$vars['searchType'] = 'bill';
			return $this->_render('no_results', $vars );
		}
		$vars['billBundleDetails'] = $this->search_model->getBillBundleDetails($billNo);
		$vars['bundleBalanceDetails'] = $this->search_model->getCoilBalanceDetails($vars['billDetails']->vIRnumber, $vars['billDetails']->vprocess);
		$vars['bundleCols'] = $this->search_model->getDisplayColumns($vars['billDetails']->vprocess);

		$this->_render('display_bill_search_results', $vars);
	}

	function displayCoilDetails($coilNo) {
		$vars['coilDetails'] = $this->search_model->getCoilDetails($coilNo);
		if(count($vars['coilDetails']) == 0) {
			$vars['searchType'] = 'coil';
			return $this->_render('no_results', $vars );
		}
		$vars['billDetails'] = $this->search_model->getBillDetailsByCoilNumber($vars['coilDetails']->vIRnumber);
		$vars['bundleBalanceDetails'] = $this->search_model->getCoilBalanceDetails($coilNo, $vars['coilDetails']->vprocess);
		$vars['bundleCols'] = $this->search_model->getDisplayColumns($vars['coilDetails']->vprocess);

		$this->_render('display_coil_search_results', $vars);
	}
}
