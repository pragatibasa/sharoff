<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class coil_labels extends Fuel_base_controller {

	public $nav_selected =  'coil_labels';
	public $view_location = 'coil_labels';
		
	function __construct() {
		parent::__construct();
		$this->config->load('coil_labels');
		$this->load->language('coil_labels');
		$this->coil_labels = $this->config->item('coil_labels');
		$this->load->module_model(COIL_LABELS_FOLDER, 'coil_labels_model');
	}
	
	function index() {
		$this->_render('coil_labels');
	}
	
	function list_coil_labels() {
		return $this->coil_labels_model->getPaginatedCoilLabels($_REQUEST);
	}

	function getBundleDetails() {
		$vars['coilDetails'] = (array) $this->coil_labels_model->getCoilDetails($_REQUEST['coilNumber'])[0];
		$vars['bundleDetails'] = $this->coil_labels_model->getBundleDetails($_REQUEST['coilNumber']);
		$this->_render('labels', $vars);
	}

	function printAllPrnFiles() {
		$vars['coilDetails'] = (array) $this->coil_labels_model->getCoilDetails($_REQUEST['coilNumber'])[0];
		$vars['bundleDetails'] = $this->coil_labels_model->getBundleDetails($_REQUEST['coilNumber']);

		$coilPrnFiles = array();
		foreach($vars['bundleDetails'] as $bundle) {
			$coilPrnFiles[] = $this->generatePRNFile($vars['coilDetails'],$bundle);	
		}
		$zipname = $_REQUEST['coilNumber'].'.zip';
		$zip = new ZipArchive();
		$overwrite = true;
		if($zip->open($zipname, ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		foreach ($coilPrnFiles as $file) {
			$zip->addFile($file);
		}
		$zip->close();

		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);
	}

	function printSinglePrnFile() {
		$vars['coilDetails'] = (array) $this->coil_labels_model->getCoilDetails($_REQUEST['coilNumber'])[0];
		$vars['bundleDetails'] = $this->coil_labels_model->getBundleDetails($_REQUEST['coilNumber'], $_REQUEST['bundleNumber'])[0];
		if($vars['coilDetails']['nPartyName'] === 'TSPDL') {
			$content = $this->generateTataPRNFile($vars['coilDetails'],$vars['bundleDetails']);
		} else {
			$content = $this->generatePRNFile($vars['coilDetails'],$vars['bundleDetails']);
		}

		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/miniERP/labels/".$vars['coilDetails']['vIRnumber']."_".$vars['bundleDetails']['nSno'].".prn","wb");
		fwrite($fp,$content);
		fclose($fp);
		$file = $_SERVER['DOCUMENT_ROOT'] . "/miniERP/labels/".$vars['coilDetails']['vIRnumber']."_".$vars['bundleDetails']['nSno'].".prn";

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
			readfile($file);
			unlink($file);
            exit;
        }
	}

	function generatePRNFile($coilDetails,$bundleDetails) {
		$content = "<xpml><page quantity='0' pitch='75.1 mm'></xpml>SIZE 97.5 mm, 75.1 mm\n";
		$content .= "GAP 3 mm, 0 mm\n";
		$content .= "DIRECTION 0,0\n";
		$content .= "REFERENCE 0,0\n";
		$content .= "OFFSET 0 mm\n";
		$content .= "SET PEEL OFF\n";
		$content .= "SET CUTTER OFF\n";
		$content .= "SET PARTIAL_CUTTER OFF\n";
		$content .= "<xpml></page></xpml><xpml><page quantity='1' pitch='75.1 mm'></xpml>SET TEAR ON\n";
		$content .= "CLS\n";
		$content .= "BOX 8,10,768,590,3\n";
		$content .= "BAR 8,510, 758, 3\n";
		$content .= "BAR 8,439, 758, 3\n";
		$content .= "BAR 8,369, 758, 3\n";
		$content .= "BAR 8,299, 758, 3\n";
		$content .= "BAR 8,299, 758, 3\n";
		$content .= "BAR 8, 158, 758, 3	\n";
		$content .= "BAR 8,88, 758, 3\n";

		$content .= "BAR 377,439, 3, 71\n";
		$content .= "BAR 377,369, 3, 71\n";
		$content .= "BAR 377, 299, 3, 71\n";
		$content .= "BAR 377, 228, 3, 72\n";
		$content .= "BAR 377, 158, 3, 71\n";
		$content .= "BAR 377, 89, 3, 70\n";
		$content .= "BAR 234, 439, 146, 3\n";

		$content .= "CODEPAGE 1252 \n";

		if((substr_count($coilDetails['vIRnumber'],'.') == 1) ) {
			 $coilNumbers = preg_split('/\./',$coilDetails['vIRnumber']); 
		} else if((substr_count($coilDetails['vIRnumber'],'.') > 1) ) {
			$coilNumbers = preg_split('/^([^\.]+)./',$coilDetails['vIRnumber']);			
			$coilNumbers[0] = preg_split('/\./',$coilDetails['vIRnumber'])[0];
		}
		$barcodeData = (isset($coilNumbers) && count($coilNumbers) == 2) ? '!105'.$coilNumbers[0].'!100.!099'.$coilNumbers[1] : '!104'.$coilDetails['vIRnumber'];

		$content .= 'TEXT 556,584,"0",180,12,12,"ASPEN STEEL PVT LTD"'."\n";
		$content .= 'TEXT 724,541,"0",180,10,10,"Plot no 16E, Phase 2 Sector 1, Bidadi, Ramnagar: 562109"'."\n";
		$content .= 'BARCODE 645,503,"128M",27,0,180,2,4,"'.$barcodeData.'"'."\n";
		$content .= 'TEXT 580,471,"0",180,8,8,"'.$coilDetails['vIRnumber'].'"'."\n";
		$content .= 'TEXT 760,489,"0",180,10,10,"Tag No:"'."\n";
		$content .= 'TEXT 362,490,"0",180,10,10,"Customer :"'."\n";
		$content .= 'TEXT 225,490,"0",180,8,10,"'.$coilDetails['nPartyName'].'"'."\n";
		$content .= 'TEXT 760,417,"0",180,10,10,"A Coil No:"'."\n";
		$content .= 'TEXT 760,348,"0",180,10,10,"Parent Coil No:"'."\n";

		if(isset($coilNumbers) && count($coilNumbers) == 2)
		 	$content .= 'TEXT 541,352,"0",180,15,14,"'.$coilNumbers[1].'"'."\n";
		else 
			$content .= 'TEXT 541,352,"0",180,15,14,""'."\n";

		$content .= 'TEXT 541,425,"0",180,15,14,"'.((isset($coilNumbers) && count($coilNumbers) == 2) ? $coilNumbers[0] : $coilDetails['vIRnumber']).'"'."\n";
			// $content .= 'TEXT 541,425,"0",180,15,14,"Size(in mm):"'."\n";
		$content .= 'TEXT 641,282,"0",180,12,14,"'.$bundleDetails['fThickness'].'X'.(($coilDetails['vprocess'] == 'Cutting') ? $coilDetails['fWidth'] : $bundleDetails['nWidth']).'"'."\n";
		$content .= 'TEXT 362,277,"0",180,10,10,"Process Date: "'."\n";
		$content .= 'TEXT 151,277,"0",180,10,10,"'.date('d/m/Y',strtotime($bundleDetails['dDate'])).'"'."\n";
		$content .= 'TEXT 760,207,"0",180,10,10,"Wt(Kgs) :"'."\n";
		$content .= 'TEXT 541,212,"0",180,16,14,"'.(($coilDetails['vprocess'] == 'Cutting') ? $bundleDetails['nBundleweight'] : $bundleDetails['nWeight']).'"'."\n";
		$content .= 'TEXT 362,207,"0",180,10,10,"Packing Type:"'."\n";
		$content .= 'TEXT 160,207,"0",180,10,10,""'."\n";
		$content .= 'TEXT 420,65,"0",180,10,10,"Prod Sign :"'."\n";
		$content .= 'TEXT 757,65,"0",180,10,10,"QC :"'."\n";
		$content .= "BOX 22,23,267,81,3\n";
		$content .= "BOX 440,23,685,81,3\n";
		$content .= 'TEXT 760,137,"0",180,10,10,"No Of Pc\'s : "'."\n";
		$content .= 'TEXT 546,142,"0",180,14,14,"'.(($coilDetails['vprocess'] == 'Cutting') ? $bundleDetails['nNoOfPieces'] : '-').'"'."\n";
		$content .= 'TEXT 362,137,"0",180,10,10,"BUNDLE NO:"'."\n";
		$content .= 'TEXT 172,147,"0",180,21,18,""'."\n";
		$content .= 'TEXT 362,420,"0",180,10,10,"Spec :"'."\n";
		$content .= 'TEXT 240,427,"0",180,16,15,"'.$coilDetails['vDescription'].'"'."\n";
		$content .= 'TEXT 362,347,"0",180,10,10,"Process Name:"'."\n";
		$content .= 'TEXT 160,347,"0",180,10,10,"'.$coilDetails['vprocess'].'"'."\n";
		$content .= 'TEXT 730,294,"0",180,10,10,"Size"'."\n";
		$content .= 'TEXT 757,261,"0",180,10,10,"(in mm)"'."\n";
		$content .= 'TEXT 651,280,"0",180,12,12,":"'."\n";
		$content .= "PRINT 1,1\n";
		
		$content .= "<xpml></page></xpml><xpml><end/></xpml>";
		return $content;
	}

	function generateTataPRNFile($coilDetails,$bundleDetails) {

		if((substr_count($coilDetails['vIRnumber'],'.') == 1) ) {
			$coilNumbers = preg_split('/\./',$coilDetails['vIRnumber']); 
	   } else if((substr_count($coilDetails['vIRnumber'],'.') > 1) ) {
		   $coilNumbers = preg_split('/^([^\.]+)./',$coilDetails['vIRnumber']);			
		   $coilNumbers[0] = preg_split('/\./',$coilDetails['vIRnumber'])[0];
	   }
		$content = "<xpml><page quantity='0' pitch='76.2 mm'></xpml>SIZE 99.1 mm, 76.2 mm\n";
		$content .= "SPEED 3\n";
		$content .= "DENSITY 7\n";
		$content .= "DIRECTION 0,0\n";
		$content .= "REFERENCE 0,0\n";
		$content .= "OFFSET 0 mm\n";
		$content .= "SET PEEL OFF\n";
		$content .= "SET CUTTER OFF\n";
		$content .= "SET PARTIAL_CUTTER OFF\n";
		$content .= "<xpml></page></xpml><xpml><page quantity='1' pitch='75.1 mm'></xpml>SET TEAR ON\n";
		$content .= "CLS\n";
		$content .= "CODEPAGE 1252\n";
		$content .= 'TEXT 727,576,"0",180,10,16,"TATA STEEL PROCESSING AND DISTRIBUTION LIMITED"'."\n";
		$content .= 'TEXT 475,534,"ROMAN.TTF",180,1,12,"Plot no 16 E, phase 2, sector 1, Bidadi industrial area, Bangalore - 562109"'."\n";
		$content .= 'TEXT 784,477,"ROMAN.TTF",180,1,11,"BATCH NO"'."\n";
		$content .= 'TEXT 784,437,"ROMAN.TTF",180,1,11,"COIL NO"'."\n";
		$content .= 'TEXT 784,397,"ROMAN.TTF",180,1,11,"SIZE (mm)"'."\n";
		$content .= 'TEXT 784,356,"ROMAN.TTF",180,1,10,"(TxWxL)"'."\n";
		$content .= 'TEXT 784,320,"ROMAN.TTF",180,1,11,"GRADE"'."\n";
		$content .= 'TEXT 784,280,"ROMAN.TTF",180,1,11,"NET WT (mt)"'."\n";
		$content .= 'TEXT 784,239,"ROMAN.TTF",180,1,11,"CUSTOMER"'."\n";
		$content .= 'TEXT 784,199,"ROMAN.TTF",180,1,11,"DATE"'."\n";
		$content .= 'TEXT 613,478,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 595,477,"0",180,14,11,"'.$coilDetails['vIRnumber'].'"'."\n";
		$content .= 'TEXT 594,437,"0",180,14,11,"'.(isset($coilNumbers) ? $coilNumbers[1] : '').'"vi'."\n";
		$content .= 'TEXT 597,385,"0",180,16,18,"'.$bundleDetails['fThickness'].'X'.(($coilDetails['vprocess'] == 'Cutting') ? $coilDetails['fWidth'] : $bundleDetails['nWidth']).'"'."\n";
		$content .= 'TEXT 594,281,"0",180,14,11,"'.$coilDetails['fQuantity'].'"'."\n";
		$content .= 'TEXT 597,239,"0",180,14,11,""'."\n";
		$content .= 'TEXT 387,472,"ROMAN.TTF",180,1,11,"MATERIAL"'."\n";
		$content .= 'TEXT 387,439,"ROMAN.TTF",180,1,11,"CAST NO."'."\n";
		$content .= 'TEXT 387,321,"ROMAN.TTF",180,1,11,"PIECES (nos)"'."\n";
		$content .= 'TEXT 387,285,"ROMAN.TTF",180,1,11,"GROSS WT (mt)"'."\n";
		$content .= 'TEXT 597,199,"0",180,14,11,"'.date('d/m/Y',strtotime($bundleDetails['dDate'])).'"'."\n";
		$content .= 'BARCODE 751,150,"128M",74,0,180,3,6,"!105'.$coilDetails['vIRnumber'].'!100;'.$coilDetails['fQuantity'].'"'."\n";
		$content .= 'TEXT 220,472,"0",180,14,11,"'.$coilDetails['vDescription'].'"'."\n";
		$content .= 'TEXT 220,439,"0",180,14,11,"'.$coilDetails['vCast'].'"'."\n";
		$content .= 'TEXT 142,321,"0",180,14,11,"'.(($coilDetails['vprocess'] == 'Cutting') ? $bundleDetails['nNoOfPieces'] : '-').'"'."\n";
		$content .= 'TEXT 142,281,"0",180,14,11,"'.(($coilDetails['vprocess'] == 'Cutting') ? $bundleDetails['nBundleweight'] : $bundleDetails['nWeight']).'"'."\n";
		$content .= 'TEXT 594,320,"0",180,14,11,"'.$coilDetails['vGrade'].'"'."\n";
		$content .= 'TEXT 613,439,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 613,378,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 613,320,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 613,280,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 613,241,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 613,201,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 238,475,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 238,441,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 163,323,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= 'TEXT 163,284,"ROMAN.TTF",180,1,12,":"'."\n";
		$content .= "PRINT 1,1\n";
		$content .= "<xpml></page></xpml><xpml><end/></xpml>\n";
	   	return $content;
	}
}	
