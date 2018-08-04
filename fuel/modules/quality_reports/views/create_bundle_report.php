<style>
#cuttingReport {
  text-align : center;
  width: 100%;
}
#cuttingReport td{ 
  padding: 0px !important;
  padding-top : 8px !important;
}
</style>
<?php  include_once(QUALITY_REPORTS_PATH.'views/layout.php'); ?>
<div id="innerpanel">
  <fieldset>
    <legend>Coil Details:</legend>
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
        <tr>
          <td><label><?=lang('coil_thickness')?></label></td>
          <td><label>:<?=$coil_details->fThickness?></label></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend> Report details for coil</legend>
      <table id="cuttingReport" border="1" align="center" cellspacing="10" cellpadding="10">
      <form enctype="multipart/form-data" name="slittingReport" id="slittingReportForm" class="form-group">
            <input type="hidden" name="coilnumber" value="<?=$coil_details->vIRnumber?>">
            <tr> <td></td><?php foreach($bundle_details as $bundle_detail) { ?><td>Bundle Number <?=$bundle_detail->nSno?></td> <?php } ?> </tr>
            <tr> <td>Length Observed</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input style="width:100px;" type="text" name="length[]"></td> <?php } ?> </tr>
            <tr> <td>Weight Observed</td> <?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="weight[]"></td> <?php } ?> </tr>
            <tr> <td>Thickness Observed</td> <?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="thickness[]"></td> <?php } ?> </tr>

            <tr> <td>Diagonal in mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="diagonal[]"></td> <?php } ?> </tr>
            <tr> <td>Last 5 sheets</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="last5[]"></td> <?php } ?> </tr>
            <tr> <td>First 5 sheets</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="first5[]"></td> <?php } ?> </tr>

            <tr> <td>Wave/Hawa</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="wave[]"><option value="No">No</option><option value="Yes">Yes</option></select></td><?php } ?> </tr>
            <tr> <td>In mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="waveMM[]"></td> <?php } ?> </tr>
            <tr> <td>No of sheets ? </td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="waveNo[]"></td> <?php } ?> </tr>
            <tr> <td>Right/Left/Both</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="side[]"><option value="N/A">N/A</option><option value="Right">Right</option><option value="Left">Left</option><option value="Both">Both</option></select></td> <?php } ?> </tr>

            <tr> <td>Centre Buckle/Doom</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="center[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
            <tr> <td>In mm</td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="centerMM[]"></td> <?php } ?> </tr>
            <tr> <td>No of sheets ? </td><?php foreach($bundle_details as $bundle_detail) { ?><td><input type="text" style="width:100px;" name="centerNo[]"></td> <?php } ?> </tr>
            
            <tr> <td>Dinch Marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="dinchMarks[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
            <tr> <td>Black spots</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="black_spots[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
            <tr> <td>Scratches</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="scratches[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
            <tr> <td>Wire rope marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="wire_rope_marks[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
            <tr> <td>Marks</td><?php foreach($bundle_details as $bundle_detail) { ?><td><select style="width:114px;" name="other_marks[]"><option value="No">No</option><option value="Yes">Yes</option></select></td> <?php } ?> </tr>
          </table>
          </br>
          </br>
          Remarks : <textarea name="finalJudgement"></textarea><br>
          Operator Name : <input type="text" name="preparedBy"><br>
          Checked By : <input type="text" name="verifiedBy"><br>
          Reported to : <input type="text" name="reportedTo"><br>
          <!-- Quality report sent to : <input type="text"> -->
      </form>
    </fieldset>
    <div align="right">
  	    <input class="btn btn-success" style="cursor: pointer;" id="saveSlitReport" type="button" value="Save Report"/>
  	    <input class="btn btn-inverse" style="cursor: pointer;" id="cancelReport" type="button" value="Cancel" />
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.back').click(function() {
    var url = '<?php echo fuel_url('workin_progress');?>';
    window.location.replace(url);
  })
  $('#saveSlitReport').click(function() {
      var myForm = document.getElementById('slittingReportForm');
      var formData = new FormData(myForm);
      var form = $('#slittingReportForm').serialize();
      $.ajax({
        url : "<?php echo fuel_url('quality_reports/insert_cutting_report');?>/",
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data){
          if(data == 'success') {
            alert('Report saved successfully');
          } else {
            alert('Error while saving report');
          }
        }
      });
  });

  $('.copydetails').click(function() {
    $('.actualThicknessMin').val($('.actualThicknessMin')[0].value);
    $('.actualThicknessMax').val($('.actualThicknessMax')[0].value);

    $('.actualWidthMin').val($('.actualWidthMin')[0].value);
    $('.actualWidthMax').val($('.actualWidthMax')[0].value);

    $('.burrMin').val($('.burrMin')[0].value);
    $('.burrMax').val($('.burrMax')[0].value);

    $('.slitSurface0').val($('.slitSurface0')[0].value);
    $('.slitSurface1').val($('.slitSurface1')[0].value);
    $('.slitSurface2').val($('.slitSurface2')[0].value);
    $('.slitSurface3').val($('.slitSurface3')[0].value);
    $('.slitSurface4').val($('.slitSurface4')[0].value);

    $('.slitSurfaceDescription0').val($('.slitSurfaceDescription0')[0].value);
    $('.slitSurfaceDescription1').val($('.slitSurfaceDescription1')[0].value);
    $('.slitSurfaceDescription2').val($('.slitSurfaceDescription2')[0].value);
    $('.slitSurfaceDescription3').val($('.slitSurfaceDescription3')[0].value);
    $('.slitSurfaceDescription4').val($('.slitSurfaceDescription4')[0].value);

    $('.slitFinalJudgement').val($('.slitFinalJudgement')[0].value);

    $('.camber[value="'+$('.camber:checked')[0].value+'"]').prop('checked', true);
  });
});
</script>
