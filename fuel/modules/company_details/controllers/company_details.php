<?php

require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Company_details extends Fuel_base_controller {
	private $data;
	private $gdata;
	public $nav_selected = 'company_details';
	public $view_location = 'company_details';
	private $rate_details;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->module_model(COMPANY_DETAILS_FOLDER, 'Company_details_model');
		$this->config->load('company_details');
		$this->load->language('company_details');
		$this->Company_details = $this->config->item('Company_details');
		$this->data = $this->Company_details_model->form_fields();
		if(isset($this->data)) {
			if(isset($this->data[0]))  {
		}
	}		
}


/*cid cname ide_receive ide_payable addr1 addr2 city state zipcode country update_state ctno toll_no fax email web tax_id vat cst_no service_tax duty_no*/
	function savedetails(){
	    $arr = $this->Company_details_model->savecompany($_POST);
	}

	function index()
	{
		if(!empty($this->data) && isset($this->data)) {
			//$vars['gdata']= $this->coil();
			$vars['formdata']= $this->formdisplay();
			$vars['data']= $this->data;
			$this->_render('company_details', $vars);
		} else {
			redirect(fuel_url('#'));
		}
	}

	function formdisplay()
	{	
		$formdata = $this->Company_details_model->form_fields();
		$datajson = json_encode($formdata); 
		return $formdata;
	}



}
/* End of file */
/* Location: ./fuel/modules/controllers*/