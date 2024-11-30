<?php
function user_auth($userdata){
   global $DB,$CFG;
   $sql="SELECT * FROM {user} WHERE suspended=? AND (username=? OR email=?) AND deleted=? AND confirmed=? ";
   $data=$DB->get_record_sql($sql,array(0,$userdata['username'],$userdata['email'],0,1));
   $token = openssl_random_pseudo_bytes(32);
   $token = bin2hex($token);
  
   if(!empty($data)){
      $std=new stdClass();
      $std->userid=$data->id;
      $std->token=$token;
      $std->status=1;
      $std->createddate=time();
      if($DB->insert_record('user_tokens', $std)){
         $msg['status']=true;
         $msg['msg']="Token generated successfully";
         $msg['data']=$std;
         $msg['launch_url']=$CFG->wwwroot.'/local/oawa_auth/launch/?userid='.$std->userid.'&token='.$std->token;
         return $msg;
      }else{
         $msg['status']=false;
         $msg['msg']="something wrong";
         return $msg;
      }
   }else{
     $std=new stdClass();
     $std->username=$userdata['username'];
     $std->email=$userdata['email'];
     $std->confirmed=1;
     $std->deleted=0;
     $std->suspended=0;
     $std->password=md5(randomPassword());
     $std->firstname=$userdata['first_name'];
     $std->lastname=$userdata['last_name'];
     $std->mnethostid=1;
     $std->timecreated=time();

     if($userid=$DB->insert_record('user',$std)){
         $std1=new stdClass();
         $std1->userid=$userid;
         $std1->token=$token;
         $std1->status=1;
         $std1->createddate=time();
         if($DB->insert_record('user_tokens', $std1)){
            $msg['status']=true;
            $msg['msg']="Token generated successfully";
            $msg['data']=$std1;
            $msg['lunch_url']=$CFG->wwwroot.'/local/oawa_auth/launch/?userid='.$std1->userid.'&token='.$std1->token;
            return $msg;
         }else{
            $msg['status']=false;
            $msg['msg']="something wrong";
            return $msg;
         }
     }else{
         $msg['status']=false;
         $msg['msg']="something wrong";

         return $msg;
     }
   }
   
}
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function getCourse(){
  global $DB;
  $sql="SELECT * FROM {course} WHERE visible=?";
  $coursedata=$DB->get_records_sql($sql,array(1));
  return $coursedata;
}

