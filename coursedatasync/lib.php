<?php function woocourseenroledata($data){
         global $DB, $CFG, $USER;
     
$fname                =  $data['fname'];
$lname                =  $data['lname'];
$user_name            = $data['useremail'];
 $email                =  $data['useremail'];
$courseid           = $data['courseid'];
$method=$data['method'];
$coursedata=$DB->get_record("course", array("id"=>$courseid));
$allcourse_name=$coursedata->fullname;
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
$pwd = substr( str_shuffle( $chars ), 0, 12 );
$password=md5($pwd); 

// get admin emial
$adminEmail = $DB->get_record("user", array("id"=>2));
$email_id=$adminEmail->email;
$userinfos=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name' or email='$user_name'");

if(empty($userinfos)){
    //echo "new user";die;
    $userinsert  = new stdClass();
    $userinsert->username = $user_name;
    $userinsert->password=$password;
    $userinsert->firstname= $fname;
    $userinsert->lastname= $lname;
    $userinsert->email= $email;
    $userinsert->timecreated= time();
    $userinsert->timemodified= time();
    $userinsert->middlename= " ";
    $userinsert->confirmed= 1;
    $userinsert->mnethostid= 1;

   
    $insertRecords=$DB->insert_record('user', $userinsert);
    $userinfo=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name' and email='$email' ");
    if(!empty($userinfo)){
        $userpreinfo=$DB->get_record_sql("SELECT id FROM {user_preferences} where userid = $userinfo->id and value=1 ");
        if(empty($userpreinfo)){
            $forceinsert  = new stdClass();
            $forceinsert->userid = $userinfo->id;
            $forceinsert->name="auth_forcepasswordchange";
            $forceinsert->value=1;
            $insertRecords=$DB->insert_record('user_preferences', $forceinsert);
        }
  
    
             // enrolCourse($coursevalue, $userinfo->id, 5);
            $start=time();
            $end=strtotime("+1 year");


            enrolCourseuser($courseid, $userinfo->id, 5,$end,$start,$method);


 $messagehtml =   "<!DOCTYPE html>
<html>
<head>
    <title>Mail</title>
    <link rel='stylesheet' type='text/css' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>
<body><p>Hi <b>".$fname."</b>,</p>
    <p><b>$fname  $lname </b> has registered LMS Portal.</p>
    <p> Thank you for registration. To access your course, go to ".$CFG->wwwroot."/login/index.php Your user name is <b>$user_name</b> , and your password is <b>$pwd</b> </p>
    
    <p><b>Login Username:</b> $user_name</p>
    <p><b>Login Password:</b> $pwd</p>
    <p><b>Login URL :</b> ".$CFG->wwwroot."/login/index.php</p>
    <p><b>Your Course:</b>  ".$allcourse_name."</p>
   
<table style='background-color: rgb(237 235 235);' cellpadding='15'>
 <tr><td>
 <p style='font-weight: 600;'>Click below to access your Learning Account!</p>
 <a  href='".$CFG->wwwroot."/login/index.php' style='padding: 10px 50px;
 display: inline-block;
 background-color: rgb(177, 24, 48);
 border-radius: 4px;
 border: 2px solid white;
 color: #fff;
 text-decoration: none;
 font-weight: bold;'> MY LEARNING ACCOUNT</a>
</td> 
</tr>
</table>

    <p>Thanks, </p>

    <p>".$CFG->wwwroot." </p></body></html>";

         $fromUser = "noreply@braingroom.com";
        $subject = 'Welcome to Braingroom Learning! Registration confirmation';
        $emailuser = new stdClass();
        $emailuser->email = $email;
        $emailuser->firstname = $fname;
        $emailuser->lastname= $lname;
        $emailuser->maildisplay = true;
        $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
        $emailuser->id = 1;
        $emailuser->firstnamephonetic = false;
        $emailuser->lastnamephonetic = false;
        $emailuser->middlename = false;
        $emailuser->username = false;
        $emailuser->alternatename = false;

email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
      




  }
}
else{

            $start=time();
            $end=strtotime("+1 year");
            enrolCourseuser($courseid, $userinfos->id, 5,$end,$start,$method);



}



}


function enrolCourseuser($courseid, $userid, $roleid,$endtime,$startime,$method) {
    global $DB, $CFG;

    $query = "SELECT * FROM {enrol} WHERE `enrol` ='".$method."' AND `courseid` = '".$courseid."'";
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
            $enrol_manual = enrol_get_plugin($method);
            $enrol_manual->enrol_user($enrollmentID, $userid, $roleid, $userenrol->timestart, $userenrol->timeend);
           // add_to_log($courseid, 'course', 'enrol', '../enrol/users.php?id='.$courseid, $courseid, $userid); //there should be userid somewhere!
            //redirect('http://lln.axisinstitute.edu.au/my');
        } else {
            $oldenroll = $DB->get_record('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid));
            $oldenroll->timestart = $startime;
            $oldenroll->timeend = $endtime;
            if($oldenroll){
                $insertRecords=$DB->update_record('user_enrolments', $oldenroll);
            }
        }
    }
}


