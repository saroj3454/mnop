<?php 

function learncourseimage($courseid){
	global $DB, $CFG;
    $imageurl= "";
    require_once($CFG->dirroot. '/course/classes/list_element.php');
    $course = $DB->get_record('course', array('id' => $courseid));
    $course = new core_course_list_element($course);
    foreach ($course->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();
        $imageurl = file_encode_url("$CFG->wwwroot/pluginfile.php", '/'. $file->get_contextid(). '/'. $file->get_component(). '/'. $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
        return $imageurl;
    }
    return $imageurl;
}
function learncoursecontent($courseid){
global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');

$new=new core_course_external();
$data=$new->get_course_contents($courseid, $options = array());
return $data;
}

function coursealldetails($courseid){
global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    $options = array("ids"=>array($courseid));
    $new=new core_course_external();
    $data=$new->get_courses($options);
    return $data;

}

function coursedescription($courseid,$coursedescription){
    global $DB, $CFG;
    //$context = context_system::instance();
    require_once($CFG->libdir .'/filelib.php');
    $context = context_course::instance($courseid);
   return $text = file_rewrite_pluginfile_urls($coursedescription, 'pluginfile.php', $context->id, 'course', 'summary', null);
}

function categoriesdescription($id,$description){
    global $DB, $CFG;
     require_once($CFG->libdir .'/filelib.php');
    //$context = context_system::instance();
    $context = context_coursecat::instance($id);
   return $text = file_rewrite_pluginfile_urls($description, 'pluginfile.php', $context->id, 'coursecat', 'description', null);

}


function categoriesdataretundata($woo_course_categoriesid,$moodle_course_categoriesid,$woo_categories_tableid){
 global $DB, $CFG, $USER;

 $coursescategories=$DB->get_record('blocks_custom_categories',array('moodle_course_categoriesid'=>$moodle_course_categoriesid));
        $data=new stdClass();
        $data->woo_course_categoriesid=$woo_course_categoriesid;
        $data->moodle_course_categoriesid=$moodle_course_categoriesid;
        $data->woo_categories_tableid=$woo_categories_tableid;
       
     if(empty($coursescategories)){
         $data->createdtime=time();
        $DB->insert_record('blocks_custom_categories',$data);
        echo "inserted course categories";
     }else{
        $data->id=$coursescategories->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_categories',$data);
         echo "updated course categories";
     }

}
function coursedataretundata($coursedata){
    global $DB, $CFG, $USER;
    $data=new stdClass();
    $data->woo_course_categoriesid=$coursedata->woo_course_categoriesid;
    $data->moodle_course_categoriesid=$coursedata->moodle_course_categoriesid;
    $data->woocommerceid=$coursedata->woocommerceid;
    $data->courseid=$coursedata->courseid;
    $data->woocommerce_tableid=$coursedata->woocommerce_tableid;
    $moodlecourses=$DB->get_record('blocks_custom_courses',array('courseid'=>$coursedata->courseid));
    if(empty($moodlecourses)){
        $data->createdtime=time();
       $DB->insert_record('blocks_custom_courses',$data);
       echo "inserted course";
    }else{
        $data->id=$moodlecourses->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_courses',$data);
        echo "updated course";
    }

}

function coursesectionreturndata($sectiondata){
    global $DB, $CFG, $USER;
    $data=new stdClass();
    $data->woo_sectiontable_id=$sectiondata->woo_sectiontable_id;
    $data->moodle_course_id=$sectiondata->moodle_course_id;
    $data->moodle_section_id=$sectiondata->moodle_section_id;
    $moodlesection=$DB->get_record('blocks_custom_section',array('moodle_section_id'=>$sectiondata->moodle_section_id));
    if(empty($moodlesection)){
        $data->createdtime=time();
       $DB->insert_record('blocks_custom_section',$data);
       echo "inserted section";
    }else{
        $data->id=$moodlesection->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_section',$data);
        echo "updated section";
    }
}

function coursemodulereturndata($coursemodule){
     global $DB, $CFG, $USER;
    $data=new stdClass();
    $data->woo_moduletable_id=$coursemodule->woo_moduletable_id;
    $data->woo_section_tableid=$coursemodule->woo_section_tableid;
    $data->woo_course_tableid=$coursemodule->woo_course_tableid;
    $data->moodle_courseid=$coursemodule->moodle_courseid;
    $data->moodle_section_id=$coursemodule->moodle_section_id;
    $data->moodle_module_id=$coursemodule->moodle_module_id;
    $moodlemoudle=$DB->get_record('blocks_custom_module',array('moodle_module_id'=>$coursemodule->moodle_module_id));
    if(empty($moodlemoudle)){
        $data->createdtime=time();
        $DB->insert_record('blocks_custom_module',$data);
       echo "inserted module";
    }else{
        $data->id=$moodlemoudle->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_module',$data);
        echo "updated module";
    }

   

}

