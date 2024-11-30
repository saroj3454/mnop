<?php
require_once(dirname(__FILE__).'/../../../config.php');
require_once($CFG->dirroot.'/local/ecommerce/classes/controller/productController.php');
require_login();
if(!is_siteadmin()){
  	redirect($CFG->wwwroot, "You don't have required permission to access this page", null, \core\output\notification::NOTIFY_WARNING);
}
$qs  = $_SERVER['QUERY_STRING'];
$pageurl = new moodle_url("/local/ecommerce/admin/index.php?".$qs);

$context = context_system::instance();
$PAGE->requires->jquery();
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/ecommerce/js/admin.js'));
$PAGE->set_pagelayout("base");
$PAGE->set_url('/local/ecommerce/index.php');
$products = new productController();
$products->browse($pageurl);