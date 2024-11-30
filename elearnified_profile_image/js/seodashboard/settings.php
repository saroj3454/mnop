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
 * @package   local_seodashboard
 * @copyright Sarojsahoo41@gmail.com / 08658022345
 * @author    Derick Turner
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if(has_capability('local/seodashboard:seoadmindashboard',context_system::instance()) && has_capability('local/seodashboard:seoadmindashboard_view',context_system::instance())){
$ADMIN->add('courses', new admin_externalpage('seodashboard', 'Seo Dashboard',
        new moodle_url("/local/seodashboard/index.php")));
}