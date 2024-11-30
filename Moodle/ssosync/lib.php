<?php 

function syncauthapidata(){
       global $PAGE, $CFG,$DB;
    $condata=get_config('local_ssosync');
     $data=array();
     $data['clientid']=$condata->clientid;
     $data['secretkey']=$condata->secretkey;
     if(!empty($condata->wordpressurl)){
                if(substr($condata->wordpressurl, -1) == '/') {
               $fapiurl=$condata->wordpressurl;
                }else{
                $fapiurl=$condata->wordpressurl."/";
                } 
      }
     $data['wordpressurl']=$fapiurl;
     if(!empty($condata->secretkey) && !empty($condata->clientid)){
        $data['dataavl']='avl';
     }else{
        $data['dataavl']=''; 
     }
    return $data;
}


function wordpressPagesdata(){
   global $PAGE, $CFG,$DB;
  $avlpage=$DB->get_record('local_ssosync_pages');
  $alldata=array();
    foreach($avlpage as $value){
      $data=array();
      $data['post_title']=$value->post_title;
      $data['post_url']=$value->post_url;
      array_push($alldata,$data);
    }
    return $alldata;

}

function wordpressPages($data){
   global $PAGE, $CFG,$DB;
   foreach($data as $value){
      if(!empty($value)){
         $avlpage=$DB->get_record('local_ssosync_pages',array('post_title'=>$value['post_title']));
         if(empty($avlpage)){
            $post =new stdClass();
            $post->postid=$value['postid'];
            $post->post_title=$value['post_title'];
            $post->post_url=$value['post_url'];
            $post->created=time();
            $DB->insert_record('local_ssosync_pages', $post);  
            
         }else{
            $post =new stdClass();
            $post->postid=$value['postid'];
            $post->post_title=$value['post_title'];
            $post->post_url=$value['post_url'];
            $post->id=$avlpage->id;
            $post->updated=time();
            $DB->update_record('local_ssosync_pages', $post);
         }     
      }
   }
     $wavlpage=$DB->get_record('local_ssosync_pages',array('post_title'=>'Login to Moodle'));
     if(!empty($wavlpage)){
            $loginurl=$DB->get_record('config',array('name'=>'alternateloginurl'));
            if(!empty($loginurl)){
               $wurl=new stdClass();
               $wurl->value=$wavlpage->post_url;
               $wurl->id=$loginurl->id;
               // $DB->update_record('config',$wurl); 
               set_config('alternateloginurl',$wavlpage->post_url);
               purge_caches();
            }
     }


return true;
}

function moodleloginurlDeactive(){
    global $PAGE, $CFG,$DB;
  set_config('alternateloginurl','');
  purge_caches(); 
  return "sucess"; 
}

function wordpressUsers($data){
     global $PAGE, $CFG,$DB;
      if(empty($data['last_name'])){
         $datalastname=$data['first_name'];
      }else{
        $datalastname=$data['last_name'];
      }


if(!empty($data['user_login']) && !empty($data['first_name']) && !empty($datalastname)){
   $password="";
  
   
   $return=array();
   $user_name=$data['user_login'];
   $fname=$data['first_name'];
   $lname=$datalastname;
   $email=$data['user_email'];
   if(!empty($data['user_image'])){
   $userimage=$data['user_image'];
   }
  $userinfos=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name' or email='$user_name' or email='$email' or username='$email'");
   if(empty($userinfos)){
      $userinsert  = new stdClass();
    $userinsert->username = $user_name;
   if(!empty($data['user_pass'])){
    $password=$data['user_pass'];
     $password=md5($password);
    }else{
       $password=md5("P@ssw0rd");
    }
    $userinsert->password= $password;
    $userinsert->firstname= $fname;
    $userinsert->lastname= $lname;
    $userinsert->email= $email;
    $userinsert->timecreated= time();
    $userinsert->timemodified= time();
    $userinsert->middlename= " ";
    $userinsert->confirmed= 1;
    $userinsert->mnethostid= 1;
      if(!empty($password)){
        $insertRecords=$DB->insert_record('user', $userinsert);
        if(!empty($data['user_image']) && !empty($insertRecords)){
        $data=userimagedata($insertRecords,$userimage);
        }
        $return['status']="sucess";
        $return['userid']=$insertRecords;
        return $return;
      }
    

   }else{

    $userinsert  = new stdClass();
    $userinsert->id=$userinfos->id;
    $userinsert->username=$user_name;
    $userinsert->firstname= $fname;
    $userinsert->lastname= $lname;
    if(!empty($data['user_pass'])){
    $password=$data['user_pass'];
    $password=md5($password);
    $userinsert->password= $password;
    }
  
    $userinsert->email= $email;
    $userinsert->timemodified= time();
    $userinsert->middlename= " ";
    $userinsert->confirmed= 1;
    $userinsert->mnethostid= 1;
    $DB->update_record('user', $userinsert);
      if(!empty($userinfos->id)){
     $data=userimagedata($userinfos->id,$userimage);
     
                if($data=='sucess'){
                $return['status']="sucess";
                $return['userid']=$userinfos->id; 
                return $return;
                }
      }else{
    $return['status']="sucess";
    $return['userid']=$userinfos->id; 
   
    return $return;
      }
    
   }
}

}
function userimagedata($userid,$imageurl){
  global $PAGE, $CFG,$DB, $USER;
  require_once($CFG->libdir.'/adminlib.php');
  require_once($CFG->libdir.'/filelib.php');
   $path=$imageurl;
   // $imgData = base64_encode(file_get_contents($path));
   $wsfiledata_decoded = file_get_contents($path);
   $fs = get_file_storage();
   $context = context_user::instance($userid, MUST_EXIST);
   $user = core_user::get_user($userid, '*', MUST_EXIST);
   $USER = $user;
   $newpicture = $user->picture;
   $userdata=$DB->get_record('user',array('id'=>$userid));
   $fileinfo = array(
      'contextid' =>$context->id, 
      'component' => 'user',
      'filearea' => 'newicon',     // usually = table name
      'itemid' => '0',               // usually = ID of row in table
      'filepath' => "/",           // any path beginning and ending in /
      'filename' =>'f1.jpg'
   ); // any filename
   if(!empty($path)){
      $user->imagefile = 0;
      $fs->create_file_from_string($fileinfo, $wsfiledata_decoded);
      $filemanageroptions = array('maxbytes'       => $CFG->maxbytes,
           'subdirs'        => 0,
           'maxfiles'       => 1,
           'accepted_types' => 'optimised_image');
      file_prepare_draft_area($user->imagefile, $context->id, 'user', 'newicon', 0, $filemanageroptions);
      $user->imagefile = $user->imagefile;
   } else {
      $user->deletepicture = 1;

   }
   core_user::update_picture($user, $filemanageroptions);
 // $DB->set_field('user', 'picture',$imagedataid, array('id' => $userid));
   $DB->delete_records("files", array("contextid"=>$context->id, 'component' => 'user','filearea' => 'newicon'));
   // \core\event\user_updated::create_from_userid($userid)->trigger();
    purge_caches();
return "sucess";
   // }

}

