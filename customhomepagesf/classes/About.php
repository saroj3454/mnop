<?php class About
{
	function __construct()
	{
		$this->aboutpagehtmlheader();
		$this->aboutpagemenu();
		$this->about();
		$this->aboutpagefooter();
		
	}

	function aboutpagehtmlheader(){
		global $DB, $OUTPUT,$CFG;
		$html=html_writer::start_tag('html');
		$html.=html_writer::start_tag('head');
		$html.=html_writer::tag('title','About');
		$html.=html_writer::start_tag('meta',array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
		$html.=html_writer::tag('script','',array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'));
		$html.=html_writer::tag('script','',array('src'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'));
		 $html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/style.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/about.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/custom.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/normalize.css'));
		


		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		$html.=html_writer::end_tag('head');
		$html.=html_writer::start_tag('body',array('class'=>'body'));
		echo $html;
	}
	function aboutpagemenu(){
		global $DB, $OUTPUT,$CFG;
		 include $CFG->dirroot .'/local/customhomepage/classes/Homepage.php';
		 $pagehtml= Homepage::header();
	
	}
	
	function about()
	{
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('id'=>'about','class'=>'about'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'section-title aos-init aos-animate','data-aos'=>'fade-up'));
		$html.= html_writer::tag('h2','About Us',array('class'=>''));
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',array('class'=>''));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'row'));
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-6 order-1 order-lg-2'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/About-image.jpeg','title'=>'#1 LMS Portal','class'=>'img-fluid','alt'=>'aboutus'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content'));
		$html.= html_writer::tag('h3','#1 LMS Solution for Teaching and Learning',array('class'=>''));
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',array('class'=>'fst-italic'));
		$html.= html_writer::start_tag('ul');
		$html.= html_writer::start_tag('li',array('class'=>''));
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',array('class'=>'bi bi-check-circle'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>''));
		$html.= html_writer::tag('p','sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',array('class'=>'bi bi-check-circle'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>''));
		$html.= html_writer::tag('p','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',array('class'=>'bi bi-check-circle'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::end_tag('ul');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',array('class'=>''));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
		echo $html;
	}
		
		
		
		
		
		
		
	
	
	public static function aboutpagefooter(){
		global $DB, $OUTPUT,$CFG;
		 require_once($CFG->dirroot .'/local/customhomepage/classes/Homepage.php');
		 $r= Homepage::homefooter();
	
	}




}
	
	
	
	
	
	
	
	
	
	