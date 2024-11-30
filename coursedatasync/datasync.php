<?php function createcategories(\core\event\course_category_created $event){
global $DB, $USER,$CFG;
          require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
     	if($event->objecttable=="course_categories" && $event->action=="created"){
          	$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
              	$url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/sync.php';
              	$r=apidata($url,moodlecategoriesdata($event->objectid));


     	}


}
function updatecategories(\core\event\course_category_updated $event){
     global $DB, $USER,$CFG;
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
     if($event->objecttable=="course_categories" && $event->action=="updated"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/sync.php';
               $r=apidata($url,moodlecategoriesdata($event->objectid));
              
          }
}
function deletecategories(\core\event\course_category_deleted $event){
     global $DB, $USER,$CFG;  
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
     if($event->objecttable=="course_categories" && $event->action=="deleted"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/deletecategoriessync.php';
               $r=deleteapidata($url,eventmoodlecategoriesdata($event->objectid));
          }
}
function createcourse(\core\event\course_created $event){
    global $DB, $USER,$CFG; 

   

     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course" && $event->action=="created"){
      	$courselanguage=coursecreatelanguae($event->objectid);
      	if(empty($courselanguage)){
       		 

   $dynamicdata=$DB->get_records_sql("SELECT * FROM {customfield_field} WHERE categoryid='2'");	
   				$alldata=array();
			foreach ($dynamicdata as $value) {
				if($_REQUEST['customfield_'.$value->shortname]=='1'){
			array_push($alldata[$value->shortname]=$value->name);
				}	
			}


            // if($_REQUEST['customfield_en']=='1'){
            //   $languagedata['en']="English";
            // }
            //  if($_REQUEST['customfield_ta']=='1'){
            //   $languagedata['ta']="Tamil";
            // }
            // if($_REQUEST['customfield_mr']=='1'){
            //   $languagedata['mr']="मराठी";
            // }
            // if($_REQUEST['customfield_hi']=='1'){
            //   $languagedata['hi']="हिंदी";
            // }




        }
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursesync.php';
              $r=courseapidata($url,moodlecoursecategoriesdata($event->objectid,$alldata));
          }

}
function updatecourse(\core\event\course_updated $event){
     global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course" && $event->action=="updated"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursesync.php';
               $r=courseapidata($url,moodlecoursecategoriesdata($event->objectid,''));

              
          }
}
function deletecourse(\core\event\course_deleted $event){
     global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course" && $event->action=="deleted"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursedeletesync.php';
               $r=deletecourseapidata($url,moodlecoursedeletedata($event->objectid));
          }
}
function course_section_created(\core\event\course_section_created $event){
     global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_sections" && $event->action=="created"){

      	$courselanguage=coursecreatelanguae($event->courseid);
      	if(empty($courselanguage)){

   $dynamicdata=$DB->get_records_sql("SELECT * FROM {customfield_field} WHERE categoryid='2'");	
   				$alldata=array();
			foreach ($dynamicdata as $value) {
				if($_REQUEST['customfield_'.$value->shortname]=='1'){
			array_push($alldata[$value->shortname]=$value->name);
				}	
			}

       		 // $languagedata=array();
          //   if($_REQUEST['customfield_en']=='1'){
          //     $languagedata['en']="English";
          //   }
          //    if($_REQUEST['customfield_ta']=='1'){
          //     $languagedata['ta']="Tamil";
          //   }
          //   if($_REQUEST['customfield_mr']=='1'){
          //     $languagedata['mr']="मराठी";
          //   }
          //   if($_REQUEST['customfield_hi']=='1'){
          //     $languagedata['hi']="हिंदी";
          //   }

        }
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/sectionsync.php';
               $r=sectioncourseapidata($url,moodlesectioncreated($event->objectid,$event->courseid,$alldata));
            
          }
}
function course_section_updated(\core\event\course_section_updated $event){
     global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_sections" && $event->action=="updated"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/sectionsync.php';
               $r=sectioncourseapidata($url,moodlesectioncreated($event->objectid,$event->courseid,''));
            
          }
}
function course_section_deleted(\core\event\course_section_deleted $event){
      global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_sections" && $event->action=="deleted"){
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/deletesectionsync.php';
               $r=deletesectionapidata($url,moodlesectiondeleted($event->objectid,$event->courseid));

          }
}

function course_module_created(\core\event\course_module_created $event){
  global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_modules" && $event->action=="created"){
            $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
            $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/modulesync.php';
           $r=modulecreateapidata($url,moodlemodulecreate($event->objectid,$event->courseid));
    }
}
function course_module_updated(\core\event\course_module_updated $event){
  global $DB, $USER,$CFG; 
     require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_modules" && $event->action=="updated"){
            $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
            $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/modulesync.php';
           $r=modulecreateapidata($url,moodlemoduleupdate($event->objectid,$event->courseid,$event->other['name']));
         
    }
}
function course_module_deleted(\core\event\course_module_deleted $event){
  global $DB, $USER,$CFG; 
   require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
      if($event->objecttable=="course_modules" && $event->action=="deleted"){
            $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
            $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/deletemodulesync.php';
           $r=moduledeleteapidata($url,moodlemoduledelete($event->objectid,$event->courseid));
        
    }
}

function enrol_instance_created(\core\event\enrol_instance_created $event){
  global $DB, $USER,$CFG; 
   require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
   
   if($event->target=="enrol_instance" && $event->action=="created" && $event->other['enrol']='razorpay' || $event->target=="enrol_instance" && $event->action=="created" && $event->other['enrol']='paytm'){
    $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
    $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursesync.php';
    $courselanguage=coursecreatelanguae($event->courseid);
      	if(empty($courselanguage)){
       		
      		
   $dynamicdata=$DB->get_records_sql("SELECT * FROM {customfield_field} WHERE categoryid='2'");	
   				$alldata=array();
			foreach ($dynamicdata as $value) {
				if($_REQUEST['customfield_'.$value->shortname]=='1'){
			array_push($alldata[$value->shortname]=$value->name);
				}	
			}

       		 // $languagedata=array();
          //   if($_REQUEST['customfield_en']=='1'){
          //     $languagedata['en']="English";
          //   }
          //    if($_REQUEST['customfield_ta']=='1'){
          //     $languagedata['ta']="Tamil";
          //   }
          //   if($_REQUEST['customfield_mr']=='1'){
          //     $languagedata['mr']="मराठी";
          //   }
          //   if($_REQUEST['customfield_hi']=='1'){
          //     $languagedata['hi']="हिंदी";
          //   }

        }

    $r=courseapidata($url,courseenrol_instance($event->courseid,$alldata));
   }

}

function enrol_instance_updated(\core\event\enrol_instance_updated $event){
  global $DB, $USER,$CFG; 
   require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
   if($event->target=="enrol_instance" && $event->action=="updated" && $event->other['enrol']='razorpay' || $event->target=="enrol_instance" && $event->action=="updated" && $event->other['enrol']='paytm'){
    $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
    $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursesync.php';
    $r=courseapidata($url,courseenrol_instance($event->courseid,''));
   }

}

function enrol_instance_deleted(\core\event\enrol_instance_deleted $event){
  global $DB, $USER,$CFG; 
   require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
   if($event->target=="enrol_instance" && $event->action=="deleted" && $event->other['enrol']='razorpay' || $event->target=="enrol_instance" && $event->action=="deleted" && $event->other['enrol']='paytm'){
    $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
    $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/coursesync.php';
    $r=courseapidata($url,courseenrol_instance($event->courseid,''));
   }

}