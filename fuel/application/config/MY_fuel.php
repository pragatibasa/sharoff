<?php
// IMPORTANT: for a complete list of fuel configurations, go to the modules/fuel/config/fuel.php file

// path to the fuel admin from the web base directory... MUST HAVE TRAILING SLASH!
$config['fuel_path'] = 'fuel/';

// the name to be displayed on the top left of the admin
$config['site_name'] = 'HOODUKU ERP';

// options are cms, views, and auto. cms pulls views and variables from the database,
// views mode pulls views from the views folder and variables from the _variables folder.
// the auto option will first check the database for a page and if it doesn't exist or is not published, it will then check for a corresponding view file.
$config['fuel_mode'] = 'AUTO';

// used for system emails.
$config['domain'] = '';

// default password to alert against
$config['default_pwd'] = 'admin';

// specifies which modules are allowed to be used in the fuel admin
$config['modules_allowed'] = array(
	//'user_guide',
	//'blog',
	'backup',
	'inward_entry',
	'stock_report',
	'customer_summary',
	'average_party',
	'total_average',
	'customer_billing',
	'customer_inward',
	'customer_outward',
	'inventory_stock',
	'inward',
	'inward_entry_create',
	'partywise_register',
	'weigh_updation',
	'group_access',
	'aged_payable',
	'workin_progress',
	'inventory_valuation',
	'slitting_thickness','recoiling_thickness',
	'billing_statement',
	'finish_task',
	'billing_instruction',
	'billing_inventory',
	'billing',
	'cutting_instruction',
	//'rate_details',
	'partyname_details',
	'coil_details',
//	'cronjobs',
	'slitting_instruction',
	'transfer_instruction',
//	'bill_descriptions',
	'recoiling',
	'rate_details_width',
	'rate_details_length',
	'rate_details_weight',
	'rate_details_thickness',
	'tax_details',
	'billing_summary',
	'factory_material',
	'company_details',
	'inventory_tax_details',
	'rate_direct_billing',
	'bill_details',
	'search',
	'quality_reports',
	'coil_labels',
	'coil_reconcile',
	'vehicle_despatch',
	);
//Configuration ASPEN PANEL
$config['nav']['Sharoff Steel']=array(
	//'inward_entry/create' => 'Create Inward Entry',
//	'inward_entry' => lang('module_inward_entry'),
//	'workin_progress' => lang('workin_progress'),
//	'reports' => lang('reports'),
	);

$config['nav']['modules'] = array();


$config['nav']['Sharoff_Steel'] = array('inward_entry' => 'Inward Register',
										'inward' => 'Inward',
										'partywise_register' => 'Partywise Register',
										'workin_progress' => 'Workin Progress',
										'weigh_updation' => 'Weigh Bridge Outward Updation'
);


$config['nav']['Master'] = array('rate_details_width' => 'Rate Details Width',
          'rate_details_length' => 'Rate Details Length',
          'rate_details_weight' => 'Rate Details Weight',
          'rate_details_thickness' => 'Rate Details Thickness',
          'partyname_details' => 'Party Name Details',
          'material_description' => 'Material Description',
          'tax_details' => 'Tax Details',
          'company_details' => 'Company Details',
      //   'bill_descriptions'=> 'Bill Descriptions',
          'slitting_thickness'=> ' Slit Thickness',
          'recoiling_thickness'=> 'Recoiling Thickness',
          'rate_direct_billing' => ' Rate for Direct Bill',

);



$config['nav']['Reports'] = array('stock_report' => 'Customer Stock Report ',
										'customer_inward' => 'Customer Inward Report',
										'customer_billing' => 'Customer Billing Report',
										'customer_outward' => 'Customer Outward Report',
										'coil_reconcile' => 'Coil Reconcilliation Report',
										'vehicle_despatch' => 'Vehicle wise despatch Report',
										//'customer_summary' => 'Customer Summary',
										'factory_material' => 'Factory Material Movement ',
										//'partywise_report_holding' => 'Partywise Average ',
										'total_average' => 'Total party holding',
										'average_party' => 'Partywise Average Holding',
										'bill_details' => 'Bill Details',
										'quality_reports' => 'Quality Reports',
										'coil_labels' => 'Coil Labels'
);