function user_enrollments($data){

}
function user_registration($userdata){
  global $DB;
  $sql="SELECT * FROM {user} WHERE username=? OR email=?";
  $data=$DB->get_record_sql($sql,array($userdata['username'],$userdata['email']));
  if(empty($data)){
     $std=new stdClass();
     $std->username=$userdata['username'];
     $std->email=$userdata['email'];
     $std->confirmed=1;
     $std->deleted=0;
     $std->suspended=0;
     $std->password=md5($userdata['password']);
     $std->firstname=$userdata['fname'];
     $std->lastname=$userdata['lname'];
     $std->mnethostid=1;
     $std->timecreated=time();

     if($userid=$DB->insert_record('user',$std)){
      $wordpressuser=new stdClass();
      $wordpressuser->moodleuserid=$userid;
      $wordpressuser->wordpressuserid=$userdata['wordpressuserid'];
      $wordpressuser->createdtime=time();
      $sync=$DB->insert_record('sync_user',$wordpressuser);
              if(!empty($sync)){
                $msg['status']=true;
                $msg['msg']="User added successfully";
                return $msg;
              }
          
         }else{
            $msg['status']=false;
            $msg['msg']="something wrong";
            return $msg;
         }

  }else{
    $msg['status']=false;
    $msg['msg']="user already exists";
    return $msg;
  }
}
function admin_Auth($admin,$id){
  if(!empty($admin) && !empty($id)){
    if($admin=='oawa' && $id=='2'){
      global $PAGE, $CFG,$DB;
       $authtk=$DB->get_record('admin_tokens',array('admin_userid'=>'2'));
       if(!empty($authtk)){
        $DB->delete_records('admin_tokens',array('admin_userid'=>'2'));
       }

     $token = openssl_random_pseudo_bytes(16);
      $token = bin2hex($token);
      $data=new stdClass();
      $data->admin_userid='2';
      $data->token=$token;
      $alldata=$DB->insert_record('admin_tokens',$data);  
      $authdata=$DB->get_record('admin_tokens',array('admin_userid'=>'2'));
      return $authdata->token;
    }
  }
    
}
function user_Validate($userdata){
   global $DB,$CFG;
   $sql="SELECT * FROM {user} WHERE suspended=? AND (username=? OR email=?) AND deleted=? AND confirmed=? ";
   $data=$DB->get_record_sql($sql,array(0,$userdata['username'],$userdata['email'],0,1));
   if(!empty($data)){
    return $data->id; 
   }
 }

 function allCourseName($valueCourseid){
  global $DB, $CFG;
  $data=$DB->get_record('course',array('id'=>$valueCourseid));
 return $return="<a href='".$CFG->wwwroot."/course/view.php?id=".$data->id."'>".$data->fullname."</a>";
}

 function courseURL($valueCourseid){
  global $DB, $CFG;
  $data=$DB->get_record('course',array('id'=>$valueCourseid));
 return $return="<a href='".$CFG->wwwroot."/course/view.php?id=".$data->id."'>".$data->fullname."</a> URL : ".$CFG->wwwroot."/course/view.php?id=".$data->id."";
} 
function courseredirectURL($valueCourseid){
  global $DB, $CFG;
  $data=$DB->get_record('course',array('id'=>$valueCourseid));
 return $return=$CFG->wwwroot."/course/view.php?id=".$data->id;
}
function courseName($valueCourseid){
  global $DB, $CFG;
  $data=$DB->get_record('course',array('id'=>$valueCourseid));
 return $return=$data->fullname;
}

 function user_Enrolled($data){
  global $DB,$CFG;
  if(!empty($data['course']) && !empty($data['userid']) && !empty($data['orderid'])){
  $moodledata=$DB->get_record('mail_confirmation',array('orderid'=>$data['orderid']));
    if(empty($moodledata)){

        $arraycourse=array();
        $courseurl=array();
        $allCourse=array();
        $redirectdcoursepage=array();

             foreach (explode(",",$data['course']) as $valueCourseid) {
                enrolCourse($valueCourseid, $data['userid'],'5','0',time());
                
                array_push($allCourse, allCourseName($valueCourseid));
                array_push($arraycourse, courseName($valueCourseid));
                array_push($courseurl,courseURL($valueCourseid));
                array_push($redirectdcoursepage,courseredirectURL($valueCourseid));
                

             }
            $allcoursenameurl = implode(",", $allCourse);
            $coursename = implode(",", $arraycourse);
            $courseurl = implode("<br>", $courseurl);
            foreach (explode(",",$data['course']) as $valueCourseid) {
                $context = get_context_instance(CONTEXT_COURSE, $valueCourseid);        
                $teachers = get_role_users('3', $context);
                foreach ($teachers as $teachersvalue) {
                emailTeacher($teachersvalue->firstname,$teachersvalue->lastname,$teachersvalue->email,$valueCourseid,$data['userid']);
                }
                $admins = get_admins();
                foreach($admins as $admin) {
                  emailAdmin($admin->firstname,$admin->lastname,$admin->email,$valueCourseid,$data['userid'],$data['userconformation'],$coursename);
                }
            }


    $r=emailStudent($data['userid'],$coursename,$courseurl,$allcoursenameurl);
$retunsucessdata=array();
   if(count($redirectdcoursepage)==1){
     $retunsucessdata['redirecturl']=$redirectdcoursepage['0'];
   }else{
  $retunsucessdata['redirecturl']=$CFG->wwwroot."/my/";
   }

    if($r){
     $vtest=new stdClass();
     $vtest->course_id= $data['course'];
     $vtest->userid= $data['userid'];
     $vtest->createdtime=time();
     $vtest->orderid=$data['orderid'];
     $return=$DB->insert_record('mail_confirmation', $vtest);
     $retunsucessdata['returnid']=$return;
      return $retunsucessdata;
    }

}
    }  

 }

