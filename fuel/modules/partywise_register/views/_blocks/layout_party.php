<?php include_once(PARTYWISE_REGISTER_PATH.'views/_blocks/toolbar_party.php');?>
<script language="javascript" type="text/javascript">
  $(window).load(function() {
	$("tr#childlist").hide();
	var lfScrollbar = $('#contentsfolder');	 
	fleXenv.updateScrollBars(lfScrollbar); 
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".tabLinkpr").each(function(){
      $(this).click(function(){
        tabeId = $(this).attr('id');
        $(".tabLinkpr").removeClass("activeLinkpr");
        $(this).addClass("activeLinkpr");
        $(".tabcontentpr").addClass("hidepr");
        $("#"+tabeId+"-1").removeClass("hidepr");
        return false;	  
      });
    });  
  });
</script><br /><br />
<div id="main_content" style="overflow:hidden;"> 
<div class="tab-boxpr"> 
	<div style="width:640px;">
    <a href="javascript:"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Main CoilDetails</h1></div></a>
    <a href="javascript:"><div class="tabLinkpr " id="contpr-2" style="float:left;"><h1>ProcessedDetail</h1></div></a>
	</div>
</div>
 
 
<!-- MAIN PARTWISE @START -->
<div class="tabcontentpr" id="contpr-1-1">
<div id="party_list">
<div id="contentsfolder" style="width:100%; height:550px; overflow-x:hidden; overflow-y:auto;">
<div id="partycontent" style="width:100%; min-height:550px; overflow:hidden;"> 


<script src="<?=$this->asset->js_path('jquery.tablesorter.pager', 'partywise_register')?>"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter', 'partywise_register')?>	"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter.widgets', 'partywise_register')?>	"></script>
		
<div id="DynamicGridp_2" >
</div>
	
</div>
</div>
</div>
</div>
<!-- @END -->

<!-- SUB PARTWISE @START -->
<div class="tabcontentpr hidepr" id="contpr-2-1" style="height:541px;"> 
<div id="pr-content" style="width:100%; height:500px; overflow-x:hidden; overflow-y:hidden;">
<h2 class="innercellpr" style="margin-bottom:0px !important;"><div class="pr-content-title">List for <span class="container_root" id="pr_container_name">/</span> coil number:</div></h2>
<div id="contentsholder" style="width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">
<div id="content" style="width:100%; min-height:500px; overflow-x:hidden; overflow-y:auto;">
	<div id="DynamicGrid_2">
        Select a Parent Coil
	</div>
</div>
</div>
</div>
</div>
<!-- @END -->
</div>

<?php //echo $totalweight; ?>
<input id="partnamecheck" type="hidden" value="" name="partnamecheck" />
	
<div align="right">
<?php //echo (round($totalweight,3)); ?>
<label>Total Weight</label>
		<input id="totalweight_calcualation" type="text" DISABLED/>(in Tons)  
		&nbsp; &nbsp; &nbsp;
</div>






<script language="javascript" type="text/javascript">
function totalweight_check(){
	var party_account_name = $('#party_account_name').val();
	var dataString = '&party_account_name='+party_account_name;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('partywise_register/totalweight_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(i, j){
			 var weight = j.weight;
			document.getElementById("totalweight_calcualation").value = weight.toFixed(3);});
	   }  
	}); 
}

</script>

