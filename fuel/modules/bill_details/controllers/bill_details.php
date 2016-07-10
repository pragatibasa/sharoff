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

		$containers			= $this->bill_details_model->list_items();
		$latestBillNumber 	= $this->bill_details_model->getLatestBillNumber();

		$folders = [];
		if(!empty($containers)){
			foreach($containers as $container) {
				$obj = new stdClass();
				$obj->nBillNo = $container['nBillNo'];
				$obj->dBillDate = $container['dBillDate'];
				$obj->nPartyName = $container['nPartyName'];

				$obj->duplicate_bill = site_url('bill_details/duplicate_bill').'/?billno='.$container['nBillNo'];
				$obj->cancel_bill = site_url('bill_details/cancel_bill').'/?billno='.$container['nBillNo'];
				if( $latestBillNumber == $container['nBillNo'] )
					$obj->delete_bill = site_url('bill_details/delete_bill').'/?billno='.$container['nBillNo'];
	
				$folders[] = $obj;
			}
			$vars['billdetails_lists'] = $folders; 
		} else {
			$status = array("status"=>"No Results!");
            $vars['billdetails_lists'] = $status;
		}

		$this->_render('bill_details',$vars);
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
}	
