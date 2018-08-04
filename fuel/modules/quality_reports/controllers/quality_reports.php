<?php

require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Quality_reports extends Fuel_base_controller {
	public $nav_selected = 'quality_reports';
	public $view_location = 'quality_reports';

	private $coilNumber;
	private $vProcess;

	function __construct() {
		parent::__construct();
		$this->config->load('quality_reports');
		$this->load->language('quality_reports');
		$this->quality_reports = $this->config->item('quality_reports');
		$this->load->module_model(QUALITY_REPORTS_FOLDER, 'quality_reports_model');
		$this->load->model('quality_reports_model');
	}

	function index() {
		$this->_render('quality_reports');
	}

	function create_report() {
		$this->coilNumber = $_REQUEST['partyid'];
		$vProcess = $_REQUEST['process'];
		if($vProcess == PROCESS_CUTTING) {
			$this->displayCreateCuttingReport();
		} else if($vProcess == PROCESS_SLITTING) {
			$this->displayCreateSlittingReport();
		}

	}

	function displayCreateCuttingReport() {
		$vars['coil_details'] = $this->quality_reports_model->getCoilDetails($this->coilNumber);
		$vars['bundle_details'] = $this->quality_reports_model->getBundleDetails($this->coilNumber);
		return $this->_render('create_bundle_report', $vars );		
	}

	function displayCreateSlittingReport() {
		$vars['coil_details'] = $this->quality_reports_model->getCoilDetails($this->coilNumber);
		$vars['slit_details'] = $this->quality_reports_model->getSlitDetails($this->coilNumber);
		return $this->_render('create_slit_report', $vars );
	}

	function insert_report() {
		$this->quality_reports_model->insertCoilDetails($_POST,$_FILES);
	}

	function insert_cutting_report() {
		$this->quality_reports_model->insertCuttingDetails($_POST,$_FILES);		
	}

	function list_quality_reports() {
		$containers	= $this->quality_reports_model->list_items();
		$folders = [];
		if(!empty($containers)){
			foreach($containers as $container) {
				$obj = new stdClass();
				$obj->report_id = $container->id;
				$obj->coilNumber = $container->coil_number;
				$obj->nPartyName = $container->nPartyName;
				$obj->coilReceivedDate = $container->dReceivedDate;
				$obj->reportCreatedOn = $container->created_on;
				$obj->coilStatus = $container->vStatus;

				$obj->view_report = ($container->report_type == '2') ? site_url('quality_reports/view_slit_report').'/?report_id='.$container->id : site_url('quality_reports/view_cutting_report').'/?report_id='.$container->id;

				$folders[] = $obj;
			}
		}
		echo json_encode($folders);exit;
	}

	function view_slit_report() {
		$vars['report_id'] = $_REQUEST['report_id'];
		$vars['slit_coil_details'] = $this->quality_reports_model->getCoilReportDetails($_REQUEST['report_id']);
		$vars['coil_details'] = $this->quality_reports_model->getCoilDetails($vars['slit_coil_details']->coil_number);
		$vars['slit_details'] = $this->quality_reports_model->getSlitReportDetails($vars['slit_coil_details']->id);
		$vars['surfaceDetails'] = $this->quality_reports_model->surfaceDetails();
		return $this->_render('view_slit_report', $vars );
	}

	function view_cutting_report() {
		$vars['report_id'] = $_REQUEST['report_id'];
		$vars['quality_details'] = $this->quality_reports_model->getQualityReportDetails($_REQUEST['report_id']);
		$vars['coil_details'] = $this->quality_reports_model->getCoilDetails($vars['quality_details']->coil_number);
		$vars['bundle_details'] = $this->quality_reports_model->getCuttingReportDetails($_REQUEST['report_id']);
		return $this->_render('view_cutting_report', $vars );
	}

	function view_quality_pdf() {
		$this->quality_reports_model->displayQualityReportPdf($_REQUEST['report_id']);
		return;
	}

	function view_cutting_quality_pdf() {
		$this->quality_reports_model->displayCuttingQualityReportPdf($_REQUEST['report_id']);
		return;
	}
}

/* End of file */
/* Location: ./fuel/modules/controllers*/
