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
namespace local_courses;
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
defined('MOODLE_INTERNAL') || die();

class catalogue {
    
    /**
     * Returns the total of courses count.
     *
     * @return int
     * @throws \dml_exception
     */
    public function get_totalcourses() {
        global $DB;

        return $totalcourses = $DB->count_records('course');
    }

    public function get_check_coursecatageory($catagoryid = null){
        global $DB, $CFG, $USER;
        if (!is_null($catagoryid)) {
            $sql = 'SELECT * FROM {course_categories} WHERE coursecount >= 0 AND visible = 1 and id = '.$catagoryid;
        }else{
            $sql = 'SELECT * FROM {course_categories} WHERE coursecount >= 0 AND visible = 1';
        }
        if ($DB->record_exists_sql($sql, array())) {
            return true;
        }else{
            return false;
        }
    }
    public function get_coursecatageory(){
        global $DB, $CFG, $USER, $PAGE, $OUTPUT;

        $template = new stdClass();

        if ($this->get_check_coursecatageory()) {
            $template->catagorycount = $this->get_check_coursecatageory();
            $template->catstring = get_string('catagories', 'local_courses');
            $template->coursecatagories = html_writer::tag('ul', html_writer::tag('li', 
                    html_writer::tag('a',' All Course', array('href' => $CFG->wwwroot.'/local/catalogue/index.php')), 
                    array()).$this->local_catalog_getcategory(0,0) , 
                array('class' => 'maincoursecatagory'));
            /* get all course catagory */
           return $OUTPUT->render_from_template('local_courses/catacategory', $template);
        }else{
            return array();
        }
    }

    public function local_catalog_getcategory($parent, $level) {
        global $DB, $USER, $CFG; 
        $localcat = "";
        $localcat = "<ul class=\"list-group ".(($level !=0)?'collapse':'')."\" id=\"catlist".$parent."\">";
        $level++;
        $categories = $DB->get_records("course_categories", array("parent"=>$parent, "visible"=>1));
        foreach ($categories as $key => $cat) {

            $localcat .=  "<li><a href='".$CFG->wwwroot."/local/catalogue/index.php?categoryid=".$cat->id."'> $cat->name </a> ";
           if($level < 4){
                $subcategory = $this->local_catalog_getcategory($cat->id, $level);
                if (!empty($subcategory)) {
                    $localcat .="<i class=\"fa fa-caret-down\" aria-hidden=\"true\" data-toggle=\"collapse\" data-target=\"#catlist".$cat->id."\"></i>";
                    $localcat .=$subcategory;
                }
            }
            $localcat .='</li>';
        }
        $localcat .= "</ul>";
        return $localcat;
    }

    public function local_courses_courses($param = null){
        global $DB, $CFG, $OUTPUT;
        $template = new stdClass();
        if ($this->get_totalcourses() > 0) {
            $template->courselist =  array_values($this->getcourses($param)); 
            return $OUTPUT->render_from_template('local_courses/cataloguecourse', $template);
        }else{
            /* No course available */
        }
    }
    
    public function getcourses($param=null){
        global $DB, $CFG;
        if (!is_null($param)) {
          $categoryid = $param['categoryid'];
          if (self::get_check_coursecatageory($categoryid)) {
                $allcourses = $DB->get_records_sql('SELECT * FROM {course} WHERE category = ? AND visible = 1', [$categoryid]);        
          }else{
               $allcourses = $DB->get_records_sql('SELECT * FROM {course} WHERE category != 0 AND visible = 1');             
          }
        }else{
               $allcourses = $DB->get_records_sql('SELECT * FROM {course} WHERE category != 0 AND visible = 1');
        }
        foreach ($allcourses as $course) {
           $course->courseimage = $this->course_image($course->id);
           $course->catagoryname = $this->catagoryname($course->category);
           $course->coursedetails = new moodle_url('/local/catalogue/index.php', array('courseid' => $course->id));
        }
        return $allcourses;
    }

