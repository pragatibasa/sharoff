<div id="action">
 <div id="actions" class="erpbuttonbar">
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
  </ul>
 </div>
</div>
<div id="msgtext"></div>