<?php
// included in the main config/MY_fuel_modules.php


$config['modules']['search'] = array(
		'module_name' => 'SEARCH',
		'module_uri' => 'search',
		'model_name' => 'search_model',
		'model_location' => 'search',
		'permission' => 'search',
		'nav_selected' => 'search',
		'instructions' => lang('module_instructions', 'search')
);		