<script type="text/javascript">


	$("#party_account_name").change(function(data) {
		 var account_id = $("#party_account_name").val();
		var loading = '<div id="DynamicGridLoadingp_2"> '+
            	   ' <img src="<?=img_path() ?>loading.gif" /><span> Loading Party List... </span> '+ 
    	    	   ' </div>';
	   $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('partywise_register/list_party');?>",
		data: "party_account_name=" + account_id,
        dataType: "json"
        }).done(function( msg ) {
	      //  obj = JSON.parse(msg);
			var mediaClass ='';
			mediaClass += '<table id="myTabels" class="tablesorter tablesorter-blue">';
			mediaClass +='<thead>';
			mediaClass +='<tr>';
			mediaClass += '  <th>Select</th>';
			mediaClass += '  <th>Coilnumber</th>';
			mediaClass += '  <th>Received Date</th>';
			mediaClass += '  <th>Description</th>';
			mediaClass += '  <th>Thickness</th>';
			mediaClass += '  <th>Width</th>';
			mediaClass += '  <th>Weight</th>';
			mediaClass += '  <th>Present Weight</th>';
			mediaClass += '  <th>Status</th>';
			mediaClass += '  <th>Process</th>';
			mediaClass +='</tr>';
			mediaClass +='</thead>';
			
			for (var i=0;i<msg.length;i++)
			{
				var item = msg[i];
				mediaClass += '<tr>';
				
		 	mediaClass += '<td>' + '<input type="radio" id="radio_'+item.coilnumber+'" name="list" value="'+item.coilnumber+'"   onClick=showchild("'+item.coilnumber+'") />' + '</td>';
				mediaClass += '<td>' + item.coilnumber + '</td>';
				mediaClass += '<td>' + item.receiveddate + '</td>';
				mediaClass += '<td>' + item.description + '</td>';
				mediaClass += '<td>' + item.thickness + '</td>';
				mediaClass += '<td>' + item.width + '</td>';
				mediaClass += '<td>' + parseFloat(item.weight).toFixed(3) + '</td>';
				mediaClass += '<td>' + parseFloat(item.pweight).toFixed(3) + '</td>';
				mediaClass += '<td>' + item.status + '</td>';
				mediaClass += '<td>' + item.process + '</td>';
					
				mediaClass += '</tr>';			
				
			}
			mediaClass += '</table>';
			
			$('#DynamicGridp_2').html(mediaClass);
			 $("#myTabels").tablesorter();
			totalweight_check();
				
		
		});

	
});




function showchild(parentid) {
	$('#pr_container_name').html(parentid);
	 $('#DynamicGrid_2').hide();
	 var loading = '<div id="DynamicGridLoading_2"> '+
            	   ' <img src="<?=img_path() ?>loading.gif" /><span> Loading child coils... </span> '+ 
    	    	   ' </div>';
	 $('#content').html(loading); 
		$.ajax({
				type: "POST",
				url: "<?php echo fuel_url('partywise_register/listChilds');?>",
                // Parent ID
				data: "partyid=" + parentid,
                dataType: "json"
				}).done(function(msg) {
				//alert(msg);
					if(msg.length == 0) {
						 $('#DynamicGrid_2').hide();
						 $('#DynamicGridLoading_2').hide();
						 var loading1 = '<div id="error_msg"> '+
                                        'No Result!'+ 
									    '</div>';
						 $('#content').html(loading1);  
					} else{
						var data = [];
                        for (var i = 0; i < msg.length; i++) {
                            var item = msg[i];
                            var thisdata = {};
							if(item.process=='Cutting'){
							thisdata["Processdate"] = item.processdate;
                            thisdata["Length in (mm)"] = item.length;
                            thisdata["BundleNumber"] = item.bundlenumber;
                            thisdata["No of sheets"] = item.bundles;
                            thisdata["Weight in (Tons)"] = parseFloat(item.weight).toFixed(3);
                            thisdata["Status"] = item.status;
							}
							else if(item.process=='Recoiling'){
							thisdata["RecoilNumber"] = item.recoilnumber;
							thisdata["Start-Date"] = item.startdate;
							thisdata["End-Date"] = item.enddate;
							thisdata["No Recoil"] = item.norecoil;
							thisdata["Status"] = item.status;
							}
							else if(item.process=='Slitting'){
							thisdata["SlittNumber"] = item.slittnumber;
							thisdata["Date"] = item.date;
							thisdata["Width in(mm)"] = item.width;
							thisdata["Status"] = item.status;
							}
							else if(item.process=='NULL'){
							'<div id="error_msg"> '+
										'No Result!'+ 
									    '</div>';
							}
                            data.push(thisdata);
                        }
						if (data.length) {
                            // If there are files
                            $('#content').html(CreateTableViewX(data, "lightPro", true)); 
							var lcScrollbar = $('#contentsholder');	 
							fleXenv.updateScrollBars(lcScrollbar); 
						} else {
							$('#DynamicGrid_2').hide();
							$('#DynamicGridLoading_2').hide();
							var loading1 = '<div id="error_msg"> '+
										'No Result!'+ 
									    '</div>';
							$('#content').html(loading1); 
							var lcScrollbar = $('#contentsholder');	 
							fleXenv.updateScrollBars(lcScrollbar);  
                        }
					}
				});
}
</script>
<script type="text/javascript">
function cuttinginstruction(id)
{
	var coilnumber = $('#vno'+id).val();
	document.getElementById('partnamecheck').value = coilnumber;
}
</script>   
