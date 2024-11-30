<?php

namespace local_courses;

error_reporting(E_ALL);
ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use local_courses\extra;
use theme_moove\util\theme_settings;

defined('MOODLE_INTERNAL') || die();

/**
 * @course class 
 */
class Commingsoon 
{



    function __construct()
    {

        $this->templateContext = new stdClass;
        $this->extracom = new extra;
        $this->themesettings = new theme_settings;
        echo $this->index();
    }

    public function index(){
        global $OUTPUT;

        $this->templateContext->logo = $this->extracom->getLogo();
        $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
        $this->templateContext->favicon = $this->extracom->favicon();
        $this->templateContext->sitename = $this->extracom->siteName();
        $this->templateContext->footerContent = $this->extracom->footerContentSocial();
        $this->templateContext->getintouchcontent = $this->extracom->footerContent();
        $this->templateContext->coursesmenu = $this->themesettings->coursecatedata();

        return $OUTPUT->render_from_template('local_courses/commingsoon', $this->templateContext);

       
    }



}