<?php 
//link the controller to the nav link

$route[FUEL_ROUTE.'bill_details'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'bill_details/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'bill_details'] = 'bill_details';