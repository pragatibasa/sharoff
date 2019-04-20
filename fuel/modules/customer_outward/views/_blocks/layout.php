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
        $("#"+tabeId+"-1").removeClass("hidepr")   
        return false;	  
      });
    });  
  });
</script><br /><br />
<div id="main_content" style="overflow:hidden;"> 
<fieldset>

<form id="userForm" method="post" action="">
		<table cellpadding="0" cellspacing="10" border="0">
			<tr>
				<td>   
					<label>From Date</label>
				</td>
				<td> 
					<input id="selector"  type="text" />
								<script>
  $(function() {
       $( "#selector" ).picker({ dateFormat: 'yy-mm-dd' });
  });
  </script>
				</td>
				
				
				
							<td>To Date</td>	
							<td><input id= "selector1" type="text"  name="Rate" /><br /></td>

	<script>
  $(function() {
       $( "#selector1" ).picker({ dateFormat: 'yy-mm-dd' });
  });
  </script>
			</tr>
		
			
</table>
<div class="pad-10">

			<input class="btn btn-success"  type="button" value="Click Here" id="save_id" onClick="functionpdf();"/> &nbsp; &nbsp; &nbsp; 
			<a style="border:none;padding:0px;" href="#" id="export" onclick="tableToExcel('DynamicGridp_2', 'Customer Inward Report')"><input class="btn btn-success"  type="button" value="Export to Excel" hidden/></a> &nbsp; &nbsp; &nbsp; 
			<div id="check_bar" style="padding-top:10px;">&nbsp;</div>

</div>

</form>
<div class="tab-boxpr"> 
	<div style="width:640px;">
    <a href="javascript:;"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Outward Report</h1></div></a> 
    </div>
</div>

<div class="tabcontentpr" id="contpr-1-1">
<div id="party_list">
<div id="contentsfolder" style="width:100%; height:400px; overflow-x:hidden; overflow-y:auto;">
<div id="partycontent" style="width:100%; min-height:400px; overflow:hidden;"> 


<script src="<?=$this->asset->js_path('jquery.tablesorter.pager', 'partywise_register')?>"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter', 'partywise_register')?>	"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter.widgets', 'partywise_register')?>	"></script>
		
<div id="DynamicGridp_2" >
</div>
	
</div>
</div>
</div>
</div>

</fieldset>
<?php //echo $totalweight; ?>
<input id="partnamecheck" type="hidden" value="" name="partnamecheck" />	
<div align="right">
<label>Total Weight</label>
		<input id="totalweight_calcualation" type="text" DISABLED/>(in Kgs)  
		&nbsp; &nbsp; &nbsp;
</div>

<script language="javascript" type="text/javascript">
var section = "demos/datepicker";
	$(function() {
		$( "#datepicker" ).datepicker();
	});

$(document).ready(function() { 

	 $("#export").hide();

});

function totalweight_check(){
		 var partyname = $("#party_account_name").val();
		 var selector = $('#selector').val();
		 var selector1 = $('#selector1').val();
	var dataString = 'partyname='+partyname+'&frmdate='+selector+'&todate='+selector1;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('customer_outward/totalweight_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(i, j){
			 var bweight = j.bweight;
		document.getElementById("totalweight_calcualation").value = bweight;});
	   }  
	}); 
}

</script>

<script type="text/javascript">

/*function functionpdf(){
	var party_account_name = $('#party_account_name').val();
	var selector = $('#selector').val();
	var selector1 = $('#selector1').val();
		//		alert(selector);
		if(party_account_name == 'Select' || selector == ' ' || selector1  == ' ')
	{ 
	alert("Please select the Partyname ");
	}
	else {
		$("#check_bar").html('<span style="font-size:20px; color:red">Please wait.. Loading PDF might take some time..</span>');
	
	var dataString =  'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1;
	$.ajax({  
		   type: "POST",  
		  // url : "<?php echo fuel_url('billing_statement/billing_pdf');?>/",  
		//   data: dataString,
		   success: function(msg)
		   {  
			$("#check_bar").html('');
			var dataString =  'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1;
			var url = "<?php echo fuel_url('customer_outward/billing_pdf');?>/?"+dataString;
		    window.open(url);
		   }  
		}); 
}
}*/





