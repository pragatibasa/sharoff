<div id="main_top_panel">
    <h2 class="ico ico_bill_summary"><?=lang('module_quality_reports')?></h2>
</div>

<div id="main_content" style="overflow:hidden;">
	<div>
		<div class="tabcontentpr" id="contpr-1-1" >
			<div id="party_list" style="width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">
			 	<div id="contentsfolder" style="width:100%; height:550px; overflow-x:hidden; overflow-y:auto;">
					<div id="partycontent" style="width:100%; min-height:550px; overflow:hidden;">
            <script src="<?=$this->asset->js_path('jquery.tablesorter.pager', 'quality_reports')?>"></script>
						<script src="<?=$this->asset->js_path('jquery.tablesorter', 'quality_reports')?>	"></script>
						<script src="<?=$this->asset->js_path('jquery.tablesorter.widgets', 'quality_reports')?>	"></script>
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
	loadQualityDetails();
});

function loadQualityDetails() {
  var loading = '<div id="DynamicGridLoadingp_2"> '+
               '<img src="<?=img_path() ?>loading.gif" /><span> Loading Quality Reports... </span> '+
             '</div>';
   $.ajax({
     type: "POST",
     url: "<?php echo fuel_url('quality_reports/list_quality_reports');?>",
     dataType: "json"
   }).done(function( msg ) {
     console.log(msg);
     var mediaClass ='';
     mediaClass += '<table id="myTable" class="tablesorter tablesorter-blue"	>';
     if(msg.length == 0) {
       var mediaClass = '<tr><td>No Results!</td></tr>';
     } else {
       mediaClass +='<thead><tr><th>Report Id</th><th>Coil Number</th><th>Party Name</th><th>Coil Status</th><th>Coil Received On</th><th>Report Created On</th><th>Actions</th></tr></thead>';
       for (var i=0;i<msg.length;i++)
       {
         var item = msg[i];
         mediaClass += '<tr>';

         mediaClass += '<td>'+ item.report_id +'</td>';
         mediaClass += '<td>' + item.coilNumber + '</td>';
         mediaClass += '<td>' + item.nPartyName + '</td>';
         mediaClass += '<td>' + item.coilStatus + '</td>';
         mediaClass += '<td>' + item.coilReceivedDate + '</td>';
         mediaClass += '<td>' + item.reportCreatedOn + '</td>';

         mediaClass += '<td>';
         mediaClass += '<a title="View Report" target="_blank" href="'+item.view_report+'"><span class="badge badge-success" style="color: #FFFFFF;">View Report</span></a>&nbsp;';
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
