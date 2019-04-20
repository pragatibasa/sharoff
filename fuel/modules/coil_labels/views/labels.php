<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<div id="main_top_panel">
    <h2 class="ico ico_bill_summary">Labels</h2>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Labels of coil : <?php echo $coilDetails['vIRnumber'];?></h3>
    </div>
    <div class="panel-body">
        <div class="container">
            <div class="row">
            <a href="<?php echo fuel_url('coil_labels/printAllPrnFiles?coilNumber='.$coilDetails['vIRnumber']);?>"><button type="button" class="btn btn-primary pull-right print-all-labels">Print All</button></a>
            </div>
            <div class="row" style="margin-top: 28px;"></div>
            <div class="row">
                <?php foreach($bundleDetails as $bundle) {
                    echo '<div class="panel panel-info">';
                    echo '<div class="panel-heading">';
                    echo "<h3 class='panel-title'>Bundle No: ".$bundle['nSno']."</h3></div>";
                    echo "<div class='panel-body'>";
                    echo "<table border='2' width='100%'>";
                    echo "<tr><td align='center' style='padding:15px;' colspan='2'><b>ASPEN STEEL PVT LTD <br>Plot no 16E, Phase 2 sector 1, Bidadi, Ramnagar: 562109</b></td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>Tag No: <img src= ".img_path('Code128Barcode.jpg','coil_labels')."><span style='display: block;font-size: 11px;margin-left: 101px;line-height: 0px;'>".$coilDetails['vIRnumber']."</span></td><td style='padding:10px;border: 2px solid black;'>Customer: <span class='labelcustomer'>".$coilDetails['nPartyName']."</span></td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>A Coil No.: ".$coilDetails['vIRnumber']."</td><td style='padding:10px;border: 2px solid black;'>Spec: ".$coilDetails['vDescription']."</td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>Parent Coil No.:</td><td style='padding:10px;border: 2px solid black;'>Process Name: ".$coilDetails['vprocess']."</td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>Size: ".$bundle['fThickness']."mm x ".(($coilDetails['vprocess'] == 'Cutting') ? $coilDetails['fWidth'] : $bundle['nWidth'])."mm x ".$bundle['nLength']."mm</td><td style='padding:10px;border: 2px solid black;'>Process Date: ".$bundle['dDate']."</td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>Wt(Kgs): ".(($coilDetails['vprocess'] == 'Cutting') ? $bundle['nBundleweight'] : $bundle['nWeight'])."</td><td style='padding:10px;border: 2px solid black;'>Packaging Type: </td></tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'>No of Pc's: ".(($coilDetails['vprocess'] == 'Cutting') ? $bundle['nNoOfPieces'] : '-')."</td><td style='padding:10px;border: 2px solid black;'>Bundle No.: ".$bundle['nSno']."</td> </tr>";
                    echo "<tr><td style='padding:10px;border: 2px solid black;'><span style='vertical-align:middle;text-align:  center;margin-top: -20px;display:inline-block;'>QC :</span> <div style='border: 2px solid black;width: 134px;height: 36px;margin-left: 16px;display:  inline-grid;'></div> </td><td style='padding:10px;border: 2px solid black;'> <span style='vertical-align: middle;text-align: center;margin-top: -20px;display: inline-block;'>Prod Sign : </span><div style='border: 2px solid black;width: 134px;height: 36px;margin-left: 16px;display: inline-grid;'></div></td> </tr>";
                    echo "</table>";
                    echo '<a href="'.fuel_url('coil_labels/printSinglePrnFile?coilNumber='.$coilDetails['vIRnumber'].'&bundleNumber='.$bundle['nSno']).'"><button type="button" class="btn btn-success pull-right print_single" style="margin-top:16px;">Print Label</button></a>';
                    echo "</div></div>";
                } ?>
            </div>
        </div>
    </div>
</div>

<script>
$('document').ready(function() {
});
</script>