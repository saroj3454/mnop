<?php class Contact
{
	function __construct()
	{
		$this->contactpagehtmlheader();
		$this->contactpagemenu();
		$this->contactbodycontent();
		$this->contactpagefooter();
		
	}

	function contactpagehtmlheader(){
		global $DB, $OUTPUT,$CFG;
		$html=html_writer::start_tag('html');
		$html.=html_writer::start_tag('head');
		$html.=html_writer::tag('title','Contact');
		$html.=html_writer::start_tag('meta',array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
		$html.=html_writer::tag('script','',array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'));
		$html.=html_writer::tag('script','',array('src'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'));
		 $html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/headerfooter.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/contact.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/custom.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/normalize.css'));
		


		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		$html.=html_writer::end_tag('head');
		$html.=html_writer::start_tag('body',array('class'=>'body'));
		echo $html;
	}
	function contactpagemenu(){
		global $DB, $OUTPUT,$CFG;
		 include $CFG->dirroot .'/local/customhomepage/classes/Homepage.php';
		 $pagehtml= Homepage::header();
	
	}
	
	function contactbodycontent(){
		global $DB, $OUTPUT,$CFG;

		echo $OUTPUT->render_from_template('local_customhomepage/contact', array());
	 
	}


	




	public static function contactpagefooter(){
		global $DB, $OUTPUT,$CFG;
		 require_once($CFG->dirroot .'/local/customhomepage/classes/Homepage.php');
		 $r= Homepage::homefooter();
	
	}




}