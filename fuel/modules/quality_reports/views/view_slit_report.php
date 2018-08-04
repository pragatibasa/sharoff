<?php  include_once(QUALITY_REPORTS_PATH.'views/layout.php'); ?>
<div id="innerpanel">
  <fieldset>
    <div style="margin-bottom: 20px;border-bottom: 1px solid #e5e5e5;">
      <legend style="border:0px; display:inline;">Coil Details:</legend>
      <div style="float:right;">
        <a href="<?php echo fuel_url('quality_reports/view_quality_pdf?report_id='.$report_id);?>" target="_blank"><button>View Pdf</button></a>
      </div>
    </div>
      <table cellpadding="2" cellspacing="10" border="0">
        <tr>
          <td><label><?=lang('coil_number')?> </label></td>
          <td><label> : <?=$coil_details->vIRnumber?></label></td>
        </tr>

        <tr>
          <td><label><?=lang('party_name')?></label></td>
          <td><label>:<?=$coil_details->nPartyName?></label></td>
        </tr>
        <tr>
          <td><label><?=lang('coil_width')?></label></td>
          <td><label>:<?=$coil_details->fWidth?></label></td>
        </tr>
        <tr>
          <td><label><?=lang('coil_thickness')?></label></td>
          <td><label>:<?=$coil_details->fThickness?></label></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend> Report details for coil</legend>
        <table cellpadding="1" cellspacing="10" border="0">
          <tr>
          	<td><span><label>Coil Grade</label></span></td>
          	<td><label>:<?=$slit_coil_details->coil_grade?></label></td>
          </tr>
          <tr>
            <td><span><label>Coil Winding</label></span></td>
            <td><label>:<?=$slit_coil_details->coil_winding?></label></td>
          </tr>
          <tr>
            <td><span><label>Job Cart/Order Number</label></span></td>
            <td><label>:<?=$slit_coil_details->order_number?></label></td>
          </tr>
          <tr>
          	<td><span><label>Surface / Strip Condition</label></span></td>
          	<td><label>:<?=$surfaceDetails[$slit_coil_details->surface_condition1]?></label></td>
          </tr>
          <tr>
            <td></td>
            <td><label>:<?=$slit_coil_details->surface_condition_desc1?></label></td>
          </tr>
          <tr>
            <td></td>
            <td><label>:<a href="<?=base_url().'uploads/'.$slit_coil_details->surface_filename1?>"><?=$slit_coil_details->surface_filename1?></a></label></td>
          </tr>
          <tr>
          	<td><span><label>Prepared By</label></span></td>
          	<td><label>:<?=$slit_coil_details->prepared_by?></label></td>
          </tr>
          <tr>
          	<td><span><label>Verified By</label></span></td>
          	<td><label>:<?=$slit_coil_details->verified_by?></label></td>
          </tr>
          <tr>
          	<td><span><label>Final Judgement</label></span></td>
          	<td><label>:<?=$slit_coil_details->final_judgement?></label></td>
          </tr>
        </table>
    </fieldset>
    <fieldset>
      <legend>Report Details for each slit</legend>
    </fieldset>
    <div class="container">
        <table border="1" width="100%">
          <tr>
            <th>Slit Number</th>
            <th colspan="2">Actual thickness</th>
            <th colspan="2">Actual Width</th>
            <th colspan="2">Burr</th>
            <th colspan="3">Surface/strip condition</th>
            <th>Camber</th>
            <th>Final judgement</th>
          </tr>
          <tr>
            <th></th>
            <th>Min</th>
            <th>Max</th>

            <th>Min</th>
            <th>Max</th>

            <th>Min</th>
            <th>Max</th>

            <th>Condition</th>
            <th>Desc</th>
            <th>Image</th>

            <th></th>
          </tr>

          <?php
            foreach($slit_details as $index => $value) { ?>
                <tr style="text-align:center;">
                  <td><?=$value->slit_number?></td>

                  <td><?=$value->actual_thickness_min?></td>
                  <td><?=$value->actual_thickness_max?></td>

                  <td><?=$value->actual_width_min?></td>
                  <td><?=$value->actual_width_max?></td>

                  <td><?=$value->burr_min?></td>
                  <td><?=$value->burr_max?></td>

                  <td><?=($value->strip_1) ? $surfaceDetails[$value->strip_1] : '-'?></td>
                  <td><?=($value->surface_desc1) ? $value->surface_desc1 : '-'?></td>
                  <td><a href="<?=base_url().'uploads/'.$value->surface_fileName1?>"><?=$slit_coil_details->surface_filename1?></a></td>

                  <td><?=($value->camber == 1 ) ? 'Yes' : 'No'?></td>
                  <td><?=($value->final_judgement) ? $value->final_judgement : '-'?></td>

                </tr>
              <?php } ?>
              </table>
        </div>
    </div>
    <div align="right" style="margin-top: 30px;">
    	<input class="btn btn-success" style="cursor: pointer;" id="saveSlitReport" type="button" value="Done"/>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#saveSlitReport').click(function() {
    var url = '<?php echo fuel_url('quality_reports');?>';
    window.location.replace(url);
  });
});
</script>
