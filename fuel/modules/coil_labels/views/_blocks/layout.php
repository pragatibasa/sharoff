<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script src="<?=$this->asset->js_path('datatables.min', 'coil_labels')?>"></script>
<div class="container">
<div id="dialog" title="Label">
    <div id="accordion">
    </div>
</div>
	<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Coil Number</th>
                <th>Received Date</th>
                <th>Coil Status</th>
                <th>Party Name</th>
                <th>Label</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Coil Number</th>
                <th>Received Date</th>
                <th>Coil Status</th>
                <th>Party Name</th>
                <th>Label</th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
	$(document).ready(function() {

        var selectedCoilId = null;
        var selectedCoilCompanyName = null;
        // $( "#dialog" ).dialog({
        //     autoOpen: false,
        //     width: "50%",
        //     height: "auto",
        //     maxWidth: "768px",
        //     open : function(event,ui) {
        //         $.ajax({  
        //             type: "POST",  
        //             url : "<?php echo fuel_url('coil_labels/getBundleDetails');?>/",  
        //             data: 'coilNumber='+selectedCoilId,
        //             async : false,
        //             success: function(msg) {
        //                 var bundleObj = JSON.parse(msg);
        //                 var strAccordionHtml = "";
        //                 for (var key in bundleObj) {
        //                     strAccordionHtml += "<h3>Bundle no: "+bundleObj[key]['nSno']+"</h3><div><table border='2' width='100%'><tr><td align='center' style='padding:15px;' colspan='2'><b>ASPEN STEEL PVT LTD <br>Plot no 16E, Phase 2 sector 1, Bidadi, Ramnagar: 562109</b></td></tr><tr><td style='padding:10px;border: 2px solid black;'>Tag No: <img src='<?php echo img_path('Code128Barcode.jpg','coil_labels') ?>'><span style='display: block;font-size: 11px;margin-left: 101px;line-height: 0px;'>"+selectedCoilId+"</span></td><td style='padding:10px;border: 2px solid black;'>Customer: <span class='labelcustomer'>"+selectedCoilCompanyName+"</span></td></tr><tr><td style='padding:10px;border: 2px solid black;'>A Coil No.: "+selectedCoilId+"</td><td style='padding:10px;border: 2px solid black;'>Spec: CR</td></tr><tr><td style='padding:10px;border: 2px solid black;'>M Coil No.:</td><td style='padding:10px;border: 2px solid black;'>Process Name: "+bundleObj[key]['vprocess']+"</td></tr><tr><td style='padding:10px;border: 2px solid black;'>Size: "+bundleObj[key]['fThickness']+"X"+bundleObj[key]['nWidth']+"X"+bundleObj[key]['nLength']+"</td><td style='padding:10px;border: 2px solid black;'>Process Date: "+bundleObj[key]['dDate']+"</td></tr><tr><td style='padding:10px;border: 2px solid black;'>Wt(Kgs): "+bundleObj[key]['nWeight']+"</td><td style='padding:10px;border: 2px solid black;'>Packaging Type: LDPE + HDPE</td></tr><tr><td style='padding:10px;border: 2px solid black;'>No of Pc's: </td><td style='padding:10px;border: 2px solid black;'>Bundle No.: "+bundleObj[key]['nSno']+"</td> </tr></table></div>";
        //                 }
        //                 $( "#accordion" ).html(strAccordionHtml);
        //                 $( "#accordion" ).accordion();
        //             }
		//         });
        //     }
        // });
       var table = $('#example').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo fuel_url('coil_labels/list_coil_labels');?>",
            "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button type='button' class='btn-primary'>Click</button>"
            } ]
        });

        $('#example tbody').on( 'click', 'button', function () {
            var data = table.row( $(this).parents('tr') ).data();
            selectedCoilId = data[0];
            window.open("<?php echo fuel_url('coil_labels/getBundleDetails?coilNumber=');?>"+selectedCoilId, '_blank');
        } );
    } );
</script>

