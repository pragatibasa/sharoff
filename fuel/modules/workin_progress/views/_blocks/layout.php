<script language="javascript" type="text/javascript">
    $(window).load(function () {
        $("tr#childlist").hide();
        var lfScrollbar = $('#contentsfolder');
        fleXenv.updateScrollBars(lfScrollbar);
    });

</script>

<div class="tab-boxpr">
    <div style="width:640px;">
        <a href="javascript:">
            <div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Workin Progress</h1></div>
        </a>
    </div>
</div>
<div>
  <a style="border:none;padding:0px;position: absolute; right:0;"  href="#" id="export" onclick="tableToExcel('myTable','Workin Progress');"><input class="btn btn-success" type="button" value="Export to Excel"/> </a>&nbsp; &nbsp; &nbsp;
  </div>

<!-- MAIN Workinprogress @START -->
<div id="main_content" style="overflow:hidden;">
    <div>
        <div class="tabcontentpr" id="contpr-1-1">
            <div id="party_list" style="width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">

                <script src="<?= $this->asset->js_path('jquery.tablesorter.pager', 'workin_progress') ?>"></script>
                <script src="<?= $this->asset->js_path('jquery.tablesorter', 'workin_progress') ?>	"></script>
                <script src="<?= $this->asset->js_path('jquery.tablesorter.widgets', 'workin_progress') ?>	"></script>
                <div>
                    <div>
                        <table id="myTable" class="tablesorter tablesorter-blue">
                            <?php if (isset($workinprogress_lists->status) && $workinprogress_lists->status == 'No Results!') {
                                ?>
                                <tr>
                                <td><?php echo $workinprogress_lists->status; ?></td></tr><?php
                            } else { ?>
                            <thead>
                            <tr>
                                <th>Coilnumber</th>
                                <th data-date-format="ddmmyyyy">Received Date</th>
                                <th data-date-format="ddmmyyyy">Size Given Date</th>
                                <th>Partyname</th>
                                <th>Material Description</th>
                                <th>Thickness</th>
                                <th>Width</th>
                                <th>Weight</th>
                                <th>Process</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i = 0; $i < count($workinprogress_lists); $i++) { ?>
                                <td><?php echo $workinprogress_lists[$i]->coilnumber ?></td>
                                <td><?php echo $workinprogress_lists[$i]->receiveddate ?></td>
                                <td><?php if ($workinprogress_lists[$i]->process == 'Cutting') echo $workinprogress_lists[$i]->sizegivendate; else if ($workinprogress_lists[$i]->process == 'Slitting') echo $workinprogress_lists[$i]->slittingdate; ?></td>
                                <td><?php echo $workinprogress_lists[$i]->partyname ?></td>
                                <td><?php echo $workinprogress_lists[$i]->materialdescription ?></td>
                                <td><?php echo $workinprogress_lists[$i]->thickness ?></td>
                                <td><?php echo $workinprogress_lists[$i]->width ?></td>
                                <td><?php echo number_format((float) $workinprogress_lists[$i]->weight,3) ?></td>
                                <td><?php echo $workinprogress_lists[$i]->process ?></td>
                                <td><?php echo $al = '<a title="Cutting Instruction" href="' . $workinprogress_lists[$i]->al . '"><span class="badge badge-success" style="color: #FFFFFF;">Cutting</span></a>';
                                    echo $as = '<a title="Slitting Instruction" href="' . $workinprogress_lists[$i]->slit . '"><span class="badge badge-success" style="color: #FFFFFF;">Slitting</span></a>';
                                    echo $fi = '<a  title="Finish Task" href="' . $workinprogress_lists[$i]->fi . '"><span class="badge badge-info" style="color: #FFFFFF;">Finish</span></a>';
                                    echo $bl = '<a title="Billing Instruction" href="' . $workinprogress_lists[$i]->bl . '"><span class="badge badge-important" style="color: #FFFFFF;">Billing</span></a>';
                                    echo $cs = '<a title="Print" href="' . $workinprogress_lists[$i]->cs . '"  target="_blank"><span class="badge" style="color: #FFFFFF;" >Processing Slip</span></a>';
                                    echo $cs = '<a title="Quality Report" href="' . $workinprogress_lists[$i]->qc . '"><span class="badge" style="color: #FFFFFF;" >Quality Report</span></a>';
                                    ?> </td>
                                </tr>
                                <tr class="even"></tr>
                            <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input id="txtcoilids" type="hidden" hidden/>
<div align="right">
    <label>Total Weight: in (Tons)</label>
    <input id="totalweight_calcualation" type="text" value="<?php echo number_format($tweight,3); ?>" DISABLED/> &nbsp; &nbsp; &nbsp;
</div>

<input id="coilid" type="hidden" value="" name="coilid">
<input id="partyid" type="hidden" value="" name="partyid">
<script type="text/javascript">
    $(function () {
        $("#myTable").tablesorter();
    });

</script>
<script type="text/javascript">

    $('#myTable tr').bind('click', function (e) {
        if ($(this).parent("thead").length == 0) {
            $(e.currentTarget).children('td, th').css('background-color', '#7FFFD4');
        }
    });

    function finishtask(id) {
        var coilnumber = $('#vnum' + id).val();
        var partyname = $('#pname' + id).val();
        document.getElementById('coilid').value = coilnumber;
        document.getElementById('partyid').value = partyname;
    }

    function cuttinginstruction(id) {
        var coilnumber = $('#vno' + id).val();
        document.getElementById('partnamecheck').value = coilnumber;
}
function tableToExcel() {
	
    var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    


tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
tab_text = tab_text + '<table><tr><td style="font-size:60px; font-style:italic; font-family:fantasy;" colspan="7" align="center"><h1>Workin Progress</h1></td><td></td><td></td><td></td><td></td></tr></table>';
//tab_text = tab_text + '<tr></tr><tr><td><b>Party Name : </b>'+$('#party_account_name').val()+'</td><td><b>From Date : </b>'+$('#selector').val()+'</td><td><b>To Date : </b>'+$('#selector1').val()+'</td></tr><tr><td></td></tr></table>';
tab_text = tab_text + "<table border='1px'>";

tab_text = tab_text + $('#myTable').html();
tab_text = tab_text + '</table>';

tab_text = tab_text + '<table border="1px"><tr></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td><h3>Total Weight : </td><td>'+$('#totalweight_calcualation').val()+' </h3></td><td></td><td></td></tr></table></body></html>';


var data_type = 'data:application/vnd.ms-excel';

var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
if (window.navigator.msSaveBlob) {
    var blob = new Blob([tab_text], {
        type: "application/csv;charset=utf-8;"
    });
    navigator.msSaveBlob(blob, $('#party_account_name').val()+'_Workin_Progress.xls');
}
} else {
$('#export').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
$('#export').attr('download','_Workin_Progress.xls');
}
}

</script>
