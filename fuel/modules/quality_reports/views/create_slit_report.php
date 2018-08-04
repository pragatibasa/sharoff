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
      <form enctype="multipart/form-data" name="slittingReport" id="slittingReportForm">
        <input type="hidden" name="coilnumber" value="<?=$coil_details->vIRnumber?>">
        <table cellpadding="1" cellspacing="10" border="0">
          <tr>
          	<td><span><label>Coil Grade</label></span></td>
          	<td><input name="coilGrade" type="text"/></td>
          </tr>
          <tr>
            <td><span><label>Coil Windin</label></span></td>
            <td><input name="coilWinding" type="text" /></td>
          </tr>
          <tr>
            <td><span><label>Job Cart/Order Number</label></span></td>
            <td><input name="orderNo" type="text" /></td>
          </tr>
          <tr>
          	<td><span><label>Surface / Strip Condition</label></span></td>
          	<td>
              <select name="coilSurface1">
                <option value="">Select Condition</option>
                <option value="black marks">Black Marks in mother coil</option>
                <option value="rust marks">Rust Marks in mother coil</option>
                <option value="water marks">Water Marks in mother coil</option>
                <option value="scratches">Scratches and impressions in mother coil</option>
                <option value="other">Other</option>
                <option value="N/A">N/A</option>
              </select>
          	<textarea placeholder="Description" name="coilSurfaceDescription1"></textarea>
            <input type="file" name="coilFile1" /></td>
          </tr>
          <tr>
          	<td></td>
          	<td>
              <select name="coilSurface2">
                <option value="">Select Condition</option>
                <option value="black marks">Black Marks in mother coil</option>
                <option value="rust marks">Rust Marks in mother coil</option>
                <option value="water marks">Water Marks in mother coil</option>
                <option value="scratches">Scratches and impressions in mother coil</option>
                <option value="other">Other</option>
                <option value="N/A">N/A</option>
              </select>
          	<textarea placeholder="Description" name="coilSurfaceDescription2"></textarea>
            <input type="file" name="coilFile2" />
          </td>
          </tr>
          <tr>
          	<td></td>
          	<td>
              <select name="coilSurface3">
                <option value="">Select Condition</option>
                <option value="black marks">Black Marks in mother coil</option>
                <option value="rust marks">Rust Marks in mother coil</option>
                <option value="water marks">Water Marks in mother coil</option>
                <option value="scratches">Scratches and impressions in mother coil</option>
                <option value="other">Other</option>
                <option value="N/A">N/A</option>
              </select>
          	<textarea placeholder="Description" name="coilSurfaceDescription3"></textarea>
            <input type="file" name="coilFile3" />
          </td>
          </tr>
          <tr>
          	<td></td>
          	<td>
              <select name="coilSurface4">
                <option value="">Select Condition</option>
                <option value="black marks">Black Marks in mother coil</option>
                <option value="rust marks">Rust Marks in mother coil</option>
                <option value="water marks">Water Marks in mother coil</option>
                <option value="scratches">Scratches and impressions in mother coil</option>
                <option value="other">Other</option>
                <option value="N/A">N/A</option>
              </select>
          	  <textarea placeholder="Description" name="coilSurfaceDescription4"></textarea>
              <input type="file" name="coilFile4" />
            </td>
          </tr>
          <tr>
          	<td></td>
          	<td>
              <select name="coilSurface5">
                <option value="">Select Condition</option>
                <option value="black marks">Black Marks in mother coil</option>
                <option value="rust marks">Rust Marks in mother coil</option>
                <option value="water marks">Water Marks in mother coil</option>
                <option value="scratches">Scratches and impressions in mother coil</option>
                <option value="other">Other</option>
                <option value="N/A">N/A</option>
              </select>
          	  <textarea placeholder="Description" name="coilSurfaceDescription5"></textarea>
              <input type="file" name="coilFile5" />
            </td>
          </tr>
          <tr>
          	<td><span><label>Prepared By</label></span></td>
          	<td><input name="preparedBy" type="text"/></td>
          </tr>
          <tr>
          	<td><span><label>Verified By</label></span></td>
          	<td><input name="verifiedBy" type="text"/></td>
          </tr>
          <tr>
          	<td><span><label>Final Judgement</label></span></td>
          	<td><textarea name="finalJudgement"></textarea></td>
          </tr>
        </table>
    </fieldset>
    <fieldset>
      <legend>Report Details for each slit <a href="javascript:void(0);" style="float:right;" class="copydetails">Copy Details</a></legend>
    </fieldset>
    <div class="container">
        <div class="accordion" id="searchAccordion">
          <?php
            foreach ($slit_details as $index => $value) { ?>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse"
                    data-parent="#searchAccordion" href="#collapse<?php echo $value->nSno;?>">Bundle number <?php echo $value->nSno;?></a>
                </div>
                <div id="collapse<?php echo $value->nSno;?>" class="accordion-body collapse in">
                  <div class="accordion-inner">
                    <table>
                      <tr>
                        <td><label>Slit Number</label></td>
                        <td><label> : <?=$value->nSno?></label></td>
                      </tr>
                      <tr>
                        <td><label>Slit Size</label></td>
                        <td><label> : <?=$value->nWidth?></label></td>
                      </tr>
                      <tr>
                        <td><label>Actual Thickness</label></td>
                        <td>
                          <label> : </label>
                          <input type="text" class="actualThicknessMin" name="actualThicknessMin[]" placeholder="Min" />
                          <input type="text" class="actualThicknessMax" name="actualThicknessMax[]" placeholder="Max" />
                        </td>
                      </tr>
                      <tr>
                        <td><label>Actual Width</label></td>
                        <td>
                          <label> : </label>
                          <input type="text" class="actualWidthMin" name="actualWidthMin[]" placeholder="Min" />
                          <input type="text" class="actualWidthMax" name="actualWidthMax[]" placeholder="Max" />
                        </td>
                      </tr>
                      <tr>
                        <td><label>Burr</label></td>
                        <td>
                          <label> : </label>
                          <input type="text" class="burrMin" name="burrMin[]" placeholder="Min" />
                          <input type="text" class="burrMax" name="burrMax[]" placeholder="Max" />
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <label>Surface / Strip Condition</label>
                        </td>
                        <td>
                          <label> : </label>
                          <select class="slitSurface slitSurface0" name="slitSurface<?php echo $index;?>[]">
                            <option value="">Select Condition</option>
                            <option value="black marks">Black Marks in mother coil</option>
                            <option value="rust marks">Rust Marks in mother coil</option>
                            <option value="water marks">Water Marks in mother coil</option>
                            <option value="scratches">Scratches and impressions in mother coil</option>
                            <option value="other">Others</option>
                            <option value="N/A">N/A</option>
                          </select>
                          <textarea placeholder="Description" class="slitSurfaceDescription slitSurfaceDescription0" name="slitSurfaceDescription<?php echo $index;?>[]"></textarea>
                          <input type="file" class="slitFile" name="slitFile<?php echo $index;?>[]"/>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        </td>
                        <td>
                          <label> : </label>
                          <select class="slitSurface slitSurface1" name="slitSurface<?php echo $index;?>[]">
                            <option value="">Select Condition</option>
                            <option value="black marks">Black Marks in mother coil</option>
                            <option value="rust marks">Rust Marks in mother coil</option>
                            <option value="water marks">Water Marks in mother coil</option>
                            <option value="scratches">Scratches and impressions in mother coil</option>
                            <option value="other">Others</option>
                            <option value="N/A">N/A</option>
                          </select>
                          <textarea placeholder="Description" class="slitSurfaceDescription slitSurfaceDescription1" name="slitSurfaceDescription<?php echo $index;?>[]"></textarea>
                          <input type="file" name="slitFile<?php echo $index;?>[]"/>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        </td>
                        <td>
                          <label> : </label>
                          <select class="slitSurface slitSurface2" name="slitSurface<?php echo $index;?>[]">
                            <option value="">Select Condition</option>
                            <option value="black marks">Black Marks in mother coil</option>
                            <option value="rust marks">Rust Marks in mother coil</option>
                            <option value="water marks">Water Marks in mother coil</option>
                            <option value="scratches">Scratches and impressions in mother coil</option>
                            <option value="other">Others</option>
                            <option value="N/A">N/A</option>
                          </select>
                          <textarea placeholder="Description" class="slitSurfaceDescription slitSurfaceDescription2" name="slitSurfaceDescription<?php echo $index;?>[]"></textarea>
                          <input type="file" name="slitFile<?php echo $index;?>[]"/>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        </td>
                        <td>
                          <label> : </label>
                          <select class="slitSurface slitSurface3" name="slitSurface<?php echo $index;?>[]">
                            <option value="">Select Condition</option>
                            <option value="black marks">Black Marks in mother coil</option>
                            <option value="rust marks">Rust Marks in mother coil</option>
                            <option value="water marks">Water Marks in mother coil</option>
                            <option value="scratches">Scratches and impressions in mother coil</option>
                            <option value="other">Others</option>
                            <option value="N/A">N/A</option>
                          </select>
                          <textarea placeholder="Description" class="slitSurfaceDescription slitSurfaceDescription3" name="slitSurfaceDescription<?php echo $index;?>[]"></textarea>
                          <input type="file" name="slitFile<?php echo $index;?>[]"/>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        </td>
                        <td>
                          <label> : </label>
                          <select class="slitSurface slitSurface4" name="slitSurface<?php echo $index;?>[]">
                            <option value="">Select Condition</option>
                            <option value="black marks">Black Marks in mother coil</option>
                            <option value="rust marks">Rust Marks in mother coil</option>
                            <option value="water marks">Water Marks in mother coil</option>
                            <option value="scratches">Scratches and impressions in mother coil</option>
                            <option value="other">Others</option>
                            <option value="N/A">N/A</option>
                          </select>
                          <textarea placeholder="Description" class="slitSurfaceDescription slitSurfaceDescription4" name="slitSurfaceDescription<?php echo $index;?>[]"></textarea>
                          <input type="file" name="slitFile<?php echo $index;?>[]"/>
                        </td>
                      </tr>
                      <tr>
                        <td><label>Camber</label></td>
                        <td>
                          <label> : </label>
                          <input type="radio" class="camber" name="camber[<?php echo $index;?>]" value="yes" />Yes
                          <input type="radio" class="camber" name="camber[<?php echo $index;?>]" checked="checked" value="no" />No
                        </td>
                      </tr>
                      <tr>
                        <td><label>Final Judgement</label></td>
                        <td>
                          <label> : </label>
                          <textarea class="slitFinalJudgement" name="slitFinalJudgement[]"></textarea>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <?php } ?>
        </div>
    </div>
  </form>
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
        url : "<?php echo fuel_url('quality_reports/insert_report');?>/",
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
