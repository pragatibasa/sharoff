<?php
//link the controller to the nav link

$route[FUEL_ROUTE.'quality_reports'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'quality_reports/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'quality_reports'] = 'quality_reports';
$route[FUEL_ROUTE.'quality_reports/create_report'] = 'quality_reports/create_report';
$route[FUEL_ROUTE.'quality_reports/insert_report'] = 'quality_reports/insert_report';
$route[FUEL_ROUTE.'quality_reports/list_quality_reports'] = 'quality_reports/list_quality_reports';
$route[FUEL_ROUTE.'quality_reports/view_slit_report'] = 'quality_reports/view_slit_report';
$route[FUEL_ROUTE.'quality_reports/view_quality_pdf'] = 'quality_reports/view_quality_pdf';
