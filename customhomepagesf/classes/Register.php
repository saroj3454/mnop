<?php class Register
{
	function __construct()
	{
		$this->loginpagehtmlheader();
		$this->loginpagemenu();
		$this->loginbodycontent();
		$this->loginpagefooter();
	}

	function loginpagehtmlheader(){
		global $DB, $OUTPUT,$CFG;
		$html=html_writer::start_tag('html');
		$html.=html_writer::start_tag('head');
		$html.=html_writer::tag('title','Register');
		$html.=html_writer::start_tag('meta',array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
		$html.=html_writer::tag('script','',array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'));
		$html.=html_writer::tag('script','',array('src'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'));
		 $html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/headerfooter.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/custom.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/normalize.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/Register.css'));


		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		$html.=html_writer::end_tag('head');
		$html.=html_writer::start_tag('body',array('class'=>'body homep'));
		echo $html;
	}
	function loginpagemenu(){
		global $DB, $OUTPUT,$CFG;
		 include $CFG->dirroot .'/local/customhomepage/classes/Homepage.php';
		 $pagehtml= Homepage::header();
	
	}
	
	function loginbodycontent(){
		
	
		$html='<div class="row">';
		$html.='<div class="container-fluid">';
		$html.='<div class="card card-registration">';
		$html.='<div class="row ">';
		$html.=self::loginLeftsideContent();
		$html.='<div class="col-sm-6">';
		$html.='<div class="card-body p-md-5 text-black">';
		$html.=self::formcontent();
		$html.=self::loginRightsideContent();
		$html.=self::loginRightsideHtml();

		$html.="</div>";
		$html.="</div>";
		$html.="</div>";
		$html.="</div>";
		$html.="</div>";
		$html.="</div>";
		echo $html;
	}


	public static function loginRightsideHtml()
	{
		$html.=html_writer::tag('p','Already have an account? Login here',array('class'=>'text-center'));
		return $html;

	}
		public static function formcontent()
	{
		$html.=html_writer::tag('h1','Get Started!',array('class'=>'text-center txt'));
		$html.=html_writer::tag('p','Use your social profile to register',array('class'=>'text-center'));
		//$html.=html_writer::start_tag('div',array('class'=>'d-flex flex-wrap align-items-center'));
		
		//$html.=html_writer::end_tag('div');
		
		
		
		return $html;
		
	}
	
	
	public static function loginLeftsideContent()
	{
		
		$html.=html_writer::start_tag('div',array('class'=>'col-sm-6 '));
		//$html.=html_writer::tag('h1','The choice of a new generation',array('class'=>'left-txt'));
		$html.=html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Join Us.jpg','alt'=>'JoinUs','class'=>'img-fluid','style'=>'border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;'));
	    $html.= html_writer::end_tag('div');
		return $html;
	
	}
	
    public static function loginRightsideContent(){
 
    	global $DB, $OUTPUT,$CFG;
		require_once($CFG->dirroot .'/local/customhomepage/classes/Registerform.php');
    	 $r= new Registerform();
		 $html=$r->render();
		 $htm=str_replace("col-md-9 form-inline felement","row",$html);
		 $htmll=str_replace("sr-only","ns",$htm);
		 $htmlll=str_replace("form-group  fitem  flable na1","form-group  fitem  flable na1 d-flex flex-column mb-3 col",$htmll);
		 $htmllll=str_replace("form-group  fitem  flable na2","form-group  fitem  flable na2 d-flex flex-column mb-3 col",$htmlll);
		$htmlllll=str_replace("btn btn-secondary ml-0","btn btn-secondary btn-block",$htmllll);
		$htmllllll=str_replace("form-group row  fitem ","form-group row  fitem d-flex flex-column mb-3",$htmlllll);
		$htmlllllll=str_replace("form-group row  fitem d-flex flex-column mb-3femptylabel ","form-group row  fitem d-flex flex-column mb-3femptylabel align-items-center",$htmllllll);
		$htmllllllll=str_replace("form-group row  fitem d-flex flex-column mb-3 ","form-group row  fitem d-flex flex-column mb-3 align-items-center",$htmlllllll);
		return $htmllllllll;
	}





	public static function loginpagefooter(){
		global $DB, $OUTPUT,$CFG;
		 require_once($CFG->dirroot .'/local/customhomepage/classes/Homepage.php');
		 $r= Homepage::homefooter();
	
	}




}

