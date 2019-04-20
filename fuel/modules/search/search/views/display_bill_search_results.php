<?php  include_once(SEARCH_PATH.'views/layout.php'); ?>
	<fieldset style="width:100%;">
		<legend><?=lang('bill_details')?></legend>
			<table cellpadding="0" cellspacing="15" border="0">
				<tr>
						<td><label><?=lang('bill_number')?></label></td>
						<td><label>: <a href="<?=site_url('bill_details/duplicate_bill')?>/?billno=<?=$billDetails->nBillNo?>"><?=$billDetails->nBillNo?></a></label></td>
					</tr>
					<tr>
						<td><label><?=lang('billed_weight')?></label></td>
						<td><label>: <?=$billDetails->billedweight?></label></td>
					</tr>
					<tr>
						<td><label><?=lang('lorry_number')?></label></td>
						<td><label>: <?=$billDetails->vOutLorryNo?></label></td>
					</tr>
					<tr>
						<td><label>Rate</label></td>
						<td><label>: <?=$billDetails->fWeightAmount?></label></td>
					</tr>
					<tr>
						<td><label><?=lang('bill_type')?></label></td>
						<td><label>: <?=$billDetails->vBillType?></label></td>
					</tr>
					<tr>
						<td><label>Total Bill Amount</label></td>
						<td><label>: <?=$billDetails->fGrantTotal?> Rs</label></td>
					</tr>
					<tr>
						<td><label>Billing Address</label></td>
						<td><label>: <?=$billDetails->tBillingAddress?></label></td>
					</tr>
				</table>
			
		</fieldset>
	
		<fieldset>
			<legend><?=lang('coil_details')?></legend>
			<table>
				<tr>
					<td><label><?=lang('coil_number')?></label></td>
					<td><label>: <?=$billDetails->vIRnumber?></label></td>
				</tr>
				<tr>
					<td><label><?=lang('coil_thickness')?></label></td>
					<td><label>:  <?=$billDetails->fThickness?></label></td>
				</tr>
				<tr>
					<td><label><?=lang('coil_width')?></label></td>
					<td><label>: <?=$billDetails->fWidth?></label></td>
				</tr>
				<tr>
					<td><label><?=lang('coil_weight')?></label></td>
					<td><label>: <?=$billDetails->fQuantity?></label></td>
				</tr>
				<tr>
					<td><label><?=lang('material_type')?></label></td>
					<td><label>: <?=$billDetails->vDescription?></label></td>
				</tr>
			</table>
		</fieldset>
	
	<div>
		<br>
		<fieldset>
			<legend><?=lang('billed_bundle_details')?></legend>
			<table border="1" width="100%">
				<tr>
					<?php foreach($bundleCols as $key => $bundleCol) { ?>
						<th width="10%"><?=$key?></th>				
					<?php } ?>
				</tr>
				<tr>
					<?php
					foreach($bundleBalanceDetails as $key => $bundleBalanceDetail) { ?>
						<tr align="center">
							<?php foreach($bundleCols as $key => $bundleCol) { ?>
								<td><?=$bundleBalanceDetail->$bundleCol?></td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tr>
			</table>
		</fieldset>
	</div>
</div>