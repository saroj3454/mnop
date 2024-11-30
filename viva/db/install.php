<?php
// This file is part of Assignement module
//
// this file lib file using viva field set
/**
 * Plugin function  are defined here.
 *
 * @package     assign_submission_viva
 * @category    Assignement install file
 * @copyright   2023 Andrew Robinson
 * @license     fullversion assign_submission_viva
 */

defined('MOODLE_INTERNAL') || die();
function xmldb_assignsubmission_viva_install() {
        global $CFG;
        require_once($CFG->dirroot . '/mod/assign/adminlib.php');

        $pluginmanager = new assign_plugin_manager('assignsubmission');
        $pluginmanager->move_plugin('viva', 'up');
        $pluginmanager->move_plugin('viva', 'up');
        $pluginmanager->move_plugin('viva', 'up');

        return true;
}