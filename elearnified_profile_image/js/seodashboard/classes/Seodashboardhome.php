<?php

namespace local_seodashboard;

error_reporting(E_ALL);
ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use theme_moove\util\theme_settings;

defined('MOODLE_INTERNAL') || die();

class Seodashboardhome 
{

    function __construct()
    {

        $this->templateContext = new stdClass;
        echo $this->index();

    }

    public function index(){
        global $CFG,$OUTPUT,$DB;
        $attribute=array('rel'=>'stylesheet','href'=>$CFG->wwwroot.'/local/seodashboard/assest/style.css');
        $css=html_writer::tag("link",'',$attribute);
        
        $thsql="SELECT count(id) FROM {searchda_categories_seo}";

        $thirddata=$DB->count_records_sql($thsql);
        $second=$DB->count_records_sql("SELECT count(id) FROM {searchda_categories_secondseo}");
        $first=$DB->count_records_sql("SELECT count(id) FROM {searchda_categories_firstseo}");


        $firstemptyseo=$DB->count_records_sql("SELECT count(id) FROM {searchda_categories_firstseo} WHERE  keywords  IS NULL OR LTRIM(RTRIM(keywords)) = '' OR description  IS NULL OR LTRIM(RTRIM(description)) = ''");
        $secondemptyseo=$DB->count_records_sql("SELECT count(id) FROM {searchda_categories_secondseo} WHERE  keywords  IS NULL OR LTRIM(RTRIM(keywords)) = '' OR description  IS NULL OR LTRIM(RTRIM(description)) = ''");
        $thirdemptyseo=$DB->count_records_sql("SELECT count(id) FROM {searchda_categories_seo} WHERE  keywords  IS NULL OR LTRIM(RTRIM(keywords)) = '' OR description  IS NULL OR LTRIM(RTRIM(description)) = ''");

        $allcount=$thirddata+$second+$first;

        $x = $firstemptyseo+$secondemptyseo+$thirdemptyseo;
        $y = $allcount;

        $percent = $x/$y;
        $percent_friendly = number_format( $percent * 100, 2 ) . '%';

        $seo_thirddata=$thirdemptyseo/$thirddata;
        $percent_seo_thirddata = number_format( $seo_thirddata * 100, 2 ) . '%';


        $seo_seconddata=$secondemptyseo/$second;
        $percent_seo_seconddata = number_format( $seo_seconddata * 100, 2 ) . '%';

        $firstdata=$firstemptyseo/$first;
        $percent_firstdata = number_format( $firstdata * 100, 2 ) . '%';


        $setting=array("style"=>$css,'allcount'=>$allcount,'thirddata'=>$thirddata,'second'=>$second,'first'=>$first,'firstemptyseo'=>$firstemptyseo,'secondemptyseo'=>$secondemptyseo,'thirdemptyseo'=>$thirdemptyseo,'workpending'=>$percent_friendly,'percent_firstdata'=>$percent_firstdata,'percent_seo_seconddata'=>$percent_seo_seconddata,'percent_seo_thirddata'=>$percent_seo_thirddata);
       return $OUTPUT->render_from_template('local_seodashboard/all-seo', $setting);
    }


}