<?php  include_once(QUALITY_REPORTS_PATH.'views/layout.php'); ?>
<div id="innerpanel">
  <fieldset>
    <div style="margin-bottom: 20px;border-bottom: 1px solid #e5e5e5;">
      <legend style="border:0px; display:inline;">Coil Details:</legend>
      <div style="float:right;">
        <a href="<?php echo fuel_url('quality_reports/view_cutting_quality_pdf?report_id='.$report_id);?>" target="_blank"><button>View Pdf</button></a>
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
          <td><label>Operator Name</label></td>
          <td><label>:<?=$quality_details->prepared_by?></label></td>
        </tr>
        <tr>
          <td><label>Checked By</label></td>
          <td><label>:<?=$quality_details->verified_by?></label></td>
        </tr>
        <tr>
          <td><label>Reported To</label></td>
          <td><label>:<?=$quality_details->reported_to?></label></td>
        </tr>
        <tr>
          <td><label>Remarks</label></td>
          <td><label>:<?=$quality_details->final_judgement?></label></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
        <legend> Report details for coil</legend>
        <table id="cuttingReport" border="1" align="center" cellspacing="10" cellpadding="10">
        <tr> <td></td><?php foreach($bundle_details as $bundle_detail) { ?><td>Bundle Number <?=$bundle_detail->bundle_number?></td> <?php } ?> </tr>
            <tr> <td>Length Observed</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->length ?></td> <?php } ?> </tr>
            <tr> <td>Weight Observed</td> <?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->weight ?></td> <?php } ?> </tr>
            <tr> <td>Thickness Observed</td> <?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->thickness ?></td> <?php } ?> </tr>

            <tr> <td>Diagonal in mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->diagonal ?></td> <?php } ?> </tr>
            <tr> <td>Last 5 sheets</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->last_5_sheets ?></td> <?php } ?> </tr>
            <tr> <td>First 5 sheets</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->first_5_sheets ?></td> <?php } ?> </tr>

            <tr> <td>Wave/Hawa</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->wave ?></td><?php } ?> </tr>
            <tr> <td>In mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->waveMM ?></td> <?php } ?> </tr>
            <tr> <td>No of sheets ? </td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->waveNo ?></td> <?php } ?> </tr>
            <tr> <td>Right/Left/Both</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->side ?></td> <?php } ?> </tr>

            <tr> <td>Centre Buckle/Doom</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->center ?></td> <?php } ?> </tr>
            <tr> <td>In mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->centerMM ?></td> <?php } ?> </tr>
            <tr> <td>No of sheets ? </td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->centerNo ?></td> <?php } ?> </tr>
            
            <tr> <td>Dinch Marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->dinch_mark ?></td> <?php } ?> </tr>
            <tr> <td>Black spots</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->black_spots ?></td> <?php } ?> </tr>
            <tr> <td>Scratches</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->scratches ?></td> <?php } ?> </tr>
            <tr> <td>Wire rope marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->wire_rope_marks ?></td> <?php } ?> </tr>
            <tr> <td>Marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td style="text-align: center;"><?php echo $bundle_detail->other_marks ?></td> <?php } ?> </tr>
        </table>
    </fieldset>
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
