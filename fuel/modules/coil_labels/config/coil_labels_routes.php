<?php 
//link the controller to the nav link

$route[FUEL_ROUTE.'coil_labels'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'coil_labels/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'coil_labels'] = 'coil_labels';
$route[FUEL_ROUTE.'coil_labels/list_coil_labels'] = 'coil_labels/list_coil_labels';
$route[FUEL_ROUTE.'coil_labels/getBundleDetails'] = 'coil_labels/getBundleDetails';
$route[FUEL_ROUTE.'coil_labels/printSinglePrnFile'] = 'coil_labels/printSinglePrnFile';
$route[FUEL_ROUTE.'coil_labels/printAllPrnFiles'] = 'coil_labels/printAllPrnFiles';

