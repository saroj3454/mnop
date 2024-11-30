<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../config.php');
require_once($CFG->dirroot . '/my/lib.php');
redirect_if_major_upgrade_required();

$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
require_login();

$pagetitle = $header = 'Product Type';
$pagettype = 'producttype-manage';
$url = '/local/ecommerce/producttype/index.php';

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}
$strmymoodle = get_string('myhome');

$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = $header ?: "PORTAL TEMPLATE";
$pagetitle = $pagetitle ?: $strmymoodle;
$pagettype = $pagettype ?: 'my-portal';
$url = $url ?: '/local/ecommerce/producttype/index.php';

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page(null, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

// Start setting up the page
$params = array();
$PAGE->set_url($url, $params);
$PAGE->set_pagelayout('admin');
$PAGE->set_pagetype($pagettype);
$PAGE->blocks->add_region('content');
$PAGE->blocks->set_default_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
$PAGE->add_body_class('elearnified-portal-dashboard');
/**
 * pass data to mustache template
 */
$templateData = [
    'output' => $OUTPUT,
    'output_header' => $OUTPUT->header(),
    'output_footer' => $OUTPUT->footer(),
    'output_blockregion_content' => $OUTPUT->custom_block_region('content')
];

echo $OUTPUT->render_from_template('local_ecommerce/default', $templateData);
