<?php
// This file is part of the Contact Form plugin for Moodle - http://moodle.org/
//
// Contact Form is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Contact Form is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Contact Form.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_oauth
 * @category    admin
 * @copyright   2022 lds
 * @license      lds
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/oauth/lib.php');

$alldistrict=alldistrict();
foreach ($alldistrict as $districtValue) {
// $value->districtid;
	$get_course=get_courseApidata();
	foreach ($get_course->data as $value) {
		if($value->data->district==$districtValue->district_id){
	coursesAction($value->data->name,$value->data->id,$districtValue->district_id,$districtValue->cat_id);
		}
	
	}
}