//'support'
$config['apps_view'] = array('site', 'apps', 'Accounts', 'Amazon',);
$config['settings_view'] = array('tools', 'manage', 'Sharoff_Steel',  'Master',  'Reports');



// whether the admin backend is enabled
$config['admin_enabled'] = TRUE;

// will auto search view files.
// If the URI is about/history and the about/history view does not exist but about does, it will render the about page
$config['auto_search_views'] = TRUE;

// max upload files size for assets
$config['assets_upload_max_size']	= 5000;

// max width for asset image uploads
$config['assets_upload_max_width']  = 1024;

// max height for asset image uploads
$config['assets_upload_max_height']  = 768;

$config['assets_excluded_dirs'] = array(
	'js',
	'css',
	'cache',
	'swf',
	);

// text editor settings  (options are markitup or ckeditor)
$config['text_editor'] = 'markitup';

// ck editor specific settings
$config['ck_editor_settings'] = array(
	'toolbar' => array(
			//array('Source'),
			array('Bold','Italic','Strike'),
			array('Format'),
			array('Image','HorizontalRule'),
			array('NumberedList','BulletedList'),
			array('Link','Unlink'),
			array('Undo','Redo','RemoveFormat'),
			array('Preview'),
			array('Maximize'),
		),
	'contentsCss' => WEB_PATH.'assets/css/main.css',
	'htmlEncodeOutput' => FALSE,
	'entities' => FALSE,
	'bodyClass' => 'ckeditor',
	'toolbarCanCollapse' => FALSE,
);

$config['fuel_javascript'] = array(
	'jquery/plugins/date',
	'jquery/plugins/jquery.datePicker',
	'jquery/plugins/jquery.fillin',
	'jquery/plugins/picker',
	'jquery/plugins/picker-1',
	'jquery/plugins/jquery.easing',
	'jquery/plugins/jquery.bgiframe',
	'jquery/plugins/jquery.tooltip',
	'jquery/plugins/jquery.scrollTo-min',
	'jquery/plugins/jqModal',
	'jquery/plugins/jquery.checksave',
	'jquery/plugins/jquery.form',
	'jquery/plugins/jquery.treeview.min',
	'jquery/plugins/jquery.hotkeys',
	'jquery/plugins/jquery.cookie',
	'jquery/plugins/jquery.fillin',
	'jquery/plugins/jquery.selso',
	'jquery/plugins/jquery-ui-1.8.4.custom.min',
	'jquery/plugins/jquery.disable.text.select.pack',
	'jquery/plugins/jquery.supercomboselect',
	'jquery/plugins/plugins',
	'jquery/plugins/jquery.tablednd.js',
	'jquery/plugins/jquery.supercomboselect',
	'jquery/plugins/plugins',
	'editors/markitup/jquery.markitup.pack',
	'editors/markitup/jquery.markitup.set',
	'editors/ckeditor/ckeditor.js',
	'fuel/linked_field_formatters.js',
	'custom/custom.js',
	'bootstrap/bootstrap.min.js',
	'bootstrap/bootstrap-lightbox.min.js',
	'bootstrap/bootstrap_override.min.js',
	'bootstrap/bootstrap-editable-inline.min.js',
	'bootstrap/bootstrap-editable.min.js',
	'bootstrap/bootbox.min.js',
	'bootstrap/chosen.jquery.min.js',
	'bootstrap/bootstrap-select.min.js',
	'bootstrap/bootstrap-select.js',
);

//Configuration ISPAT PANEL

/* End of file MY_fuel.php */
/* Location: ./application/config/MY_fuel.php */
