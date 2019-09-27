<div id="main_content" style="overflow:hidden;">
    <fieldset>
        <legend></legend>
        <form id="userForm" method="post" action="">
            <table cellpadding="0" cellspacing="10" border="0">
                <tr>
                    <td>
                        <label>From Date</label>
                    </td>
                    <td>
                        <input id="selector" type="text"/>
                        <script>
                            $(function () {
                                $("#selector").picker({dateFormat: 'yy-mm-dd'});
                            });
                        </script>
                    </td>
                    <td>To Date</td>
                    <td><input id="selector1" type="text" name="Rate"/><br/></td>
                    <script>
                        $(function () {
                            $("#selector1").picker({dateFormat: 'yy-mm-dd'});
                        });
                    </script>
                </tr>
            </table>
            <div class="pad-10">
                <input class="btn btn-success" type="button" value="Click Here" id="save_id" onClick="functionpdf();"/>
                &nbsp; &nbsp; &nbsp;
                <a style ="border:none;padding:0px;" href="#" id="export" onclick="tableToExcel('DynamicGridp_2', 'Factory Material Movement')"><input class ="btn btn-success" type="button" value="Export to Excel" hidden/></a> &nbsp; &nbsp;
                &nbsp;
                <div id="check_bar" style="padding-top:10px;">&nbsp;</div>
            </div>
        </form>
        <div class="tab-boxpr">
            <div style="width:640px;">
                <a href="javascript:">
                    <div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Factory Material</h1>
                    </div>
                </a>
            </div>
        </div>
        <div class="tabcontentpr" id="contpr-1-1">
            <div id="party_list">
                <div id="contentsfolder" style="width:100%; height:400px; overflow-x:hidden; overflow-y:auto;">
                    <div id="partycontent" style="width:100%; min-height:400px; overflow:hidden;">
                        <script src="<?= $this->asset->js_path('jquery.tablesorter.pager', 'partywise_register') ?>"></script>
                        <script src="<?= $this->asset->js_path('jquery.tablesorter', 'partywise_register') ?>	"></script>
                        <script src="<?= $this->asset->js_path('jquery.tablesorter.widgets', 'partywise_register') ?>	"></script>

                        <div id="DynamicGridp_2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("#export").hide();
    });

    var section = "demos/datepicker";
    $(function () {
        $("#datepicker").datepicker();
    });

    function totalweight_check() {
        var party_account_name = $('#party_account_name').val();
        var dataString = '&party_account_name=' + party_account_name;
        $.ajax({
            type: "POST",
            url: "<?php echo fuel_url('billing_statement/totalweight_check');?>/",
            data: dataString,
            datatype: "json",
            success: function (msg) {
                var msg3 = eval(msg);
                $.each(msg3, function (i, j) {
                    var weight = j.weight;
                    document.getElementById("totalweight_calcualation").value = weight;
                });
            }
        });
    }
</script>

<script>
    /*function functionpdf(){
        var selector = $('#selector').val();
        var selector1 = $('#selector1').val();
        //	alert(selector);
        if( selector == ' ' || selector1  == ' ')
        {
        alert("Please select the Date ");
        }
        else {
        $("#check_bar").html('<span style="font-size:20px; color:red">Please wait.. Loading PDF might take some time..</span>');
        var dataString =  'frmdate='+selector+'&todate='+selector1;
        $.ajax({
               type: "POST",
              // url : "<?php echo fuel_url('billing_statement/billing_pdf');?>/",
		//   data: dataString,
		   success: function(msg)
		   {  
			$("#check_bar").html('');
			var dataString =  'frmdate='+selector+'&todate='+selector1;
			var url = "<?php echo fuel_url('factory_material/billing_pdf');?>/?"+dataString;
		    window.open(url);
		   }  
		}); 

	}
}*/

    function functionpdf() {
        var selector = $('#selector').val();
        var selector1 = $('#selector1').val();
// document.getElementById("fromdate").value = document.getElementById("selector").value;
        $("#export").show();
        if (selector == '' && selector1 == '') {
            alert("Please select all the values");

        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo fuel_url('factory_material/export_party');?>",
                data: 'frmdate=' + selector + '&todate=' + selector1,
                dataType: "json"
            }).done(function (msg) {
                $("#check_bar").html('');
                var dataString = 'frmdate=' + selector + '&todate=' + selector1;
                var url = "<?php echo fuel_url('factory_material/billing_pdf');?>/?" + dataString;
                window.open(url);
                var mediaClass = '';
                mediaClass += '<table id="myTabels" class="tablesorter tablesorter-blue">';
                mediaClass += '<thead>';
                mediaClass += '<tr>';
                mediaClass += '  <th>Party Name</th>';
                mediaClass += '  <th>Inward Weight</th>';
                mediaClass += '  <th>Outward Weight</th>';
                mediaClass += '  <th>Balance</th>';
                mediaClass += '</tr>';
                mediaClass += '</thead>';
                for (var i = 0; i < msg.length; i++) {
                    var item = msg[i];
                    mediaClass += '<tr>';
                    mediaClass += '<td>' + item.partyname + '</td>';
                    mediaClass += '<td>' + parseFloat(item.inweight).toFixed(3) + '</td>';
                    mediaClass += '<td>' + parseFloat(item.outweight).toFixed(3) + '</td>';
                    mediaClass += '<td>' + parseFloat(item.balance).toFixed(3) + '</td>';
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
tab_text = tab_text + '<table><tr><td style="font-size:60px; font-style:italic; font-family:fantasy;" colspan="7" align="center"><h1>Factory Material Report</h1></td></tr>';

tab_text = tab_text + '<tr></tr><tr><td><b>From Date : </b>'+$('#selector').val()+'</td><td><b>To Date : </b>'+$('#selector1').val()+'</td></tr><tr><td></td></tr></table>';
tab_text = tab_text + "<table border='1px'>";
tab_text = tab_text + $('#myTabels').html();
tab_text = tab_text + '</table>';

//tab_text = tab_text + '<table border="1px"><tr></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td><h3>Total Weight : </td><td>'+$('#totalweight_calcualation').val()+' </h3></td><td></td></tr></table></body></html>';


var data_type = 'data:application/vnd.ms-excel';

var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
if (window.navigator.msSaveBlob) {
    var blob = new Blob([tab_text], {
        type: "application/csv;charset=utf-8;"
    });
    navigator.msSaveBlob(blob,'Factory_Material_Report.xls');
}
} else {
$('#export').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
$('#export').attr('download','Factory_Material_Report.xls');
}
}
</script>