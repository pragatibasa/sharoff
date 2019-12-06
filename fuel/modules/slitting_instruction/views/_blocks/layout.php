<style>.btn-info {
    background-color: #49AFCD;
    background-image: linear-gradient(to bottom, #5BC0DE, #2F96B4);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    color: #FFFFFF;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}</style>
<div id="innerpanel">
&nbsp;
&nbsp;
<fieldset>
<legend>Coil Details</legend>
	<div>
		<table cellpadding="0" cellspacing="10" border="0">
			<tr>
				<td>
					<label><?=lang('party_id')?></label>
				</td>
				<td>
					<input id="pid" name="vIRnumber" type="text" DISABLED/>
				</td>
				<td>
					<label><?=lang('party_name')?></label>
				</td>
				<td>
					<input id="pname" type="text" value="<?php echo $partyname; ?>" DISABLED />
				</td>
			</tr>
			<tr>
				<td>
					<label><?=lang('Material_description')?></label>
				</td>
				<td>
					<input id="mat_desc" name="vDescription" type="text" DISABLED/>
				</td>
				<td>
					<label><?=lang('width_txt')?></label>
				</td>
				<td>
					<input id="wid" name="fWidth" type="text" DISABLED/> (in mm)
				</td>
			</tr>
			<tr>
				<td>
					<label><?=lang('thickness_txt')?></label>
				</td>
				<td>
					<input id="thic" name="fThickness" type="text" DISABLED/> (in mm)
				</td>
				<td>
					<label><?=lang('weight_txt')?></label>
				</td>
				<td>
					<input id="wei" name="fQuantity" type="text" DISABLED/> (in tons)
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<fieldset>
<legend>Slitting Instruction</legend>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="40%" align="left" valign="top">
<form id="cisave" method="post" action="" class="__fuel_edit__" style="font-size:14px;width:500px;">
		<div class="pad-10 hide">
			<div id="date_text_label"> Date </div>
			<input type="text" id="date1" value="<?php echo date("Y-m-d"); ?>" DISABLED/>
		</div>
		<div class="pad-10">
			<div id="bundle_weight_text_label"> <?=lang('available_coil_length')?>  </div>
			<input name="remaining_weight" id="remaining_length" type="text" DISABLED/> (in mm)
		</div>
		<div class="pad-10">
			<div id="bundle_weight_text_label"> Required Length  </div>
			<input type="radio" name="balance_length" id="balance_length" onclick="balance();"/>&nbsp;Balance</br></br>
			<input id="length_v" type="text" name="length"/> (in mm)
			<input id="slitNumber" type="hidden"/>
		</div>
		<div class="pad-10">
			<div id="bundle_width_text_label" style="height:20px;">
					<span style="float:left;">Width</span>
					<span style="float:left;margin-left:115px;">No.</span>
					<span style="float:right;padding-right:208px;">Weight</span>
			</div>
			<div>
				<input id="width_v" class="width" style="width:130px;" type="text" name="width"/>
				<input class="count" style="width:60px;" type="text" name="count" value="1"/>
				<input class="weight" type="text" name="weight" disabled style="width:130px;"/>
				<span class="measure"></span>
				<span class="__fuel_edit_marker_new__" title="Click to add new width" style="position: absolute; padding: 8px; margin-left: 5px; margin-top: 7px;cursor: pointer;"></span>
				<input id="txtslitingnumber" type="hidden"   />
			</div>
		</div>
		<div class="pad-10">
			<div id="bundle_weight_text_label"> Total Width  </div>
			<input id="weight_v" type="text" name="weight" disabled/>
			<input id="txtslitingnumber" type="hidden"   />
		</div>
		<div class="pad-10">
		<!--	<input id="newsize" type="button" value="Add New Size" onClick="functionsave();"/> &nbsp; &nbsp; &nbsp;
			<input id="edit" type="button" value="UPDATE/EDIT" onClick="functionedit();" hidden/>&nbsp; &nbsp; &nbsp;
			<input id="reset" type="reset" value="Reset" onclick="functionreset();"/>-->
			<input class="btn btn-success" type="button" value="Add New Size" id="newsize" onClick="functionsave();"/> &nbsp; &nbsp; &nbsp;
			<input class="btn btn-danger" id="reset" type="reset" value="Reset" onClick="functionreset();" /> &nbsp; &nbsp; &nbsp;
			<input class=" btn-info"  type="button" value="UPDATE/EDIT"  id="edit" onClick="functionedit();" hidden/> &nbsp; &nbsp; &nbsp;
		</div>
</form>
</td>
<td width="60%" align="left" valign="top">
    <div id="contentsholder" class="flexcroll" style="width:100%; height:350px; overflow-x:hidden; overflow-y:auto;">
		<div id="content" style="width:100%; min-height:350px; overflow:hidden;">
			<div id="DynamicGrid_2">
				No Record!
			</div>
		</div>
	</div>
</td>
</tr>
<td>
</td>
<td align="right">
	<label>Total Weight</label>
		<input id="txttotalwidth" type="text" DISABLED/> (in tons)
		<input id="txtHiddentotalwidth" type="hidden" />
		&nbsp; &nbsp; &nbsp;
		<input type="button" onclick="cancelcoil();" value="Cancel" id="cancelcoil" class="btn btn-danger">
		<input class="btn btn-success"  id="saveci" type="button" value="Save" onClick="savechange();"/>
		<input id="finishci" type="button" value="Finsh" onClick="finishinstructionbutton();" hidden/>&nbsp; &nbsp; &nbsp;
</td>
</tr>
</table>
</fieldset>
</div>

<script type="text/javascript" language="javascript">
function functionreset(){
	$("#newsize").show();
	$("#edit").hide();
}

function cancelcoil(){

	var pid   =	$('#pid').val();
	var dataString = 'pid='+pid;
    $.ajax({
                 type: 'POST',
                url: "<?php echo fuel_url('slitting_instruction/cancelcoils');?>",
				data: dataString,
                success: function(){
				alert("Changed Succesfully");
				refresh_folderlist();
			}
    });
}

$(document).on( 'click', '.__fuel_edit_marker_new__',function() {
	$(this).prev('span').remove();
	var kgs = '';
	if($(this).prev('.weight').val() !== '')
		kgs = 'Tons';
	$(this).after('<span class="measure">'+kgs+'</span><span title="Delete" class="ico_delete" style="margin-top: 10px; height: 8px; margin-left: 5px; padding: 5px; position: absolute; width: 7px;cursor:pointer;"></span><input type="text" class="width" style="width:130px; margin-right: 4px;" name="width" id="width_v"><input class="count" style="width:60px;" type="text" name="count" value="1"/><input class="weight" type="text" name="weight" disabled style="width:130px;margin-left:4px;"/> <span class="measure"></span><span title="Click to add new width" style="position: absolute; padding: 8px; margin-left: 5px; margin-top: 7px;cursor: pointer;" class="__fuel_edit_marker_new__"></span>');
	$(this).next('.ico_delete').css('margin-left','4px');
	$(this).remove();
});

$(document).on( 'click', '.ico_delete', function() {
	$(this).prev('span').remove();
	$(this).prev('.weight').remove();
	$(this).prev('.count').remove();

	if( $('#length_v').val() !== '' )
		$(this).prev('.width').val(0).trigger('keyup').remove();
	else
		$(this).prev('.width').remove();
	$(this).remove();
	calculateTotalWidth();
});

$('#length_v').on('keydown', function(e) {
    var key   = e.keyCode ? e.keyCode : e.which;
    if (!( [8, 9, 13, 27, 46].indexOf(key) !== -1 ||
         (key == 65 && ( e.ctrlKey || e.metaKey  ) ) ||
         (key >= 35 && key <= 40) ||
         (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
         (key >= 96 && key <= 105)
       )) e.preventDefault();
});

$(document).on( 'blur', '#length_v',function(event) {
	if( $(this).val() == '' ) {
		return false;
	} else if( parseFloat($(this).val()) > $('#remaining_length').val() ) {
		alert('Required length cannot be greater than available length');
		$(this).val('').focus();
		return false;
	}
});

$(document).on( 'blur', '.width',function(event) {
	var currentWidth	= parseFloat($(this).val());
	var currentCount	= parseFloat($(this).next('.count').val());
	var weight = calculateWeights(currentWidth,currentCount);
	calculateTotalWidth();
	if( weight == false ) {
		if( isNaN(currentWidth) )
			$(this).val('');
		$(this).next().next('.weight').val('0');
		return false;
	} else {
		$(this).next().next('.weight').val(weight).next('.measure').text('Tons');
	}
});

$(document).on( 'blur', '.count',function(event) {
	var currentCount	= parseFloat($(this).val());
	var currentWidth	= parseFloat($(this).prev('.width').val());
	var weight = calculateWeights(currentWidth,currentCount);
	calculateTotalWidth();
	if( weight == false ) {
		if( $(this).val('') !== 1 )
			$(this).val('');
		$(this).next('.weight').val('0');
		return false;
	} else {
		$(this).next('.weight').val(weight).next('.measure').text(' Tons');
	}
});

function calculateWeights( currentWidth, currentCount ) {
	var pid   			= $('#pid').val();
	var totalWidth 		= 0;
	var thickness 		= $('#thic').val();
	var length 			= $('#length_v').val();

	if( isNaN(currentWidth) || isNaN(currentCount) || length == '' ) {
		alert("All fields are mandatory");
		return false;
	}
	totalWidth = calculateTotalWidth();
	if(totalWidth > parseFloat($('#wid').val())) {
		alert('Sum of slits width is greater than width of coil.');
		return false;
	}

	var totalWeight	= parseFloat(0.00000785*(currentWidth*currentCount)*thickness*parseFloat(length)).toFixed(3);
	return totalWeight;
}

function calculateTotalWidth() {
	var totalWidth = 0;
	$('.width').each(function() {
		var width = $(this).val();
		var count = $(this).next('.count').val();
		if( width !== '' && count !== '' ) {
			totalWidth = totalWidth+(width*count);
		}
	});
	$('#weight_v').val(totalWidth);
	return totalWidth;
}

function balance() {
	var pid = $('#pid').val();
	var remaining_length = $('#remaining_length').val();
	var dataString = 'remaining_length='+remaining_length+'&pid='+pid;
	$('#length_v').val(remaining_length);
	return false;
	$.ajax({
        type: 'POST',
        url: "<?php echo fuel_url('slitting_instruction/getBalanceLength');?>",
		data: dataString,
		success: function(msg){
			$('#length_v').val(remaining_length);
		}
    });
}

function loadfolderlist(account, accname) {
	$('#DynamicGrid_2').hide();
	var loading = '<div id="DynamicGridLoading_2"> '+
            	   ' <img src="<?=img_path() ?>loading.gif" /><span> Loading slit List... </span> '+
    	    	   ' </div>';
    $("#content").empty();
	$('#content').html(loading);
    $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('slitting_instruction/listslittingdetails');?>",
        data: "partyid=" + account,
        dataType: "json"
        }).done(function( msg ) {
			if(msg.length == 0) {
			$('#DynamicGrid_2').hide();
			$('#DynamicGridLoading_2').hide();
			var loading1 = '<div id="error_msg"> '+
                           'No Result!'+
						   '</div>';
			$('#content').html(loading1);
			} else{
            var partydata = [];
            var totalWeight = 0;
            var totalwidth = 0;
            for (var i = 0; i < msg.length; i++) {
            var item = msg[i];
            var thisdata = {};
			thisdata["Sno"] = item.Sno;
            thisdata["Slitting date"] = item.Slittingdate;
            thisdata["length"] = item.length;
            thisdata["width"] = item.width;
            thisdata["weight"] = parseFloat(item.weight).toFixed(3);

			totalWeight += Number(item.weight);
			totalwidth += Number(item.width);

			var edit = '<a class="ico_coil_edit" title="Edit" href="#" onClick=radioload('+item.Sno+','+item.length+','+item.width+','+item.weight+')><img src="<?php echo img_path('iconset/ico_edit.png'); ?>" /></a>';
			var dl = '<a class="ico_coil_delete" title="Delete" href="#" onClick=deleteItem('+item.Sno+')><img src="<?php echo img_path('iconset/ico_cancel.png'); ?>" /></a>';
            thisdata["action"] = edit+' '+dl;
			partydata.push(thisdata);
			$('#txttotalwidth').val(parseFloat(totalWeight).toFixed(3));
			$('#txtHiddentotalwidth').val(totalwidth);
			}
			if (partydata.length) {
            // If there are files
				$('#DynamicGrid_2').hide();
				$('#DynamicGridLoading_2').hide();
				$('#content').html(CreateTableViewX(partydata, "lightPro", true));
				var lcScrollbar = $('#contentsholder');
				fleXenv.updateScrollBars(lcScrollbar);
				$(".ico_coil_delete").click(function (e) {
                // When a delete icon is clicked, stop the href action
                //  and do an ajax call to delete it instead
                e.preventDefault();
                var data = {account_name: account};
                var href = $(this).attr('href');
                $.post(href, data, function (d) {
                loadfolderlist(account, accname);
                });
                });
			} else {
				$('#DynamicGrid_2').hide();
				$('#DynamicGridLoading_2').hide();
				var loading1 = '<div id="error_msg"> '+
							   'No Result!'+
							   '</div>';
				$('#content').html(loading1);
				var lfScrollbar = $('#contentsholder');
				fleXenv.updateScrollBars(lfScrollbar);
                }
			}
    });
}

function totalwidth_check() {
	var partyid = $('#pid').val();
	var dataString = '&partyid='+partyid;
	$.ajax({
	   type: "POST",
	   url : "<?php echo fuel_url('slitting_instruction/totalwidth');?>/",
		data: dataString,
		datatype : "json",
		success: function(msg){
			var msg3=eval(msg);
			$.each(msg3, function(i, j){
				var weight = j.width;
				var totalLength = j.totalLength;
				var finalTotalLength = ($('#remaining_length').val() - totalLength);
				$('#remaining_length').val(finalTotalLength);
				$('#txtHiddentotalwidth').val(j.totalWidth);
				document.getElementById("txttotalwidth").value = weight;
			});
	   }
	});
}

function deleteItem(sn){
	document.getElementById('txtslitingnumber').value = sn;
	var slitingnumber = $('#txtslitingnumber').val();
	var pid = $('#pid').val();
    var checkstr =  confirm('Are you sure you want to delete this?');

	if(checkstr == true){
		var dataString = {Slitingnumber : slitingnumber,Pid:pid};
      	$.ajax({
	    type: "POST",
		url	: "<?php echo fuel_url('slitting_instruction/delete_slit');?>",
		data : dataString,
		datatype: json,
			success: function(msg){
				refresh_folderlist();

				var pid = $('#pid').val();
				var dataString = '&pid='+pid;
				totalwidth_check();
				$.ajax({
				    type: 'POST',
        			url: "<?php echo fuel_url('slitting_instruction/getBalanceLength');?>",
					data: dataString,
					success: function(msg){
						var msg3=JSON.parse(msg);
						$('#remaining_length').val(msg3.remaining_weight);
					}
    			});
			}
		});
    } else {
	    return false;
    }
  }

function savechange(id) {
    var pid = $('#pid').val();
	var dataString = 'pid='+pid;

	$.ajax({
		type: 'POST',
		url: "<?php echo fuel_url('slitting_instruction/save_button');?>",
		data: dataString,
		success: function() {
			alert("Saved Succesfully");
			refresh_folderlist();
			totalwidth_check();
			$.ajax({
				type: 'POST',
				url: "<?php echo fuel_url('slitting_instruction/getBalanceLength');?>",
				data: dataString,
				success: function(msg){
					var msg3=JSON.parse(msg);
					$('#remaining_length').val(msg3.remaining_weight);
				}
			});
		}
	});
}

function functionedit(){
	var bundlenumber = $('#slitNumber').val();
	var width_v = $('#width_v').val();
	var pid = $('#pid').val();
	var dataString = 'bundlenumber='+bundlenumber+'&width_v='+width_v+'&pid='+pid;
	$.ajax({
		type: "POST",
		url : "<?php echo fuel_url('slitting_instruction/editbundle');?>/",
		data: dataString,
		success: function(msg){
			alert("Updated Successfully");
			$('#length_v,#weight_v').val('');
			$('#length_v').removeAttr('disabled');
			$('#balance_length').removeAttr('checked');
			$('.width').remove();
			$("#newsize").show();
			$("#edit").hide();
			$('#bundle_width_text_label').next('div').empty();
			$('#bundle_width_text_label').next('div').append('<input type="text" class="width" style="width:130px; margin-right: 4px;" name="width" id="width_v"><input class="count" style="width:60px;" type="text" name="count" value="1"/><input class="weight" type="text" name="weight" disabled style="width:130px;margin-left: 4px;"/> <span class="measure"></span><span title="Click to add new width" style="position: absolute; padding: 8px; margin-left: 5px; margin-top: 7px;cursor: pointer;" class="__fuel_edit_marker_new__"></span>');
			refresh_folderlist();
			totalwidth_check();
			$.ajax({
			    type: 'POST',
    			url: "<?php echo fuel_url('slitting_instruction/getBalanceLength');?>",
				data: dataString,
				success: function(msg){
					var msg3=JSON.parse(msg);
					$('#remaining_length').val(msg3.remaining_weight);
				}
    		});
		}
	});
}

function radioload(nSno,length,width,weight) {
	$("#edit").show();
	$("#newsize").hide();
	$('#length_v').val(length).attr('disabled',true);
	$('#slitNumber').val(nSno);
	$('.width,.ico_delete,.__fuel_edit_marker_new__').remove();
	$('#bundle_width_text_label').next('div').empty();
	$('#bundle_width_text_label').next('div').append('<input type="text" value="'+width+'" class="width" style="width:130px; margin-right: 4px;" name="width" id="width_v"><input class="count" style="width:60px;" type="text" name="count" value="1" disabled/><input class="weight" type="text" name="weight" value="'+weight+'" disabled style="width:130px;margin-left:4px"/> <span class="measure">Kgs</span>');
	$('#weight_v').val(weight);
}
</script>

<script>
var json = <?php echo($adata); ?>;
for(key in json){
	if(json.hasOwnProperty(key))
	$('input[name='+key+']').val(json[key]);
}

function functionsave() {
	var date1 = $('#date1').val();
	var length = $('#length_v').val();
	var pid = $('#pid').val();
	var thickness = $('#thic').val();
	var allWidths = [];
	var widthsSum = 0;
	$('.width').each(function() {
		if($(this).val() !== '') {
			var count = parseInt($(this).next('.count').val());
			for(i = 1; i <= count; i++) {
				widthsSum += parseFloat($(this).val());
				allWidths.push(parseFloat($(this).val()));
			}
		}
	});

	 if( length =='' ) {
	  	alert('Please enter all the details.');
	  	return false;
	 } else if( widthsSum > parseFloat($('#wid').val()) ) {
	 	alert('Sorry the Total width of bundle is more then width of coil please edit the width or delete to progress!!');
	 	return false;
	 } else{
		var dataString = 'date1='+date1+'&widths='+allWidths+'&pid='+pid+'&length='+length+'&thickness='+thickness;
		$.ajax({
			type: "POST",
			url : "<?php echo fuel_url('slitting_instruction/savebundleslit');?>/",
			data: dataString,
			success: function(msg){
				$('#balance_length').removeAttr('checked');
				$('#length_v,.width,.weight,#weight_v').val('');
				$('#bundle_width_text_label').next('div').empty();
				$('#bundle_width_text_label').next('div').append('<input type="text" class="width" style="width:130px; margin-right: 4px;" name="width" id="width_v"><input class="count" style="width:60px;" type="text" name="count" value="1"/><input class="weight" type="text" name="weight" disabled style="width:130px;margin-left:4px;"/> <span class="measure"></span><span title="Click to add new width" style="position: absolute; padding: 8px; margin-left: 5px; margin-top: 7px;cursor: pointer;" class="__fuel_edit_marker_new__"></span>');
				refresh_folderlist();
				var pid = $('#pid').val();
				var dataString = '&pid='+pid;

				$.ajax({
				    type: 'POST',
        			url: "<?php echo fuel_url('slitting_instruction/getBalanceLength');?>",
					data: dataString,
					success: function(msg){
						var msg3=JSON.parse(msg);
						$('#remaining_length').val(msg3.remaining_weight);
					}
    			});
			}
		});
	}
}

function addDate(){
	date = new Date();
	var month = date.getMonth()+1;
	var day = date.getDate();
	var year = date.getFullYear();
	if (document.getElementById('date1').value == ''){
	document.getElementById('date1').value = day + '-' + '0' +month + '-' + '0'+ year;
	}
}

function timedRefresh(timeoutPeriod){
	setTimeout("location.reload(true);",timeoutPeriod);
}

function finishinstructionbutton(id){
	var pid  =	$('#pid').val();
	var party = $('#pname').val();
	var dataString = 'partyid='+pid+'&partyname='+party+'&task=sit';
	$.ajax({
	type: "POST",
	url	: "<?php echo site_url('finish_task/finish_slit');?>/",
	data: dataString,
	success: function(){
		setTimeout("location.href='<?= site_url('fuel/finish_task'); ?>/?"+ dataString+"'", 3000);
		}
	});
}

function deleterecord(){
	var deleteid = $('#deletevalue').val();
	var dataString = 'number='+deleteid;
		$.ajax({
			type: "POST",
			url	: "<?php echo fuel_url('slitting_instruction/deleterow');?>/",
			data: dataString,
			success: function(msg){
			$("#deletemsg").html(msg);
			$('#deletevalue').val('');
			}
		});
}
</script>
