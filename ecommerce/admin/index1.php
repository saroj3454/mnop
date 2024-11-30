<?php
require_once(dirname(__FILE__).'/../../../config.php');
$context = context_system::instance();
$PAGE->set_pagelayout("base");
$PAGE->requires->jquery();
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/ecommerce/js/admin.js'));

$templateData = [
    'output' => $OUTPUT,
    'output_header' => $OUTPUT->header(),
    'output_footer' => $OUTPUT->footer(),
    'output_blockregion_content' => $OUTPUT->custom_block_region('content')
];

echo $OUTPUT->render_from_template('local_ecommerce/admin/index', $templateData);
