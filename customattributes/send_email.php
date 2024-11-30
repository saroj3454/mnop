  <?php require_once("../../../wp-config.php");
  global $wpdb;

  if (empty($_SESSION['webenquiry'])) {
    wp_redirect(site_url());
    exit();
  }


  if (empty($_POST)) {
    wp_redirect(site_url());
    exit();
  }
  $table_name = $wpdb->prefix . 'enquiry_emailsetting';
  $emaildata = $wpdb->get_row("select * from $table_name where `id`='1'");
  function get_client_ip()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }
  $message = '<html><body>';
  $message .= "<p style='font-size:14px;'>Hi  ,<p>";

  // echo "<pre>";
  // print_r($_POST);
  // die();

  date_default_timezone_set('asia/kolkata');
  $datetime = date('M-d-Y h:i:s a', time());

  if (!empty($_POST['payment_confirmid'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'equiryemail';
    $datauser = $wpdb->get_row("select * from $table_name where `id`='" . $_POST['payment_confirmid'] . "'");
    $programdata = $wpdb->get_row("select * from " . $wpdb->prefix . "enquiry_program where `id`='" . $datauser->program . "'");

    if (!empty($programdata->sub_program)) {
      $wrydata = $wpdb->get_row("select * from " . $wpdb->prefix . "enquiry_workshop where `workshopname`='" . $datauser->workshop . "'");
      $redirect = $wrydata->workshop_link;
      $rprice = $wrydata->workshop_price;
    } else {
      $redirect = $programdata->programlink;
      $rprice = $programdata->programprice;
    }

    $data = array('type' => 'applynow', 'createddate' => time(), 'firstname' => $datauser->firstname, 'lastname' => $datauser->lastname, 'emailid' => $datauser->emailid, 'mobile' => $datauser->mobile, 'dob' => $datauser->dob, 'gender' => $datauser->gender, 'program' => $datauser->program, 'workshop' => $datauser->workshop, 'state' => $datauser->state, 'city' => $datauser->city, 'address1' => $datauser->address1, 'address2' => $datauser->address2, 'pincode' => $datauser->pincode, 'edu_degree' => $datauser->edu_degree, 'payment_confim' => '1', 'payment_conf_time' => time(), 'pay_price' => $rprice, 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());



    $subject = "Confirm and pay user details";
    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $datauser->firstname . " " . $datauser->lastname . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $datauser->emailid . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $datauser->mobile . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Dob : </b><span>" . $datauser->dob . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Gender : </b><span>" . $datauser->gender . "</span></p>";
    if (!empty($datauser->edu_degree)) {
      $message .= "<p style='font-size:14px;'><b>Qualification : </b><span>" . $datauser->edu_degree . "</span></p>";
    }
    $message .= "<p style='font-size:14px;'><b>Program : </b><span>" . $programdata->programname . "</span></p>";
    if (!empty($datauser->workshop) && !empty($programdata->sub_program)) {
      $message .= "<p style='font-size:14px;'><b>" . $programdata->sub_program . " : </b><span>" . $datauser->workshop . "</span></p>";
    }

    $message .= "<p style='font-size:14px;'><b>State : </b><span>" . $datauser->state . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>City : </b><span>" . $datauser->city . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Address1 : </b><span>" . $datauser->address1 . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Address2 : </b><span>" . $datauser->address2 . "</span></p>";


    $message .= "<p style='font-size:14px;'><b>Pincode : </b><span>" . $datauser->pincode . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Price : </b><span>" . $rprice . "</span></p>";


    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  }





  if ($_POST['formtype'] == "applynow") {
    $programdata = $wpdb->get_row("select * from " . $wpdb->prefix . "enquiry_program where `id`='" . $_POST['qualification'] . "'");
    $data = array('type' => 'applynow', 'createddate' => time(), 'firstname' => $_POST['first_name'], 'lastname' => $_POST['last_name'], 'emailid' => $_POST['email'], 'mobile' => $_POST['mobile_no'], 'dob' => $_POST['date_of_birth'], 'gender' => $_POST['gender'], 'program' => $_POST['qualification'], 'workshop' => $_POST['workshops'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'address1' => $_POST['address1'], 'address2' => $_POST['address2'], 'pincode' => $_POST['pin_code'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    $redirecturl = site_url("/preview/");
    if (!empty($_POST['submitid'])) {
      $subject = "Apply now enquiry details user updated";
    } else {
      $subject = "Apply now enquiry details";
    }

    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['first_name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $_POST['mobile_no'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Dob : </b><span>" . $_POST['date_of_birth'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Gender : </b><span>" . $_POST['gender'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Program : </b><span>" . $programdata->programname . "</span></p>";
    if (!empty($_POST['workshops']) && !empty($programdata->sub_program)) {
      $message .= "<p style='font-size:14px;'><b>" . $programdata->sub_program . " : </b><span>" . $_POST['workshops'] . "</span></p>";
    }

    $message .= "<p style='font-size:14px;'><b>State : </b><span>" . $_POST['state'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>City : </b><span>" . $_POST['city'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Address1 : </b><span>" . $_POST['address1'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Address2 : </b><span>" . $_POST['address2'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Pincode : </b><span>" . $_POST['pin_code'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  } else if ($_POST['formtype'] == "registerfor_crpto_currency") {

    $data = array('type' => 'registerfor_crpto_currency', 'createddate' => time(), 'firstname' => $_POST['first_name'], 'lastname' => $_POST['last_name'], 'emailid' => $_POST['email'], 'mobile' => $_POST['mobile_no'], 'dob' => $_POST['date_of_birth'], 'gender' => $_POST['gender'], 'work_experience' => $_POST['work_experience'], 'carrer' => $_POST['carrer'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'edu_institute_name' => $_POST['edu_institute_name'], 'edu_passout_date' => $_POST['edu_passout_date'], 'edu_degree' => $_POST['edu_degree'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    $redirecturl = $emaildata->razorpay_link;
    $subject = "Crypto currency enquiry details";
    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['first_name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $_POST['mobile_no'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Dob : </b><span>" . $_POST['date_of_birth'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Gender : </b><span>" . $_POST['gender'] . "</span></p>";


    $message .= "<p style='font-size:14px;'><b>State : </b><span>" . $_POST['state'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>City : </b><span>" . $_POST['city'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Total Years of Experience : </b><span>" . $_POST['work_experience'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Career Area : </b><span>" . $_POST['carrer'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Name of Institute : </b><span>" . $_POST['edu_institute_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Month & Year of Graduation : </b><span>" . $_POST['edu_passout_date'] . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Degree Level : </b><span>" . $_POST['edu_degree'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  } else if ($_POST['formtype'] == "enquirynow") {
    $data = array('type' => 'enquirynow', 'createddate' => time(), 'firstname' => $_POST['name'], 'emailid' => $_POST['email_id'], 'mobile' => $_POST['phone'], 'message' => $_POST['messagee'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    if ($apfwa_enquiry == true) {
      $redirecturl = site_url('/apfwa-thank-you/');
    } else {
      $redirecturl = site_url('/thank-you/');
    }



    $subject = "Enquiry now details";


    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email_id'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $_POST['phone'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Message : </b><span>" . $_POST['messagee'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  } else if ($_POST['formtype'] == "brochure") {
   // echo "<pre>";
   // print_r($_POST);
    //die;
    $data = array('type' => 'brochure', 'createddate' => time(), 'firstname' => $_POST['name'], 'emailid' => $_POST['email'], 'mobile' => $_POST['phone'],'message' => $_POST['messagee'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    // $_SESSION['downloadbrochure']  = true;
    $redirecturl = site_url("/brochure/");
    $subject = "Brochure enquiry details";
    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $_POST['phone'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  } else if ($_POST['formtype'] == "get_6monthsyllabus") {
    $data = array('type' => 'get_6monthsyllabus', 'createddate' => time(), 'firstname' => $_POST['name'], 'emailid' => $_POST['email'], 'mobile' => $_POST['phone'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    $redirecturl = site_url("/thank-you/");
    $subject = "Get 6 Month Syllabus enquiry details";
    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Phone : </b><span>" . $_POST['phone'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";

    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  } else if ($_POST['formtype'] == "contactus") {
    $data = array('type' => 'contactus', 'createddate' => time(), 'firstname' => $_POST['name'], 'emailid' => $_POST['email'], 'mobile' => $_POST['phone'], 'message' => $_POST['comment'], 'ip' => get_client_ip(), 'brower_details' => $_SERVER['HTTP_USER_AGENT'], 'date' => date('Y-m-d'), 'time' => time());
    $redirecturl = site_url("/thank-you/");
    $subject = "Contact us enquiry details";
    $message .= "<p style='font-size:16px;'><b> " . $subject . "</b></p>";

    $message .= "<p style='font-size:14px;'><b>Name : </b><span>" . $_POST['name'] . " " . $_POST['last_name'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Email-id : </b><span>" . $_POST['email'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Mobile : </b><span>" . $_POST['phone'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Comment : </b><span>" . $_POST['comment'] . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Time : </b><span>" . $datetime . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Ip : </b><span>" . get_client_ip() . "</span></p>";
    $message .= "<p style='font-size:14px;'><b>Browser Details : </b><span>" . $_SERVER['HTTP_USER_AGENT'] . "</span></p>";
  }
  $sendata = $wpdb->insert($wpdb->prefix . 'equiryemail', $data);
  $lastid = base64_encode($wpdb->insert_id);
  $subject = $subject;
  // To send HTML mail, the Content-type header must be set
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  // Additional headers
  $headers .= 'From: no-reply@oawa.in' . "\r\n";
  //$headers .= 'Cc: '.$u_email.'' . "\r\n";

  // Compose a simple HTML email message 

  $message .= "<p style='font-size:18px;'>Thanks</p>";

  $message .= '</body></html>';
  // Sending email
  if (!empty($emaildata->email)) {
    foreach (explode(",", $emaildata->email) as $sendmailid) {
      $sent = wp_mail($sendmailid, $subject, $message, $headers);
    }
  }

  if ($_POST['formtype'] == "applynow") {
    $redirect = $redirecturl . "?id=" . $lastid;
  } else if ($_POST['formtype'] == "registerfor_crpto_currency") {
    $redirect = $redirecturl;
  } else if ($_POST['formtype'] == "enquirynow") {
    $redirect = $redirecturl . "?status=" . $lastid;
  } else if ($_POST['formtype'] == "brochure") {
    $redirect = $redirecturl . "?status=" . $lastid;
  } else if ($_POST['formtype'] == "get_6monthsyllabus") {
    $redirect = $redirecturl . "?status=" . $lastid;
  } else if ($_POST['formtype'] == "contactus") {
    $redirect = $redirecturl . "?status=" . $lastid;
  }
  wp_redirect($redirect);
  exit();
