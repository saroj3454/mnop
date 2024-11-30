<?php require_once('../../config.php');
global $CFG, $PAGE, $OUTPUT, $USER;
require_once($CFG->dirroot."/local/ecommerce/lib.php");
$token = optional_param('token', false, PARAM_ALPHANUM);
$PAGE->set_url('/local/ecommerce/forgot_password.php');
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
// if (empty($token)){
// 	redirect();
// }
// if you are logged in then you shouldn't be here!
if (isloggedin() and !isguestuser()) {
    redirect(new moodle_url('/',['redirect' => 0]));
}
echo $OUTPUT->header();
local_ecommerce_process_password_set($token);
echo $OUTPUT->footer();
