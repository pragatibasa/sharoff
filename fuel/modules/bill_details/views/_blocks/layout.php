<script language="javascript" type="text/javascript">
  $(window).load(function() {
	$("tr#childlist").hide();
	var lfScrollbar = $('#contentsfolder');	 
	fleXenv.updateScrollBars(lfScrollbar); 
  });
  
</script>

<div class="tab-boxpr"> 
	<div style="width:640px;">
    <a href="javascript:;"><div class="tabLinkpr activeLinkpr" id="contpr-1" style="float:left;"><h1>Bil details</h1></div></a> 
    </div>
</div>

<!-- MAIN Workinprogress @START -->
<div id="main_content" style="overflow:hidden;">  
<div>
<div class="tabcontentpr" id="contpr-1-1" >
<div id="party_list" style="width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">
 
<script src="<?=$this->asset->js_path('jquery.tablesorter.pager', 'bill_details')?>"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter', 'bill_details')?>	"></script>
<script src="<?=$this->asset->js_path('jquery.tablesorter.widgets', 'bill_details')?>	"></script>
  <div>
<div> 
<table id="myTable" class="tablesorter tablesorter-blue"  >
	<?php if( isset($billdetails_lists->status) && $billdetails_lists->status == 'No Results!') {
  		?><tr><td><?php echo $billdetails_lists->status;?></td></tr><?php
  	} else { ?>
	  	<thead>
		    <tr>
		      <th>Bill No</th>
		      <th>Bill Date</th>
		      <th>Party Name</th>
		      <th>Actions</th>
		    </tr>
	  	</thead>
	  	<tbody>
		  	<?php for($i=0; $i<count($billdetails_lists); $i++) { ?>
		    	<tr>
			     	<td><?php echo $billdetails_lists[$i]->nBillNo?></td>
				  	<td><?php echo $billdetails_lists[$i]->dBillDate?></td>
				  	<td><?php echo $billdetails_lists[$i]->nPartyName?></td>
				 	<td>
				 		<?php echo $al='<a title="Duplicate Bill" target="_blank" href="'. $billdetails_lists[$i]->duplicate_bill .'"><span class="badge badge-success" style="color: #FFFFFF;">Duplicate bill</span></a>';?>
				 		<?php echo $al='<a title="Cancel Bill" target="_blank" href="'. $billdetails_lists[$i]->cancel_bill .'"><span class="badge badge-warning" style="color: #FFFFFF;">Cancel bill</span></a>';?>				 		
				 		<?php if( isset( $billdetails_lists[$i]->delete_bill ) && $billdetails_lists[$i]->delete_bill != '' ) echo $al='<a title="Delete Bill" target="_blank" href="'. $billdetails_lists[$i]->delete_bill .'"><span class="badge badge-error" style="color: #FFFFFF;">Delete bill</span></a>';?>
				 	</td>
				</tr>
			<?php } ?>
		</tbody>
	<?php } ?>	
</table>
</div>
</div>
</div>
</div>
</div>
</div>
  <script type="text/javascript">
  $(function(){
  $("#myTable").tablesorter();
});

</script>  
  <script type="text/javascript">

$('#myTable tr').bind('click', function(e) {
    if ($(this).parent("thead").length == 0){
	    $(e.currentTarget).children('td, th').css('background-color','#7FFFD4');
    }
})
</script>  
