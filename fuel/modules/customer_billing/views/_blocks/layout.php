<?php include_once(CUSTOMER_BILLING_PATH.'views/_blocks/toolbar.php');?>	
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
			<a style="border:none;padding:0px;" href="#" id="export" onclick="tableToExcel('DynamicGridp_2', 'Customer Inward Report')"><input class="btn btn-success"  type="button" value="Export to Excel" hidden/> </a> &nbsp; &nbsp; &nbsp; 
			<div id="check_bar" style="padding-top:10px;">&nbsp;</div>
		</div>



</form>

<div class="tab-boxpr"> 
	<div style="width:640px;">
    <a href="javascript:"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Billing Report</h1></div></a>
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
<!-- @END -->

<!-- @END -->
</div>

<?php //echo $totalweight; ?>
<input id="partnamecheck" type="hidden" value="" name="partnamecheck" />

	

<label>Total Weight</label> &nbsp; <input id="totalweight_calcualation" type="text" DISABLED/>(in Tons) &nbsp;&nbsp; &nbsp;   		


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
	   url : "<?php echo fuel_url('customer_billing/totalweight_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(i, j){
			 var wei = j.wei;
	//		 alert(wei);
			document.getElementById("totalweight_calcualation").value = parseFloat(wei).toFixed(3);});
	   }  
	}); 
}


function totalbasic_check(){
	var party_account_name = $('#party_account_name').val();
	var dataString = '&party_account_name='+party_account_name;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('customer_billing/totalbasic_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(l, k){
			 var basic = k.basic;
			document.getElementById("totalbasic_calcualation").value = parseFloat(basic).toFixed(3);});
	   }  
	}); 
}


function totaltax_check(){
	var party_account_name = $('#party_account_name').val();
	var dataString = '&party_account_name='+party_account_name;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('customer_billing/totaltax_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(n, m){
			 var tax = m.tax;
			document.getElementById("totaltax_calcualation").value = parseFloat(tax).toFixed(3);});
	   }  
	}); 
}

function totalbill_check(){
	var party_account_name = $('#party_account_name').val();
	var dataString = '&party_account_name='+party_account_name;
$.ajax({  
	   type: "POST",  
	   url : "<?php echo fuel_url('customer_billing/totalbill_check');?>/",  
		data: dataString,
		datatype : "json",
		success: function(msg){
		var msg3=eval(msg);
		$.each(msg3, function(o, p){
			 var bill = p.bill;
			document.getElementById("totalbill_calcualation").value =  parseFloat(bill).toFixed(3);});
	   }  
	}); 
}

function loadfolderlist(account, accname) {
	$('#DynamicGridp_2').hide();
	var loading = '<div id="DynamicGridLoadingp_2"> '+
            	   ' <img src="<?=img_path() ?>loading.gif" /><span> Loading Party List... </span> '+ 
    	    	   ' </div>';
    $("#partycontent").empty();
	$('#partycontent').html(loading);
    $("#pr_container_name").html("/");
    $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('customer_billing/list_party');?>",
        data: "party_account_name=" + account,
        dataType: "json"
        }).done(function( msg ) {
			if(msg.length == 0) {
			$('#DynamicGridp_2').hide();
			$('#DynamicGridLoadingp_2').hide();
			var loading1 = '<div id="error_msg"> '+
                           'No Result!'+ 
						   '</div>';
			$('#partycontent').html(loading1);  
			} else{
            var partydata = [];
            for (var i = 0; i < msg.length; i++) {
            var item = msg[i];
            var thisdata = {};
			var selectcoil = '<input type="radio" id="radio_'+item.coilnumber+'" name="list" value="'+item.coilnumber+'"   onClick=showchild("'+item.coilnumber+'") />';
			thisdata["billdate"] = item.billdate;
            thisdata["billno"] = item.billno;
            thisdata["coilnumber"] = item.coilnumber;
            thisdata["Type of Material"] = item.description;
			thisdata["grade"] = item.grade;
            thisdata["Inward weight in (M.T)"] = item.weight;
			thisdata["Outward weight"] = item.oweight;
            thisdata["Basic Amount"] = item.totalamt;
            thisdata["Service Tax"] = item.Sertax;
			//thisdata["Education Tax"] = item.educationtax;
			//thisdata["SHEdutax"] = item.SHEdutax;
			 thisdata["Total Bill Amount"] = item.totalbillamount;
			totalweight_check();	
			totalbasic_check();	
			totaltax_check();
			totalbill_check();
			var pr = '<a class="ico_print" title="Print" href="'+item.pr+'" target="_blank"><img src="<?php echo img_path('iconset/ico_print.png'); ?>" /></a>';
            //thisdata["action"] = '';
			//thisdata["action"] = '';
            partydata.push(thisdata);
			}
			if (partydata.length) {
				$('#DynamicGridp_2').hide();
				$('#DynamicGridLoadingp_2').hide();
				$('#partycontent').html(CreateTableViewX(partydata, "lightPro", true)); 
				var lcScrollbar = $('#contentsholder');	 
				fleXenv.updateScrollBars(lcScrollbar); 
				$(".ico_coil_delete").click(function (e) {
                e.preventDefault();
                var data = {account_name: account};
                var href = $(this).attr('href');
                $.post(href, data, function (d) {
                loadfolderlist(account, accname);
                });
                });
			} else {
				$('#DynamicGridp_2').hide();
				$('#DynamicGridLoadingp_2').hide();
				var loading1 = '<div id="error_msg"> '+
							   'No Result!'+ 
							   '</div>';
				$('#partycontent').html(loading1); 
				var lfScrollbar = $('#contentsfolder');	 
				fleXenv.updateScrollBars(lfScrollbar);  
                }
			}
    });
}

