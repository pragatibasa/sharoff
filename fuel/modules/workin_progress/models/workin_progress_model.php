<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/inward_entry/config/inward_entry_constants.php');
require_once(APPPATH.'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH.'helpers/tcpdf/tcpdf.php');

class workin_progress_model extends Base_module_model {

    public $foreign_keys = array('nPartyId'=>array(WORKIN_PROGRESS_FOLDER=>'workin_to_permissions_model'),'nMatId'=>array(WORKIN_PROGRESS_FOLDER=>'workin_to_users_model'));

    protected $key_field = 'dReceivedDate';

    function __construct()
    {
        parent::__construct('aspen_tblinwardentry');// table name
    }

    function list_items($limit = NULL, $offset = NULL, $col = 'vIRnumber', $order = 'asc')
    {
        $this->db->select('aspen_tblinwardentry.dReceivedDate,aspen_tblrecoiling.dStartDate,aspen_tblpartydetails.nPartyName ,aspen_tblinwardentry.vIRnumber,aspen_tblmatdescription.vDescription , aspen_tblinwardentry.fThickness, aspen_tblinwardentry.fWidth,aspen_tblinwardentry.fQuantity, aspen_tblinwardentry.vStatus, aspen_tblrecoiling.nNoOfRecoils');
        $this->db->join('aspen_tblowner', 'aspen_tblowner.vIRnumber = aspen_tblinwardentry.vIRnumber', 'left');
        $this->db->join('aspen_tblpartydetails', 'aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId', 'left');
        $this->db->join('aspen_tblmatdescription', 'aspen_tblmatdescription.nMatId = aspen_tblinwardentry.nMatId', 'left');
        $this->db->join('aspen_tblrecoiling', 'aspen_tblrecoiling.vIRnumber = aspen_tblinwardentry.vIRnumber', 'left');
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }

    function save($post)
    {
        $post['status'] = "New";
        parent::save($post);
        return true;
    }

    function form_fields($values = array())
    {
        $fields = parent::form_fields();
        $CI =& get_instance();
        $CI->load->module_model(WORKIN_PROGRESS_FOLDER, 'workin_to_permissions_model');
        $CI->load->module_model(WORKIN_PROGRESS_FOLDER, 'workin_to_users_model');
        $CI->load->module_model(WORKIN_PROGRESS_FOLDER, 'workin_to_cutting_model');
        $fields['vIRnumber']['label'] = 'Coil Number';
        $fields['nPartyId']['label'] = "Parties";
        $fields['nMatId']['label'] = "Material Description";

        $workin_to_permissions_model_options = $CI->workin_to_permissions_model->options_list('nPartyId', 'nPartyName');
        $workin_to_users_model_options = $CI->workin_to_users_model->options_list('nMatId', 'vDescription');

        $workin_to_description_model_options = $CI->workin_to_cutting_model->options_list('nMatId', 'vDescription');

        return $fields;
    }

    function editCoilDetails() {
        $query = $this->db->query("select * from aspen_tblinwardentry order by col name  limit 0,1");
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
    function example() {
        return true;
    }

    function toolbar_list()
    {
        $strWorkingProgressList = "select DISTINCT aspen_tblinwardentry.vIRnumber as coilnumber , DATE_FORMAT(aspen_tblinwardentry.dReceivedDate, '%d-%m-%Y') as receiveddate, DATE_FORMAT(aspen_tblcuttinginstruction.dDate, '%d-%m-%Y') as sizegivendate ,DATE_FORMAT(aspen_tblrecoiling.dStartDate, '%d-%m-%Y') as recoilingdate ,DATE_FORMAT(aspen_tblslittinginstruction.dDate, '%d-%m-%Y') as slittingdate,aspen_tblpartydetails.nPartyName as partyname,aspen_tblmatdescription.vDescription as materialdescription, aspen_tblinwardentry.fThickness as thickness, aspen_tblinwardentry.fWidth as width, aspen_tblinwardentry.fpresent as weight,aspen_tblinwardentry.vprocess as process, ifnull(aspen_tbl_cuttingslipgenerated.numTimesGenerated,0) as numTimesGenerated From aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription  ON aspen_tblmatdescription.nMatId=aspen_tblinwardentry.nMatId LEFT JOIN aspen_tblcuttinginstruction  ON aspen_tblcuttinginstruction.vIRnumber=aspen_tblinwardentry.vIRnumber LEFT JOIN aspen_tblrecoiling  ON aspen_tblrecoiling .vIRnumber=aspen_tblinwardentry.vIRnumber LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails .nPartyId=aspen_tblinwardentry.nPartyId
		LEFT JOIN aspen_tblslittinginstruction ON aspen_tblslittinginstruction .vIRnumber=aspen_tblinwardentry.vIRnumber
		LEFT JOIN aspen_tblbillingstatus ON aspen_tblbillingstatus.vIRnumber=aspen_tblinwardentry.vIRnumber
		LEFT JOIN aspen_tbl_cuttingslipgenerated ON aspen_tbl_cuttingslipgenerated.nPartyId=aspen_tblinwardentry.vIRnumber
		where aspen_tblinwardentry.vStatus = 'Work In Progress' or aspen_tblslittinginstruction.vStatus='WIP-Slitting' or aspen_tblrecoiling.vStatus='WIP-Recoiling' or aspen_tblcuttinginstruction.vStatus='WIP-Cutting' Group by aspen_tblinwardentry.vIRnumber";
        $query = $this->db->query($strWorkingProgressList);

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

    function workweight_model() {
        $sqlbw = "select sum(distinct(fpresent))  AS totalweight From aspen_tblinwardentry LEFT JOIN aspen_tblcuttinginstruction  ON aspen_tblcuttinginstruction.vIRnumber=aspen_tblinwardentry.vIRnumber LEFT JOIN aspen_tblrecoiling  ON aspen_tblrecoiling .vIRnumber=aspen_tblinwardentry.vIRnumber LEFT JOIN aspen_tblslittinginstruction ON aspen_tblslittinginstruction.vIRnumber=aspen_tblinwardentry.vIRnumber
where aspen_tblinwardentry.vStatus = 'Work In Progress' or aspen_tblslittinginstruction.vStatus='WIP-Slitting' or aspen_tblrecoiling.vStatus='WIP-Recoiling' or aspen_tblcuttinginstruction.vStatus='WIP-Cutting' ";
        $query = $this->db->query($sqlbw);
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $rowbw)
            {
                $bweight =$rowbw->totalweight;
            }
        }
        return $bweight;
    }

    function generate_cuttingslip_model() {
        $sqlcs = "select aspen_tblpartydetails.nPartyName as partyname,aspen_tblmatdescription.vDescription as materialdescription,vInvoiceNo as Invoicenumber,fWidth as Width,fThickness as Thickness,fQuantity as Weight from aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription  ON aspen_tblmatdescription.nMatId=aspen_tblinwardentry.nMatId LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails .nPartyId=aspen_tblinwardentry.nPartyId where aspen_tblinwardentry.vIRnumber='".$_POST['coilnumber']."'";
        $query = $this->db->query($sqlcs);
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $arr[] =$row;
            }
        }
        return $arr;
    }


    function cutting_slipgenerated($partyid=''){
        if(isset($partyid)){
            $sqlpcheck= "select numTimesGenerated from aspen_tbl_cuttingslipgenerated where nPartyId='".$partyid."'";
            $query = $this->db->query($sqlpcheck);
            if ($query->num_rows() > 0) {
                //update numTimesGenerated
                $sql = ("Update aspen_tbl_cuttingslipgenerated set numTimesGenerated=numTimesGenerated+1 where nPartyId='".$partyid."'");
                $query1=$this->db->query ($sql);
            }else{
                $sql = "Insert into aspen_tbl_cuttingslipgenerated (nPartyId,numTimesGenerated,slipDate) VALUES('". $partyid. "',1, now())";
                $query = $this->db->query($sql);
            }
        }
    }

    function cutting_slipmodel($partyid='',$partyname = '') {
        if(isset($partyid) && isset($partyname)) {
            $partyname = $partyname;
            $partyid = $partyid;
        }
        $this->cutting_slipgenerated($partyid);
        $sqlpcheck= "Select vprocess from aspen_tblinwardentry where vIRnumber='".$partyid."'";
        $query = $this->db->query($sqlpcheck);
        $arr='';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
            {
                $arr[] =$row;
            }
        }
        json_encode($arr);
        //echo $row->vprocess;
        foreach ($arr as $row){
            if( $row->vprocess =='Cutting')
            {
                $sqlcutting = "select aspen_tblpartydetails.nPartyName as partyname,
                                    aspen_tblmatdescription.vDescription as materialdescription,
                                    vInvoiceNo as Invoicenumber,
                                    fWidth as Width,
                                    fThickness as Thickness,
                                    fQuantity as Weight, 
                                    jid as jswid, 
                                    ssid,
                                    vGrade,
                                    vHeatnumber,
                                    aspen_tbl_cuttingslipgenerated.nId as slipSerial,
                                    aspen_tbl_cuttingslipgenerated.slipDate as slipDate
                                from 
                                    aspen_tblinwardentry 
                                    LEFT JOIN aspen_tblmatdescription  ON aspen_tblmatdescription.nMatId=aspen_tblinwardentry.nMatId 
                                    LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId=aspen_tblinwardentry.nPartyId
                                    left join aspen_tbl_cuttingslipgenerated on aspen_tbl_cuttingslipgenerated.nPartyId = aspen_tblinwardentry.vIRnumber
                                where aspen_tblinwardentry.vIRnumber ='".$partyid."'";

                $querymain = $this->db->query($sqlcutting);

                $invoice = $partyid;
                $party_name = $querymain->row(0)->partyname;
                $material_description = $querymain->row(0)->materialdescription;
                $Invoice_number = $querymain->row(0)->Invoicenumber;
                $Width = $querymain->row(0)->Width;
                $Thickness = $querymain->row(0)->Thickness;
                $Weight = $querymain->row(0)->Weight;
                $jswid = $querymain->row(0)->jswid;
                $ssid = $querymain->row(0)->ssid;
                $vGrade = $querymain->row(0)->vGrade;
                $vHeatnumber = $querymain->row(0)->vHeatnumber;
                $slipSerialNumber = $querymain->row(0)->slipSerial;
                $slipDate = $querymain->row(0)->slipDate;

                $sqlitem ="select aspen_tblcuttinginstruction.nSno as bundlenumber, DATE_FORMAT(aspen_tblcuttinginstruction.dDate, '%d-%m-%Y') AS processdate, aspen_tblcuttinginstruction.nLength as length, aspen_tblcuttinginstruction.nNoOfPieces as noofsheets, aspen_tblcuttinginstruction.nBundleweight as bundleweight from aspen_tblcuttinginstruction where aspen_tblcuttinginstruction.vIRnumber='".$partyid."' and aspen_tblcuttinginstruction.vStatus='WIP-Cutting'";

                $queryitem = $this->db->query($sqlitem);

                date_default_timezone_set('Asia/Kolkata');

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdfname= 'cuttingslip_'.$partyid.'.pdf';
                $pdf->SetAuthor('Abhilash');
                $pdf->SetTitle('CuttingSlip');
                $pdf->SetSubject('CuttingSlip');
                $pdf->SetKeywords('Aspen, ERP, CuttingSlip');
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->AddPage();

                $html = '
			<div align="center">

		<table width="100%"  cellspacing="0" cellpadding="0" border="0">

			<tr>
				<td width="16%" align:"left"></td>
				<td width="70%"align="center" style="font-size:60px; font-style:italic; font-family: fantasy;"><h1>CUTTING SLIP</h1></td>
				<td width="25%" align:"right"></td>
		</tr>
		<tr>
				<td align="center" width="100%"><hr color=#00CC33 size=5 width=100></td>

		</tr>
		<tr>
				<td align="center" width="100%"></td>

		</tr>
			<tr>
				<td width="50%" align="left"><h2>Slip Date: '.date("d-m-Y", strtotime($slipDate)).'</h2></td>
				<td width="50%" align="left"><h2>Slip No: '.$slipSerialNumber.'</h2></td>
			</tr>
			<tr>
				<td align="center" width="100%"></td>
			</tr>
			<tr>
				<td width="50%" align="left"><h2>Coil Number: '.$invoice.'</h2></td>
				<td width="50%" align="left"><h2>JSW Coil Id: '.$jswid.'</h2></td>
			</tr>
			<tr>
				<td align="center" width="100%"></td>
    		</tr>
	        <tr>
	            <td width="50%" align="left"><h2>Party Name: '.$partyname.'</h2></td>
                <td width="50%" align="left"><h2>For Party:</h2></td>
		</tr>
		<tr>
				<td align="center" width="100%"></td>
		</tr>

		<tr>
			<td width="50%" align="left"><h2>Material Description: '.$material_description.'</h2></td>
			<td width="50%" align="left"><h2>SS Coil Id: '.$ssid.'</h2></td>

		</tr>
		<tr>
			<td align="center" width="100%"></td>
		</tr>
		<tr>
			<td width="50%" align="left"><h2>Width (mm): '.$Width.'</h2></td>
			<td width="50%" align="left"><h2>Grade : '.$vGrade.'</h2></td>
		</tr>
		<tr>
			<td align="center" width="100%"></td>
		</tr>
		<tr>
			<td width="50%" align="left"><h2>Thickness (mm): '.$Thickness.'</h2></td>
			<td width="50%" align="left"><h2>Heat No. : '.$vHeatnumber.'</h2></td>
		</tr>
		<tr>
			<td align="center" width="100%"></td>
		</tr>
		<tr>
				<td width="50%" align="left"><h2>Weight (mm): '.$Weight.'</h2></td>
				<td width="50%" align="left"><h2>Cutting Date: </h2></td>
		</tr>

		</table>';

                $html .= '
				<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>';


                $html .= '
		<table cellspacing="0" cellpadding="5" border="1px">
			<tr>
				<th align="center"><h1><b>S.No.</b></h1></th>
				<th align="center"><h1><b>Length(mm)</b></h1></th>
				<th align="center"><h1><b>Number of Pieces</b></h1></th>
				<th align="center"><h1><b>Bundle Weight (Kgs)</b></h1></th>
			</tr>';
                $lowerTable = '';

                if ($queryitem->num_rows() > 0)
                {
                    $i = 1;
                    foreach($queryitem->result() as $rowitem)
                    {
                        $primeLength = ($i == 1) ? 'Prime Length': '';
                        $i++;
                        $lowerTable .= '<tr>
                                            <td><b>'.$primeLength.'</b></td>
                                            <td><b>'.$rowitem->length.'</b></td>
                                            <td><b>Qty</b></td>
                                            <td></td>
                                            <td><b>SEC WT</b></td>
                                            <td></td>
                                        </tr>';
                        $html .= '
                            <tr>
                                <td align="center"><h1>'.$rowitem->bundlenumber.'</h1></td>
                                <td align="center"><h1>'.$rowitem->length.'</h1></td>
                                <td align="center"><h1>'.$rowitem->noofsheets.'</h1></td>
                                <td align="right"><h1>'.$rowitem->bundleweight.'(Approx)</h1></td>
                            </tr>';
                    }
                }else{
                    $html .= '
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center" >&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="right">&nbsp;</td>
			</tr>';
                }
                $html .= '</table>';

                $html .= '<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr></table>';

                $html .= '<table><tr><td><h1>Production Details</h1></td></tr><tr><td></td></tr></table>';
                $html .= '<table border="1" cellpadding="5">';
                $html .= $lowerTable;
                $html .= '<tr><td><b>NON PRIME</b></td><td></td><td><b>QTY</b></td><td></td><td><b>SEC WT</b></td><td></td></tr>';
                $html .= '<tr><td><b>SCRAP</b></td><td></td><td><b>QTY</b></td><td></td><td><b>SEC WT</b></td><td></td></tr>';
                $html .= '<tr><td><b>CROP END</b></td><td></td><td><b>QTY</b></td><td></td><td><b>SEC WT</b></td><td></td></tr>';
                $html .= '</table>';

                $html .= '<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr></table>';

                $html .= '<table><tr><td width="30%" align="center"><b>Operator</b></td><td width="60%" align="right"><b>For Sharoff Steel Traders</b></td></tr></table>';

                $html .= '
		<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center" style="font-size:45px; font-style:italic; font-family: fantasy;"><b>**Sharoff Steel Traders**</b></td>
			</tr>
		</table>';

                $pdf->writeHTML($html, true, 0, true, true);
                $pdf->Ln();
                $pdf->lastPage();
                $pdf->Output($pdfname, 'I');

            }
            else if( $row->vprocess =='Recoiling')
            {
                $sqlrecoiling = "select aspen_tblpartydetails.nPartyName as partyname,aspen_tblmatdescription.vDescription as materialdescription,fWidth as Width,fThickness as Thickness,dStartDate as Startdate,dEndDate as Enddate
	from aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription  ON aspen_tblmatdescription.nMatId=aspen_tblinwardentry.nMatId
	LEFT JOIN aspen_tblrecoiling  ON aspen_tblrecoiling.vIRnumber=aspen_tblinwardentry.vIRnumber
	LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails .nPartyId=aspen_tblinwardentry.nPartyId where aspen_tblinwardentry.vIRnumber ='".$partyid."' and aspen_tblpartydetails.nPartyName ='".$partyname."' ";
                $querymain = $this->db->query($sqlrecoiling);

                $invoice = $partyid;
                $party_name = $querymain->row(0)->partyname;
                $material_description = $querymain->row(0)->materialdescription;
                $Width = $querymain->row(0)->Width;
                $Thickness = $querymain->row(0)->Thickness;

                $sqlitem ="select aspen_tblrecoiling.nSno as recoilnumber, DATE_FORMAT(aspen_tblrecoiling.dStartDate, '%d-%m-%Y') AS startdate,  aspen_tblrecoiling.nNoOfRecoils as noofrecoils from aspen_tblrecoiling where aspen_tblrecoiling.vIRnumber='".$partyid."'";
                $queryitem = $this->db->query($sqlitem);

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdfname= 'recoilingslip_'.$partyid.'.pdf';
                $pdf->SetAuthor('Abhilash');
                $pdf->SetTitle('Recoilingslip');
                $pdf->SetSubject('Recoilingslip');
                $pdf->SetKeywords('Aspen, ERP, Recoilingslip');
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->AddPage();

                $html = '

					<div align="center">

		<table width="100%"  cellspacing="0" cellpadding="0" border="0">

			<tr>
				<td width="16%" align:"left"></td>
				<td width="70%"align="center" style="font-size:60px; font-style:italic; font-family: fantasy;"><h1>RECOILING SLIP</h1></td>
				<td width="25%" align:"right"></td>
		</tr>
		<tr>
				<td align="center" width="100%"><hr color=#00CC33 size=5 width=100></td>

		</tr>
		<tr>
				<td align="center" width="100%"></td>

		</tr>
			<tr>
				<td align="left"><h1><b>Coil Number:</b> '.$invoice.'</h1></td>
			</tr><tr>
				<td align="center" width="100%"></td>

		</tr>
	<tr><td align="left">
					<h1><b>Party Name: </b> '.$partyname.'</h1></td>

		</tr>
			<tr>
				<td align="center" width="100%"></td>

		</tr>

			<tr>
				<td align="left"><h1>	<b>Material Description: </b> '.$material_description.'</h1></td> </tr>	<tr>
				<td align="center" width="100%"></td>

		</tr>
		<tr>
				<td align="left"><h1>	<b>Width (mm): </b> '.$Width.'</h1></td> </tr>	<tr>
				<td align="center" width="100%"></td>

		</tr>
			<tr>

				<td align="left"><h1><b>Thickness (mm): </b> '.$Thickness.'</h1></td>
		</tr><tr>
				<td align="center" width="100%"></td>

		</tr>
		</table>';

                $html .= '
				<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>';


                $html .= '
		<table cellspacing="0" cellpadding="5" border="1px">
			<tr>
				<th align="center"><h1><b>S.No.</b></h1></th>
				<th align="center"><h1><b>Recoiling Date</b></h1></th>
				<th align="center"><h1><b>Number of Recoils</b></h1></th>
			</tr>';
                if ($queryitem->num_rows() > 0)
                {
                    foreach($queryitem->result() as $rowitem)
                    {
                        $html .= '
			<tr>
				<td align="center"><h1>'.$rowitem->recoilnumber.'</h1></td>
				<td align="center" ><h1>'.$rowitem->startdate.'</h1></td>
				<td align="center"><h1>'.$rowitem->noofrecoils.'</h1></td>
			</tr>';
                    }
                }else{
                    $html .= '
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center" >&nbsp;</td>
			</tr>';
                }
                $html .= '

		</table>';

                $html .= '
		<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center" style="font-size:45px; font-style:italic; font-family: fantasy;"><b>**Sharoff Steel Traders**</b></td>
			</tr>
		</table>';

                $pdf->writeHTML($html, true, 0, true, true);
                $pdf->Ln();
                $pdf->lastPage();
                $pdf->Output($pdfname, 'I');

            }
            else if( $row->vprocess =='Slitting')
            {
                $sqlSlitting = "select aspen_tblpartydetails.nPartyName as partyname,aspen_tblmatdescription.vDescription as materialdescription,fWidth as Width, fThickness as Thickness,dDate as Startdate,fQuantity as Weight from aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription  ON aspen_tblmatdescription.nMatId=aspen_tblinwardentry.nMatId LEFT JOIN aspen_tblslittinginstruction  ON aspen_tblslittinginstruction.vIRnumber=aspen_tblinwardentry.vIRnumber
	LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails .nPartyId=aspen_tblinwardentry.nPartyId where aspen_tblinwardentry.vIRnumber ='".$partyid."' and aspen_tblpartydetails.nPartyName ='".$partyname."' ";
                $querymain = $this->db->query($sqlSlitting);
                $invoice = $partyid;
                $party_name = $querymain->row(0)->partyname;
                $material_description = $querymain->row(0)->materialdescription;
                $Width = $querymain->row(0)->Width;
                $Thickness = $querymain->row(0)->Thickness;
                $Weight = $querymain->row(0)->Weight;

                $sqlitem ="select aspen_tblslittinginstruction.nSno as slitnumber,
		DATE_FORMAT(aspen_tblslittinginstruction.dDate, '%d-%m-%Y') AS startdate,
		aspen_tblslittinginstruction.nLength as length,
		 aspen_tblslittinginstruction.nWidth as width,
		 aspen_tblslittinginstruction.nWeight as weight
		  from aspen_tblslittinginstruction where aspen_tblslittinginstruction.vIRnumber='".$partyid."'";

                $queryitem = $this->db->query($sqlitem);

                date_default_timezone_set('Asia/Kolkata');

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdfname= 'slittingslip_'.$partyid.'.pdf';
                $pdf->SetAuthor('Abhilash');
                $pdf->SetTitle('Slittingslip');
                $pdf->SetSubject('Slittingslip');
                $pdf->SetKeywords('Aspen, ERP, Slittingslip');
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->AddPage();

                $html = '

				<div align="center">

		<table width="100%"  cellspacing="0" cellpadding="0" border="0">

			<tr>
				<td width="16%" align:"left"></td>
				<td width="70%"align="center" style="font-size:60px; font-style:italic; font-family: fantasy;"><h1>SLITTING SLIP</h1></td>
				<td width="25%" align:"right"></td>
		</tr>
		<tr>
				<td align="center" width="100%"><hr color=#00CC33 size=5 width=100></td>

		</tr>
		<tr>
				<td align="center" width="100%"></td>

		</tr>
		<tr>
				<td align="left"><h1><b>Slip Date:</b> '.date("d-m-Y").'</h1></td>
			</tr>
			<tr>
				<td align="center" width="100%"></td>

		</tr>
			<tr>
				<td align="left"><h1><b>Coil Number:</b> '.$invoice.'</h1></td>
			</tr><tr>
				<td align="center" width="100%"></td>

		</tr>
	<tr><td align="left">
					<h1><b>Party Name: </b> '.$partyname.'</h1></td>

		</tr>
			<tr>
				<td align="center" width="100%"></td>

		</tr>

			<tr>
				<td align="left"><h1>	<b>Material Description: </b> '.$material_description.'</h1></td> </tr>	<tr>
				<td align="center" width="100%"></td>

		</tr>
		<tr>
			<td align="left"><h1><b>Width (mm): </b>'.$Width.'</h1></td> </tr>	<tr>
			<td align="center" width="100%"></td>
		</tr>
		<tr>
			<td align="left"><h1><b>Thickness (mm): </b> '.$Thickness.'</h1></td>
		</tr>
		<tr><td align="center" width="100%"></td></tr>
		<tr>
			<td align="left"><h1><b>Weight (kgs): </b> '.$Weight.'</h1></td>
		</tr>
		<tr><td align="center" width="100%"></td></tr>
		<tr>
			<td align="center" width="100%"></td>
		</tr>
		</table>';

                $html .= '
				<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>';


                $html .= '
		<table cellspacing="0" cellpadding="5" border="1px">
			<tr>
				<th align="center"><h1><b>S.No.</b></h1></th>
				<th align="center"><h1><b>Slitting Date</b></h1></th>
				<th align="center"><h1><b>Length</b></h1></th>
				<th align="center"><h1><b>Width</b></h1></th>
				<th align="center"><h1><b>Weight</b></h1></th>
			</tr>';
                if ($queryitem->num_rows() > 0)
                {
                    foreach($queryitem->result() as $rowitem)
                    {
                        $html .= '

			<tr>
				<td align="center"><h1>'.$rowitem->slitnumber.'</h1></td>
				<td align="center" ><h1>'.$rowitem->startdate.'</h1></td>
				<td align="center"><h1>'.$rowitem->length.'</h1></td>
				<td align="center"><h1>'.$rowitem->width.'</h1></td>
				<td align="center"><h1>'.round($rowitem->weight).'</h1></td>
			</tr>';
                    }
                }else{
                    $html .= '
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center" >&nbsp;</td>

			</tr>';
                }
                $html .= '

		</table>';

                $html .= '
		<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center" style="font-size:45px; font-style:italic; font-family: fantasy;"><b>**Sharoff Steel Traders**</b></td>
			</tr>
		</table>';

                $pdf->writeHTML($html, true, 0, true, true);
                $pdf->Ln();
                $pdf->lastPage();
                $pdf->Output($pdfname, 'I');

            }

            $arr='';
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $arr[] =$row;
                }
            }
            return json_encode($arr[0]);
        }

    }

}
class Workinprogress_model extends Base_module_record {

}
