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
 * @subpackage oauth
 * @copyright  lds
 */




defined('MOODLE_INTERNAL') || die;

if ( $hassiteconfig ){
    $ADMIN->add('root', new admin_category('oauth', get_string('pluginname', 'local_oauth')));
    $ADMIN->add('oauth', new admin_externalpage('oauthsettings', get_string('settings'),
            $CFG->wwwroot.'/admin/settings.php?section=local_oauth', 'local/oauth:manage'));

  $settings = new admin_settingpage('local_oauth', 'SSO Sync');
  $ADMIN->add('localplugins', $settings);
   $settings->add(new admin_setting_configtext('local_oauth/api_key', get_string('api_key', 'local_oauth'),"", "", PARAM_RAW_TRIMMED));
   $settings->add(new admin_setting_configtext('local_oauth/api_secretkey', get_string('api_secretkey', 'local_oauth'),"", "", PARAM_RAW_TRIMMED));
 $settings->add(new admin_setting_configtext('local_oauth/data_api_token', get_string('data_api_token', 'local_oauth'),"", "", PARAM_RAW_TRIMMED));
 $settings->add(new admin_setting_configtext('local_oauth/event_api_token', get_string('event_api_token', 'local_oauth'),"", "", PARAM_RAW_TRIMMED));
}
