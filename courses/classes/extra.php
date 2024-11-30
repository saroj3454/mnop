<?php

namespace local_courses;
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use theme_moove\output\core_renderer;


use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;



use pix_icon;
use theme_config;
use core_text;
use help_icon;
use context_system;

use local_courses\extra;
use theme_moove\util\theme_settings;

defined('MOODLE_INTERNAL') || die();


/**
 * @class Deals with Category.
 */
class extra
{

     /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */



     public function vrtuz_menu1() {
        global $USER, $CFG,$DB;
        $templateContext = new stdClass();
        $extracom = new extra;
        $themesettings = new theme_settings;
        $list = "";

        if (!isloggedin()) {

             
            
            $list .= '<li class="nl-simple" aria-haspopup="true"><a href="/login/index.php">Login</a></li>';
            }

        else{

            $user = $DB->get_record('user', array('id' => $USER->id));
            
            $name= $user->firstname.' '.$user->lastname;
            $sess=$USER->sesskey;


            $list.='<li aria-haspopup="true">
                <a href="#" class="lang-select">
                    <img src="https://vrtuz.com/pluginfile.php/5/user/icon/moove/f2?rev=1359" />'.$name.'<span class="wsarrow"></span>
                </a>
                <ul class="sub-menu last-sub-menu">
                    <li aria-haspopup="true"><a href="https://vrtuz.com/my/"><span class="fa fa-dashboard"></span>Dashboard</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/user/profile.php?id='.$USER->id.'"><span class="icon fa slicon-user fa-fw "></span>Profile</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/grade/report/overview/index.php"><span class="icon fa slicon-book-open fa-fw  "></span>GRAGES</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/message/index.php"><span class="icon fa slicon-bubble fa-fw " title="Messages" aria-label="Messages"></span>MESSAGE</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/user/preferences.php"><span class= "icon fa slicon-wrench fa-fw "></span>PREFERENCES</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/course/switchrole.php?id=1&switchrole=-1&returnurl=%2F"><span class="icon fa fa-user-secret fa-fw "></span>SWITCH ROLE TO</a></li>
                    <li aria-haspopup="true"><a href="https://vrtuz.com/login/logout.php?sesskey=.'.$sess.'"><span class="fa fa-logout"></span>LOGOUT</a></li>
                    
                </ul>
            </li>';
            
        }

        return $list; 
    }




    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG,$PAGE,$DB;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }
        

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = '';
            if (!$loginpage) {
                $returnstr .= "<a class='btn btn-login-top' href=\"$loginurl\">" . get_string('login') . '</a>';
            }

            $theme = theme_config::load('moove');

            if (!$theme->settings->disablefrontpageloginbox) {
                return html_writer::tag(
                    'li',
                    html_writer::span(
                        $returnstr,
                        'login'
                    ),
                    array('class' => $usermenuclasses)
                );
            }

            $context = [
                'loginurl' => $loginurl,
                'logintoken' => \core\session\manager::get_login_token(),
                'canloginasguest' => $CFG->guestloginbutton and !isguestuser(),
                'canloginbyemail' => !empty($CFG->authloginviaemail),
                'cansignup' => $CFG->registerauth == 'email' || !empty($CFG->registerauth)

            ];

            return $this->render_from_template('theme_moove/frontpage_guest_loginbtn', $context);
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, @$this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = '';

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $actionmenu = new action_menu();
        $actionmenu->set_menu_trigger(
            $returnstr
        );
        $actionmenu->set_alignment(action_menu::TR, action_menu::BR);
        $actionmenu->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;

            // Adds username to the first item of usermanu.
            $userinfo = new stdClass();
            $userinfo->itemtype = 'text';
            $userinfo->title = $user->firstname . ' ' . $user->lastname.'<br>ID:&nbsp;'.$user->username;;
            $userinfo->url = new moodle_url('/user/profile.php', array('id' => $user->id));
            $userinfo->pix = 'i/user';
            //$userinfo->username1 =$user->username;

            array_unshift($opts->navitems, $userinfo);

            
            foreach ($opts->navitems as $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $actionmenu->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'text':
                        $amls = new action_menu_link_secondary(
                            $value->url,
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall')),
                            $value->title,
                            array('class' => 'text-username')
                        );

                        $actionmenu->add($amls);
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $amls = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $amls->attributes['data-title'] = $value->titleidentifier;
                        }
                        $actionmenu->add($amls);
                        break;

                       
                        
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount) {
                    $actionmenu->add($divider);
                }
            }
        }

        return html_writer::tag(
            'li',
            $this->render($actionmenu),
            array('class' => $usermenuclasses)
        );
    }


    public function getLogo(){
         $theme = \theme_config::load('moove');
        return $theme->setting_file_url('logo', 'logo');
    }

    public function getFooterLogo(){
         $theme = \theme_config::load('moove');
        return $theme->setting_file_url('footerlogo', 'footerlogo');
    }

    public function favicon() {
        global $CFG;
        $theme = \theme_config::load('moove');
        $favicon = $theme->setting_file_url('favicon', 'favicon');
        if (!empty(($favicon))) {
            $urlreplace = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $favicon = str_replace($urlreplace, '', $favicon);
            return new moodle_url($favicon);
        }
    }

    public function footerContent(){
        $theme = \theme_config::load('moove');
        return $theme->settings->getintouchcontent;
    }

    public function footerContentSocial(){
        $theme = \theme_config::load('moove');
        $settingsthese = new \stdClass;
        $settingsthese->instagram =  $theme->settings->instagram;
        $settingsthese->facebook =  $theme->settings->facebook;
        $settingsthese->linkedin =  $theme->settings->linkedin;
        $settingsthese->twitter =  $theme->settings->twitter;
        $settingsthese->youtube =  $theme->settings->youtube;
        $settingsthese->whatsapp =  $theme->settings->whatsapp;
        $settingsthese->mail =  $theme->settings->mail;
        $settingsthese->mobile = $theme->settings->mobile;
        return $settingsthese;  
    }

    public function siteName(){
        global $SITE;
        return $SITE->shortname;
    }

    public function limittext($text, $limit, $blogid) {
        $text = strip_tags($text);
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

    public function get_user_picture($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $user = $DB->get_record('user', array('id' => $userid));

        $userimg = new \user_picture($user);

        $userimg->size = $imgsize;

        return $userimg->get_url($PAGE);
    }
    public function is_login_page() {
        global $PAGE;
        // This is a real bit of a hack, but its a rarety that we need to do something like this.
        // In fact the login pages should be only these two pages and as exposing this as an option for all pages
        // could lead to abuse (or at least unneedingly complex code) the hack is the way to go.
        return in_array(
            $PAGE->url->out_as_local_url(false, array()),
            array(
                '/login/index.php',
                '/login/forgot_password.php',
            )
        );
    }

}



