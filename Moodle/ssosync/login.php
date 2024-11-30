<?php require_once("../../config.php");
global $DB, $OUTPUT, $PAGE, $USER;
if(!empty($USER->id)){
  redirect($CFG->wwwroot);
}

if(!empty($_POST['submit'])){
  $returndata=array();

  if(!empty($_POST['username'])){
 
   
   $userdata=$DB->get_record_sql("SELECT id FROM {user} where username='".$_POST['username']."' or email='".$_POST['username']."'");
    if(!empty($userdata)){
          $admins = get_admins();
          $isadmin = false;
          foreach($admins as $admin) {
              if ($userdata->id == $admin->id) {
                  $isadmin = true;
                  break;
              }
          }

          if ($isadmin) {
                $user = authenticate_user_login($_POST['username'],$_POST['password'], false, $_POST['logintoken']);
                 if(!empty($user)){
                      $userdata = $DB->get_record("user", array("id"=>$user->id));
                       complete_user_login($userdata);
                      \core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());
                      redirect($CFG->wwwroot);
                 }else{
                   $returndata['error']='Wrong Credential';
                 }


          } else { 
            $returndata['error']='Using Only Admin Credential';
          }


    }else{
      // $returndata['error']='User Record Not Found';
      $returndata['error']='Wrong Credential';
    }  
  }

}


?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style type="text/css">
    *{
      padding: 0px;
      margin: 0px;
      font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
    }
  #wrapper{
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    overflow: none; 
    margin: 0px;
    padding: 0px;
    background-color: #143957;
  }
  .adminWrap{
    background-color: #f5f5f5;
    padding: 50px 30px;
    border-radius: 5px;
    width: 25%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .adminHeading{
    margin: 20px 0px;
  }

  .input input {width: 94%;padding: 10px 10px;margin: 10px 0px;font-size: 18px;}
  .input label {padding: 10px 0px;margin: 10px 0px;font-size: 18px;}

  .w-100{width: 100%;}

  .adminSubmit{width: 100%;background-color: #000333;padding: 8px;cursor: pointer; border: 1px solid #000333;font-size: 18px;margin: 10px 0;border-radius: 2px;color:  #fff;}

  .adminSubmit:hover{background-color: #c0d907; border: 1px solid #c0d907;}

  @media(max-width: 768px){
    .adminWrap{
      width: 70%;
    }
  }
</style>

</head>
<body>

  <div id="wrapper">
  <div class="adminWrap">
    <div class="adminHeading">
      <h1>Admin Login</h1>
    </div>
    <div class="w-100">
        <form method="post">
          <input type="hidden" name="logintoken" value="<?php echo \core\session\manager::get_login_token(); ?>">
          <div class="input">
             <label for='adminUser'>User Name:</label><br>
             <input type='text' id='adminUser' name='username' placeholder='User Name'>
          </div>
          <div class="input">
             <label for='adminPassword'>Password</label><br>
             <input type='password' id='adminPassword' name='password' placeholder='Password'>
          </div>
          <div>
             <input type='submit' name='submit' value='Submit' class="adminSubmit">
          </div>

        <?php if(!empty($returndata['error'])){ echo "<span style='color:red'>".$returndata['error']."</span>"; } ?>
 
        </form> 
    </div>
  </div>
  </div>


</body>
</html>