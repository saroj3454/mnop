<?php 
function wordpresslogout(\core\event\user_loggedout $event){
global $DB, $USER,$CFG;

    if($event->objecttable=="user" && $event->action=="loggedout"){ 
        $url=$CFG->wordpressurl.'/wp-content/plugins/customattributes/logout.php';
        redirect($url);
        
     	  }
}
function user_updated(\core\event\user_updated $event){
global $DB, $USER,$CFG;

    if($event->target=="user" && $event->action=="updated"){ 
    	$wp_userid="";
    	if(is_siteadmin())
			{
				$syncuser=$DB->get_record('sync_user',array('moodleuserid'=>$event->objectid));
				if(!empty($syncuser)){
					$wp_userid=base64_encode($syncuser->wordpressuserid);
				}
				
			}
    	$record=$DB->get_record('user',array('id'=>$event->objectid));
		$newpassword="";
		if(!empty($_POST['newpassword'])){
		$newpassword=base64_encode($_POST['newpassword']);
		}
		$record=$DB->get_record('user',array('id'=>$event->objectid));
		$url=$CFG->wordpressurl."/wp-content/plugins/customattributes/update_profile.php?firstname=".base64_encode($_POST['firstname'])."&lastname=".base64_encode($_POST['lastname'])."&uname=".base64_encode($record->username)."&email=".base64_encode($record->email)."&userid=".base64_encode($event->objectid)."&actionuserid=".base64_encode($event->userid)."&npass=".$newpassword."&wpuser=".$wp_userid;
		redirect($url);
        
     }
}

function user_password_updated(\core\event\user_password_updated $event){
	global $DB, $USER,$CFG;
// echo "<pre>";
// 	print_r($event);
// echo "---";
// print_r($_POST);
// die();
	if(!empty($_POST['newpassword2'])){
		if(!empty($event->userid)){ 
			$syncuser=$DB->get_record('sync_user',array('moodleuserid'=>$event->userid));
			$wp_userid=base64_encode($syncuser->wordpressuserid);
			if(!empty($_POST['newpassword2'])){
					$newpassword=base64_encode($_POST['newpassword2']);
			}
			$url=$CFG->wordpressurl."/wp-content/plugins/customattributes/update_password.php?npass=".$newpassword."&wpuser=".$wp_userid;
			redirect($url);
	   }
	}
//forget_password
	if(!empty($_POST['password2'])){
		if(!empty($event->relateduserid)){ 
					$syncuser=$DB->get_record('sync_user',array('moodleuserid'=>$event->relateduserid));
					$wp_userid=base64_encode($syncuser->wordpressuserid);
					if(!empty($_POST['password2'])){
							$newpassword=base64_encode($_POST['password2']);
					}
					$url=$CFG->wordpressurl."/wp-content/plugins/customattributes/update_password.php?npass=".$newpassword."&wpuser=".$wp_userid."&forget=forget";
					redirect($url);
			   }
	}

	




}