function coursecommentapidata($url,$data){
       global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);

 return $r=coursecommentreturndata($result);
}

function coursecommentreturndata($data){
    global $DB, $CFG, $USER;
   foreach ($data as $commentvalue) {
     if(!empty($commentvalue->wooid)){
            $commentdata=$DB->get_record('blocks_custom_coursecomment',array('id'=>$commentvalue->id));
           if(!empty($commentdata)){
        $topbar_slider=new stdClass();
        $topbar_slider->id=$commentdata->id;
        $topbar_slider->wooid=$commentvalue->wooid;
        $DB->update_record('blocks_custom_coursecomment', $topbar_slider,true);
        
                }
              }
   }
   return true;
}

 function singlecoursecommentapidata($url,$data){
       global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);
 return $r=$result;
}

function deletesinglecoursecommentapidata($url,$data){
       global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);
 return $r=$result;
}









function coursestarratedata($courseid,$numberOfRatings){
    global $DB, $CFG, $USER;
    $ratedata=$DB->get_record('blocks_custom_courserate',array('courseid'=>$courseid));
    if(!empty($ratedata)){
      $ratedata->starrtingdata=startratingdisplay($ratedata->userrated,$ratedata->startrate5,$ratedata->startrate4,$ratedata->startrate3,$ratedata->startrate2,$ratedata->startrate1);

         if(!empty($ratedata->userrated)){
                  if(!empty($numberOfRatings)){
                                      $totalrate=$ratedata->userrated+$numberOfRatings;
                              }else{
                                  $totalrate=$ratedata->userrated;
                              }
                       $ratedata->totalrate=$totalrate;       
            }                  


      return $ratedata;
    }
  }


function courseenrolledinblock($courseid){
    global $DB, $CFG, $USER;
    $ratedata=$DB->get_record('blocks_custom_courserate',array('courseid'=>$courseid));
    if(!empty($ratedata)){
      return $ratedata->userenrolled;
    }
  }



function courserateincoursepage($courseid){
    global $DB, $CFG, $USER;
    $ratedata=$DB->get_record('blocks_custom_courserate',array('courseid'=>$courseid));
    if(!empty($ratedata)){
       $starrtingdata=startratingdisplay($ratedata->userrated,$ratedata->startrate5,$ratedata->startrate4,$ratedata->startrate3,$ratedata->startrate2,$ratedata->startrate1);
      $html="";
      if(!empty($starrtingdata['percent'])){
              
              $rating=$starrtingdata['percent'];
              if(!empty($rating)){
              $html.=str_repeat('<li class="list-inline-item"><i class="fa fa-star"></i></li>',$rating );
            }

              $unratingstar=5-$rating;
              if(!empty($unratingstar)){
                $html.=str_repeat('<li class="list-inline-item"><i class="fa fa-star-o"></i></li>',$unratingstar);
              }


            

    return $html;




      }


    }


}

function courserateinblock($courseid,$numberOfRatings){
    global $DB, $CFG, $USER;
    $ratedata=$DB->get_record('blocks_custom_courserate',array('courseid'=>$courseid));
    if(!empty($ratedata)){
       $starrtingdata=startratingdisplay($ratedata->userrated,$ratedata->startrate5,$ratedata->startrate4,$ratedata->startrate3,$ratedata->startrate2,$ratedata->startrate1);
      $html="";
      if(!empty($starrtingdata['percent'])){
              $html.='<div class="ccn-external-stars">';
              $rating=$starrtingdata['percent'];
              if(!empty($rating)){
              $html.=str_repeat('<li class="list-inline-item"><i class="fa fa-star"></i></li>',$rating );
            }

              $unratingstar=5-$rating;
              if(!empty($unratingstar)){
                $html.=str_repeat('<li class="list-inline-item"><i class="fa fa-star-o"></i></li>',$unratingstar);
              }

              if(!empty($ratedata->userrated)){
                  if(!empty($numberOfRatings)){
                                      $totalrate=$ratedata->userrated+$numberOfRatings;
                              }else{
                                  $totalrate=$ratedata->userrated;
                              }


                 $html.='<li class="list-inline-item"><span>('.$totalrate.')</span></li>';
              }

              $html.='</div>';

    return $html;




      }


    }


}




