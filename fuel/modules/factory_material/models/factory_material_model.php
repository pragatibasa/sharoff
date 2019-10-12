<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH . 'models/base_module_model.php');
require_once(APPPATH . 'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH . 'helpers/tcpdf/tcpdf.php');

class factory_material_model extends Base_module_model {

    function __construct() {
        parent::__construct('aspen_tblmatdescription');
    }


    function select_coilname() {
        $query = $this->db->query("select * from aspen_tblmatdescription order by vDescription ");
        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }


    function export_partyname() {
        $condition = '';
        $condition1 = '';
        if(isset($_POST['frmdate']) && isset($_POST['todate'])) {
            $condition = "WHERE  aspen_tblinwardentry.dReceivedDate BETWEEN '".$_POST['frmdate']."' AND '".$_POST['todate']."'";
            $condition1 = "WHERE aspen_tblbilldetails.dBillDate BETWEEN '".$_POST['frmdate']."' AND '".$_POST['todate']."'";
        }

        $sql = "SELECT aspen_tblpartydetails.nPartyName as partyname, COALESCE(t1.inweight,0) as inweight, COALESCE(t2.outweight, 0) as outweight, COALESCE(t1.inweight,0)-COALESCE(t2.outweight, 0) as balance
     FROM aspen_tblpartydetails
     LEFT JOIN 
        (SELECT aspen_tblpartydetails.nPartyId,round(SUM(aspen_tblinwardentry.fQuantity),3) AS inweight from aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId $condition group by aspen_tblpartydetails.nPartyId) t1
    on aspen_tblpartydetails.nPartyId = t1.nPartyId
     LEFT JOIN
    (SELECT  aspen_tblpartydetails.nPartyId ,round(sum(aspen_tblbilldetails.fTotalWeight),3) AS outweight FROM aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId left join aspen_tblbilldetails on aspen_tblinwardentry.vIRnumber = aspen_tblbilldetails.vIRnumber $condition1 group by aspen_tblpartydetails.nPartyId with rollup) t2 on aspen_tblpartydetails.nPartyId = t2.nPartyId;";

        $query = $this->db->query($sql);

        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }


    function billgeneratemodel() {

        $condition = '';
        $condition1 = '';
        if(isset($_REQUEST['frmdate']) && isset($_REQUEST['todate'])) {
            $condition = "WHERE  aspen_tblinwardentry.dReceivedDate BETWEEN '".$_REQUEST['frmdate']."' AND '".$_REQUEST['todate']."'";
            $condition1 = "WHERE aspen_tblbilldetails.dBillDate BETWEEN '".$_REQUEST['frmdate']."' AND '".$_REQUEST['todate']."'";
        }

        $sql = "SELECT aspen_tblpartydetails.nPartyName as partyname, COALESCE(t1.inweight,0) as inweight, COALESCE(t2.outweight, 0) as outweight, COALESCE(t1.inweight,0)-COALESCE(t2.outweight, 0) as balance
     FROM aspen_tblpartydetails
     LEFT JOIN 
        (SELECT aspen_tblpartydetails.nPartyId,round(SUM(aspen_tblinwardentry.fQuantity),3) AS inweight from aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId $condition group by aspen_tblpartydetails.nPartyId) t1
    on aspen_tblpartydetails.nPartyId = t1.nPartyId
     LEFT JOIN
    (SELECT  aspen_tblpartydetails.nPartyId ,round(sum(aspen_tblbilldetails.fTotalWeight),3) AS outweight FROM aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId left join aspen_tblbilldetails on aspen_tblinwardentry.vIRnumber = aspen_tblbilldetails.vIRnumber $condition1 group by aspen_tblpartydetails.nPartyId with rollup) t2 on aspen_tblpartydetails.nPartyId = t2.nPartyId;";


        $sumSql = " SELECT sum(COALESCE(t1.inweight,0)) as tot_inweight, sum(COALESCE(t2.outweight,0)) as tot_outweight, sum(COALESCE(t1.inweight,0)-COALESCE(t2.outweight, 0)) as tot_balance
         FROM aspen_tblpartydetails
         LEFT JOIN 
             (SELECT aspen_tblpartydetails.nPartyId,round(SUM(aspen_tblinwardentry.fQuantity),3) AS inweight from aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId $condition group by aspen_tblpartydetails.nPartyId) t1
         on aspen_tblpartydetails.nPartyId = t1.nPartyId
          LEFT JOIN
        (SELECT  aspen_tblpartydetails.nPartyId ,round(sum(aspen_tblbilldetails.fTotalWeight),3) AS outweight FROM aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId left join aspen_tblbilldetails on aspen_tblinwardentry.vIRnumber = aspen_tblbilldetails.vIRnumber $condition1 group by aspen_tblpartydetails.nPartyId) t2 on aspen_tblpartydetails.nPartyId = t2.nPartyId;";

        $querymain = $this->db->query($sql);
        $querySum = $this->db->query($sumSql);


        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdfname = 'factoryMovement.pdf';
        $resolution = array(72, 150);
        $pdf->SetAuthor('ASPEN');
        $pdf->SetTitle('Invoice');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('Aspen, bill, invoice');
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
					<div align="center"><h1>TOTAL FACTORY MATERIAL MOVEMENT</h1></div>
                <table width="100%" cellspacing="0" cellpadding="5" border="0">';
        if($condition != '') {
			$html .= '<tr>
				
				<td align="center"><h2>From Date:&nbsp;&nbsp;&nbsp;' . $_REQUEST['frmdate'] . '</h2></td>
				<td align="center"><h2>To Date:&nbsp;&nbsp;&nbsp;' . $_REQUEST['todate'] . '</h2></td>
            </tr>';
                }
			$html .= '<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
		
			</tr>
		</table>';

        $html .= '
		<table cellspacing="0" cellpadding="5" border="0.5px">
			<tr>
				<th align="center"><h2>Party Name</h2></th>		
				<th align="center"><h2>Inward Weight</h2></th>					
                <th align="center"><h2>Outward Weight</h2></th>
                <th align="center"><h2>Balance</h2></th>
               
			</tr>';

        if ($querymain->num_rows() > 0) {

            foreach ($querymain->result() as $rowitem) {
                $html .= '			
                         <tr>
                         <td align="center"><h2>' . $rowitem->partyname . '</h2></td>			
                         <td align="center"><h2>' .number_format((float)$rowitem->inweight,3) . '</h2></td>			
                         <td align="center" ><h2>' .number_format((float)$rowitem->outweight,3) . '</h2></td>
                         <td align="center" ><h2>' .number_format((float)$rowitem->balance,3) . '</h2></td>		
                         </tr>';
            }
            if ($querySum->num_rows() > 0) {
                    $html .= '                    
            <tr>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center" >&nbsp;</td>
        </tr>
            <tr>
            <th align="center"><h2>Total</h2></th>
        			
                 <td align="center"><h2>' . number_format((float)$querySum->result()[0]->tot_inweight, 3) . '</h2></td>			
                 <td align="center" ><h2>' . number_format((float)$querySum->result()[0]->tot_outweight, 3) . '</h2></td>
                 <td align="center" ><h2>' . number_format((float)$querySum->result()[0]->tot_balance, 3) . '</h2></td>		
            </tr>';
            }
        } else {
            $html .= '
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center" >&nbsp;</td>
                    </tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, 0, true, true);
        $pdf->Ln();
        $pdf->lastPage();
        $pdf->Output($pdfname, 'I');
    }

    function form_fields()
    {
        $fields['nMinLength']['type'] = 'Min length';
        $fields['nMaxLength']['type'] = 'Max length';
        $fields['nAmount']['type'] = 'Amount';
        return $fields;
    }

    function CoilTable()
    {
        if (isset($_POST['coil'])) {
            $coilname = $_POST['coil'];
        }
        $sql = "SELECT DATE_FORMAT(aspen_tblinwardentry.dReceivedDate, '%d-%m-%Y') as receiveddate,aspen_tblpartydetails.nPartyName as partyname,aspen_tblinwardentry.fThickness as thickness, aspen_tblinwardentry.fWidth as width, aspen_tblinwardentry.fQuantity as weight, aspen_tblinwardentry.vStatus as status , aspen_tblinwardentry.vIRnumber as coilnumber,aspen_tblinwardentry.vprocess as process FROM aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription ON aspen_tblmatdescription.nMatId = aspen_tblinwardentry.nMatId LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId LEFT JOIN aspen_tblcuttinginstruction ON aspen_tblcuttinginstruction.vIRnumber = aspen_tblinwardentry.vIRnumber ";
        if (!empty($coilname)) {
            $sql .= " WHERE  aspen_tblmatdescription.vDescription='" . $coilname . "'";
        }

        $query = $this->db->query($sql);
        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }


    function list_partyname($description = '')
    {
        $sql = "SELECT DATE_FORMAT(aspen_tblinwardentry.dReceivedDate, '%d-%m-%Y') as receiveddate,aspen_tblpartydetails.nPartyName as partyname, aspen_tblmatdescription.vDescription as description, aspen_tblinwardentry.fThickness as thickness, aspen_tblinwardentry.fWidth as width, aspen_tblinwardentry.fQuantity as weight, aspen_tblinwardentry.vStatus as status , aspen_tblinwardentry.vIRnumber as coilnumber,aspen_tblinwardentry.vprocess as process FROM aspen_tblinwardentry LEFT JOIN aspen_tblmatdescription ON aspen_tblmatdescription.nMatId = aspen_tblinwardentry.nMatId LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId LEFT JOIN aspen_tblcuttinginstruction ON aspen_tblcuttinginstruction.vIRnumber = aspen_tblinwardentry.vIRnumber ";
        if (!empty($description)) {
            $sql .= " WHERE  aspen_tblmatdescription.vDescription='" . $description . "'";
        }
        $sql .= " group by aspen_tblinwardentry.vIRnumber order by aspen_tblinwardentry.dReceivedDate asc";
        $query = $this->db->query($sql);
        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }

    function totalweight_check() {
        $condition = '';
        $condition1 = '';
        if(isset($_POST['frmdate']) && isset($_POST['todate'])) {
            $condition = "WHERE  aspen_tblinwardentry.dReceivedDate BETWEEN '".$_POST['frmdate']."' AND '".$_POST['todate']."'";
            $condition1 = "WHERE aspen_tblbilldetails.dBillDate BETWEEN '".$_POST['frmdate']."' AND '".$_POST['todate']."'";
        }

        $sql = " SELECT sum(COALESCE(t1.inweight,0)) as tot_inweight, sum(COALESCE(t2.outweight,0)) as tot_outweight, sum(COALESCE(t1.inweight,0)-COALESCE(t2.outweight, 0)) as tot_balance
         FROM aspen_tblpartydetails
         LEFT JOIN 
             (SELECT aspen_tblpartydetails.nPartyId,round(SUM(aspen_tblinwardentry.fQuantity),3) AS inweight from aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId $condition group by aspen_tblpartydetails.nPartyId) t1
         on aspen_tblpartydetails.nPartyId = t1.nPartyId
          LEFT JOIN
        (SELECT  aspen_tblpartydetails.nPartyId ,round(sum(aspen_tblbilldetails.fTotalWeight),3) AS outweight FROM aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId left join aspen_tblbilldetails on aspen_tblinwardentry.vIRnumber = aspen_tblbilldetails.vIRnumber $condition1 group by aspen_tblpartydetails.nPartyId) t2 on aspen_tblpartydetails.nPartyId = t2.nPartyId;";

        $query = $this->db->query($sql);

        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
	}
}

class factorymaterial_model extends Base_module_model
{

}
