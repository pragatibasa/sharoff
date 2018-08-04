<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/search/config/search_constants.php');

class search_model extends Base_module_model {
		
    function __construct() {
        parent::__construct('aspen_tblbilldetails');// table name
    }	

    function getDisplayColumns( $strProcess ){
      $arrProcessColumns = array('Cutting' =>  array('Bundle Number' => 'nSno', 'Bundle Weight' => 'fWeight','Billed Weight'=> 'fbilledWeight','Balance' => 'nbalance', 'Balance Weight' => 'nbalance'),
                                  'Slitting' => array('Bundle Number' => 'nSno', 'Bundle Weight' => 'nWeight','Length'=> 'nLength','Width' => 'nWidth', 'Status' => 'vStatus')
                                 );

      return $arrProcessColumns[$strProcess];
    }

  	function getBillDetails($billNo) {
      $strSql = 'select * from aspen_tblbilldetails ab left join aspen_tblinwardentry ai on ab.vIRnumber = ai.vIRnumber left join aspen_tblmatdescription as am on am.nMatId = ai.nMatId where nBillNo ='.$billNo;
      $querymain = $this->db->query($strSql);
      return $querymain->row(0);
  	}

  	function getCoilDetails($coilNo) {
      $strSql = 'select * from aspen_tblinwardentry ai left join aspen_tblmatdescription as am on am.nMatId = ai.nMatId where vIRnumber = '.$coilNo;
      $querymain = $this->db->query($strSql);
      return $querymain->row(0);
  	}

    function getBillBundleDetails($billNo) {
      $strSql = 'select * from aspen_tblBillBundleAssociation where nBillNumber ='.$billNo .' order by nBundleNumber';
      $querymain = $this->db->query($strSql);
      foreach ($querymain->result() as $row) {
        $arr[] =$row;
      }
      return $arr;
    }

    function getCoilBalanceDetails($coilNo, $process) {
      if($process == 'Slitting') {
        $strSql = 'select aspen_tblslittinginstruction.vIRnumber,
        aspen_tblslittinginstruction.nSno,
        aspen_tblslittinginstruction.dDate,
        aspen_tblslittinginstruction.nWidth,
        aspen_tblslittinginstruction.nWeight,
        aspen_tblslittinginstruction.nLength,
        aspen_tblbillingstatus.vBillingStatus as vStatus
          from aspen_tblbillingstatus 
          left join aspen_tblslittinginstruction on ( aspen_tblslittinginstruction.nSno = aspen_tblbillingstatus.nSno and aspen_tblbillingstatus.vIRnumber = aspen_tblslittinginstruction.vIRnumber ) 
          where aspen_tblbillingstatus.vIRnumber = '.$coilNo .' order by aspen_tblbillingstatus.nSno';
      } else 
        $strSql = 'select * from aspen_tblbillingstatus where vIRnumber = '.$coilNo .' order by nSno';
  
      $querymain = $this->db->query($strSql);
      foreach ($querymain->result() as $row) {
        $arr[] =$row;
      }
      return $arr; 
    }

    function getBillDetailsByCoilNumber($coilNo) {
      $strSql = 'select ab.*, GROUP_CONCAT(abb.nBundleNumber SEPARATOR ", " ) as bundleCount from aspen_tblbilldetails ab left join aspen_tblBillBundleAssociation abb on ab.nBillNo = abb.nBillNumber where vIRnumber = '.$coilNo.' group by nBillNumber';
      $querymain = $this->db->query($strSql);
      foreach ($querymain->result() as $row) {
        $arr[] =$row;
      }
      return $arr;  
    }
}	