function authtokengenerate($userid){
    global $PAGE, $CFG,$DB;
   $token = openssl_random_pseudo_bytes(16);
      $token = bin2hex($token);
      $tokendata=new stdClass();
      $tokendata->token=$token;
      $data=new stdClass();
      $data->userid=$userid;
      $data->authtoken=$token;
      $data->created=time();
      $alldata=$DB->insert_record('local_ssosync_authtoken',$data);  
      $authdata=$DB->get_record('local_ssosync_authtoken',array('userid'=>$userid));
      return $authdata->authtoken;
}

function local_ssosync_after_require_login() {
    global $DB, $CFG, $USER,$OUTPUT;
      if(!empty($USER->id)){
          $userdata = $DB->get_record("user", array("id"=>$USER->id));
         if($userdata->picture!=$USER->picture){
                  $USER->picture=$userdata->picture;

         }
      }

// echo $_SERVER['PHP_SELF'];
// die();

    if(!is_siteadmin($USER->id)){
      if($_SERVER['PHP_SELF']=='/user/editadvanced.php' || $_SERVER['PHP_SELF']=='/login/change_password.php'){
         $pagedata=syncauthapidata();
         if(!empty($pagedata['wordpressurl'])){
           $baseurl=$pagedata['wordpressurl'];
         }
        redirect($baseurl."wp-admin/profile.php");
      }
    }   
}


function local_ssosync_extend_change_password_form(){
    global $DB, $CFG, $USER;
      if(!empty($USER->id)){
         $userdata = $DB->get_record("user", array("id"=>$USER->id));
         if($userdata->picture!=$USER->picture){
          $USER->picture=$userdata->picture;
         }
      }
       if(!is_siteadmin($USER->id)){
             if($_SERVER['PHP_SELF']=='/user/editadvanced.php' || $_SERVER['PHP_SELF']=='/login/change_password.php'){
                  $pagedata=syncauthapidata();
                  if(!empty($pagedata['wordpressurl'])){
                    $baseurl=$pagedata['wordpressurl'];
                  }
                  redirect($baseurl."wp-admin/profile.php");
               }

       }

}

function local_ssosync_after_config(){
   global $DB, $CFG, $USER;
    if(!empty($USER->id)){
         $userdata = $DB->get_record("user", array("id"=>$USER->id));
$passworddata=checkpasswordSync($userdata->username,$userdata->email);
// print_r($passworddata);
// die();
         if($userdata->picture!=$USER->picture){
                  $USER->picture=$userdata->picture;
         }
         if($userdata->firstname!=$USER->firstname){
            $USER->firstname=$userdata->firstname;
         }
         if($userdata->lastname!=$USER->lastname){
            $USER->lastname=$userdata->lastname;
         }
      } 
 if(!is_siteadmin($USER->id)){
             if($_SERVER['PHP_SELF']=='/user/edit.php'){
                  $pagedata=syncauthapidata();
                  if(!empty($pagedata['wordpressurl'])){
                    $baseurl=$pagedata['wordpressurl'];
                  }
                  redirect($baseurl."wp-admin/profile.php");
               }

       }


}

function checkpasswordSync($username,$email){
  global $DB, $CFG, $USER;
    $condata=get_config('local_ssosync');
        if(!empty($condata->wordpressurl)){
          // echo $username."----".$email;
  // echo $condata->wordpressurl.'wp-content/plugins/ssoconfig/passwordauth.php';
                    $curl = curl_init();
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => $condata->wordpressurl.'wp-content/plugins/ssoconfig/passwordauth.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('username' => $username,'email' => $email),
                  ));

                  $response = curl_exec($curl);
                    // var_dump($response);
                    // print_r($response);
                  curl_close($curl);
                  return $response;





            
      }
}
