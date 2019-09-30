<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH . 'models/base_module_model.php');
require_once(APPPATH . 'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH . 'helpers/tcpdf/tcpdf.php');

class factory_material_model extends Base_module_model
{

    function __construct()
    {
        parent::__construct('aspen_tblmatdescription');
    }


    function select_coilname()
    {
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
        if(isset($_POST['frmdate']) && isset($_POST['todate'])) {
            $condition = "AND aspen_tblinwardentry.dReceivedDate BETWEEN '".$frmdate."' AND '".$todate."'";
        }
 
        $sql = "SELECT 
                    aspen_tblpartydetails.nPartyName as partyname,
                    SUM(aspen_tblinwardentry.fQuantity) AS inweight,
                    SUM(aspen_tblinwardentry.billedweight) AS outweight,
                    SUM(aspen_tblinwardentry.fQuantity) - SUM(aspen_tblinwardentry.billedweight) as balance 
                FROM
                    aspen_tblinwardentry
                        LEFT JOIN
                    aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId
                WHERE
                    fpresent > 0
                    $condition
                GROUP BY aspen_tblpartydetails.nPartyName with rollup";
  
        $query = $this->db->query($sql);

        $arr = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }


    function billgeneratemodel()
    {

        $condition = '';
        if(isset($_POST['frmdate']) && isset($_POST['todate'])) {
            $condition = "AND aspen_tblinwardentry.dReceivedDate BETWEEN '".$frmdate."' AND '".$todate."'";
        }
        $sqlrpt = "SELECT 
                    aspen_tblpartydetails.nPartyName as partyname,
                    SUM(aspen_tblinwardentry.fQuantity) AS inweight,
                    SUM(aspen_tblinwardentry.billedweight) AS outweight,
                    SUM(aspen_tblinwardentry.fQuantity) - SUM(aspen_tblinwardentry.billedweight) as balance 
                FROM
                    aspen_tblinwardentry
                        LEFT JOIN
                    aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId
                WHERE
                    fpresent > 0
                    $condition
                GROUP BY aspen_tblpartydetails.nPartyName with rollup";

//print_r($sqlrpt);exit;

        $querymain = $this->db->query($sqlrpt);


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
				
				<td align="center"><h2>From Date:&nbsp;&nbsp;&nbsp;' . $frmdate . '</h2></td>
				<td align="center"><h2>To Date:&nbsp;&nbsp;&nbsp;' . $todate . '</h2></td>
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
            $resultCount = count($querymain->result())-1;
            for($i = 0; $i < $resultCount; $i++) {
                $html .= '			
                         <tr>
                         <td align="center"><h2>' . $querymain->result()[$i]->partyname . '</h2></td>			
                         <td align="center"><h2>' .number_format((float)$querymain->result()[$i]->inweight,3) . '</h2></td>			
                         <td align="center" ><h2>' .number_format((float)$querymain->result()[$i]->outweight,3) . '</h2></td>
                         <td align="center" ><h2>' .number_format((float)$querymain->result()[$i]->balance,3) . '</h2></td>		
                         </tr>';
            }
            $html .= '                    
            <tr>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center" >&nbsp;</td>
        </tr>
            <tr>
            <th align="center"><h2>Total</h2></th>
        			
                 <td align="center"><h2>' .number_format((float)$querymain->result()[$resultCount]->inweight,3) . '</h2></td>			
                 <td align="center" ><h2>' .number_format((float)$querymain->result()[$resultCount]->outweight,3) . '</h2></td>
                 <td align="center" ><h2>' .number_format((float)$querymain->result()[$resultCount]->balance,3) . '</h2></td>		
            </tr>';
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
   /* function totalweight_check($partyname='',$frmdate='',$todate='') {
		$sql=  "SELECT SUM( fQuantity ) as weight FROM aspen_tblinwardentry LEFT JOIN aspen_tblpartydetails ON aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId where aspen_tblpartydetails.nPartyName = '".$partyname."' and aspen_tblinwardentry.dReceivedDate BETWEEN '".$frmdate."' AND '".$todate."'";

		$query = $this->db->query($sql);
		$arr='';
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $row)
		   {
		      $arr[] =$row;
		   }
		}
		return $arr;
	}*/


}

class factorymaterial_model extends Base_module_model
{

}
