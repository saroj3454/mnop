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
//$alluserdata=alluserapidata();	
// foreach ($alluserdata->data as $schoolValue) {
// 	$schooldata=schooldata($schoolValue->data->id);
// 	foreach ($schooldata->data as $apicourseValue) {
// 		groupAction($schoolValue->data->id,$schoolValue->data->name,$apicourseValue->data->id);
// 	}
// }

$allgroupdata=allgroupdata();
	foreach ($allgroupdata as $groupvalue) {
		$alluserdata=alluserapidata();
		foreach ($alluserdata->data as $userValue) {
				if($userValue->data->roles->student->school==$groupvalue->api_groupid){
						$userdata=array();
						$userdata['apischoolid']=$userValue->data->roles->student->school;
						$userdata['apiuserid']=$userValue->data->id;
						$userdata['username']=$userValue->data->roles->student->credentials->district_username;
						$userdata['firstname']=$userValue->data->name->first." ".$userValue->data->name->middle;
						$userdata['lastname']=$userValue->data->name->last;
						$userdata['gender']=$userValue->data->roles->student->gender;
						$userdata['grade']=$userValue->data->roles->student->grade;
						$userdata['email']=$userValue->data->email;
						$userdata['otheremail']=$userValue->data->roles->student->email;
						$userdata['address']=$userValue->data->roles->student->location->address;
						$userdata['city']=$userValue->data->roles->student->location->city;
						$userdata['state']=$userValue->data->roles->student->location->state;
						$userdata['zip']=$userValue->data->roles->student->location->zip;
						$userid=userAction($userdata);

        				enrolledAction($groupvalue->groupid,$groupvalue->api_courseid,$userid);
				
					//print_r($groupvalue->api_groupid,$groupvalue->groupid,$userid,);
        				require_once($CFG->libdir.'/adminlib.php');
					purge_caches();
				
				}

		}
	}