function startratingdisplay($userrated,$starrate5,$starrate4,$starrate3,$starrate2,$starrate1){
    $maximum=$userrated*5;
     $star5value=5*($userrated*($starrate5/100));
     $star4value=4*($userrated*($starrate4/100));
     $star3value=3*($userrated*($starrate3/100));
     $star2value=2*($userrated*($starrate2/100));
     $star1value=1*($userrated*($starrate1/100));

$allvalue=$star5value+$star4value+$star3value+$star2value+$star1value;
$percent=$allvalue/$maximum*100;
$rateingdata=$percent*5/100;

$data=array();
$data['percent']=ceil($rateingdata);
$data['rateing']=$rateingdata;


return $data;
}

function courserateapidata($url,$data){
       global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);
return $r=courserateenrolledreturndata($result);
}

function courserateenrolledreturndata($data){
    global $DB, $CFG;
    $rateingdata=$DB->get_record('blocks_custom_courserate',array('courseid'=>$data->courseid));
    if(!empty($rateingdata)){
        $topbar_slider=new stdClass();
        $topbar_slider->id=$rateingdata->id;
        $topbar_slider->wooid=$data->wooid;
        $DB->update_record('blocks_custom_courserate', $topbar_slider,true);
        return true;
    }


}


function moodlecategoriesdata($id){
	global $DB, $CFG;
	require_once($CFG->libdir .'/filelib.php');
	    $coursecategories=$DB->get_record('course_categories',array('id'=>$id));
	    $context = context_coursecat::instance($id);
	    $text = file_rewrite_pluginfile_urls($coursecategories->description, 'pluginfile.php', $context->id, 'coursecat', 'description', null);
    	$data=array();
    	$data['id']=$coursecategories->id;
    	$data['name']=$coursecategories->name;
        $data['description']=$text;
        return $data;
}


function apidata($url,$data){
    global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$result=json_decode($response);
 eventcategoriesdataretundata($result->woo_course_categoriesid,$result->moodle_course_categoriesid,$result->woo_categories_tableid);
}


function eventcategoriesdataretundata($woo_course_categoriesid,$moodle_course_categoriesid,$woo_categories_tableid){
 global $DB, $CFG, $USER;

 $coursescategories=$DB->get_record('blocks_custom_categories',array('moodle_course_categoriesid'=>$moodle_course_categoriesid));
        $data=new stdClass();
        $data->woo_course_categoriesid=$woo_course_categoriesid;
        $data->moodle_course_categoriesid=$moodle_course_categoriesid;
        $data->woo_categories_tableid=$woo_categories_tableid;
       
     if(empty($coursescategories)){
         $data->createdtime=time();
        $DB->insert_record('blocks_custom_categories',$data);
        // echo "inserted course categories";
     }else{
        $data->id=$coursescategories->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_categories',$data);
         // echo "updated course categories";
     }

}

function eventmoodlecategoriesdata($id){
  global $DB, $CFG, $USER;
	$coursescategories=$DB->get_record('blocks_custom_categories',array('moodle_course_categoriesid'=>$id));
	if(!empty($coursescategories)){
			$data=array();
			$data['woo_course_categoriesid']=$coursescategories->woo_course_categoriesid;
			$data['moodle_course_categoriesid']=$coursescategories->moodle_course_categoriesid;
			$data['woo_categories_tableid']=$coursescategories->woo_categories_tableid;
			return $data;
	}


}


function deleteapidata($url,$data){
global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);

 eventcategoriesdelete($result->woo_course_categoriesid,$result->moodle_course_categoriesid,$result->woo_categories_tableid);

}
function eventcategoriesdelete($woo_course_categoriesid,$moodle_course_categoriesid,$woo_categories_tableid){
	global $DB, $CFG, $USER;
	 $coursescategories=$DB->get_record('blocks_custom_categories',array('woo_course_categoriesid'=>$woo_course_categoriesid,'moodle_course_categoriesid'=>$moodle_course_categoriesid,'woo_categories_tableid'=>$woo_categories_tableid));
					if(!empty($coursescategories)){
								if($DB->record_exists('blocks_custom_categories',array('id'=>$coursescategories->id))){
                                 $DB->delete_records('blocks_custom_categories',array('id' =>$coursescategories->id));
                                }
                         }
}

