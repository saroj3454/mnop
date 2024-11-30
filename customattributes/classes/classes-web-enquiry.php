<?php 
class WebEnquiry{
	  public $fileurl;
	function __construct(){
		$this->fileurl=FILE_URL;
	add_action('widgets_init',[ $this,'enquiryform']);
	add_action('widgets_init',[ $this,'brochureform']);
	add_action('widgets_init',[ $this,'applynowform']);
	add_action('widgets_init',[ $this,'monthsyllabusform']);
	add_action('enquiryform',[ $this,'enquiryformdiplay']);
	add_action('brochurediplay',[ $this,'brochurediplay']);
	add_action('applynowdiplay',[ $this,'applynowdiplay']);
	add_action('monthsyllabusdiplay',[ $this,'monthsyllabusdiplay']);
	}
	function enquiryform(){
		register_sidebar(array(
            'name'          => esc_html__( 'Enquire Now Form Section', 'oawa' ),
            'id'            => 'enquiryformdata',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
	}
	function enquiryformdiplay(){
		if (is_active_sidebar( 'enquiryformdata') ){
            dynamic_sidebar( 'enquiryformdata' );
            }
	}
    function brochureform(){
    	register_sidebar(array(
            'name'          => esc_html__( 'Brochure Form Section', 'oawa' ),
            'id'            => 'brochureformdata',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
    } 
    function brochurediplay(){
		if(is_active_sidebar('brochureformdata') ){
            dynamic_sidebar( 'brochureformdata' );
          }
	}
	function applynowform(){
		register_sidebar(array(
            'name'          => esc_html__( 'Apply Now Form Section', 'oawa' ),
            'id'            => 'applyformdata',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
	}
	function applynowdiplay(){
		if(is_active_sidebar('applyformdata') ){
            dynamic_sidebar( 'applyformdata' );
          }
	}
	function monthsyllabusform(){
		register_sidebar(array(
            'name'          => esc_html__( 'Month Syllabus Form Section', 'oawa' ),
            'id'            => 'monthsyllabus',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
	}
	function monthsyllabusdiplay(){
		if(is_active_sidebar('monthsyllabus') ){
            dynamic_sidebar( 'monthsyllabus' );
          }
	}






}
 $webhook=new WebEnquiry();