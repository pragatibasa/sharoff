<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class bill_details extends Fuel_base_controller {

	public $nav_selected =  'bill_details';
	public $view_location = 'bill_details';
		
	function __construct() {
		parent::__construct();
		$this->config->load('bill_details');
		$this->load->language('bill_details');
		$this->billing_instruction = $this->config->item('bill_details');
		$this->load->module_model(BILL_DETAILS_FOLDER, 'bill_details_model');
	}
	
	function index() {
		$this->_render('bill_details');
	}

	function list_bill_details() {
		$containers	= $this->bill_details_model->getPaginatedBillDetails($_REQUEST);
	}

	function duplicate_bill() {
		$billNo = $_REQUEST['billno'];
		$this->bill_details_model->generateDuplicateBill( $billNo );
	}

	function cancel_bill() {
		$billNo = $_REQUEST['billno'];
		$this->bill_details_model->processCancelBill( $billNo );
	}

	function delete_bill() {
		$billNo = $_REQUEST['billno'];
		$this->bill_details_model->processDeleteBill( $billNo );
	}

	function display_search_results() {
		$searchType = $_REQUEST['searchType'];
		$searchValue = $_REQUEST['searchValue'];
		
	}
}	