function courseapidata($url,$data){
	global $DB, $CFG, $USER;
	$curl = curl_init();
	$senddata=json_encode(array('admin'=>$data));
	curl_setopt_array($curl, array(
 	 CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => '',
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 0,
  	CURLOPT_FOLLOWLOCATION => true,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => 'POST',
  	CURLOPT_POSTFIELDS =>$senddata,
  	CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$result=json_decode($response);

    // print_r($response);
    // die();

$returnvalue=$result->wordpresscourse;
 eventcoursedataretundata($returnvalue);
if(!empty($result->return_moodlecateories)){
	$categoriesreturndata=$result->return_moodlecateories;
  eventcategoriesdataretundata($categoriesreturndata->woo_course_categoriesid,$categoriesreturndata->moodle_course_categoriesid,$categoriesreturndata->woo_categories_tableid);
}



if(!empty($returnvalue->wordpresssection)){
    // $returnsectiondata=$returnvalue->wordpresssection;
      foreach($returnvalue->wordpresssection as $coursesection){
                                eventcoursesectionreturndata($coursesection);
                                foreach($coursesection->moduledata as $coursemodule){
                                     eventcoursemodulereturndata($coursemodule);
                                }

                           }
    
}



}

function moodlecoursecategoriesdata($courseid,$languagedata){
	global $DB, $CFG, $USER;
	require_once($CFG->dirroot. '/course/externallib.php');
	 $coursedata=$DB->get_record('course',array('id'=>$courseid));
	  $coursescategories=$DB->get_record('blocks_custom_categories',array('moodle_course_categoriesid'=>$coursedata->category));
					if(!empty($coursescategories)){
						$data=array();
						$data['courseid']=$coursedata->id;
						$data['fullname']=$coursedata->fullname;
						$data['shortname']=$coursedata->shortname;
						$data['summary']=eventcoursedescription($coursedata->id,$coursedata->summary);
						$data['visible']=$coursedata->visible;
						$data['courseimage']=eventlearncourseimage($coursedata->id);
						$rdata=eventcourseprice($coursedata->id);
						$data['cost']=$rdata['cost'];
						$data['currency']=$rdata['currency'];
						$coursecontentdata=eventlearncoursecontent($coursedata->id);
                        $data['coursealldetails']=eventcoursealldetails($coursedata->id);
                        $data['moodle_course_categoriesid']=$coursedata->category;
                        $data['categories']=moodlecategoriesdata($coursedata->category);
                        $data['activity']=$coursecontentdata;
                        if(!empty($languagedata)){
                           $data['courselanguage']=$languagedata;
                        }
                       


					 }else{

						$data=array();
						$data['moodle_course_categoriesid']=$coursedata->category;
						$data['categories']=moodlecategoriesdata($coursedata->category);
						$data['courseid']=$coursedata->id;
						$data['fullname']=$coursedata->fullname;
						$data['shortname']=$coursedata->shortname;
						$data['summary']=eventcoursedescription($coursedata->id,$coursedata->summary);
						$data['visible']=$coursedata->visible;
						$data['courseimage']=eventlearncourseimage($coursedata->id);
						$rdata=eventcourseprice($coursedata->id);
						$data['cost']=$rdata['cost'];
						$data['currency']=$rdata['currency'];
						$coursecontentdata=eventlearncoursecontent($coursedata->id);
                        $data['coursealldetails']=eventcoursealldetails($coursedata->id);
                        $data['activity']=$coursecontentdata;
                        if(!empty($languagedata)){
                           $data['courselanguage']=$languagedata;
                        }

					 }

                     if(empty(courselanguagedescription($coursedata->id))){

                        

                        $mdesc=array();
                        $mdesc['cdhi']=$_REQUEST['customfield_cdhi_editor']['text'];
                        $mdesc['cdmr']=$_REQUEST['customfield_cdmr_editor']['text'];
                        $mdesc['cdta']=$_REQUEST['customfield_cdta_editor']['text'];


                        $data['multilanguage']=newrequestcourselanguagedesc($coursedata->id,$mdesc);
                        
                       


                     }else{
                         $data['multilanguage']=courselanguagedescription($coursedata->id);
                     }

                    



                    // $data['courserating']=moodlecourseratedata($courseid);
                    // $data['coursedataid']=$courseid;
					 return $data;

}

function newrequestcourselanguagedesc($courseid,$mdesc){
    global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    require_once($CFG->libdir .'/filelib.php');
        $context = context_course::instance($courseid);
        $alldata=array();
        foreach($mdesc as $key => $value){
            $datavalue=$DB->get_record_sql("SELECT * FROM {customfield_field} WHERE `shortname`='".$key."'");
            if(!empty($datavalue)){
                    $getdata=array();
                    $getdata['shortname']=$datavalue->shortname;
                    $getdata['name']=$datavalue->name;
                    $getdata['descriptiondata']=$value;
                    $getdata['courseid']=$courseid;
                    array_push($alldata,$getdata);
            }

        }

        return $alldata;

}


function courselanguagedescription($courseid){
    global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    require_once($CFG->libdir .'/filelib.php');
    $context = context_course::instance($courseid);

    $data=$DB->get_records_sql("SELECT cd.id,cf.shortname,cf.name,cd.value FROM {customfield_field} as cf INNER JOIN {customfield_data} as cd ON cf.id=cd.fieldid WHERE cd.contextid='".$context->id."' and (cf.shortname='cdhi' or cf.shortname='cdmr' or cf.shortname='cdta')");
    $alldata=array();
    foreach($data as $value){
        $getdata=array();
        $getdata['shortname']=$value->shortname;
        $getdata['name']=$value->name;
        $getdata['descriptiondata']=$value->value;
        $getdata['courseid']=$courseid;
        array_push($alldata,$getdata);
    }

return $alldata;

}




function eventcoursedescription($courseid,$coursedescription){
    global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    require_once($CFG->libdir .'/filelib.php');
    $context = context_course::instance($courseid);
   return $text = file_rewrite_pluginfile_urls($coursedescription, 'pluginfile.php', $context->id, 'course', 'summary', null);

}

function eventlearncourseimage($courseid){
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

function eventcourseprice($courseid){
    global $DB, $CFG, $USER;
     $coursespaytm =  $DB->get_record('enrol',array('courseid'=>$courseid,'enrol'=>'paytm','status'=>'0'));
     $coursesrazorpay = $DB->get_record('enrol',array('courseid'=>$courseid,'enrol'=>'razorpay','status'=>'0'));
    $data=array();
    $data['cost']="0";
    $data['currency']="";
    
    if($coursespaytm->cost>=$coursesrazorpay->cost){
          $data['cost']=$coursespaytm->cost;
          $data['currency']=$coursespaytm->currency;
    }else{
        $data['cost']=$coursesrazorpay->cost;
        $data['currency']=$coursesrazorpay->currency;
    }
    if(empty($data['cost'])){
      $data['cost']=0;
    }
    return $data;
}

function eventlearncoursecontent($courseid){
	global $DB, $CFG;
	require_once($CFG->dirroot. '/course/externallib.php');
	$new=new core_course_external();
	$data=$new->get_course_contents($courseid, $options = array());
	return $data;
}

function eventcoursealldetails($courseid){
   global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    $options = array("ids"=>array($courseid));
    $new=new core_course_external();
    $data=$new->get_courses($options);
    return $data;

}

function eventcoursedataretundata($coursedata){
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
       // echo "inserted course";
    }else{
        $data->id=$moodlecourses->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_courses',$data);
        // echo "updated course";
    }

}

function moodlecoursedeletedata($courseid){
	 global $DB, $CFG, $USER;
	 $moodlecourses=$DB->get_record('blocks_custom_courses',array('courseid'=>$courseid));
    if(!empty($moodlecourses)){
    	return $moodlecourses;
    }

}

function deletecourseapidata($url,$data){
global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$result=json_decode($response);
 
eventcoursedelete($result->id,$result->woo_course_categoriesid,$result->moodle_course_categoriesid,$result->woocommerceid,$result->courseid,$result->woocommerce_tableid);

}
function eventcoursedelete($id,$woo_course_categoriesid,$moodle_course_categoriesid,$woocommerceid,$courseid,$woocommerce_tableid){
     global $DB, $CFG, $USER;            
    if($DB->record_exists('blocks_custom_courses',array('id'=>$id))){
     $DB->delete_records('blocks_custom_courses',array('id' =>$id));
    }   


        $data=$DB->get_records('blocks_custom_section',array('moodle_course_id' =>$courseid));
        foreach($data as $sectiondata){
            $DB->delete_records('blocks_custom_section',array('id' =>$sectiondata->id));
        }

         $moduledata=$DB->get_records('blocks_custom_module',array('moodle_courseid' =>$courseid));
        foreach($moduledata as $modulevalue){
            $DB->delete_records('blocks_custom_module',array('id' =>$modulevalue->id));
        }
                      
}

function moodlesectioncreated($sectionid,$courseid,$languagedata){
      global $DB, $CFG, $USER; 
     // $sectiondata=$DB->get_record('blocks_custom_section',array('moodle_section_id'=>$sectionid));
     //        if(empty($sectiondata)){


     //                } 
      $data=moodlecoursecategoriesdata($courseid,$languagedata);
      $data['msectionid']=$sectionid;
      return $data;
}
function sectioncourseapidata($url,$data){
global $DB, $CFG, $USER;
$curl = curl_init();
$senddata=json_encode(array('admin'=>$data));
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$senddata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

$result=json_decode($response);
$returnvalue=$result->wordpresscourse;
 eventcoursedataretundata($returnvalue);
if(!empty($result->return_moodlecateories)){
    $categoriesreturndata=$result->return_moodlecateories;
  eventcategoriesdataretundata($categoriesreturndata->woo_course_categoriesid,$categoriesreturndata->moodle_course_categoriesid,$categoriesreturndata->woo_categories_tableid);
}

if(!empty($returnvalue->wordpresssection)){
    // $returnsectiondata=$returnvalue->wordpresssection;
      foreach($returnvalue->wordpresssection as $coursesection){
                                eventcoursesectionreturndata($coursesection);
                                foreach($coursesection->moduledata as $coursemodule){
                                     eventcoursemodulereturndata($coursemodule);
                                }

                           }
    
}


}

function eventcoursesectionreturndata($sectiondata){
    global $DB, $CFG, $USER;
    $data=new stdClass();
    $data->woo_sectiontable_id=$sectiondata->woo_sectiontable_id;
    $data->moodle_course_id=$sectiondata->moodle_course_id;
    $data->moodle_section_id=$sectiondata->moodle_section_id;
    $moodlesection=$DB->get_record('blocks_custom_section',array('moodle_section_id'=>$sectiondata->moodle_section_id));
    if(empty($moodlesection)){
        $data->createdtime=time();
       $DB->insert_record('blocks_custom_section',$data);
       // echo "inserted section";
    }else{
        $data->id=$moodlesection->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_section',$data);
        // echo "updated section";
    }
}

function eventcoursemodulereturndata($coursemodule){
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
       // echo "inserted module";
    }else{
        $data->id=$moodlemoudle->id;
        $data->updatedtime=time();
        $DB->update_record('blocks_custom_module',$data);
        // echo "updated module";
    }
}

function deletesectionapidata($url,$data){
    global $DB, $CFG, $USER;
    $curl = curl_init();
    $senddata=json_encode(array('admin'=>$data));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$senddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $result=json_decode($response);
    eventmoodlesectionreturndelete($result);

}

function moodlesectiondeleted($moodle_section_id,$courseid){
     global $DB, $CFG, $USER;
        $moodlesectiondata=$DB->get_record('blocks_custom_section',array('moodle_section_id'=>$moodle_section_id,'moodle_course_id'=>$courseid));
        if(!empty($moodlesectiondata)){
            return $moodlesectiondata;
        }
}

function eventmoodlesectionreturndelete($data){
     global $DB, $CFG, $USER;
    $coursesection=$DB->get_record('blocks_custom_section',array('moodle_course_id'=>$data->moodle_course_id,'moodle_section_id'=>$data->moodle_section_id));
                    if(!empty($coursesection)){
                                if($DB->record_exists('blocks_custom_section',array('id'=>$coursesection->id))){
                                 $DB->delete_records('blocks_custom_section',array('id' =>$coursesection->id));
                                }
                         }
}
function moodlemodulecreate($moduleid,$courseid){
  global $DB, $CFG, $USER; 
$data=moodlecoursecategoriesdata($courseid,'');
      $data['moduleid']=$moduleid;
      return $data;
}
function moodlemoduleupdate($moduleid,$courseid,$modulename){
  global $DB, $CFG, $USER; 
$data=moodlecoursecategoriesdata($courseid,'');
      $data['moduleid']=$moduleid;
      $data['modulename']=$modulename;
      return $data;
}

function courseenrol_instance($courseid,$languagedata){
  global $DB, $CFG, $USER; 
  $data=moodlecoursecategoriesdata($courseid,$languagedata);
      // $data['moduleid']=$moduleid;
      return $data;
}

function modulecreateapidata($url,$data){
    global $DB, $CFG, $USER;
    $curl = curl_init();
    $senddata=json_encode(array('admin'=>$data));  
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$senddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $result=json_decode($response);
    $returnvalue=$result->wordpresscourse;
    eventcoursedataretundata($returnvalue);
if(!empty($result->return_moodlecateories)){
    $categoriesreturndata=$result->return_moodlecateories;
  eventcategoriesdataretundata($categoriesreturndata->woo_course_categoriesid,$categoriesreturndata->moodle_course_categoriesid,$categoriesreturndata->woo_categories_tableid);
}

if(!empty($returnvalue->wordpresssection)){
      foreach($returnvalue->wordpresssection as $coursesection){
                                eventcoursesectionreturndata($coursesection);
                                foreach($coursesection->moduledata as $coursemodule){
                                     eventcoursemodulereturndata($coursemodule);
                                }

                           }
    
}

}

function moduledeleteapidata($url,$data){
    global $DB, $CFG, $USER;
    $curl = curl_init();
    $senddata=json_encode(array('admin'=>$data));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$senddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $result=json_decode($response);
   eventmoodlemodulereturndelete($result);

}

function moodlemoduledelete($moodleid,$courseid){
     global $DB, $CFG, $USER;
        $moodlemoduledata=$DB->get_record('blocks_custom_module',array('moodle_module_id'=>$moodleid,'moodle_courseid'=>$courseid));
        if(!empty($moodlemoduledata)){
            return $moodlemoduledata;
        }
}
function eventmoodlemodulereturndelete($data){
   global $DB, $CFG, $USER;
    $coursemodule=$DB->get_record('blocks_custom_module',array('woo_section_tableid'=>$data->woo_section_tableid,'moodle_module_id'=>$data->moodle_module_id));
      if(!empty($coursemodule)){
                  if($DB->record_exists('blocks_custom_module',array('id'=>$coursemodule->id))){
                   $DB->delete_records('blocks_custom_module',array('id' =>$coursemodule->id));
                  }
           }
}
function moodlecourseratecreate($courseid){
  global $DB, $CFG, $USER; 
      $data=moodlecoursecategoriesdata($courseid,'');
      $data['courserating']=moodlecourseratedata($courseid);
      $data['coursedataid']=$courseid;
      return $data;
}
function moodlecourseratedata($courseid){
  global $DB, $CFG, $USER; 
    $data=$DB->get_records('theme_edumy_courserate',array('course'=>$courseid));
    if(!empty($data)){
         return $data;
    }
   
}

function coursecreatelanguae($courseid){
    global $DB, $CFG;
    require_once($CFG->dirroot. '/course/externallib.php');
    require_once($CFG->libdir .'/filelib.php');
     $context = context_course::instance($courseid);
  $data=$DB->get_records_sql("SELECT cd.id,cf.name FROM {customfield_data} as cd JOIN {customfield_field} as cf ON cd.fieldid=cf.id WHERE cd.contextid='".$context->id."' and cd.intvalue='1' and cf.type='checkbox'");
return $data;

}
function cocoon_course_instructor_statussyncdata($blockid,$cinstanceid,$status){
  global $DB, $CFG,$PAGE;

 $data=$DB->get_record('block_instances',array('id'=>$blockid));
$instancevalue=unserialize(base64_decode($data->configdata));
$cv=$DB->get_record_sql("SELECT *  FROM {context} WHERE `id` = '".$cinstanceid."' and `contextlevel`='50'");
$courseid=$cv->instanceid;
$blockid=$blockid;
  if(!empty($instancevalue->user)){
  $userpicture = new user_picture(core_user::get_user($instancevalue->user));
  $userpicture->size = 150; // Size f1.
  $profileimageurl = $userpicture->get_url($PAGE)->out(false);
}
 $userdata=$DB->get_record('user',array('id'=>$instancevalue->user));


$sendata=array();
$sendata['blockid']=$blockid;
$sendata['courseid']=$courseid;
$sendata['title']=$instancevalue->title;
$sendata['firstname']=$userdata->firstname;
$sendata['lastname']=$userdata->lastname;
$sendata['userprofileimg']=$profileimageurl;
$sendata['name']=$instancevalue->name;
$sendata['position']=$instancevalue->position;
$sendata['students']=$instancevalue->students;
$sendata['courses']=$instancevalue->courses;
$sendata['text']=$instancevalue->bio['text'];
$sendata['description']=$userdata->description;
$sendata['visible']=$status;


$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
$url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/statusaboutacademysync.php';
 $curl = curl_init();
    $sendeddata=json_encode(array('admin'=>$sendata));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$sendeddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);



}


