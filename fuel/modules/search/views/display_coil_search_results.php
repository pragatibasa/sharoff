<?php  include_once(SEARCH_PATH.'views/layout.php'); ?>
	<div style="width:100%;float:left;">
		<div style="width:100%;float:left;">
			<fieldset>
				<legend><?=lang('coil_details')?></legend>
				<div>
					<table>
						<tr>
							<td><label><?=lang('coil_number')?></label></td>
							<td><label>:<?=$coilDetails->vIRnumber?></label></td>
						</tr>
						<tr>
							<td><label><?=lang('coil_thickness')?></label></td>
							<td><label>:<?=$coilDetails->fThickness?></label></td>
						</tr>
						<tr>
							<td><label><?=lang('coil_width')?></label></td>
							<td><label>:<?=$coilDetails->fWidth?></label></td>
						</tr>
						<tr>
							<td><label><?=lang('coil_weight')?></label></td>
							<td><label>:<?=$coilDetails->fQuantity?></label></td>
						</tr>
						<tr>
							<td><label><?=lang('material_type')?></label></td>
							<td><label>:<?=$coilDetails->vDescription?></label></td>
						</tr>
						<tr>
							<td><label>Process</label></td>
							<td><label>:<?=$coilDetails->vprocess?></label></td>
						</tr>
					</table>
				</div>
			</fieldset>
		</div>
	</div>
	<div>
		<fieldset>
			<legend><?=lang('bill_details')?></legend>
			<table border="1" width="100%">
				<tr>
					<th>Bill Number</th>
					<th>Bundles Billed</th>
					<th>Billed Weight</th>
					<th>Bill Date</th>
					<th>Bill Amount</th>
				</tr>
				<tr>
					<?php
					foreach($billDetails as $key => $billDetail) { ?>
					 	<tr align="center">
							<td><a href="<?=site_url('bill_details/duplicate_bill')?>/?billno=<?=$billDetail->nBillNo?>"><?=$billDetail->nBillNo?></a></td>
							<td><?=$billDetail->bundleCount?></td>
							<td><?=$billDetail->fTotalWeight?></td>
							<td><?=$billDetail->dBillDate?></td>
							<td><?=$billDetail->fGrantTotal?> Rs.</td>
						</tr>
					<?php } ?>
				</tr>
			</table>
		</fieldset>
	</div>
	<div>
		<fieldset>
			<legend><?=lang('bundle_details')?></legend>
			<table border="1" width="100%">
				<tr>
					<?php foreach($bundleCols as $key => $bundleCol) { ?>
						<th><?=$key?></th>				
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