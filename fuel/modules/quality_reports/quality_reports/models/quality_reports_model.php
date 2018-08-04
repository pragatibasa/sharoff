<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/billing/config/billing_constants.php');
require_once(APPPATH.'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH.'helpers/tcpdf/tcpdf.php');

class Quality_reports_model extends Base_module_model {

    function __construct() {
        parent::__construct('aspen_tblinwardentry');
    }

    function getCoilDetails($coilNumber) {
      $strCoilNumberSql = 'select * from aspen_tblinwardentry as ai left join aspen_tblpartydetails as ap on ai.nPartyId = ap.nPartyId where vIRnumber='.$coilNumber;
      $resCoillDetailsObj = $this->db->query($strCoilNumberSql);
      return $resCoillDetailsObj->row(0);
    }

    function getSlitDetails($coilNumber) {
      $strSlitDetailsSql = 'select * from aspen_tblslittinginstruction where vIRnumber='.$coilNumber;
      $resSlitlDetailsObj = $this->db->query($strSlitDetailsSql);
      $arr = array();
      foreach ($resSlitlDetailsObj->result() as $row) {
        $arr[] =$row;
      }
      return $arr;
    }

    function insertCoilDetails($postData,$fileData) {
      $strInsertCoilDetails = "insert into aspen_tblSlitQualityReport(coil_grade,coil_winding,order_number,surface_condition1,surface_condition_desc1,surface_filename1,surface_condition2,surface_condition_desc2,surface_filename2,surface_condition3,surface_condition_desc3,surface_filename3,surface_condition4,surface_condition_desc4,surface_filename4,surface_condition5,surface_condition_desc5,surface_filename5,prepared_by,verified_by,created_on,updated_on,final_judgement,coil_number) values('".$postData['coilGrade']."','".$postData['coilWinding']."','".$postData['orderNo']."','".$postData['coilSurface1']."','".$postData['coilSurfaceDescription1']."','".$fileData['coilFile1']['name']."','".$postData['coilSurface2']."','".$postData['coilSurfaceDescription2']."','".$fileData['coilFile2']['name']."','".$postData['coilSurface3']."','".$postData['coilSurfaceDescription3']."','".$fileData['coilFile3']['name']."','".$postData['coilSurface4']."','".$postData['coilSurfaceDescription4']."','".$fileData['coilFile4']['name']."','".$postData['coilSurface5']."','".$postData['coilSurfaceDescription5']."','".$fileData['coilFile5']['name']."','".$postData['preparedBy']."','".$postData['verifiedBy']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".$postData['finalJudgement']."','".$postData['coilnumber']."')";

      $resInsertCoilReport = $this->db->query($strInsertCoilDetails);
      $label_id = mysql_insert_id();
      move_uploaded_file($_FILES['coilFile1']['tmp_name'],WEB_ROOT.'/uploads/'.$_FILES['coilFile1']['name']);
      move_uploaded_file($_FILES['coilFile2']['tmp_name'],WEB_ROOT.'/uploads/'.$_FILES['coilFile2']['name']);
      move_uploaded_file($_FILES['coilFile3']['tmp_name'],WEB_ROOT.'/uploads/'.$_FILES['coilFile3']['name']);
      move_uploaded_file($_FILES['coilFile4']['tmp_name'],WEB_ROOT.'/uploads/'.$_FILES['coilFile4']['name']);
      move_uploaded_file($_FILES['coilFile5']['tmp_name'],WEB_ROOT.'/uploads/'.$_FILES['coilFile5']['name']);

      if($resInsertCoilReport) {
        $strSlitDetails = "select * from aspen_tblslittinginstruction where vIRnumber = '".$postData['coilnumber']."'";
        $resSlittingDetails = $this->db->query($strSlitDetails);
        foreach ($resSlittingDetails->result() as $index => $row) {
          $camberValue = ($postData['camber'][$index] == 'yes') ? 1 : 0;
          $strInsertSlitBundleDetails = "insert into aspen_tblSlittingQualityReport
          (coil_number,coil_report_id,slit_number,actual_thickness_min,actual_thickness_max,
          actual_width_min,actual_width_max,burr_min,burr_max,strip_1,surface_desc1,
          surface_fileName1,strip_2,surface_desc2,
          surface_fileName2,
          strip_3,surface_desc3,
          surface_fileName3,
          strip_4,surface_desc4,
          surface_fileName4,
          strip_5,surface_desc5,
          surface_fileName5,camber,final_judgement) values('".$postData['coilnumber']."','".$label_id."','".$row->nSno."',
          '".$postData['actualThicknessMin'][$index]."','".$postData['actualThicknessMax'][$index]."',
          '".$postData['actualWidthMin'][$index]."','".$postData['actualWidthMax'][$index]."',
          '".$postData['burrMin'][$index]."','".$postData['burrMax'][$index]."',
          '".$postData['slitSurface0'][$index]."',
          '".$postData['slitSurfaceDescription0'][$index]."','".$fileData['slitFile0']['name'][$index]."',
          '".$postData['slitSurface1'][$index]."',
          '".$postData['slitSurfaceDescription1'][$index]."','".$fileData['slitFile1']['name'][$index]."',
          '".$postData['slitSurface2'][$index]."',
          '".$postData['slitSurfaceDescription2'][$index]."','".$fileData['slitFile2']['name'][$index]."',
          '".$postData['slitSurface3'][$index]."',
          '".$postData['slitSurfaceDescription3'][$index]."','".$fileData['slitFile3']['name'][$index]."',
          '".$postData['slitSurface4'][$index]."',
          '".$postData['slitSurfaceDescription4'][$index]."','".$fileData['slitFile4']['name'][$index]."',
          '".$camberValue."','".$postData['slitFinalJudgement'][$index]."')";
          print_r($strInsertSlitBundleDetails);exit;
          $resInsertSlitBundle = $this->db->query($strInsertSlitBundleDetails);
          if($resInsertSlitBundle) {
            move_uploaded_file($_FILES['slitFile0']['tmp_name'][$index],WEB_ROOT.'/uploads/'.$_FILES['slitFile0']['name']);
            move_uploaded_file($_FILES['slitFile1']['tmp_name'][$index],WEB_ROOT.'/uploads/'.$_FILES['slitFile1']['name']);
            move_uploaded_file($_FILES['slitFile2']['tmp_name'][$index],WEB_ROOT.'/uploads/'.$_FILES['slitFile2']['name']);
            move_uploaded_file($_FILES['slitFile3']['tmp_name'][$index],WEB_ROOT.'/uploads/'.$_FILES['slitFile3']['name']);
            move_uploaded_file($_FILES['slitFile4']['tmp_name'][$index],WEB_ROOT.'/uploads/'.$_FILES['slitFile4']['name']);
          }
        }
        echo 'success';exit;
      }
    }

    function list_items() {
      $querymain = $this->db->query("select * from aspen_tblSlitQualityReport as aq left join aspen_tblinwardentry as ai
      on aq.coil_number = ai.vIRnumber left join aspen_tblpartydetails ap on ai.nPartyId = ap.nPartyId");
  		return $querymain->result();
    }

    function getCoilReportDetails($reportId) {
      $querymain = $this->db->query('select * from aspen_tblSlitQualityReport where report_id='.$reportId);
      return $querymain->row(0);
    }

    function getSlitReportDetails($reportId) {
      $querymain = $this->db->query('select * from aspen_tblSlittingQualityReport where coil_report_id='.$reportId);
      return $querymain->result();
    }

    function surfaceDetails() {
      return array('black marks' => 'Black Marks in mother coil',
                  'rust marks' => 'Rust Marks in mother coil',
                'water marks' => 'Water Marks in mother coil',
              'scratches' => 'Scratches and impressions in mother coil',
            'other' => 'Other',
          'N/A' => 'N/A');
    }

    function displayQualityReportPdf($reportId) {
      $vars = array();
      $vars['slit_coil_details'] = $this->getCoilReportDetails($reportId);
      $vars['coil_details'] = $this->getCoilDetails($vars['slit_coil_details']->coil_number);
      $vars['slit_details'] = $this->getSlitReportDetails($reportId);
      $vars['surfaceDetails'] = $this->surfaceDetails();

      $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdfname= 'QualityReport'.$vars['slit_coil_details']->coil_number.'.pdf';
      $resolution= array(72, 150);
      $pdf->SetAuthor('ASPEN');
      $pdf->SetTitle('Quality Report');
      $pdf->SetSubject('Quality Report');
      $pdf->SetKeywords('Aspen, quality, report, slitting');
      // set default header data
      $pdf->SetHeaderData('', '', 'Slitting Inspection Report', 'Prepared on : '.date('d-m-Y',strtotime($vars['slit_coil_details']->created_on)).' by ASPEN STEEL PVT LTD');
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
       <table align="center" width="100%" cellspacing="0" cellpadding="5"  border="0.1">
         <tr>
           <td width="100%" align="center" style="font-size:30px; font-style:italic; font-family: fantasy;"><h1>Coil Details</h1></td>
         </tr>
         <tr>
           <td width="50%" align="left"><h1><b>Coil Number: '.$vars['slit_coil_details']->coil_number.'</b></h1></td>
           <td align="left" width="50%"><h1> Party Name: '.$vars['coil_details']->nPartyName.' </h1></td>
         </tr>
         <tr>
           <td width="50%" align="left"><h1><b>Coil Width: '.$vars['coil_details']->fWidth.'</b></h1></td>
           <td width="50%" align="left"><h1><b>Coil Thickness: '.$vars['coil_details']->fThickness.'</b></h1></td>
         </tr>
         <tr>
           <td width="50%" align="left"><h1><b>Coil Grade: '.$vars['slit_coil_details']->coil_grade.'</b></h1></td>
           <td width="50%" align="left"><h1><b>Coil Winding: '.$vars['slit_coil_details']->coil_winding.'</b></h1></td>
         </tr>
         <tr>
          <td width="50%" align="left"><h1><b>Job Cart/Order Number: '.$vars['slit_coil_details']->order_number.'</b></h1></td>
          <td width="50%" align="left"><h1><b>Prepared By: '.$vars['slit_coil_details']->prepared_by.'</b></h1></td>
         </tr>
         <tr>
          <td width="50%" align="left"><h1><b>Verified By: '.$vars['slit_coil_details']->verified_by.'</b></h1></td>
          <td width="50%" align="left"><h1><b>Final Judgement: '.$vars['slit_coil_details']->final_judgement.'</b></h1></td>
         </tr>
       </table>';
       $html .= '<br /><table><tr style="text-align:center;"><td><h1><b>Slit Details</b></h1></td></tr></table><br />

       <table border="1" width="100%">
         <tr style="text-align:center;">
           <th><h1><b>Slit Number</b></h1></th>
           <th colspan="2"><h1><b>Actual thickness</b></h1></th>
           <th colspan="2"><h1><b>Actual Width</b></h1></th>
           <th colspan="2"><h1><b>Burr</b></h1></th>
           <th colspan="2"><h1><b>Surface/strip condition</b></h1></th>
           <th><h1><b>Camber</b></h1></th>
           <th><h1><b>Final judgement</b></h1></th>
         </tr>
         <tr style="text-align:center;">
           <th></th>
           <th><h1><b>Min</b></h1></th>
           <th><h1><b>Max</b></h1></th>

           <th><h1><b>Min</b></h1></th>
           <th><h1><b>Max</b></h1></th>

           <th><h1><b>Min</b></h1></th>
           <th><h1><b>Max</b></h1></th>

           <th><h1><b>Condition</b></h1></th>
           <th><h1><b>Desc</b></h1></th>

           <th></th>
           <th></th>
         </tr>';
        foreach($vars['slit_details'] as $index => $value) {
              $html .= '<tr style="text-align:center;"><td><h1><b>'.$value->slit_number.'</b></h1></td><td><h1><b>'.$value->actual_thickness_min.'</b></h1></td>';
              $html .= '<td><h1><b>'.$value->actual_thickness_max.'</b></h1></td><td><h1><b>'.$value->actual_width_min.'</b></h1></td><td><h1><b>'.$value->actual_width_max.'</b></h1></td>';

              $html .= '<td><h1><b>'.$value->burr_min.'</b></h1></td><td><h1><b>'.$value->burr_max.'</b></h1></td>';

              $html .= '<td><h1><b>'.((strlen(trim($value->strip_1))>0) ? $vars['surfaceDetails'][$value->strip_1] : '-').'</b></h1></td>';
              $html .= '<td><h1><b>'.(($value->surface_desc1) ? $value->surface_desc1 : '-').'</b></h1></td>';

              $html .= '<td><h1><b>'.(($value->camber == 1 ) ? 'Yes' : 'No').'</b></h1></td>';
              $html .= '<td><h1><b>'.(($value->final_judgement) ? $value->final_judgement : '-').'</b></h1></td></tr>';
            }

            $html .= '</table>

       <table cellspacing="0" cellpadding="5" border="0">
         <tr><td align="left">&nbsp;</td><td align="right">&nbsp;</td></tr>
         <tr>
           <td colspan="2" align="right" style="font-size:45px; font-style:italic; font-family: fantasy;"><b>QC sign ______________________</b></td>
         </tr>
       </table>';


      $pdf->writeHTML($html, true, 0, true, true);
      $pdf->Ln();
      $pdf->lastPage();
      $pdf->Output($pdfname, 'I');

    }
}