function cocoon_course_intro_deletesyncdata($blockid,$cinstanceid){
   global $DB, $CFG,$PAGE;
  $data=$DB->get_record('block_instances',array('id'=>$blockid));
$instancevalue=unserialize(base64_decode($data->configdata));
$cv=$DB->get_record_sql("SELECT *  FROM {context} WHERE `id` = '".$cinstanceid."' and `contextlevel`='50'");
$courseid=$cv->instanceid;
$blockid=$blockid;

$sendata=array();
$sendata['blockid']=$blockid;
$sendata['courseid']=$courseid;


$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
$url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/deletecourse_intromysync.php';
 $curl = curl_init();
    $sendeddata=json_encode(array('admin'=>$sendata));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$sendeddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);


}


function cocoon_course_instructor_deletesyncdata($blockid,$cinstanceid){
   global $DB, $CFG,$PAGE;
  $data=$DB->get_record('block_instances',array('id'=>$blockid));
$instancevalue=unserialize(base64_decode($data->configdata));
$cv=$DB->get_record_sql("SELECT *  FROM {context} WHERE `id` = '".$cinstanceid."' and `contextlevel`='50'");
$courseid=$cv->instanceid;
$blockid=$blockid;

$sendata=array();
$sendata['blockid']=$blockid;
$sendata['courseid']=$courseid;


$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
$url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/deleteaboutacademysync.php';
 $curl = curl_init();
    $sendeddata=json_encode(array('admin'=>$sendata));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$sendeddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);


}

