<?php 
//link the controller to the nav link

$route[FUEL_ROUTE.'bill_details'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'bill_details/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'bill_details'] = 'bill_details';
$route[FUEL_ROUTE.'bill_details/duplicate_bill'] = 'bill_details/duplicate_bill';
$route[FUEL_ROUTE.'bill_details/cancel_bill'] = 'bill_details/cancel_bill';
$route[FUEL_ROUTE.'bill_details/delete_bill'] = 'bill_details/delete_bill';
$route[FUEL_ROUTE.'bill_details/list_bill_details'] = 'bill_details/list_bill_details';
$route[FUEL_ROUTE.'bill_details/display_search_results'] = 'bill_details/display_search_results';
