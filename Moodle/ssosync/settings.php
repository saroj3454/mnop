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

/**
 * General plugin functions.
 *
 * @package    local
 * @subpackage ssosync
 * @copyright  lds
 */




defined('MOODLE_INTERNAL') || die;

if ( $hassiteconfig ){
    $ADMIN->add('root', new admin_category('ssosync', get_string('pluginname', 'local_ssosync')));
    $ADMIN->add('ssosync', new admin_externalpage('ssosyncsettings', get_string('settings'),
            $CFG->wwwroot.'/admin/settings.php?section=local_ssosync', 'local/ssosync:manage'));

  $settings = new admin_settingpage('local_ssosync', 'SSO Sync');
  $ADMIN->add('localplugins', $settings);
   $settings->add(new admin_setting_configtext('local_ssosync/clientid', get_string('clientid', 'local_ssosync'),"", "admin", PARAM_RAW_TRIMMED));
   $settings->add(new admin_setting_configtext('local_ssosync/secretkey', get_string('secretkey', 'local_ssosync'),"", "P@ssw0rd", PARAM_RAW_TRIMMED));
   $settings->add(new admin_setting_configtext('local_ssosync/wordpressurl', get_string('wordpressurl', 'local_ssosync'),"", "Ex: https://webdev.digiface.org/", PARAM_RAW_TRIMMED));
}