function cocoon_course_intro_syncdata($blockid,$cinstanceid,$status){
    global $DB, $CFG,$PAGE;
  $data=$DB->get_record('block_instances',array('id'=>$blockid));
$instancevalue=unserialize(base64_decode($data->configdata));
$cv=$DB->get_record_sql("SELECT *  FROM {context} WHERE `id` = '".$cinstanceid."' and `contextlevel`='50'");
$courseid=$cv->instanceid;
$blockid=$blockid;



$extraimg=$DB->get_record_sql("SELECT * FROM {context} WHERE `contextlevel` = '80' AND `instanceid` = '".$blockid."'");


$rdata=$DB->get_record_sql("SELECT *  FROM {files} WHERE `contextid` = '".$extraimg->id."' and `component`='block_cocoon_course_intro' and filename!='.'");
if(!empty($rdata->filename)){

$profileimageurl=$CFG->wwwroot."/pluginfile.php/".$extraimg->id."/block_cocoon_course_intro/content/".$rdata->filename;


}else{
    if(!empty($instancevalue->user)){
      $userpicture = new user_picture(core_user::get_user($instancevalue->user));

      $udata;
      $userpicture->size = 150; // Size f1.
      $profileimageurl = $userpicture->get_url($PAGE)->out(false);
    }

}


 $userdata=$DB->get_record('user',array('id'=>$instancevalue->user));
$sendata=array();
$sendata['blockid']=$blockid;
$sendata['courseid']=$courseid;
$sendata['firstname']=$userdata->firstname;
$sendata['lastname']=$userdata->lastname;
$sendata['userprofileimg']=$profileimageurl;
$sendata['show_teacher']=$instancevalue->show_teacher;
$sendata['video_url']=$instancevalue->video_url;
$sendata['accent']=$instancevalue->accent;
$sendata['teacher']=$instancevalue->teacher;
$sendata['visible']=$status;



$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
 $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/courseintrosync.php';
 $curl = curl_init();
    $sendeddata=json_encode(array('admin'=>$sendata));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$sendeddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);


}
function cocoon_course_instructor_syncdata($blockid,$cinstanceid){
  global $DB, $CFG,$PAGE;
  $data=$DB->get_record('block_instances',array('id'=>$blockid));
$instancevalue=unserialize(base64_decode($data->configdata));
$cv=$DB->get_record_sql("SELECT *  FROM {context} WHERE `id` = '".$cinstanceid."' and `contextlevel`='50'");
$courseid=$cv->instanceid;
$blockid=$blockid;
  if(!empty($instancevalue->user)){
  $userpicture = new user_picture(core_user::get_user($instancevalue->user));
  $userpicture->size = 150; // Size f1.
  $profileimageurl = $userpicture->get_url($PAGE)->out(false);
}
 $userdata=$DB->get_record('user',array('id'=>$instancevalue->user));


$sendata=array();
$sendata['blockid']=$blockid;
$sendata['courseid']=$courseid;
$sendata['title']=$instancevalue->title;
$sendata['firstname']=$userdata->firstname;
$sendata['lastname']=$userdata->lastname;
$sendata['userprofileimg']=$profileimageurl;
$sendata['name']=$instancevalue->name;
$sendata['position']=$instancevalue->position;
$sendata['students']=$instancevalue->students;
$sendata['courses']=$instancevalue->courses;
$sendata['text']=$instancevalue->bio['text'];
$sendata['description']=$userdata->description;


$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
$url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/aboutacademysync.php';
 $curl = curl_init();
    $sendeddata=json_encode(array('admin'=>$sendata));
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$sendeddata,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);



}

