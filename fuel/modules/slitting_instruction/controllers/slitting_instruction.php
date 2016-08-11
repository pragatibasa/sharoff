<?php

require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class slitting_instruction extends Fuel_base_controller {
	private $data;
	public $nav_selected = 'slitting_instruction';
	public $view_location = 'slitting_instruction';
	private $partyid;
	private $partyname;
	private $qdata;
	private $adata;
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('slitting_instruction');
		$this->load->language('slitting_instruction');
		$this->slitting_instruction = $this->config->item('slitting_instruction');
		$this->load->module_model(SLITTING_INSTRUCTION_FOLDER, 'slitting_instruction_model');
		$this->data = $this->slitting_instruction_model->formdisplay();	
		if(isset($this->data)) {
			if(isset($this->data[0]))  {
		}
		$this->uri->init_get_params();
		$this->partyid = (string) $this->input->get('partyid', TRUE);
		$this->partyname = (string) $this->input->get('partyname', TRUE);
	}		
}	
	function index()
	{
		if(!empty($this->data) && isset($this->data)) {
			$vars['data']= $this->data;
			$vars['partyname']= $this->partyname;
			$vars['partyid']= $this->partyid;
			$vars['qdata']= $this->BundleName();
			$vars['adata']= $this->slitting_instruction_party($this->partyid, $this->partyname);
			
			//$vars['adata']= 
			$this->_render('slitting_instruction', $vars);
		} else {
			redirect(fuel_url('#'));
		}
	}
	
	function save_button()
	{
	if (!empty($_POST)) {
		 $savevar = $this->slitting_instruction_model->savechangemodel($_POST['pid']);
		return $savevar;
	}
	}
	
	function listslittingdetails($partyid = '') 
	 {
	   if(empty($partyid)) { 
			$partyid = $_POST['partyid'];
	   }
	   $this->load->module_model(SLITTING_INSTRUCTION_FOLDER, 'slitting_instruction_model');
	   $slitlists = $this->slitting_instruction_model->slitlistdetails($partyid);
	   
	   if(!empty($slitlists)){
			$files = array();
			foreach($slitlists as $cl) {
				$obj = new stdClass();
				$obj->Sno = $cl->Sno;
				$obj->Slittingdate = $cl->Slittingdate;
				$obj->width = $cl->width;
				$obj->weight = $cl->weight;
				$obj->length = $cl->length;
			/*	$obj->dl = fuel_url('slitting_instruction/delete_coil').
				'/?coilnumber='.$cl->Sno.'&partynumber='.$cl->pnumber;*/
				$files[] = $obj;
			}
			echo json_encode($files);
		}else{
			$status = array("status"=>"No Results!");
            echo json_encode($status);
		}
	 }
	

	function BundleName() 
 {
   $this->load->module_model(SLITTING_INSTRUCTION_FOLDER, 'slitting_instruction_model');
   $qdata = $this->slitting_instruction_model->BundleTable($this->partyid);
   
   $qdatajson = json_encode($qdata); 
   return $qdata;
 }
/*
 function delete_coil(){
        $queryStr = $_SERVER['QUERY_STRING'];
        parse_str($queryStr, $args);
        $Sno = $args["coilnumber"];
        $partynumber = $args["partynumber"];
		$this->load->model('slitting_instruction_model');
		$this->slitting_instruction_model->delete_coilnumber($Sno, $partynumber);
		echo $Sno;
	}
	*/
	function totalwidth(){
		$this->load->module_model(SLITTING_INSTRUCTION_FOLDER, 'slitting_instruction_model');
		$twid = $this->slitting_instruction_model->totalwidthmodel($_POST['partyid']);
		echo json_encode($twid);
		exit;
	
	}

	function delete_slit(){
		$this->load->module_model(SLITTING_INSTRUCTION_FOLDER, 'slitting_instruction_model');
		$this->slitting_instruction_model->delete_slitnumbermodel($_POST['Slitingnumber'], $_POST['Pid']);
	}
  
	function editbundle() {
		if (!empty($_POST)) {
			$arr = $this->slitting_instruction_model->editbundlemodel($_POST['bundlenumber'], $_POST['width_v']);
			if(empty($arr)) echo 'Success'; else echo 'Unable to save';
		}
		
		else{
			//redirect(fuel_uri('#'));
		}
	}
	
	
	function savebundleslit() {
	   if (!empty($_POST)) {
	    $arr = $this->slitting_instruction_model->savebundleslitting($_POST['pid'],$_POST['date1'], $_POST['widths'],$_POST['length'],$_POST['thickness']);
	    if(empty($arr)) { echo 'Success'; exit; } else { echo 'Unable to save';exit;};
		
	   } else{
	    //redirect(fuel_uri('#'));
	   }
	}

function deleterow() 
 {
   if (!empty($_POST)) {
    $arr = $this->slitting_instruction_model->deleteslittingmodel($_POST['number']);
    if(empty($arr)) echo 'Success'; else echo 'Unable to save';
	
   }
   
   else{
    //redirect(fuel_uri('#'));
   }
}
	
	function slitting_instruction_party($pid, $pname) {
		$adata = $this->slitting_instruction_model->getCuttingInstruction($pid, $pname);
		return $adata;
	}

	function getBalanceLength() {
		$pid = $_POST['pid'];
		$remaining_weight = $_POST['remaining_length'];
		$adata = $this->slitting_instruction_model->getBalanceLength($pid, $remaining_weight);
		echo $adata; exit;
	}	

	function getLengthWithWidthGreater() {
		$pid = $_POST['pid'];
		$adata = $this->slitting_instruction_model->getLengthWithWidthGreater($pid);
		if( count($adata) > 0 ) {
			echo implode(',', $adata);exit;			
		} else 
			echo false;exit;
	}
}
/* End of file */
/* Location: ./fuel/modules/controllers*/