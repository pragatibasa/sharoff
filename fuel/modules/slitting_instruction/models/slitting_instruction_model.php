<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class slitting_instruction_model extends Base_module_model {
	
 	public $required = array('vIRnumber','nSno','dDate','nLength','nNoOfPieces','nTotalWeight');
	protected $key_field = 'vIRnumber';
	
    function __construct()
    {
        parent::__construct('aspen_tblslittinginstruction');
    }
		
	function getcoildetails() {
		
		$this->save($save);
	}
	
	function formdisplay()
	{
		$fields['nPartyName']['label'] = 'Party Name';
		$fields['nMatId']['label'] = 'Material Description';
		$fields['fWidth']['label'] = 'Width';
		$fields['fThickness']['label'] = 'Thickness';
		$fields['fLength']['label'] = 'Length';
		$fields['fQuantity']['label'] = 'Weight';
		$fields['dReceivedDate']= datetime_now();
		$fields['nLength']['label'] = 'Length of a cutting instruction';
		$fields['numbers'] = array('type' => 'enum', 'label' => 'Customer Rate', 'options' => array('yes' => 'Add Discount','no' => 'Remove Discount'), 'required' => TRUE);
		return $fields;
	}

	function totalwidthmodel($partyid){
		$sqlsw = "select round(sum(nWeight)) as width,round(sum(nWidth),2) as totalWidth, sum(distinct nLength) as totalLength from aspen_tblslittinginstruction
					where aspen_tblslittinginstruction.vIRnumber='".$partyid."'";

		$query = $this->db->query($sqlsw);
		$arr='';
		if ($query->num_rows() > 0) {
		 	foreach ($query->result() as $row)
			{
				$arr[] =$row;
			}
		}	

		return $arr;
	}
	
	function delete_slitnumbermodel($Slitingnumber='', $Pid='') {
		$sql ="DELETE FROM aspen_tblslittinginstruction WHERE vIRnumber ='".$Pid."' and nSno = '".$Slitingnumber."'";
		$query = $this->db->query($sql);
	}
	
  
	function editbundlemodel(){
		if(isset( $_POST['bundlenumber']) && isset( $_POST['width_v'])) {
			$bundlenumber = $_POST['bundlenumber'];
			$width_v = $_POST['width_v'];
			$vIRnumber = $_POST['pid'];
	 	}
		$sql = ("UPDATE aspen_tblslittinginstruction SET nWidth='". $width_v. "'");
       	$sql.=" WHERE aspen_tblslittinginstruction.nSno='".$bundlenumber."' and vIRnumber=".$vIRnumber."";

    	$query1=$this->db->query ($sql);
	}
	 
	function savechangemodel (){ 
		$sqlnsno = $this->db->query ("SELECT nSno FROM aspen_tblslittinginstruction");

		if ($sqlnsno->num_rows() >= 0){
		   foreach ($sqlnsno->result() as $row){
				$arr[] =$row;
			}
		}
		json_encode($arr);
		foreach ($arr as $row){
			if($row->nSno > 0){
			$sql = $this->db->query ("UPDATE aspen_tblslittinginstruction  SET vStatus='WIP-Slitting' WHERE vIRnumber='".$_POST['pid']."' and nSno!=0");
			$sql = $this->db->query ("UPDATE aspen_tblinwardentry  SET vprocess='Slitting' WHERE vIRnumber='".$_POST['pid']."'");
  
			}
		}
		$sql = $this->db->query ("UPDATE aspen_tblinwardentry  SET vStatus='Work In Progress' WHERE vIRnumber='".$_POST['pid']."'");
		
  
	}
	
	function getCuttingInstruction($pid) {
		if(isset($pid)) {
			$partyid = $pid;
		}
		$sql = "SELECT aspen_tblinwardentry.vIRnumber,
		 	aspen_tblinwardentry.dReceivedDate,
		  	aspen_tblmatdescription.vDescription,
		   	aspen_tblinwardentry.fThickness,
		    aspen_tblinwardentry.fWidth,
		    ( aspen_tblinwardentry.fQuantity-COALESCE(sum(aspen_tblbillingstatus.fWeight),0)) as fQuantity,
		    aspen_tblinwardentry.vStatus
		FROM aspen_tblinwardentry 
		LEFT JOIN aspen_tblmatdescription ON aspen_tblmatdescription.nMatId = aspen_tblinwardentry.nMatId
		LEFT JOIN aspen_tblbillingstatus ON aspen_tblbillingstatus.vIRnumber = aspen_tblinwardentry.vIRnumber
		LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId ";
		if(!empty($partyid)) {
			$sql.="WHERE aspen_tblinwardentry.vIRnumber='".$partyid."' ";
		}
		$query = $this->db->query($sql);
		$arr='';
		if ($query->num_rows() > 0) {
		   	foreach ($query->result() as $row) {
				$arr[] =$row;
		   	}
		}
		$strExhaustedLength = 'select COALESCE(sum(distinct nLength),0) as usedLength from aspen_tblslittinginstruction where vIRnumber ='.$partyid;

		$queryExhaustedLength = $this->db->query($strExhaustedLength);

		$floatUsedLength = 0;
		
		if($queryExhaustedLength->num_rows() > 0) {
		   	$floatUsedLength = $queryExhaustedLength->result()[0]->usedLength;
		}

		$arr[0]->remaining_weight = round((($arr[0]->fQuantity/($arr[0]->fThickness*$arr[0]->fWidth*785)))*100000000)- $floatUsedLength;

		return json_encode($arr[0]);
	}	
		
function BundleTable($pid) {
 if(isset( $_POST['pid'])) {
  $pid = $_POST['pid'];
  }
  $sql = "select nSno,dDate,nWidth from aspen_tblslittinginstruction  "; 
  if(isset($pid)) {
  $sql.="WHERE aspen_tblslittinginstruction.vIRnumber='".$pid."'";
  }
    $query = $this->db->query($sql);
    $arra='';
    if ($query->num_rows() > 0)
    {
    foreach ($query->result() as $row)
    {
    $arra[] =$row;
    }
    } 
    return $arra;
  }

	function savebundleslitting( $pid, $date, $widths, $length, $thickness ) {
		$arrWidths = explode(',', $widths);
		foreach ($arrWidths as $key => $width) {
			$weight = round((0.00000785*$width*$thickness*$length));
			$sql = $this->db->query ("Insert into aspen_tblslittinginstruction(vIRnumber,dDate,nWidth,nWeight,nLength) VALUES(  '". $pid. "','". $date. "','". $width. "','".$weight."','".$length."')");
		}
  	}
		
	function deleteslittingmodel($deleteid)
	{
		 $querycheck = $this->db->query("select * from aspen_tblslittinginstruction where nSno = '".$deleteid."'");
	 $arr = $querycheck->result();
	 if(!empty($arr)) {
		$sql = $this->db->query("DELETE FROM aspen_tblslittinginstruction WHERE nSno='".$deleteid."'");
	}
	else{
		return false;
	  }
    }
	
	function slitlistdetails($partyid = '') {
		$sqlci = "select aspen_tblslittinginstruction.nSno as Sno,DATE_FORMAT(aspen_tblslittinginstruction.dDate, '%d-%m-%Y') AS Slittingdate,aspen_tblslittinginstruction.nWidth as width, aspen_tblslittinginstruction.vIRnumber as pnumber, aspen_tblslittinginstruction.nWeight as weight, aspen_tblslittinginstruction.nLength as length FROM aspen_tblslittinginstruction WHERE aspen_tblslittinginstruction.vIRnumber='".$partyid."'";
		
		$query = $this->db->query($sqlci);
		$arr='';
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $row)
		   {
		      $arr[] =$row;
		   }
		}
		return $arr;
  	}	
		
	function delete_coilnumber($Sno='', $partynumber='') {
		$sql ="DELETE FROM aspen_tblslittinginstruction WHERE vIRnumber ='".$partynumber."' and nSno = '".$Sno."'";
		$query = $this->db->query($sql);
	}	
		
	function getBalanceLength( $partynumber, $remaining_weight ) {
		$sql = "select COALESCE( ($remaining_weight - sum( distinct nLength ) ),$remaining_weight ) as balance from aspen_tblslittinginstruction where vIRnumber = '$partynumber'";
		$query = $this->db->query($sql);
		return round($query->result()[0]->balance,2);
	}

	function getLengthWithWidthGreater($pid) {
		$sql = "select lengths.nLength,lengths.width,inward.fWidth from 
				(select nLength,sum(nWidth) as width from aspen_tblslittinginstruction where vIRnumber=$pid group by nLength) as lengths, 
				 aspen_tblinwardentry inward where vIRnumber =$pid and
				lengths.width > inward.fWidth";
		$query = $this->db->query($sql);
		$arr = [];
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$arr[] =$row->nLength;
		   	}
		}
		return $arr;
	}

	function cancelcoilmodel(){
		$sql = ("UPDATE aspen_tblslittinginstruction SET vStatus='RECEIVED' ");
		$sql.="WHERE aspen_tblslittinginstruction.vIRnumber='".$_POST['pid']."'";
		$query1=$this->db->query ($sql);
	  
		$sql1 =("UPDATE aspen_tblinwardentry SET vStatus='RECEIVED'");
		$sql1.="WHERE vIRnumber='".$_POST['pid']."'";
		$query1=$this->db->query ($sql1);
	  
		$sql2 ="DELETE FROM aspen_tblslittinginstruction WHERE vIRnumber='".$_POST['pid']."'";
		$query = $this->db->query($sql2);
	}
}

class Splittinginstructions_model extends Base_module_record {
	
 	
}