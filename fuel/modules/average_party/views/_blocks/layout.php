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
<fieldset>

<form id="userForm" method="post" action="">



<div class="pad-10">

	<label><h3>To view Partywise Average Holding Report for each Party , Please click on the button</h3></label> </br>
			


			<input class="btn btn-success"  type="button" value="Click Here" id="save_id" onClick="functionpdf();"/> &nbsp; &nbsp; &nbsp; 
			<a style ="border:none;padding:0px;"href="#" id="export" onclick="tableToExcel('DynamicGridp_2', 'Customer Outward Report')"><input class="btn btn-success"  type="button" value="Export to Excel"  hidden/></a> &nbsp; &nbsp; &nbsp; 
			<div id="check_bar" style="padding-top:10px;">&nbsp;</div>


</div>

</form>

<div class="tab-boxpr"> 
	<div style="width:640px;">
    <a href="javascript:"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Average Party</h1></div></a>
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
	var party_account_name = $('#party_account_name').val();
	var dataString = '&party_account_name='+party_account_name;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('average_party/totalweight_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(i, j){
			 var weight = j.weight;
			document.getElementById("totalweight_calcualation").value = weight;});
	   }  
	}); 
}


/*function functionpdf(){
	var party_account_name = $('#party_account_name').val();
	if(party_account_name == 'Select' )
	{ 
	alert("Please select the Partyname ");
	}
	else {
		$("#check_bar").html('<span style="font-size:20px; color:red">Please wait.. Loading PDF might take some time..</span>');
	var dataString =  'partyname='+party_account_name;
	$.ajax({  
		   type: "POST",  
		  // url : "<?php echo fuel_url('billing_statement/billing_pdf');?>/",  
		//   data: dataString,
		   success: function(msg)
		   {  
			$("#check_bar").html('');
			var dataString =  'partyname='+party_account_name;
			var url = "<?php echo fuel_url('average_party/billing_pdf');?>/?"+dataString;
		    window.open(url);
		   }  
		}); 
}
}*/



function functionpdf() {

		 var partyname = $("#party_account_name").val();
		 $("#export").show();
	if(partyname == 'Select' )
	{ 
	alert("Please select all the values");

	}
	else 
	 {
	   $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('average_party/export_party');?>",
		data: 'partyname='+partyname ,
        dataType: "json"
        }).done(function( msg ) {
	    $("#check_bar").html('');
			var dataString =  'partyname='+partyname;
			var url = "<?php echo fuel_url('average_party/billing_pdf');?>/?"+dataString;
		    window.open(url);
			var mediaClass ='';
			mediaClass += '<table id="myTabels" class="tablesorter tablesorter-blue">';
			mediaClass +='<thead>';
			mediaClass +='<tr>';
			mediaClass += '  <th>Coil Number</th>';
			mediaClass += '  <th>Received Date</th>';
			mediaClass += '  <th>Width</th>';
			mediaClass += '  <th>Thickness</th>';
			mediaClass += '  <th>Weight</th>';
			mediaClass += '  <th>Billed Date</th>';
			mediaClass += '  <th>No. of Days</th>';
			mediaClass +='</tr>';
			mediaClass +='</thead>';
		
			for (var i=0;i<msg.length;i++)
			{
				var item = msg[i];
				mediaClass += '<tr>';
				mediaClass += '<td>' + item.coilno + '</td>';
				mediaClass += '<td>' + item.indate + '</td>';
				mediaClass += '<td>' + item.width + '</td>';
				mediaClass += '<td>' + item.thickness + '</td>';
				mediaClass += '<td>' + parseFloat(item.quantity).toFixed(3) + '</td>';	
				mediaClass += '<td>' + item.bdate + '</td>';
				mediaClass += '<td>' + item.noofdays + '</td>';
				mediaClass += '</tr>';			
				
			}
			mediaClass += '</table>';
			
			$('#DynamicGridp_2').html(mediaClass);
			 $("#myTabels").tablesorter();
			totalweight_check();
				
		
		});

	}
}

function tableToExcel(){ 
  	var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';

tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

tab_text = tab_text + '<table><tr><td style="font-size:60px; font-style:italic; font-family:fantasy;" colspan="7" align="center"><h1>AVERAGE PARTYWISE REPORT</h1></td></tr>';

tab_text = tab_text + '<tr></tr><tr><td><b>Party Name : </b>'+$('#party_account_name').val()+'</td><td><b>From Date : </b>'+$('#selector').val()+'</td><td><b>To Date : </b>'+$('#selector1').val()+'</td></tr><tr><td></td></tr></table>';

var table = document.getElementById('myTabels'),
    tableClone = table.cloneNode(true),
    elementsToRemove = tableClone.querySelectorAll('.partyname');

for (var i = elementsToRemove.length; i--;) {
    elementsToRemove[i].remove();
}


tab_text = tab_text + "<table border='1px'>";
tab_text = tab_text + tableClone.innerHTML;
tab_text = tab_text + '</table>';

tab_text = tab_text + '<table border="1px"><tr></tr><tr><td></td><td></td><td></td><td><h3>Total Weight : </td><td>'+$('#totalweight_calcualation').val()+' </h3></td><td></td><td></td><td></td><td></td></tr></table></body></html>';

var data_type = 'data:application/vnd.ms-excel';

var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
    if (window.navigator.msSaveBlob) {
        var blob = new Blob([tab_text], {
            type: "application/csv;charset=utf-8;"
        });
        navigator.msSaveBlob(blob, $('#party_account_name').val()+'_Inward_Report.xls');
    }
} else {
	$('#export').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
    $('#export').attr('download', $('#party_account_name').val()+'_Inward_Report.xls');
}
}

</script>  