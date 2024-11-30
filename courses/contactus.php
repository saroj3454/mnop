<?php
require_once(__DIR__ . '/../../config.php');
global $CFG,$USER,$DB;
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST)) {
	$name=$_POST['contactName'];
	$email=$_POST['contactEmail'];
	$message=$_POST['contactMessage'];

	$record = new stdClass();
	$record->name = $name;
	$record->email = $email;
	$record->message = $message;

	$table='contact_us';
	$result=$DB->insert_record($table, $record, false);
	if($result!=''){
			
		 $table1='<h2 style="text-align:center">************* Contact From Details ***************</h2>';
          $table1.='<table>';
          $table1.='<tr><td>Sr.No.</td><td>Name</td><td>Email</td><td>Message</td></tr>';
          $table1.='<body>';
          $table1.= "<tr><td>1</td><td>".$record->name."</td><td>".$record->email."</td><td>".$record->message."</td></tr>";
 		  $table1.='</body>';
          $table1.='</table>';
         
          
          	 $messagehtml =   "<p>Hi Admin,</p><br>".$table1."<br>
                    <p>Thanks, </p>
                    <p>VRTUZ Team</p>";
                        $fromUser =$record->email;
                        $subject = 'Contact Form Reveived from'.' '.$name;
                        $emailuser1='contact@solutionatease.com';
                        
                        $emailuser = new stdClass();
                        $emailuser->email = $emailuser1;
                        //$emailuser->firstname = $firstname;
                        //$emailuser->lastname= $lastname;
                        $emailuser->maildisplay = true;
                        $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
                        $emailuser->id = 1;
                        $emailuser->firstnamephonetic = false;
                        $emailuser->lastnamephonetic = false;
                        $emailuser->middlename = false;
                        $emailuser->username = false;
                        $emailuser->alternatename = false;

                        // Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						// More headers
						$headers .= 'From: <'.$fromUser.'>'."\r\n";
						//$headers .= 'Cc: myboss@example.com' . "\r\n";
						                         //print_r($emailuser);

                        
        	                 if(mail($emailuser->email,$subject,$messagehtml,$headers)){
			                     echo '<div class="alert alert-success alert-dismissible">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Success!</strong> Message sent.
									</div>';
			                 }else{
			                     echo '<div class="alert alert-danger alert-dismissible">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <strong>Oops!</strong> Message not sent.
								</div>';
			                 }
                        
		
			}
}


//DATABASE QUERY HERE.

?>