    public function course_image($courseid) {
        global $DB, $CFG;
        $courserecord = $DB->get_record('course', array('id' => $courseid));
        $course = new core_course_list_element($courserecord);
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
            if ($isimage) {
                return $url;
            } else {
                return $CFG->wwwroot.'/local/catalogue/pix/noimage.png';
            }
        }
    }

    public function catagoryname($catagoryid){
        global $DB;
        $catagories = $DB->get_record("course_categories", array('id' =>$catagoryid));
        return $catagories->name;
    } 

    /**
     * Returns the course catalog details here to local_courses/cataloguecoursedetails template .
     *
     * @return int
     * @throws \dml_exception
     */

    public function local_courses_course_details($courseid = null){
        global $OUTPUT, $USER, $CFG;
        $user = null;
        if (!is_null($courseid)) {
           $course = $courseid['courseid'];
        }
        if (isloggedin()) {
            $user = $USER;
        }
        $template = new stdClass();
        $template->courseid = $course;
        if (self::course_image($course)) {
            $imageaddress = self::course_image($course);
        $template->courseimage = html_writer::start_tag('div', array('class' => 'courseimage' ,  'style'=>"background-image: url('$imageaddress');" )).html_writer::end_tag('div') ;
        }
        $template->logintoken =  \core\session\manager::get_login_token();
        $template->coursedetails = self::getcoursedetails($courseid);
        $template->is_enrolled = self::checkenrol($courseid); 
        $template->hasinstructor = (count(self::local_courses_instuctors_details($course)) > 0 ? true : false);
        $template->instructor = array_values(self::local_courses_instuctors_details($course));
        $template->customdata = self::local_custom_field_data($courseid);
        $template->courseduration = self::courseduration($courseid);
        $template->certficate = self::certficate($course);
        $template->sectiondetails = self::courseSectionsDetails($course);
        $template->isloggedin =  (!is_null($user) ? true : false);
        $template->userdetails = $user;
        $template->courselink = $CFG->wwwroot.'/course/view.php?id='.$course;

        if (count(self::courseSectionsDetails($course)) > 0) {
        $template->hassections = TRUE;
        }else{
        $template->hassections = FALSE;    
        }
        
        return $OUTPUT->render_from_template('local_courses/cataloguecoursedetails', $template);   
    }

    public static function getcoursedetails($param = null){
            global $DB;
            if (!is_null($param)) {
              $courseid = $param['courseid'];
              $course = $DB->get_record_sql('SELECT * FROM {course} WHERE category != 0 AND visible = 1 AND id = ?', [$courseid]);
            return $course;
        }
    }

    /* Function to get the instructor details */

    public function local_courses_instuctors_details($courseid = null){
        global $DB;
        $instructors = array();
        $show_users = [3, 4];
        if (!is_null($courseid)) {
            $coursecontext = context_course::instance($courseid);
            if (!empty($coursecontext)) { 
                $sql =  "SELECT * FROM {role_assignments} WHERE contextid = $coursecontext->id AND roleid IN (".implode(',',$show_users).")";
                $role_assignments =  $DB->get_records_sql($sql, array());
                if (!empty($role_assignments)) {
                    foreach ($role_assignments as  $teachers) {
                        if ($DB->record_exists('user', array('id' => $teachers->userid, 'deleted' => 0, 'suspended' => 0, 'confirmed' => 1))) {
                            array_push($instructors, self::local_user_details($teachers->userid));
                        }
                    }  
                }
            }
        }
        return $instructors;
    }

     public static function local_user_details($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $userobj = optional_param('id', $userid, PARAM_INT);
        $user = $DB->get_record('user', ['id' => $userobj], '*', MUST_EXIST);
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        $user->userimage = $userimg->get_url($PAGE);
        return $user;
    }

    public static function local_custom_field_data($course = null){
        global $DB;
        $custom_data = array();
        $levelpreviousdata = array();
        $customdata = new stdClass();
        $customdata->level = null;
        $customdata->highlights = null;
        $levelValues = $DB->get_record('customfield_field', array('id' => 1));
        $levelsconfigdata = $levelValues->configdata;
        $levelpreviousdata = explode("\r\n", json_decode($levelsconfigdata)->options);
        array_unshift($levelpreviousdata,"");
        unset($levelpreviousdata[0]);

            $coursecon = new \core_course_list_element(self::getcoursedetails($course));
            $get = $coursecon->get_custom_fields();
            foreach ($get as $key => $value) {
                array_push($custom_data, $value->get_value());
            }
            $level = $custom_data[0];
            $highlights = $custom_data[1];
            if (!empty($highlights)) {
                $customdata->highlights = $highlights;
            }else{
                $customdata->highlights = self::getcoursedetails($course)->summary;
            }

            if (!empty($level)) {
                if (!empty($levelpreviousdata)) {
                    $customdata->level = $levelpreviousdata[$level];
                }
            }
        return $customdata;
    }

    public static function courseduration($course = null){
        $startdate = null;
        $enddate = null;
        $duration = null;
        if (self::getcoursedetails($course)) {
            $startdate = self::getcoursedetails($course)->startdate;
            $enddate = self::getcoursedetails($course)->enddate;
        }
        if (!is_null($startdate) && !is_null($enddate)) {
            $datediff = $enddate - $startdate;
            if (round($datediff / (60 * 60 * 24)) > 1) {
                $duration = round($datediff / (60 * 60 * 24))." Days";  
            }else{
                $duration = round($datediff / (60 * 60 * 24))." Day";
            }
        }
        return $duration;
    }

    public function checkenrol($param = null){
        global $USER;
        $courseid = $param['courseid'];
        if (isloggedin() && $courseid) {
        $context = context_course::instance($courseid); 
        return is_enrolled($context, $USER->id, '', true);
        }else{
            return FALSE;
        }
    }




    public static function certficate($course = null){
        global $DB; $certficate = FALSE;
        if ($DB->count_records('customcert', array('course' => $course))>0) {
            $certficate = TRUE;
        }
        return $certficate; 
    }

    public static function courseSectionsDetails($courseid = null){
        global $DB,$CFG, $USER;
        require_once($CFG->dirroot.'/course/lib.php');
        require_once($CFG->libdir.'/completionlib.php');
        if (is_null($courseid)) {
            return FALSE;
        }
        $userid = null;
        $ttlArt = 0;
        $nofitem=0;
        if (isloggedin()) {
            $userid = $USER->id;
        }
        $array_of_sections = array();

        $coursesection = $DB->get_records_sql("SELECT * FROM {course_sections} WHERE course=$courseid AND visible = 1 AND sequence != '' ");
        foreach($coursesection as $csvalue){
            if($csvalue->name==""){
                $sectionname="Tile ".$nofitem;
            }else{
                $sectionname=$csvalue->name;
            }
            $nofitem++; 
            $count = 0; 
            $cnt = 0;
            $titem=$csvalue->sequence;
            $sequenceid=explode(',',$titem);
            $topic_name = $sectionname; 

            $test = array();
            foreach($sequenceid as $sequenceval){ 
                if($sequenceval){ 
                    $c_modulesinfo = $DB->get_record_sql("SELECT * FROM {course_modules} WHERE course=$courseid and deletioninprogress=0  and id=$sequenceval" );
                    if ($c_modulesinfo->module) {
                        $moduleinfo = $DB->get_record_sql("SELECT * FROM {modules} WHERE id=$c_modulesinfo->module");
                        $modulename=$moduleinfo->name;
                        $s= "select * from  mdl_$modulename WHERE id=$c_modulesinfo->instance";
                        $tableinfo =$s;
                        $courseinfossss = $DB->get_records_sql($tableinfo);
                        if ($courseinfossss) {
                            foreach($courseinfossss as $tavleRow){
                                $count++;
                                $ttlArt++;
                                if($csvalue->section == 0){
                                    $topic_name = "course details";
                                }else{
                                    if ($topic_name == 'Tile 0') {
                                        $topic_name = 'Tile 1';
                                    }
                                } 
                                $tname=$tavleRow->name;
                                $completionstate = null;
                                if (!is_null($userid)) {
                                $curs_mdl_cmpinfo = $DB->get_records_sql("SELECT completionstate,viewed,timemodified FROM {course_modules_completion} WHERE coursemoduleid =$c_modulesinfo->id and userid=$userid and completionstate=1"); 
                
                                    if ($curs_mdl_cmpinfo) {
                                        $completionstate = array();
                                        array_push($completionstate, array('completionstate'=>$curs_mdl_cmpinfo[1]->completionstate,
                                                                           'viewed'=>$curs_mdl_cmpinfo[1]->viewed,
                                                                           'timemodified'=>date("l, d M Y, h:i A",$curs_mdl_cmpinfo[1]->timemodified)));
                                        $cnt = count((array)$curs_mdl_cmpinfo);
                                    }  
                                }
                                $modulename = $modulename;
                                $module_id = $c_modulesinfo->id;
                                $modulename_Name = $tname;
                                $sectiondeatils = array('modulename'=>$modulename,
                                                        'module_id'=>$module_id,
                                                        'modulename_Name'=>$tname,
                                                        'completionstate'=>$completionstate);
                                array_push($test, $sectiondeatils);
                            }

                        }
                    }
                }
            }
            if ($cnt!=0 and $count!=0) {
                $report = $cnt/$count*100;
            }else{
                $report = 0;
            }
            $cnt = 0;
            $coursesectionobj = new stdClass();
            $coursesectionobj->sectionid = $csvalue->id;
            $coursesectionobj->section_sequence = $csvalue->section;
            $coursesectionobj->sectionname = $topic_name;
            $coursesectionobj->progress = number_format($report);
            $coursesectionobj->summary = $csvalue->summary;
            $coursesectionobj->sectiondata = $test;
            array_push($array_of_sections, $coursesectionobj);
        }
        return $array_of_sections;
    }

}
