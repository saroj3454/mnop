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

class block_customhomepage extends block_list {
    function init() {
        $this->title = get_string('pluginname', 'block_customhomepage');
        // $this->title = "Learning Plan reminder";
    }

    function get_content () {
        global $USER, $CFG, $DB, $SESSION, $OUTPUT,$COURSE;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass;
        $this->content->items  = array();
        $this->content->icons  = array();
        $this->content->footer = '';
        $spacer = array('height'=>1, 'width'=> 4); 
		$topic = array('height'=>2);
        $subtopic = array('height'=>1, 'width'=> 15);
        $pointearned_content='Home Page ';
         $content="";
         $content.="
         	        

         ";
         $this->content->items[]=$content;
if(is_siteadmin()){
 $topbar_content = $CFG->wwwroot . '/blocks/customhomepage/totalenrolledcourse.php';
 $this->content->items[] = html_writer::tag('a', get_string('totalenrolledcourse', 'block_customhomepage'), array('href' => $topbar_content));
$topbar_content = $CFG->wwwroot . '/blocks/customhomepage/commentupdate.php';
 $this->content->items[] = html_writer::tag('a', get_string('commentupdate', 'block_customhomepage'), array('href' => $topbar_content));

 $topbar_content = $CFG->wwwroot . '/blocks/customhomepage/wordpresssyncconfiguration.php';
 $this->content->items[] = html_writer::tag('a', get_string('wordpress_sync', 'block_customhomepage'), array('href' => $topbar_content));


  $topbar_content = $CFG->wwwroot . '/local/razorpaywebhook/emailconfiguration.php';
 $this->content->items[] = html_writer::tag('a','<i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Webhook Email-id', array('href' => $topbar_content));

 }
 

		       
        
       	return $this->content;
    }

 }


