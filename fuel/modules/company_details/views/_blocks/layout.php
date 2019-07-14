<div id="innerpanel"> 
&nbsp;
&nbsp;
<fieldset>
<legend><strong>Company Details</strong><br/></legend>
&nbsp;<form id="cisave" method="post" name="companyDetails" action="">

    <div>
		<table cellpadding="0" cellspacing="10" border="0">
			<tr>
				<td>
					<label>The name of the company<span class="required">*</span></label>
				</td>
				<td>  
					<input id="cname" type="text" name="cname" value=""/>
				</td>
			</tr>
			<tr>
				<td>
					<label>The default name or identifier to use for all receivable operations.<span class="required">*</span></label>
				</td> 
				<td>
					<input id="ide_receive" name="ide_receive" type="text"/> 
				</td>
			</tr>	
			<tr>
				<td>   
					<label>The default name or identifier to use for all payable operations.<span class="required">*</span></label>
				</td>
				<td>
					<input id="ide_payable" name="ide_payable" type="text"/> 
							
				</td>
			</tr>
			<tr>
				<td>
					<label>Head office address<span class="required">*</span></label>
				</td>
                <td><textarea id="addr1" name="headOffice" type="text"></textarea>&nbsp;&nbsp;<span>Info : Please enter the exact head office address to be displayed on the bills</span></td>
			</tr>
			<tr>
				<td>   
					<label>Branch office address<span class="required">*</span></label>
				</td>
				<td><textarea id="addr2" name="branchOffice" type="text"></textarea>&nbsp;&nbsp;<span>Info : Please enter the exact branch office address to be displayed on the bills</span></td>
			</tr>
			<tr>
				<td>
					<label>Enter the general company email address</label>
				</td>
				<td> 
					<input id="email" name="email"  type="text" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Enter GST registration number</label>
				</td>
				<td>
					<input id="duty_no" name="gstNumber"  type="text" />
				</td>
			</tr>
		</table>
	</div>
        <div class="pad-10">
            <input id="newsize" class="btn btn-primary" type="button" value="Company Registry" onClick="inwardregistrybutton();"/> &nbsp; &nbsp; &nbsp;
            <input id="newsize" class="btn btn-success" type="button" value="Save" onClick="functionsave(); "/> &nbsp; &nbsp; &nbsp;
        </div>
    </form>
</fieldset>	

</div>

<script language="javascript" type="text/javascript">

function inwardregistrybutton(id) {
	$.ajax({  
		type: "POST",  
		success: function(){  
	    	setTimeout("location.href='<?= site_url('fuel/company_details_entry'); ?>'", 100);
		}
    });
}
                     
function functionsave() {
    var data = $('form').serialize();
    console.log(data);
    $.ajax({
       type: "POST",
       url : "<?php echo fuel_url('company_details/savedetails');?>/",
       data: data,
       success: function(msg) {
            alert("Company details saved successfully");
       }
    });
	
}
</script>
