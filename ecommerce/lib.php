<?php

/**
 * Extend navigation to add new options.
 *
 * @package    local_navigation
 * @author     Carlos Escobedo <http://www.twitter.com/carlosagile>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  2017 Carlos Escobedo <http://www.twitter.com/carlosagile>)
 */

use local_portal\listeners\auth;

defined('MOODLE_INTERNAL') || die();
function local_ecommerce_extend_navigation(global_navigation $navigation) {
    global $DB, $USER, $CFG, $PAGE;
    $sidemenu="ecommerce | ". $CFG->wwwroot."/local/ecommerce/index.php";
    $url = $CFG->wwwroot."/local/ecommerce/index.php";
    $masternode = $PAGE->navigation->add("ecommerce", $url, navigation_node::TYPE_CONTAINER);
    $masternode->title("ecommerce");
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
function local_ecommerce_custom_menu_item(custom_menu_item $menunode, $parent, $pmasternode, $flatenabled) {
    global $PAGE, $CFG;
    static $submenucount = 0;
    if ($menunode->has_children()) {
        echo $menunode->has_children();
        die;
        $submenucount++;
        $url = $CFG->wwwroot;
        if ($menunode->get_url() !== null) {
            $url = new moodle_url($menunode->get_url());
        } else {
            $url = null;
        }
        if ($parent > 0) {
            $masternode = $pmasternode->add(local_ecommerce_get_string($menunode->get_text()),
                                            $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
        } else {
            $masternode = $PAGE->navigation->add(local_ecommerce_get_string($menunode->get_text()),
                                            $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
            if ($flatenabled) {
                $masternode->isexpandable = true;
                $masternode->showinflatnavigation = true;
            }
        }
        // echo "<pre>";
        // print_r($menunode->get_children());
        foreach ($menunode->get_children() as $menunode) {
            local_designer_custom_menu_item($menunode, $submenucount, $masternode, $flatenabled);
        }
        // print_r($menunode);
        // echo "</pre>";
        // die;
    } else {
        $url = $CFG->wwwroot;
        if ($menunode->get_url() !== null) {
            $url = new moodle_url($menunode->get_url());
        } else {
            $url = null;
        }
        if ($parent) {
            $childnode = $pmasternode->add(local_ecommerce_get_string($menunode->get_text()),
                                        $url, navigation_node::TYPE_CUSTOM);
            $childnode->title($menunode->get_title());
        } else {

            $masternode = $PAGE->navigation->add(local_ecommerce_get_string($menunode->get_text()), $url, navigation_node::TYPE_CONTAINER);
            $masternode->title($menunode->get_title());
            // echo "<pre>";
            // print_r($menunode);
            // var_dump($menunode->get_title());
            // var_dump($flatenabled);
            // die;
            if ($flatenabled) {
                $masternode->isexpandable = true;
                $masternode->showinflatnavigation = true;
            }
        }
    // die;
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
function local_ecommerce_get_string($string) {
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

function local_ecommerce_for_get_confirmation_email($user, $resetrecord) {
    global $CFG;

    $site = get_site();
    $supportuser = core_user::get_support_user();
    $pwresetmins = isset($CFG->pwresettime) ? floor($CFG->pwresettime / MINSECS) : 30;


    $data = new stdClass();

    $data->sitename  = format_string($site->fullname);
    $data->token      = $resetrecord->token;
    $data->resetminutes = $pwresetmins;
    $data->user = $user;

    //send the reset email using CMS as message HTML Template
    auth::send_password_reset_email($data);
}

function userAssignerole($userid){
     global $DB, $CFG, $OUTPUT, $PAGE, $SESSION;
   $data=$DB->get_record('user',array('id'=>$userid,'auth'=>'oauth2'));
 if(!empty($data)){       
        $companydata = $DB->get_records_sql = $DB->get_records_sql("SELECT c.* FROM {company_domains} as cd INNER JOIN {company} as c on cd.companyid=c.id where c.suspended='0' AND cd.domain LIKE '%" . $_SERVER['SERVER_NAME'] . "%'");
            if (!empty($companydata)) {
                // IOMAD.
                foreach ($companydata as $companyvalue) {
                    if (!empty($companyvalue->id)) {
                        require_once($CFG->dirroot . '/local/iomad/lib/company.php');
                        $company = new company($companyvalue->id);

                        // assign the user to the company.
                        $company->assign_user_to_company($userid);

                        // Assign them to any department.
                        $defaultdepartment = company::get_company_parentnode($companyvalue->id);
                        $company->assign_user_to_department($defaultdepartment->id, $userid);

                      
                        if ($CFG->local_iomad_signup_autoenrol) {
                            $userrecord=$DB->get_record('user',array('id'=>$userid));
                            $company->autoenrol($userrecord);
                        }
                    }
                }
           }
   }
}

function local_ecommerce_process_password_set($token) {
    global $DB, $CFG, $OUTPUT, $PAGE, $SESSION;
    require_once($CFG->dirroot.'/user/lib.php');
    require_once($CFG->dirroot."/login/lib.php");

    $pwresettime = isset($CFG->pwresettime) ? $CFG->pwresettime : 1800;
    $sql = "SELECT u.*, upr.token, upr.timerequested, upr.id as tokenid
              FROM {user} u
              JOIN {user_password_resets} upr ON upr.userid = u.id
             WHERE upr.token = ?";
    $user = $DB->get_record_sql($sql, array($token));
    $forgotpasswordurl = "";
    if (empty($user) or ($user->timerequested < (time() - $pwresettime - DAYSECS))) {
    
        echo get_string('noresetrecord');
        echo html_writer::tag('br', '', array());
        echo html_writer::tag('button','Continue', array('class'=>"btn btn-primary", "data-toggle"=>"modal","data-target"=>"#forgot-form"));
       
        die; // Never reached.
    }
    if ($user->timerequested < (time() - $pwresettime)) {
        // There is a reset record, but it's expired.
        // Direct the user to the forgot password page to request a password reset.
        $pwresetmins = floor($pwresettime / MINSECS);

            echo get_string('resetrecordexpired', '', $pwresetmins);
            echo html_writer::tag('br', '', array());
            echo html_writer::tag('button','Continue', array('class'=>"btn btn-primary", "data-toggle"=>"modal","data-target"=>"#forgot-form"));

        die; // Never reached.
    }

    if ($user->auth === 'nologin' or !is_enabled_auth($user->auth)) {
        // Bad luck - user is not able to login, do not let them set password.
        //echo $OUTPUT->header();
        print_error('forgotteninvalidurl');
        die; // Never reached.
    }

    // Check this isn't guest user.
    if (isguestuser($user)) {
        print_error('cannotresetguestpwd');
    }
    echo $OUTPUT->render_from_template('local_ecommerce/forgetpassword',array());


if(!empty($_POST['forget_submit'])){
    if(!empty($_POST['password']) && !empty($_POST['cpassword'])){

       if($_POST['password']==$_POST['cpassword']){
        $data=new stdClass();
        $data->username=$user->username;
        $data->token=$token;
        $data->password=$_POST['password'];
        $data->password2=$_POST['cpassword'];
        $data->submitbutton="Save changes";

         // Delete this token so it can't be used again.
        $DB->delete_records('user_password_resets', array('id' => $user->tokenid));
        $userauth = get_auth_plugin($user->auth);
        if (!$userauth->user_update_password($user, $data->password)) {
            print_error('errorpasswordupdate', 'auth');
        }
        user_add_password_history($user->id, $data->password);
        if (!empty($CFG->passwordchangelogout)) {
            \core\session\manager::kill_user_sessions($user->id, session_id());
        }
        // Reset login lockout (if present) before a new password is set.
        login_unlock_account($user);
        // Clear any requirement to change passwords.
        unset_user_preference('auth_forcepasswordchange', $user);
        unset_user_preference('create_password', $user);

        if (!empty($user->lang)) {
            // Unset previous session language - use user preference instead.
            unset($SESSION->lang);
        }
        complete_user_login($user); // Triggers the login event.

        \core\session\manager::apply_concurrent_login_limit($user->id, session_id());

        $urltogo = core_login_get_return_url();
        unset($SESSION->wantsurl);

        // Plugins can perform post set password actions once data has been validated.
        core_login_post_set_password_requests($data, $user);

        redirect($urltogo, get_string('passwordset'), 1);
        }else{
              redirect($CFG->wwwroot.'/local/ecommerce/forgot_password.php?token='.$token, "Password and Confirm password not match", null, \core\output\notification::NOTIFY_ERROR);
        }  



    }else{
        //  redirect(new moodle_url("/"), "Invalid Event", null, \core\output\notification::NOTIFY_ERROR);
    }
}

    

    // Token is correct, and unexpired.
    
   
  
}