function emailAdmin($firstname,$lastname,$email,$valueCourseid,$userid,$admindetails,$coursename){
  global $DB,$CFG;
  date_default_timezone_set('asia/kolkata');
$date = date('M-d-Y h:i:s a', time());

   $userdata = $DB->get_record('user', array('id' => $userid));
$messagehtml=$messagehtml = "
<html>
<head>
<title></title>
</head>
<body>
<p>Hi ".$firstname." ".$lastname." ,</p>
<p><a href='".$CFG->wwwroot."/user/profile.php?id=".$userid."'><b>".$userdata->firstname." ".$userdata->lastname."</b></a> student has been enrolled in <b>".allCourseName($valueCourseid)."</b> courses </p>
<p>Student enrolled ".allCourseName($valueCourseid)." course time <b>".$date."</b></p>
<br>

<table  border='1' style='width:400px'><tr><th colspan='2' style='background:#61CEC9;color:white;'>Transaction Details</th></tr>

  <tr><td style='width:50%'>Course Name </td><td>".$coursename."</td></tr>
  <tr><td style='width:50%'>Payment Method</td><td>".$admindetails['_payment_method']."</td></tr>
  <tr><td style='width:50%'>Payment Method Title</td><td>".$admindetails['_payment_method_title']."</td></tr>
  <tr><td style='width:50%'>User Ip Address</td><td>".$admindetails['_customer_ip_address']."</td></tr>
  <tr><td style='width:50%'>User Browser </td><td>".$admindetails['_customer_user_agent']."</td></tr>
  <tr><td style='width:50%'>Billing First Name</td><td>".$admindetails['_billing_first_name']."</td></tr>
  <tr><td style='width:50%'>Billing Last Name</td><td>".$admindetails['_billing_last_name']."</td></tr>
  <tr><td style='width:50%'>Billing Address</td><td>".$admindetails['_billing_address_1']."</td></tr>
  <tr><td style='width:50%'>Billing City</td><td>".$admindetails['_billing_city']."</td></tr>
  <tr><td style='width:50%'>Billing State</td><td>".$admindetails['_billing_state']."</td></tr>
  <tr><td style='width:50%'>Billing Postcode</td><td>".$admindetails['_billing_postcode']."</td></tr>
  <tr><td style='width:50%'>Billing Country</td><td>".$admindetails['_billing_country']."</td></tr>
  <tr><td style='width:50%'>Billing Email</td><td>".$admindetails['_billing_email']."</td></tr>
  <tr><td style='width:50%'>Billing Phone</td><td>".$admindetails['_billing_phone']."</td></tr>
  <tr><td style='width:50%'>Currency</td><td>".$admindetails['_order_currency']."</td></tr>
  <tr><td style='width:50%'>Order Total</td><td>".$admindetails['_order_total']."</td></tr>
  <tr><td style='width:50%'>Order_id</td><td>".$admindetails['_order_key']."</td></tr>
  <tr><td style='width:50%'>Transaction Id</td><td>".$admindetails['_transaction_id']."</td></tr>
</table>

<br>

Thanks<br>
OAWA
</body>
</html>";
  $subject = courseName($valueCourseid).' Student Enrolled Confirmation';
    $emailuser = new stdClass();
    $emailuser->email = $email;
    $emailuser->maildisplay = true;
    $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $emailuser->id = 1;
    $emailuser->firstnamephonetic = false;
    $emailuser->lastnamephonetic = false;
    $emailuser->middlename = false;
    $emailuser->username = false;
    $emailuser->alternatename = false;
    $admiMail = email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
    return true;
}






function emailTeacher($firstname,$lastname,$email,$valueCourseid,$userid){
  global $DB,$CFG;
  date_default_timezone_set('asia/kolkata');
$date = date('M-d-Y h:i:s a', time());

   $userdata = $DB->get_record('user', array('id' => $userid));
$messagehtml=$messagehtml = "
<html>
<head>
<title></title>
</head>
<body>
<p>Hi ".$firstname." ".$lastname." ,</p>
<p><a href='".$CFG->wwwroot."/user/profile.php?id=".$userid."'><b>".$userdata->firstname." ".$userdata->lastname."</b></a> student has been enrolled in <b>".allCourseName($valueCourseid)."</b> courses </p>
<p>Student enrolled ".allCourseName($valueCourseid)." course time <b>".$date."</b></p>
<br>
Thanks<br>
OAWA
</body>
</html>";
  $subject = courseName($valueCourseid).' Student Enrolled Confirmation';
    $emailuser = new stdClass();
    $emailuser->email = $email;
    $emailuser->maildisplay = true;
    $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $emailuser->id = 1;
    $emailuser->firstnamephonetic = false;
    $emailuser->lastnamephonetic = false;
    $emailuser->middlename = false;
    $emailuser->username = false;
    $emailuser->alternatename = false;
    $admiMail = email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
    return true;
}

function emailStudent($userid,$coursename,$courseurl,$allcoursenameurl){
  global $DB,$CFG;
  $userdata = $DB->get_record('user', array('id' => $userid));
$messagehtml=$messagehtml = "
<html>
<head>
<title></title>
</head>
<body>
<p>Hi <b>".$userdata->firstname." ".$userdata->lastname."</b> ,</p>

<p>You have enrolled in <b>".$allcoursenameurl."</b> course</p>
<p>Your course  ".$courseurl."</p>
<br>
Thanks<br>
OAWA
</body>
</html>";

$subject = 'Course Enrolled Confirmation';
    $emailuser = new stdClass();
    $emailuser->email =  $userdata->email;
    $emailuser->maildisplay = true;
    $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $emailuser->id = 1;
    $emailuser->firstnamephonetic = false;
    $emailuser->lastnamephonetic = false;
    $emailuser->middlename = false;
    $emailuser->username = false;
    $emailuser->alternatename = false;
    $admiMail = email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
    return true;

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