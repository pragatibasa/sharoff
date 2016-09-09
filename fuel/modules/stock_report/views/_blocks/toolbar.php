<div id="action">
 <div id="actions" class="erpbuttonbar" style="width:100%;">
  <ul>
   <li class="end">
    <div id="dd_my_accounts" style="border:0px solid #ffffff;">
		<select id="party_account_name"  name="party_account_name" style="min-width:200px;" >
			<?php
				echo '<option value="'.Select.'">'.Select."</option>";
        $CI =& get_instance();
        $userdata = $CI->fuel_auth->user_data();
        if(($userdata['super_admin']== 'yes')) {
				  foreach($data as $record) {
            echo '<option value="'.$record->nPartyName.'">'.$record->nPartyName."</option>";
				  }
        } else {
          echo '<option value="'.$chkuser[0]->nPartyName.'">'.$chkuser[0]->nPartyName."</option>";
        }
			?>
		</select>
    </div>
   </li> 
   <li class="end"  style="padding-left:20px;">
 <input class="btn btn-success"  type="button" value="Export to Excel" id="export" onclick="tableToExcel('myTabels', 'Stock Report')"/> &nbsp; &nbsp; &nbsp; </li>   
  </ul>
 </div>
</div>
<div id="msgtext"></div>