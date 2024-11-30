<?php function enrolledAction($groupid,$apicourseid,$userid){
    global $DB,$PAGE, $CFG;
  $coursedata= $DB->get_record_sql("SELECT cc.courseid FROM {cleverapi_cr_group} as gr right join {cleverapi_courses} as cc on cc.api_courseid=gr.api_courseid where gr.groupid='$groupid' and gr.api_courseid='$apicourseid'");
  if(!empty($coursedata)){
    enrolCourse($coursedata->courseid, $userid,'5','0',time());
    $data=$DB->get_record('groups_members',array('groupid'=>$groupid,'userid'=>$userid));
    if(empty($data)){
      $groupdata=new stdClass();
      $groupdata->groupid=$groupid;
      $groupdata->userid=$userid;
      $groupdata->timeadded=time();
      $DB->insert_record('groups_members',$groupdata);
       echo "Userid-".$userid." enrol in group";
    }


  }else{
    echo "Userid-".$userid."Not enrol";
  }

}



function enrolCourse($courseid, $userid, $roleid,$endtime,$startime) {
    global $DB, $CFG;
    $query = 'SELECT * FROM {enrol} WHERE enrol = "manual" AND courseid = '.$courseid;
    $enrollmentID = $DB->get_record_sql($query);
    if(!empty($enrollmentID->id)) {
        if (!$DB->record_exists('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid))) {
            $userenrol = new stdClass();
            $userenrol->status = 0;
            $userenrol->userid = $userid;
            $userenrol->enrolid = $enrollmentID->id;
            $userenrol->timestart  = $startime;
            $userenrol->timeend = $endtime;
            $userenrol->modifierid  = 2;
            $userenrol->timecreated  = time();
            $userenrol->timemodified  = time();
            //print_r($userenrol);die;
            $enrol_manual = enrol_get_plugin('manual');
            $enrol_manual->enrol_user($enrollmentID, $userid, $roleid, $userenrol->timestart, $userenrol->timeend);
           // add_to_log($courseid, 'course', 'enrol', '../enrol/users.php?id='.$courseid, $courseid, $userid); //there should be userid somewhere!
            //redirect('http://lln.axisinstitute.edu.au/my');
            return true;
        } else {
            $oldenroll = $DB->get_record('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid));
            $oldenroll->timestart = $startime;
            $oldenroll->timeend = $endtime;
            if($oldenroll){
                $insertRecords=$DB->update_record('user_enrolments', $oldenroll);
            }
             return true;
        }
    }
}

function userAction($userdata){
  global $DB,$PAGE, $CFG;

           $cleverapi=new stdClass();
           $cleverapi->apiuserid=$userdata['apiuserid'];
           $cleverapi->apischoolid=$userdata['apischoolid'];
         

          $user=new stdClass();
          $user->auth="manual";
          $user->confirmed="1";
          $user->username=$userdata['username'];
          
          $user->email=$userdata['email'];
          $user->firstname=$userdata['firstname'];
          $user->lastname=$userdata['lastname'];
          $user->address=$userdata['address'];
          $user->city=$userdata['city'];
          $user->zip=$userdata['zip'];
         $data=$DB->get_record('clever_api_user',array('apiuserid'=>$userdata['apiuserid']));
        if(empty($data)){ 
                $recorduser=$DB->get_record_sql("select * from {user} where `username`='$user->username' or `email`='$user->username' or `username`='$user->email' or `email`='$user->email'");
                if(!empty($recorduser)){
                   $cleverapi->userid=$recorduser->id;
                   $cleverapi->createdtime=time();
                   $DB->insert_record('clever_api_user',$cleverapi); 
                   return $recorduser->id;
                }else{
                  $user->password=md5("P@ssw0rd123");
                   $userid=$DB->insert_record('user',$user); 
                    $cleverapi->userid=$userid;
                   $cleverapi->createdtime=time();
                   $DB->insert_record('clever_api_user',$cleverapi);
                   return $userid;
                }
        }else{
            $user->id=$data->userid;
             $DB->update_record('user',$user); 
            $cleverapi->id=$data->id;
            $cleverapi->updatedtime=time();
            $DB->update_record('clever_api_user',$cleverapi); 
            return $data->userid;

        }
}



function alluserapidata(){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.clever.com/v3.0/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.get_config('local_oauth','data_api_token')
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return json_decode($response);

}

function allgroupdata(){
  global $DB,$PAGE, $CFG;
   return $groups=$DB->get_records('cleverapi_cr_group');    
}

 function allschool(){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.clever.com/v3.0/schools',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.get_config('local_oauth','data_api_token')
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return json_decode($response);

}
function schooldata($schoolid){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.clever.com/v3.0/schools/'.$schoolid.'/courses',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.get_config('local_oauth','data_api_token')
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return  json_decode($response);
}

function courseshortname($apicourseName){
      global $DB,$PAGE, $CFG;
      if(is_numeric($apicourseName)){
          $data=$DB->get_record('course',array('id'=>$apicourseName));
          if(!empty($data)){
            return $data->shortname;
          }

        }else{
            $data=$DB->get_records('course',array('shortname'=>$apicourseName));
          if(empty($data)){
            return $apicourseName;
          }else{
     return $apicourseName."".count($data)+1;
          }
        }
}
function coursesAction($apicourseName,$apicourseId,$apidistrictId,$moodleCateId){
         global $DB,$PAGE, $CFG;
             require_once($CFG->dirroot.'/course/lib.php');
         $courserecord=new stdClass();
            $courserecord->fullname = $apicourseName;
            
            $courserecord->category = $moodleCateId;
            $courserecord->visible = '1';
            $courserecord->startdate = time();
    $data=$DB->get_record('cleverapi_courses',array('api_courseid'=>$apicourseId));
        if(empty($data)){ 
            $courserecord->shortname = courseshortname($apicourseName);

        $course = create_course($courserecord);
            if(!empty($course->id)){
                $apirecord = new stdClass();
                    $apirecord->api_districtid=$apidistrictId;
                    $apirecord->api_courseid=$apicourseId;
                    $apirecord->courseid=$course->id;
                    $apirecord->createdtime=time();
                    $DB->insert_record('cleverapi_courses',$apirecord);  
            }

        }else{
                $courserecord->id = $data->courseid;
                $courserecord->shortname = courseshortname($data->courseid);
                $DB->update_record('course',$courserecord);
                  $apirecord=new stdClass();
                $apirecord->id=$data->id;
                $apirecord->api_districtid=$apidistrictId;
                $apirecord->api_courseid=$apicourseId;
                $apirecord->updatedtime=time();
                $DB->update_record('cleverapi_courses',$apirecord); 

        }
}

function alldistrict(){
         global $DB,$PAGE, $CFG;
     return $avldistrict=$DB->get_records('district');    
}

function apicoursedata($apicourseid){
   global $DB,$PAGE, $CFG;
  return $coursesdata=$DB->get_record('cleverapi_courses',array('api_courseid'=>$apicourseid)); 
}
function groupAction($apigroupid,$groupname,$apicourseid){
   global $DB,$PAGE, $CFG;
  require_once($CFG->dirroot.'/group/lib.php');
  $course=apicoursedata($apicourseid);
  if(!empty($course->courseid)){
      $groupdata=new stdClass();
      $groupdata->name=$groupname;
      $groupdata->courseid=$course->courseid;
        $grodata=$DB->get_record('cleverapi_cr_group',array('api_groupid'=>$apigroupid,'api_courseid'=>$apicourseid));
        $cleverapi=new stdClass();
        $cleverapi->api_groupid=$apigroupid;
        $cleverapi->api_courseid=$apicourseid;

        if(empty($grodata)){ 
      $cleverapi->createdtime=time();
      $data=groups_create_group($groupdata);
      $cleverapi->groupid=$data;
      $DB->insert_record('cleverapi_cr_group',$cleverapi);
      }else{

    $groupdata->id=$grodata->groupid;
    $groupdata->submitbutton="Save changes";

    groups_update_group($groupdata);
    $cleverapi->id=$grodata->id;
    $cleverapi->updatedtime=time();
    $DB->update_record('cleverapi_cr_group',$cleverapi);
      }
   } 
}

function get_courseApidata(){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.clever.com/v3.0/courses',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.get_config('local_oauth','data_api_token')
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return json_decode($response);


}
function getdistrictIDdata($districtid,$districttoken){
		  $curl = curl_init();
		  curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.clever.com/v3.0/districts/'.$districtid,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer '.$districttoken
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		 $responsedata=json_decode($response);
		 $returndata=array();
		 $returndata['name']=$responsedata->data->name;
		 $returndata['launch_date']=$responsedata->data->launch_date;
        
         $data=createcoursecategories($responsedata->data->name,$districtid);
         if(!empty($data['courseid'])){
            $returndata['courseid']=$data['courseid'];
         }
		 return $returndata;
		 
}

function createcoursecategories($categories,$districtid){
     global $DB,$PAGE, $CFG;
require_once($CFG->dirroot.'/course/lib.php');
$avldistrict=$DB->get_record('all_district',array('districtid'=>$districtid));
$returndata=array();
$coursedata=new stdClass();
$coursedata->parent='0';
$coursedata->name=$categories;
$coursedata->idnumber=$categories;
$coursedata->description_editor="";

if(empty($avldistrict->courseid) && !empty($avldistrict->id)){
$coursedata->id='0';
$coursedata->submitbutton='Create category';
$category = core_course_category::create($coursedata);
$returndata['status']="sucess";
$returndata['courseid']=$category->id;
}else{
$coursedata->id=$avldistrict->courseid;
$coursedata->submitbutton='Save changes';
core_course_category::update($coursedata);
$returndata['status']="sucess";
$returndata['courseid']=$avldistrict->courseid;
}

return $returndata;

// echo"<pre>";

// print_r($returndata);
// die();


}

function local_oauth_extend_navigation(global_navigation $navigation) {

    $settings = new stdClass;
    $settings->enabled = 1;
    $settings->flatenabled = 1;
    if(is_siteadmin()){
    $settings->menuitems = get_string('districts_auth', 'local_oauth').' | '.new moodle_url('/local/oauth/all_district.php');
    }
    if (!empty($settings->menuitems) && $settings->enabled) {
        $menu = new custom_menu($settings->menuitems, current_language());
        if ($menu->has_children()) {
            foreach ($menu->get_children() as $item) {
                oauth_custom_menu_item($item, 0, null, $settings->flatenabled);
            }
        }
    }
}

/**
 * ADD custom menu in navigation recursive childs node
 * Is like render custom menu items
 *
 * @param custom_menu_item $menunode {@link custom_menu_item}
 * @param int $parent is have a parent and it's parent itself
 * @param object $pmasternode parent node
 * @param int $flatenabled show master node in boost navigation
 * @return void
 */
function oauth_custom_menu_item(custom_menu_item $menunode, $parent, $pmasternode, $flatenabled) {
    global $PAGE, $CFG;

    static $submenucount = 0;

    if ($menunode->has_children()) {
        $submenucount++;
        $url = $CFG->wwwroot;
        if ($menunode->get_url() !== null) {
            $url = new moodle_url($menunode->get_url());
        } else {
            $url = null;
        }
        if ($parent > 0) {
            $masternode = $pmasternode->add(local_oauth_get_string($menunode->get_text()),
                                            $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
        } else {
            $masternode = $PAGE->navigation->add(local_oauth_get_string($menunode->get_text()),
                                            $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
            if ($flatenabled) {
                $masternode->isexpandable = true;
                $masternode->showinflatnavigation = true;
            }
        }
        foreach ($menunode->get_children() as $menunode) {
            navigation_custom_menu_item($menunode, $submenucount, $masternode, $flatenabled);
        }
    } else {
        $url = $CFG->wwwroot;
        if ($menunode->get_url() !== null) {
            $url = new moodle_url($menunode->get_url());
        } else {
            $url = null;
        }
        if ($parent) {
            $childnode = $pmasternode->add(local_oauth_get_string($menunode->get_text()),
                                        $url, navigation_node::TYPE_CUSTOM);
            $childnode->title($menunode->get_title());
        } else {
            $masternode = $PAGE->navigation->add(local_oauth_get_string($menunode->get_text()),
                                        $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
            if ($flatenabled) {
                $masternode->isexpandable = true;
                $masternode->showinflatnavigation = true;
            }
        }
    }

    return true;
}

/**
 * Translate Custom Navigation Nodes
 *
 * This function is based in a short peace of Moodle code
 * in  Name processing on user_convert_text_to_menu_items.
 *
 * @param string $string text to translate.
 * @return string
 */
function local_oauth_get_string($string) {
    $title = $string;
    $text = explode(',', $string, 2);
    if (count($text) == 2) {
        // Check the validity of the identifier part of the string.
        if (clean_param($text[0], PARAM_STRINGID) !== '') {
            // Treat this as atext language string.
            $title = get_string($text[0], $text[1]);
        }
    }
    return $title;
}

function local_oauth_extend_settings_navigation(){}

function local_oauth_after_config(){
     global $DB, $CFG;
   $code= optional_param('code', '', PARAM_TEXT);
         if(!empty($code)){
	         $lesstantime= strtotime('-1 minute');
	         $tokenData=$DB->get_record_sql("SELECT * FROM {clever_acces_token} where `createdtime`>$lesstantime or `code`='$code'");
	         if(empty($tokenData)){
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://clever.com/oauth/tokens',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS =>'{
					"code": "'.$code.'",
					"grant_type": "authorization_code",
					"redirect_uri": "https://staging.coremathstandards.com/admin/oauth2callback.php"
					}',
					CURLOPT_HTTPHEADER => array(
					'Authorization: Basic '.base64_encode("94f269d8af3caf1e0ad6:ab64301982ee614ffc446c1b40db1a2937d97540"),
					'Content-Type: application/json'
					),
					));

					$response = curl_exec($curl);
					curl_close($curl);
					$apidata=json_decode($response);
					
							$data=new stdClass();
							$data->acess_token=$apidata->access_token;
							$data->code=$code;
							$data->createdtime=time();	
							if(!empty($apidata->access_token)){
								$avl=$DB->insert_record('clever_acces_token',$data,true);
								if(!empty($avl)){
									redirect($CFG->wwwroot."/login/cleverlogin.php?id=1");
								}
							}

					
				    
	         }

         


        


                
    
     }else{
       // echo"Current Code is empty";
      
     }
}
