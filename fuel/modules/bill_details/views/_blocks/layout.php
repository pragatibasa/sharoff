<script language="javascript" type="text/javascript">
  $(window).load(function() {
	$("tr#childlist").hide();
	var lfScrollbar = $('#contentsfolder');	 
	fleXenv.updateScrollBars(lfScrollbar); 
  });
  
</script>

<div class="tab-boxpr"> 
	<div style="width:600px;float:left;">
    	<a href="javascript:;"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Bil details</h1></div></a> 
    </div>
    <span style="float:right;display:block;"> Search By : 
    	<form style="display:inline;" method="GET" action="<?php echo fuel_url('search');?>">
	    	<select name="searchType"> 
	    		<option value="bill">Bill no.</option>
	    		<option value="coil">Coil no.</option>
	    	</select>
	    	<input type="text" name="searchValue" style="width:250px;">
	    	<input style="margin-top:-10px;" type="submit" value="Search"> 
    	</form>
    </span>
</div>

<!-- MAIN Workinprogress @START -->
<div id="main_content" style="overflow:hidden;">  
	<div>
		<div class="tabcontentpr" id="contpr-1-1" >
			<div id="party_list" style="width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">
			 	<div id="contentsfolder" style="width:100%; height:550px; overflow-x:hidden; overflow-y:auto;">
					<div id="partycontent" style="width:100%; min-height:550px; overflow:hidden;"> 
						<script src="<?=$this->asset->js_path('jquery.tablesorter.pager', 'bill_details')?>"></script>
						<script src="<?=$this->asset->js_path('jquery.tablesorter', 'bill_details')?>	"></script>
						<script src="<?=$this->asset->js_path('jquery.tablesorter.widgets', 'bill_details')?>	"></script>
					  	<div id="DynamicGridp_2" >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	loadBillDetails();
});

function cancel_bill(billNo) {
	var href = '<?php echo site_url("bill_details/cancel_bill")."/?billno="; ?>';
    if (confirm("Are you sure you want to cancel the bill with bill number "+billNo) == true) {
        $.ajax({  
			type: "POST",  
			url : href+billNo,
			success: function(msg) {
				if(msg == 1) {
					alert('Bill number '+billNo+' has been cancelled.');
					loadBillDetails();
				}
			}
		});
    } else 
		return false;
}

function delete_bill(billNo) {
	var href = '<?php echo site_url("bill_details/delete_bill")."/?billno="; ?>';
    if (confirm("Are you sure you want to delete the bill with bill number "+billNo) == true) {
        $.ajax({  
			type: "POST",  
			url : href+billNo,
			success: function(msg) {
				if(msg == 1) {
					alert('Bill number '+billNo+' has been deleted.');
					loadBillDetails();
				}
			}
		});
    } else 
		return false;
}

function loadBillDetails() {
		var loading = '<div id="DynamicGridLoadingp_2"> '+
            	   '<img src="<?=img_path() ?>loading.gif" /><span> Loading Bill Details... </span> '+ 
    	    	   '</div>';
	$.ajax({
		type: "POST",
		url: "<?php echo fuel_url('bill_details/list_bill_details');?>",
		dataType: "json"
	}).done(function( msg ) {
			var mediaClass ='';
			mediaClass += '<table id="myTable" class="tablesorter tablesorter-blue"	>';
			if(msg.length == 0) {
				var mediaClass = '<tr><td>No Results!</td></tr>';
			} else {
				mediaClass +='<thead><tr><th>Bill No</th><th>Bill Date</th><th>Party Name</th><th>Bill Status</th><th>Actions</th></tr></thead>';
				for (var i=0;i<msg.length;i++)
				{
					var item = msg[i];
					mediaClass += '<tr>';
					
			 		mediaClass += '<td>'+ item.nBillNo +'</td>';
					mediaClass += '<td>' + item.dBillDate + '</td>';
					mediaClass += '<td>' + item.nPartyName + '</td>';
					mediaClass += '<td>' + item.BillStatus + '</td>';

					mediaClass += '<td>';
					mediaClass += '<a title="Duplicate Bill" target="_blank" href="'+item.duplicate_bill+'"><span class="badge badge-success" style="color: #FFFFFF;">Duplicate bill</span></a>&nbsp;';
					if( 'undefined' != typeof item.cancel_bill ) 
						mediaClass += '<a class="cancel_bill" title="Cancel Bill" onClick="cancel_bill('+item.nBillNo+');" href="javascript:void(0);"><span class="badge badge-warning" style="color: #FFFFFF;">Cancel bill</span></a>&nbsp;';
					if( 'undefined' != typeof item.cancel_bill && 'undefined' != typeof item.delete_bill ) 
						mediaClass += '<a class="delete_bill" title="Delete Bill" onClick="delete_bill('+item.nBillNo+');" href="javascript:void(0)"><span class="badge badge-error" style="color: #FFFFFF;">Delete bill</span></a>';
					mediaClass += '</td>';
					mediaClass += '</tr>';
				}
			}
			mediaClass += '</table>';
				
			$('#DynamicGridp_2').html(mediaClass);
			$("#myTable").tablesorter();
	});
}
</script>