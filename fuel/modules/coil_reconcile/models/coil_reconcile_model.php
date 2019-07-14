<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/reports/config/reports_constants.php');
require_once(APPPATH.'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH.'helpers/tcpdf/tcpdf.php');

class coil_reconcile_model extends Base_module_model {
  function __construct() {
      parent::__construct('aspen_tblinwardentry');// table name
  }
}