/*function functionpdf(){
	var party_account_name = $('#party_account_name').val();
		//	alert(selector);
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
			var url = "<?php echo fuel_url('customer_billing/billing_pdf');?>/?"+dataString;
		    window.open(url);
		   }  
		}); 
}
}*/


/*function functionpdf(){
	var party_account_name = $('#party_account_name').val();
	var selector = $('#selector').val();
	var selector1 = $('#selector1').val();
		if(party_account_name == 'Select' || selector == ' ' || selector1  == ' ')
	{ 
	alert("Please select the Partyname ");
	}
	else {
	$("#check_bar").html('<span style="font-size:20px; color:red">Please wait.. Loading PDF might take some time..</span>');
		var dataString =  'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1;
	$.ajax({  
		   type: "POST",  
		   success: function(msg)
		   {  
		$("#check_bar").html('');
			var dataString =  'partyname='+party_account_name+'&frmdate='+selector+'&todate='+selector1;
			var url = "<?php echo fuel_url('customer_billing/billing_pdf');?>/?"+dataString;
		    window.open(url);
		   }  
		}); 
}

}*/




function functionpdf() {
		 var partyname = $("#party_account_name").val();
		 var selector = $('#selector').val();
		 var selector1 = $('#selector1').val();
		 $("#export").show();
	if(partyname == 'Select' && selector == '' && selector1  == '' )
	{ 
	alert("Please select all the values");

	}
	else 
	 {
	   $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('customer_billing/export_party');?>",
		data: 'partyname='+partyname+'&frmdate='+selector+'&todate='+selector1 ,
        dataType: "json"
        }).done(function( msg ) {
	    $("#check_bar").html('');
			var dataString =  'partyname='+partyname+'&frmdate='+selector+'&todate='+selector1;
			var url = "<?php echo fuel_url('customer_billing/billing_pdf');?>/?"+dataString;
		    window.open(url);
			var mediaClass ='';
			mediaClass += '<table id="myTabels" class="tablesorter tablesorter-blue">';
			mediaClass +='<thead>';
			mediaClass +='<tr>';
			mediaClass += '  <th>Coil Number</th>';
			mediaClass += '  <th>Invoice Date</th>';
			mediaClass += '  <th>Invoice Number </th>';
			mediaClass += '  <th>Material Description</th>';
			mediaClass += '  <th>Grade</th>';
			mediaClass += '  <th>Weight</th>';
			mediaClass += '  <th>Invoice Weight</th>';
			//mediaClass += '  <th>Basic Amount</th>';
			//mediaClass += '  <th>Service Tax</th>';
			//mediaClass += '  <th>Education Tax</th>';
			//mediaClass += '  <th>SHEdu Tax</th>';
			//mediaClass += '  <th>Total bill amount</th>';
			mediaClass +='</tr>';
			mediaClass +='</thead>';
			
			for (var i=0;i<msg.length;i++)
			{
				var item = msg[i];
				mediaClass += '<tr>';
				mediaClass += '<td>' + item.coilnumber + '</td>';
				mediaClass += '<td>' + item.billdate + '</td>';
				mediaClass += '<td>' + item.billno + '</td>';
				mediaClass += '<td>' + item.description + '</td>';
				mediaClass += '<td>' + item.grade + '</td>';
				mediaClass += '<td>' + parseFloat(item.weight).toFixed(3) + '</td>';
				mediaClass += '<td>' + parseFloat(item.oweight).toFixed(3) + '</td>';
				//mediaClass += '<td>' + item.totalamt + '</td>';
				//mediaClass += '<td>' + item.Sertax + '</td>';
				//mediaClass += '<td>' + item.SHEdutax + '</td>';  
				//mediaClass += '<td>' + item.educationtax + '</td>';
				//mediaClass += '<td>' + item.totalbillamount + '</td>';
				mediaClass += '</tr>';			
				
			}
			mediaClass += '</table>';
			
			$('#DynamicGridp_2').html(mediaClass);
			 $("#myTabels").tablesorter();
			totalweight_check();	
			totalbasic_check();	
			totaltax_check();
			totalbill_check();
				
		
		});

	}
}


function tableToExcel(){ 
  	var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';

tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

tab_text = tab_text + '<table><tr><td style="font-size:60px; font-style:italic; font-family:fantasy;" colspan="7" align="center"><h1>CUSTOMERS MATERIAL BILLING REPORT</h1></td></tr>';

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

tab_text = tab_text + '<table border="1px"><tr></tr><tr><td></td><td></td><td></td><td></td><td><h3>Total Weight : </td><td>'+$('#totalweight_calcualation').val()+' </h3></td><td></td></tr></table></body></html>';

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