function local_coursedatasync_extend_navigation(global_navigation $navigation) {

    $settings = new stdClass;
    $settings->enabled = 0;
    $settings->flatenabled = 0;
    if(is_siteadmin()){
    $settings->menuitems = get_string('courserating', 'local_coursedatasync').' | '.new moodle_url('/local/coursedatasync/index.php');
    }
    if (!empty($settings->menuitems) && $settings->enabled) {
        $menu = new custom_menu($settings->menuitems, current_language());
        if ($menu->has_children()) {
            foreach ($menu->get_children() as $item) {
                coursedatasync_custom_menu_item($item, 0, null, $settings->flatenabled);
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
function coursedatasync_custom_menu_item(custom_menu_item $menunode, $parent, $pmasternode, $flatenabled) {
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
            $masternode = $pmasternode->add(local_coursedatasync_get_string($menunode->get_text()),
                                            $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
        } else {
            $masternode = $PAGE->navigation->add(local_coursedatasync_get_string($menunode->get_text()),
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
            $childnode = $pmasternode->add(local_coursedatasync_get_string($menunode->get_text()),
                                        $url, navigation_node::TYPE_CUSTOM);
            $childnode->title($menunode->get_title());
        } else {
            $masternode = $PAGE->navigation->add(local_coursedatasync_get_string($menunode->get_text()),
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
function local_coursedatasync_get_string($string) {
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

function local_coursedatasync_extend_settings_navigation(){}