function functionpdf() {

		 var party_account_name = $("#party_account_name").val();
		 var selector = $('#selector').val();
		 var selector1 = $('#selector1').val();
		 $("#export").show();
	if(party_account_name == 'Select' && selector == '' && selector1  == '' )
	{ 
	alert("Please select all the values");

	}
	else 
	 {
	   $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('customer_outward/export_party');?>",
		data: 'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1 ,
        dataType: "json"
        }).done(function( msg ) {
	    $("#check_bar").html('');
			var dataString =  'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1;
			var url = "<?php echo fuel_url('customer_outward/billing_pdf');?>/?"+dataString;
		    window.open(url);
			var mediaClass ='';
			mediaClass += '<table id="myTabels" class="tablesorter tablesorter-blue">';
			mediaClass +='<thead>';
			mediaClass +='<tr>';
			mediaClass += '  <th>Bill Number</th>';
			mediaClass += '  <th>Billed Date</th>';
			mediaClass += '  <th>Bill Type</th>';
			mediaClass += '  <th>Coilnumber</th>';
			mediaClass += '  <th>Description</th>';
			mediaClass += '  <th>Thickness</th>';
			mediaClass += '  <th>Width</th>';
			mediaClass += '  <th>Billed Weight</th>';
			mediaClass += '  <th>Vehicle Number</th>';
			mediaClass +='</tr>';
			mediaClass +='</thead>';
		
			for (var i=0;i<msg.length;i++)
			{
				var item = msg[i];
				mediaClass += '<tr>';
				mediaClass += '<td>' + item.billno + '</td>';
				mediaClass += '<td>' + item.billdate + '</td>';
				mediaClass += '<td>' + item.billtype + '</td>';
				mediaClass += '<td>' + item.coilnumber + '</td>';
				mediaClass += '<td>' + item.description + '</td>';
				mediaClass += '<td>' + item.thickness + '</td>';
				mediaClass += '<td>' + item.width + '</td>';
				mediaClass += '<td>' + item.bweight + '</td>';
				mediaClass += '<td>' + item.vehicleno + '</td>';
				mediaClass += '</tr>';			
				
			}
			mediaClass += '</table>';
			
			$('#DynamicGridp_2').html(mediaClass);
			 $("#myTabels").tablesorter();
			totalweight_check();
				
		
		});

	}
}

function tableToExcel() {
		var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';

tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

tab_text = tab_text + '<table><tr><td style="font-size:60px; font-style:italic; font-family: fantasy;"><h1>ASPEN STEEL PVT LTD</h1></td></tr><tr><td><h4>Branch At: Plot no 16E, Bidadi Industrial Area, Phase 2 Sector 1, Bidadi, Ramnagara-562105, <b>Email: aspensteel_unit2@yahoo.com </b></h4></td></tr><tr><td><h4>Head Office At: 54/1, Medahalli, Old Madras Road, Bangalore-560049</h4></td></tr><tr><td></td></tr></table>';

tab_text = tab_text + "<table border='1px'>";
tab_text = tab_text + $('#myTabels').html();
tab_text = tab_text + '</table>';

tab_text = tab_text + '<table border="1px"><tr></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td><h3>Total Weight : </td><td>'+$('#totalweight_calcualation').val()+' ( in kgs )</h3></td><td></td></tr></table></body></html>';

var data_type = 'data:application/vnd.ms-excel';

var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
    if (window.navigator.msSaveBlob) {
        var blob = new Blob([tab_text], {
            type: "application/csv;charset=utf-8;"
        });
        navigator.msSaveBlob(blob, $('#party_account_name').val()+'_Outward_Report.xls');
    }
} else {
	$('#export').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
    $('#export').attr('download', $('#party_account_name').val()+'_Outward_Report.xls');
}
}

</script>  