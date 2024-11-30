<?php
require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
// global $PAGE;
// $PAGE->requires->jquery();
 
//  $message="success";
//  $to="info.vrtuz@gmail.com";
// $name=$_POST['contactName'];
// $email=$_POST['contactEmail'];
// $message=$_POST['Subject'];
// $message=$_POST['contactMessage'];


// if($send){
//   $msg['status']=true;
//   $msg['msg']="Feedback successfully";
//   echo json_encode($msg);
// }else{
//     $msg['status']=false;
//   $msg['msg']="Something wrong, Please try after sometime";
//   echo json_encode($msg);
// }


// 'contactName='+$("#s_name").val()+'&contactEmail='+
//                             $("#s_email").val() +'&Subject='+$("#s_subject").val()
//                             +'&contactMessage='+
//                             $("#s_message").val(),
$fromUser = "info.vrtuz@gmail.com";
$emailuser = new stdClass();
        $mailcontent='<html> <body><table border="1" style="width:80%;margin:auto">
  <tbody><tr>
    <th colspan="2" style="background:steelblue none repeat scroll 0 0;color:#fff;font-size:20px;padding:25px"><span class="il"> Contact Us/</span> <span class="il">Feedback </span> Form</th>
  
   
  </tr>

  <tr>
    <td style="padding:10px;padding-left:35px;background:#eee;width:50%">Name</td>
    <td style="padding:10px;padding-left:35px;background:#eee;width:50%">'.$_POST["contactName"].'</td>
   
  </tr>
   
  <tr>
     <td style="padding:10px;padding-left:35px;background:#eee;width:50%">Email</td>
    <td style="padding:10px;padding-left:35px;background:#eee;width:50%"><a href="mailto:'.$_POST["contactEmail"].'" target="_blank">'.$_POST["contactEmail"].'</a></td>
  
  </tr>
  <tr>
   <td style="padding:10px;padding-left:35px;background:#eee;width:50%">Subject</td>
    <td style="padding:10px;padding-left:35px;background:#eee;width:50%">'.$_POST["Subject"].'</td>
    
  </tr>
  

   <tr>
   <td style="padding:10px;padding-left:35px;background:#eee;width:50%">Message</td>
    <td style="padding:10px;padding-left:35px;background:#eee;width:50%">'.$_POST["contactMessage"].'</td>
    
  </tr>
  
  
</tbody></table> </body></html>';
        $subject = 'Contact Us/Feedback Form';
        $emailuser->email = "info.vrtuz@gmail.com";
        $emailuser->firstname = "First Name";
        $emailuser->lastname= "Last name";
        $emailuser->maildisplay = true;
        $emailuser->mailformat = 1; 
        $emailuser->id = 1;
        $emailuser->firstnamephonetic = false;
        $emailuser->lastnamephonetic = false;
        $emailuser->middlename = false;
        $emailuser->username = false;
        $emailuser->alternatename = false;
        $mail = email_to_user($emailuser,$fromUser, $subject, $message = '', $mailcontent);
         $emailuser->email = "info.vrtuz@gmail.com";
        email_to_user($emailuser,$fromUser, $subject, $message = '', $mailcontent);
        if($mail){
  
  echo $msg['msg']="Contact Us/Feedback successfully";
  
}