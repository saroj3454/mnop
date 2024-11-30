<?php 
require_once("../../config.php");
require_once("lib.php");
$data = json_decode(file_get_contents('php://input'), true); 
if(!empty($data['clientid']) && !empty($data['secretkey'])){
	$syncauthdata=syncauthapidata();
	if(!empty($syncauthdata['dataavl'])){
		if($data['clientid']==$syncauthdata['clientid'] && $data['secretkey']==$syncauthdata['secretkey']){
			if(!empty($data['authstatus'])){
				$data=array();
				$data['secure']="secure";
			 echo json_encode($data);
			}
			if(!empty($data['action'])){
				//wordpress loginpage and register page data sync
				if($data['action']=='wordpressurl'){
					if(!empty($data['pagedata'])){
					 echo json_encode(wordpressPages($data['pagedata']));
					}
				}
				//userregister
				if($data['action']=='moodleregister'){
					if(!empty($data['userdata'])){
					 echo json_encode(wordpressUsers($data['userdata']));
					}
				}
				//userloginurl
				if($data['action']=='moodledeactiveloginurl'){
						if(!empty($data['moodledeactive'])){
						echo json_encode(moodleloginurlDeactive($data['userdata']));
						}
				}


			}
		}

	}
}



