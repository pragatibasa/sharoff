<?php
//link the controller to the nav link

$route[FUEL_ROUTE.'coil_reconcile'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'coil_reconcile/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'coil_reconcile'] = 'coil_reconcile';
