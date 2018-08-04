<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['quality_reports'] = array(
		'module_name' => 'QUALITY REPORTS',
		'module_uri' => 'quality_reports',
		'model_name' => 'quality_reports_model',
		'model_location' => 'quality_reports',
		'permission' => 'quality_reports',
		'nav_selected' => 'quality_reports',
		'instructions' => lang('module_instructions', 'quality_reports'),
		/*'default_col' => 'date_added',
		'display_field' => 'id',
		'filters' => array('date_added' => array('default' => date('Y-m-d',time()), 'label' => 'Queue Day', 'type' => 'select')),
		*/
		'item_actions' => array('save', 'view', 'publish', 'activate', 'delete', 'duplicate', 'create', 'others' => array('partywise_register/editCoil' => lang('edit_coil'),'partywise_register/cuttingInstruction' => lang('cutting_instruction')))
	);
