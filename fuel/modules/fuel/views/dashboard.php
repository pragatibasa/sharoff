<?php if ($change_pwd){ ?>
<div class="jqmWindow jqmWindowShow warning" id="change_pwd_notification">
	<p><?=lang('warn_change_default_pwd', $this->config->item('default_pwd', 'fuel'))?></p>

	<div class="buttonbar" id="yes_no_modal" style="width: 400px;">
		<ul>
			<li class="end"><a href="#" class="ico ico_no jqmClose" id="change_pwd_cancel"><?=lang('dashboard_change_pwd_later')?></a></li>
			<li class="end"><a href="<?=fuel_url('my_profile/edit/')?>" class="ico ico_yes" id="change_pwd_go"><?=lang('dashboard_change_pwd')?></a></li>
		</ul>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>

<div id="main_top_panel">
	<h2 class="ico ico_tools_dashboard"><?=lang('section_dashboard')?></h2>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  $(document).ready(function() {
      	  $("#jDashTop").jDashboard({ columns: 2 });
      	  $("#jDash").jDashboard({ columns: 2 });
      });
</script>
<div align="center">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td rowspan="2">&nbsp;</td>
<td>
	<?php if($super_admin == 'yes') { ?>
		<p style="margin-left:16px;">Quick Links : <a href="<?=fuel_url('inward')?>">Inward Entry</a> &nbsp; | &nbsp; <a href="<?=fuel_url('partywise_register')?>">Partywise register</a> &nbsp; | &nbsp; <a href="<?=fuel_url('workin_progress')?>">Workin progress</a> &nbsp; | &nbsp; <a href="<?=fuel_url('bill_details')?>">Bill details</a></p>
	<?php } else { ?>
		<p style="margin-left:16px;">Quick Links : <a href="<?=fuel_url('partywise_register')?>">Partywise register</a> &nbsp; | &nbsp; <a href="<?=fuel_url('bill_details')?>">Bill details</a></p>
	<?php } ?> 
</td>
<td rowspan="2">&nbsp;</td>
</tr>
<tr>
<td width="910px" align="center">
<div id="jDashTop">
	<div class="jdash-item">
		<h1 class="jdash-head">Recent Inward Entry</h1>
		<div class="jdash-body">
			<div>
			<table id="data_table" class="data" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>Coilnumber</th>
				<th>InvoiceNo</th>
				<th>Invoice Date</th>
				<th>Status</th>
			</tr>
			</thead>
			<tbody>
			<?php if(!empty($recentinward)){ ?>
			<?php foreach($recentinward as $rinwardt){ ?>
			<tr>
				<td><?php echo $rinwardt->vIRnumber; ?></td>
				<td><?php echo $rinwardt->vInvoiceNo; ?></td>
				<td><?php echo $rinwardt->dInvoiceDate; ?></td>
				<td><?php echo $rinwardt->vStatus; ?></td>
			</tr>
			<?php } 
			 } ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>
	
	<!--<div class="jdash-item">
		<h1 class="jdash-head">Recent Workin Progress</h1>
		<div class="jdash-body">
			<div>
			<table id="data_table" class="data" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>Coilnumber</th>
				<th>Partyname</th>
				<th>SlittingDate</th>
				<th>CuttingDate</th>
				<th>RecoilingDate</th>
			</tr>
			</thead>
			<tbody>
			<?php// if(!empty($recentwip)){ ?>
			<?php //foreach($recentwip as $rwip){ ?>
			<tr>
				<td><?php //echo $rwip->coilnumber; ?></td>
				<td><?php// echo $rwip->partyname; ?></td>
				<td><?php //echo $rwip->cuttingdate; ?></td>
				<td><?php //echo $rwip->recoilingdate; ?></td>
				<td><?php// echo $rwip->slittingdate; ?></td>
			</tr>
			<?php //} ?>
			<? //}else { ?>
			<tr>
				<td colspan="4">
					<b>No Record Present!</b>					
					<div>
						<a class="ico ico_workin_progress" href="<?=fuel_url('workin_progress')?>">Click here to check workin progress.</a>
					</div>
				</td>
			</tr>
			<?php// } ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>-->
	
	<!--<div class="jdash-item">
		<h1 class="jdash-head">Bill Description</h1>
		<div class="jdash-body">
			<div>
			<table id="data_table" class="data" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>Billno</th>
				<th>Coil Number</th>
				<th>Bill Date</th>
				<th>Lorry Number</th>
			</tr>
			</thead>
			<tbody>
			<?php// if(!empty($recentbilldes)){ ?>
			<?php //foreach($recentbilldes as $rbilldes){ ?>
			<tr>
				<td><?php //echo $rbilldes->Billno; ?></td>
				<td><?php// echo $rbilldes->CoilNumber; ?></td>
				<td><?php// echo $rbilldes->BillDate; ?></td>
				<td><?php //echo $rbilldes->WeightAmount; ?></td>
			</tr>
			<?php// } ?>
			<? //}else { ?>
			<tr>
				<td colspan="4">
					<b>No Record Present!</b>					
					<div>
						<a class="ico ico_bill_description" href="<?=fuel_url('bill_description')?>">Click here to check bill description.</a>
					</div>
				</td>
			</tr>
			<?php// } ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>-->
	
	<div class="jdash-item">
		<h1 class="jdash-head">Tax Details</h1>
		<div class="jdash-body">
			<div>
			<table id="data_table" class="data" cellspacing="0" cellpadding="0">
			<thead>
			<tr> 
				<th>Tax Id</th>
				<th>Type Of Tax</th>
				<th>Percentage</th>
			</tr>
			</thead>
			<tbody>
			<?php if(!empty($recenttax)){ ?>
			<?php foreach($recenttax as $rtax){ ?>
			

			<tr>
				<td><?php echo $rtax->taxid; ?></td>
				<td><?php echo $rtax->type; ?></td>
				<td><?php echo $rtax->percentage; ?></td>
			</tr>
			<?php } 
			}else { ?>
			<tr>
				<td colspan="4">
					<b>No Record Present!</b>					
					<div>
						<a class="ico ico_inventory_billing" href="<?=fuel_url('tax_details')?>">Click here to create new tax details.</a>
					</div>
				</td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>				
</div>

</td>
</tr>
</table>
</div>
