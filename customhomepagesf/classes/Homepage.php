<?php class Homepage
{
	function __construct()
	{
		$this->htmlheader();
		//$this->topbar();
		$this->header();
		$this->carousel();
		$this->aboutsection();
		$this->featuressection();
		$this->banner();
		$this->services();
		$this->courses();
		$this->clientsection();
		$this->benefitsection();
		$this->homefooter();
	}
	function htmlheader()
	{
		global $DB, $OUTPUT,$CFG;
		$html=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'));
		$html=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://use.fontawesome.com/releases/v5.8.1/css/all.css','integrity'=>'sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf','crossorigin'=>'anonymous'));
		$html=html_writer::start_tag('html');
		$html.=html_writer::start_tag('head');
		$html.=html_writer::tag('title',get_string('homepage_title','local_customhomepage'));
		$html.=html_writer::start_tag('meta',array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css'));
		$html.=html_writer::tag('script','',array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'));
		$html.=html_writer::tag('script','',array('src'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'));
		 $html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/style.css'));
		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/custom.css'));

		//$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/normalize.css'));


		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		$html.=html_writer::end_tag('head');
		$html.=html_writer::start_tag('body',array('class'=>'body'));
		echo $html;
	}
	
	function topbar()
	{
		global $DB, $OUTPUT,$CFG;
	    $html.= html_writer::start_tag('section',array('class'=>'d-flex align-items-center'));
	    $html.= html_writer::start_tag('div',array('class'=>'container d-flex justify-content-center justify-content-md-between'));
	    $html.= html_writer::start_tag('div',array('class'=>'contact-info d-flex align-items-center'));
	    $html.= html_writer::tag('i','',array('class'=>'bi bi-envelope-fill'));
	    $html.= html_writer::tag('a','support@campustutr.com',array('href'=>'#'));
	    $html.= html_writer::tag('i','',array('class'=>'bi bi-phone-fill phone-icon'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'social-links d-none d-md-block'));
	    $html.= html_writer::start_tag('a',array('href'=>'#','class'=>'twitter'));
	    $html.= html_writer::tag('i','',array('class'=>'fa fa-twitter'));
	    $html.= html_writer::end_tag('a');
	    $html.= html_writer::start_tag('a',array('href'=>'#','class'=>'facebook'));
	    $html.= html_writer::tag('i','',array('class'=>'fa fa-facebook-square'));
	    $html.= html_writer::end_tag('a');
	    $html.= html_writer::start_tag('a',array('href'=>'#','class'=>'instagram'));
	    $html.= html_writer::tag('i','',array('class'=>'fa fa-instagram'));
	    $html.= html_writer::end_tag('a');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('section');
	    echo $html;
	}
	
	
	
	
	function header()
	{
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('header',array('class'=>'d-flex align-items-center','id'=>'header'));
		$html.= html_writer::start_tag('div',array('class'=>'container d-flex align-items-center'));
		$html.= html_writer::start_tag('h1',array('class'=>'logo mr-auto'));
		$html.=html_writer::tag('a','Logo',array('href'=>$CFG->wwwroot));
		$html.= html_writer::end_tag('h1');
		$html.= html_writer::start_tag('nav',array('class'=>'navbar','id'=>'navbar'));
		$html.= html_writer::start_tag('ul');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Home',array('class'=>'nav-link scrollto active','href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','About',array('class'=>'nav-link scrollto ','href'=>$CFG->wwwroot.'/local/customhomepage/about.php'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Services',array('class'=>'nav-link scrollto ','href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>'dropdown'));
		$html.=html_writer::start_tag('a',array('href'=>'#'));
		$html.=html_writer::start_span('drop') . 'Drop Down' . html_writer::end_span();
		$html.=html_writer::tag('i','',array('class'=>'fal fa-chevron-down'));
		$html.=html_writer::end_tag('a',array('href'=>'#'));
		$html.=html_writer::start_tag('ul');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Drop Down 1',array('href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Drop Down 2',array('href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Drop Down 3',array('href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::end_tag('ul');
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Contact',array('class'=>'nav-link scrollto ','href'=>$CFG->wwwroot.'/local/customhomepage/contact.php'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.=html_writer::tag('a','Get Started',array('class'=>'getstarted scrollto','href'=>$CFG->wwwroot.'/local/customhomepage/register.php'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('ul');
		$html.=html_writer::tag('i','',array('class'=>'far fa-bars mobile-nav-toggle'));
		$html.= html_writer::end_tag('nav');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('header');
		
		
		
		
		
		
		
		
	
		
		
		
		
		
		
		
		
		
		
		
		
		/*$html.= html_writer::start_tag('div',array('class'=>'page-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'header-section wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small nav-container'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-header-area'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-grid hide'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-dd95d815-212e-ff34-91cf-6a6cf9a344b6-e26d923d','class'=>'top-bar-email-section'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-email-link'));
		$html.= html_writer::tag('img','',array('src'=>'','loading'=>'lazy','alt'=>'image','class'=>'email-icon'));
		$html.=html_writer::tag('a',get_string('mail','local_customhomepage'),array('class'=>'email-link','href'=>'#'));

		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_8806bd3d-74a6-8b28-5aa6-1984df11d36e-e26d923d','class'=>'top-bar-contact-section'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-contact-link'));
		$html.= html_writer::tag('img','',array('src'=>'','loading'=>'lazy','alt'=>'image_1','class'=>'contact-icon'));
		$html.=html_writer::tag('a',get_string('phnumber','local_customhomepage'),array('class'=>'contact-link','href'=>'#'));

		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');



		$html.= html_writer::start_tag('nav',array('class'=>'navbar sticky-top navbar-expand-lg'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.=html_writer::tag('a','Logo',array('class'=>'navbar-brand','href'=>$CFG->wwwroot));
		$html.=html_writer::start_tag('button',array('class'=>'navbar-toggler','type'=>'button','data-toggle'=>'collapse','data-target'=>'#navbarSupportedContent', 'aria-controls'=>'navbarSupportedContent','aria-expanded'=>'false','aria-label'=>'Toggle navigation'));
		$html.=html_writer::start_tag('i',array('class'=>'fas fa-bars'));
		$html.= html_writer::end_tag('i');
		$html.= html_writer::end_tag('button');
		$html.= html_writer::start_tag('div',array('class'=>'collapse navbar-collapse','id'=>'navbarSupportedContent'));
		$html.= html_writer::start_tag('ul',array('class'=>'navbar-nav mr-auto w-100 justify-content-end'));
		$html.= html_writer::start_tag('li',array('class'=>'nav-item active'));
		$html.=html_writer::tag('a','Home',array('class'=>'nav-link','href'=>$CFG->wwwroot));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>'nav-item'));
		$html.=html_writer::tag('a','About',array('class'=>'nav-link','href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>'nav-item'));
		$html.=html_writer::tag('a','Services',array('class'=>'nav-link','href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>'nav-item'));
		$html.=html_writer::tag('a','Contact',array('class'=>'nav-link','href'=>'#'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li',array('class'=>'nav-item'));
		$html.=html_writer::tag('a','Sign Up',array('class'=>'nav-link','href'=>$CFG->wwwroot.'/local/customhomepage/register.php'));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::end_tag('ul');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('nav');
		
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');*/
		echo $html;
	}

	function carousel()
	{
		global $DB, $OUTPUT,$CFG;
		//Carousel
		$html.= html_writer::start_tag('section',array('id'=>'hero'));
		$html.= html_writer::start_tag('div',array('id'=>'heroCarousel','class'=>'carousel slide carousel-fade','data-interval'=>'5000','data-ride'=>'carousel'));
		$html.=html_writer::start_tag('ol',array('class'=>'carousel-indicators','id'=>'hero-carousel-indicators'));
        $html.=html_writer::tag('li','',array('data-target'=>'#heroCarousel', 'data-slide-to'=>'0','class'=>'active','aria-current'=>'true'));
        $html.=html_writer::tag('li','',array('data-target'=>'#heroCarousel','data-slide-to'=>'1'));
        $html.=html_writer::tag('li','',array('data-target'=>'#heroCarousel','data-slide-to'=>'2'));
        $html.=html_writer::end_tag('ol');
		$html.= html_writer::start_tag('div',array('class'=>'carousel-inner','role'=>'listbox'));
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg);','class'=>'carousel-item active'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-container '));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::tag('h2','Lorem ipsum dolor sit amet',array('class'=>'animate__animated animate__fadeInDown'));
		$html.= html_writer::tag('p','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',array('class'=>'animate__animated animate__fadeInUp'));
		$html.= html_writer::tag('a','Learn More',array('href'=>'#','class'=>'btn-get-started animate__animated animate__fadeInUp scrollto'));
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg);','class'=>'carousel-item'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-container'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::tag('h2','Lorem ipsum dolor sit amet',array('class'=>'animate__animated animate__fadeInDown'));
		$html.= html_writer::tag('p','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',array('class'=>'animate__animated animate__fadeInUp'));
		$html.= html_writer::tag('a','Learn More',array('href'=>'#','class'=>'btn-get-started animate__animated animate__fadeInUp scrollto'));
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg);','class'=>'carousel-item'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-container '));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::tag('h2','Lorem ipsum dolor sit amet',array('class'=>'animate__animated animate__fadeInDown'));
		$html.= html_writer::tag('p','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',array('class'=>'animate__animated animate__fadeInUp'));
		$html.= html_writer::tag('a','Learn More',array('href'=>'#','class'=>'btn-get-started animate__animated animate__fadeInUp scrollto'));
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('div');
		$html.= html_writer::start_tag('a',array('href'=>'#heroCarousel','class'=>'carousel-control-prev','role'=>'button','data-slide'=>'prev'));
		$html.= html_writer::tag('span','',array('class'=>'carousel-control-prev-icon bi bi-chevron-left','aria-hidden'=>'true'));
		$html.=html_writer::end_tag('a');
		$html.= html_writer::start_tag('a',array('href'=>'#heroCarousel','class'=>'carousel-control-next','role'=>'button','data-slide'=>'next'));
		$html.= html_writer::tag('span','',array('href'=>'heroCarousel','class'=>'carousel-control-next-icon bi bi-chevron-right','aria-hidden'=>'true'));
		$html.=html_writer::end_tag('a');
		$html.=html_writer::end_tag('div');
		$html.=html_writer::end_tag('section');
			
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*$html.=html_writer::start_tag('div',array('id'=>'carouselExampleSlidesOnly','class'=>'carousel slide carousel-fade','data-ride'=>'carousel'));
		$html.=html_writer::start_tag('ol',array('class'=>'carousel-indicators'));
    $html.=html_writer::tag('li','',array('data-target'=>'#carouselExampleIndicators', 'data-slide-to'=>'0','class'=>'active'));
    $html.=html_writer::tag('li','',array('data-target'=>'#carouselExampleIndicators','data-slide-to'=>'1'));
    $html.=html_writer::tag('li','',array('data-target'=>'#carouselExampleIndicators', 'data-slide-to'=>'2'));
  $html.=html_writer::end_tag('ol');
		$html.= html_writer::start_tag('div',array('class'=>'carousel-inner'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-item drk active'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg','class'=>'d-block w-100', 'alt'=>'Slider1'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-caption d-none d-md-block w-50 ml-auto mr-auto'));
		$html.= html_writer::tag('div','Platform-as-a-Service (PaaS) ',array('class'=>'text-center'));
		$html.= html_writer::tag('div',get_string('slide1','local_customhomepage'),array('class'=>'text-center','style'=>'font-size:38px;'));
		$html.= html_writer::start_tag('div',array('class'=>'text-center h-divider'));
		$html.= html_writer::tag('div','',array('class'=>'shadow'));
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::tag('div',get_string('default','local_customhomepage'),array('class'=>'text-center'));
		$html.= html_writer::tag('button','Know More',array('class'=>'btn btn-outline-primary','type'=>'button'));
		
		
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'carousel-item drk '));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg','class'=>'d-block w-100', 'alt'=>'Slider2'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-caption d-none d-md-block w-50 ml-auto mr-auto'));
		$html.= html_writer::tag('div',get_string('slide2','local_customhomepage'),array('class'=>'text-center'));
		$html.= html_writer::tag('div',get_string('default','local_customhomepage'),array('class'=>'text-center'));
		
		
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'carousel-item drk '));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg','class'=>'d-block w-100','alt'=>'Slider3'));
		$html.= html_writer::start_tag('div',array('class'=>'carousel-caption d-none d-md-block w-50 ml-auto mr-auto'));
		$html.= html_writer::tag('div',get_string('slide3','local_customhomepage'),array('class'=>'text-center'));
		$html.= html_writer::tag('div',get_string('default','local_customhomepage'),array('class'=>'text-center'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
 		$html.= html_writer::end_tag('div');*/
 		echo $html;
	}	
	
	
	function aboutsection()
	{
		global $DB, $OUTPUT,$CFG;
        //#1 LMS CONTENT 
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
		
		
		
		function featuressection()
	{
		$html.= html_writer::start_tag('section',array('id'=>'features','class'=>'features'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'section-title aos-init aos-animate','data-aos'=>'fade-up'));
		$html.= html_writer::tag('h2','Features',array('class'=>''));
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'row'));
	    $html.= html_writer::start_tag('div',array('class'=>'col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon-box aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'100'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon'));
	    $html.= html_writer::tag('i','',array('class'=>'bx bx-file'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('h4',array('class'=>'title'));
	    $html.= html_writer::tag('a','Lorem Ipsum',array('href'=>'#'));
	    $html.= html_writer::end_tag('h4');
	    $html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('class'=>'description'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon-box aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'200'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon'));
	    $html.= html_writer::tag('i','',array('class'=>'bx bx-file'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('h4',array('class'=>'title'));
	    $html.= html_writer::tag('a','Lorem Ipsum',array('href'=>'#'));
	    $html.= html_writer::end_tag('h4');
	    $html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('class'=>'description'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon-box aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'300'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon'));
	    $html.= html_writer::tag('i','',array('class'=>'bx bx-file'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('h4',array('class'=>'title'));
	    $html.= html_writer::tag('a','Lorem Ipsum',array('href'=>'#'));
	    $html.= html_writer::end_tag('h4');
	    $html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('class'=>'description'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon-box aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'400'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon'));
	    $html.= html_writer::tag('i','',array('class'=>'bx bx-file'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('h4',array('class'=>'title'));
	    $html.= html_writer::tag('a','Lorem Ipsum',array('href'=>'#'));
	    $html.= html_writer::end_tag('h4');
	    $html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('class'=>'description'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	     $html.= html_writer::start_tag('div',array('class'=>'col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon-box aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'400'));
	    $html.= html_writer::start_tag('div',array('class'=>'icon'));
	    $html.= html_writer::tag('i','',array('class'=>'bx bx-file'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('h4',array('class'=>'title'));
	    $html.= html_writer::tag('a','Lorem Ipsum',array('href'=>'#'));
	    $html.= html_writer::end_tag('h4');
	    $html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit',array('class'=>'description'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('section');
	    echo $html;
	}
		
		function banner(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('id'=>'cta','class'=>'cta'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'row'));
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-9 text-center text-lg-start'));
		$html.= html_writer::tag('h3','Lorem ipsum dolors',array('class'=>''));
		$html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',array('class'=>''));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-3 cta-btn-container text-center'));
		$html.= html_writer::tag('a','Join Now',array('href'=>'#','class'=>'cta-btn align-middle'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
			echo $html;
	}
		
		
		function services(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('id'=>'services','class'=>'services'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'row'));
		$html.= html_writer::start_tag('div',array('class'=>'col-md-6 d-flex align-items-stretch'));
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/Become-a-Tutor.jpg);','class'=>'card aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'100'));
		
		$html.= html_writer::start_tag('div',array('class'=>'card-body'));
		$html.= html_writer::start_tag('h1',array('class'=>'card-title'));
		$html.= html_writer::tag('a','Become a Tutor',array('href'=>'#'));
		$html.= html_writer::end_tag('h1');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>'card-text'));
		$html.= html_writer::start_tag('div',array('class'=>'read-more'));
		$html.= html_writer::start_tag('a',array('href'=>'#'));
		$html.= html_writer::tag('div','Read More',array('class'=>'card-link bi bi-arrow-right'));
		//$html.= html_writer::tag('i','',array('class'=>'bi bi-arrow-right'));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-md-6 d-flex align-items-stretch mt-4 mt-md-0'));
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/Sell-Material-Content.jpg);','class'=>'card aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'100'));
		$html.= html_writer::start_tag('div',array('class'=>'card-body'));
		$html.= html_writer::start_tag('h1',array('class'=>'card-title'));
		$html.= html_writer::tag('a','Sell Material Content',array('href'=>'#'));
		$html.= html_writer::end_tag('h1');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>'card-text'));
		$html.= html_writer::start_tag('div',array('class'=>'read-more'));
		$html.= html_writer::start_tag('a',array('href'=>'#'));
		$html.= html_writer::tag('div','Read More',array('class'=>'card-link bi bi-arrow-right'));
		//$html.= html_writer::tag('i','',array('class'=>'bi bi-arrow-right'));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-md-6 d-flex align-items-stretch mt-4'));
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/Corporate-Learning.jpg);','class'=>'card aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'100'));
		$html.= html_writer::start_tag('div',array('class'=>'card-body'));
		$html.= html_writer::start_tag('h1',array('class'=>'card-title'));
		$html.= html_writer::tag('a','Corporate Learning',array('href'=>'#'));
		$html.= html_writer::end_tag('h1');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>'card-text'));
		$html.= html_writer::start_tag('div',array('class'=>'read-more'));
		$html.= html_writer::start_tag('a',array('href'=>'#'));
		$html.= html_writer::tag('div','Read More',array('class'=>'card-link bi bi-arrow-right'));
		//$html.= html_writer::tag('i','',array('class'=>'bi bi-arrow-right'));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-md-6 d-flex align-items-stretch mt-4'));
		$html.= html_writer::start_tag('div',array('style'=>'background-image:url('.$CFG->wwwroot.'/local/customhomepage/assest/image/Become-a-Tutor.jpg);','class'=>'card aos-init aos-animate','data-aos'=>'fade-up','data-aos-delay'=>'100'));
		$html.= html_writer::start_tag('div',array('class'=>'card-body'));
		$html.= html_writer::start_tag('h1',array('class'=>'card-title'));
		$html.= html_writer::tag('a','Become a Tutor',array('href'=>'#'));
		$html.= html_writer::end_tag('h1');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>'card-text'));
		$html.= html_writer::start_tag('div',array('class'=>'read-more'));
		$html.= html_writer::start_tag('a',array('href'=>'#'));
		$html.= html_writer::tag('div','Read More',array('class'=>'card-link bi bi-arrow-right'));
		//$html.= html_writer::tag('i','',array('class'=>'bi bi-arrow-right'));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		
	
		
		$html.= html_writer::end_tag('section');
		echo $html;
	}
		
		
		function courses(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('id'=>'popular-courses','class'=>'courses'));
		$html.= html_writer::start_tag('div',array('class'=>'container aos-init aos-animate','data-aos'=>'fade-up'));
		$html.= html_writer::start_tag('div',array('class'=>'section-title'));
		$html.= html_writer::tag('h2','Courses',array('class'=>''));
		$html.= html_writer::tag('p','Popular Courses',array('class'=>''));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'row aos-init aos-animate','data-aos'=>'zoom-in'));
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 d-flex align-items-stretch'));
		$html.= html_writer::start_tag('div',array('class'=>'course-item'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Training.jpg','class'=>'img-fluid'));
		$html.= html_writer::start_tag('div',array('class'=>'course-content'));
		$html.= html_writer::start_tag('div',array('class'=>'d-flex justify-content-between align-items-center mb-3'));
		$html.= html_writer::tag('h4','Training',array('class'=>''));
		$html.= html_writer::tag('p','',array('class'=>'price'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('h3',array('class'=>''));
		$html.= html_writer::tag('a','Faculty Training',array('href'=>'#'));
		$html.= html_writer::end_tag('h3');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>''));
		$html.= html_writer::start_tag('div',array('class'=>'trainer d-flex justify-content-between align-items-center'));
		$html.= html_writer::start_tag('div',array('class'=>'trainer-profile d-flex align-items-center'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Final-Logo.png','class'=>'img-fluid'));
		$html .= html_writer::start_span('') . 'By KP Academy' . html_writer::end_span();
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'trainer-rank d-flex align-items-center'));
		$html.= html_writer::tag('i','&nbsp;100',array('class'=>'bx bx-user'));
		$html.= html_writer::tag('i','&nbsp;75',array('class'=>'bx bx-heart'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 d-flex align-items-stretch'));
		$html.= html_writer::start_tag('div',array('class'=>'course-item'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Training.jpg','class'=>'img-fluid'));
		$html.= html_writer::start_tag('div',array('class'=>'course-content'));
		$html.= html_writer::start_tag('div',array('class'=>'d-flex justify-content-between align-items-center mb-3'));
		$html.= html_writer::tag('h4','Database',array('class'=>''));
		$html.= html_writer::tag('p','',array('class'=>'price'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('h3',array('class'=>''));
		$html.= html_writer::tag('a','Snowflake',array('href'=>'#'));
		$html.= html_writer::end_tag('h3');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>''));
		$html.= html_writer::start_tag('div',array('class'=>'trainer d-flex justify-content-between align-items-center'));
		$html.= html_writer::start_tag('div',array('class'=>'trainer-profile d-flex align-items-center'));
		$html.= html_writer::tag('img','',array('src'=>'','class'=>'img-fluid'));
		$html .= html_writer::start_span('') . 'By Chandu' . html_writer::end_span();
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'trainer-rank d-flex align-items-center'));
		$html.= html_writer::tag('i','&nbsp;50',array('class'=>'bx bx-user'));
		$html.= html_writer::tag('i','&nbsp;42',array('class'=>'bx bx-heart'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 d-flex align-items-stretch'));
		$html.= html_writer::start_tag('div',array('class'=>'course-item'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Training.jpg','class'=>'img-fluid'));
		$html.= html_writer::start_tag('div',array('class'=>'course-content'));
		$html.= html_writer::start_tag('div',array('class'=>'d-flex justify-content-between align-items-center mb-3'));
		$html.= html_writer::tag('h4','Training',array('class'=>''));
		$html.= html_writer::tag('p','',array('class'=>'price'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('h3',array('class'=>''));
		$html.= html_writer::tag('a','Data Analytics and Engineering',array('href'=>'#'));
		$html.= html_writer::end_tag('h3');
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',array('class'=>''));
		$html.= html_writer::start_tag('div',array('class'=>'trainer d-flex justify-content-between align-items-center'));
		$html.= html_writer::start_tag('div',array('class'=>'trainer-profile d-flex align-items-center'));
		$html.= html_writer::tag('img','',array('src'=>'','class'=>'img-fluid'));
		$html .= html_writer::start_span('') . 'By Raj' . html_writer::end_span();
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'trainer-rank d-flex align-items-center'));
		$html.= html_writer::tag('i','&nbsp;100',array('class'=>'bx bx-user'));
		$html.= html_writer::tag('i','&nbsp;75',array('class'=>'bx bx-heart'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
		echo $html;
	}
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*$html.= html_writer::start_tag('div',array('class'=>'main-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small'));
		$html.= html_writer::start_tag('div',array('class'=>'w-layout-grid hero-content-grid'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_64e68397-0d59-bc5b-341e-820157653b54-e26d923d','class'=>'hero-content-left'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('id'=>'597468a9-e06a-ff01-992a-f173f772bf68','class'=>'hero-heading-wrapper'));
		$html.=html_writer::tag('h1',get_string('heading','local_customhomepage'),array('class'=>'heading-medium text-color-darkblue'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_215356ea-5ab5-c974-ea1e-949af4d60a40-e26d923d','data-w-id'=>'215356ea-5ab5-c974-ea1e-949af4d60a40','class'=>'hero-text-wrapper'));
		$html.= html_writer::div('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','text-regular',array('id'=>'services'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('data-w-id'=>'20e5fa4b-61d3-2004-f91b-b11c586b89a1','class'=>'hero-services-wrapper'));
		$html.= html_writer::start_tag('div',array('data-hover'=>'false', 'data-delay'=>'0','class'=>'hero-services-dropdown w-dropdown'));
		$html.= html_writer::start_tag('div',array('class'=>'dropdown-toggle w-dropdown-toggle'));
		//$html.= html_writer::start_tag('div',array('class'=>'icon w-icon-dropdown-toggle'));
		//$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::div('Our Services','dropdown-text',array('id'=>'services'));
		
		$html.= html_writer::start_tag('nav',array('class'=>'dropdown-list w-dropdown-list'));
		$html.=html_writer::tag('a',get_string('service1','local_customhomepage'),array('href'=>'#Become a Tutor','class'=>'dropdown-link w-dropdown-link'));
		$html.=html_writer::tag('a',get_string('service2','local_customhomepage'),array('href'=>'#Corporate Learning','class'=>'dropdown-link w-dropdown-link'));
		$html.=html_writer::tag('a',get_string('service3','local_customhomepage'),array('href'=>'#Sell Material Content','class'=>'dropdown-link w-dropdown-link'));
		$html.= html_writer::end_tag('nav');
		$html.= html_writer::end_tag('div');
		$html.=html_writer::start_tag('a',array('class'=>'primary-button w-button'));
		$html.=html_writer::start_span('text-span-10') . 'Join Now' . html_writer::end_span();
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/About-image.jpeg','title'=>'#1 LMS Portal','width'=>'515', 'height'=>'350', 'alt'=>'image1', 'sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 74vw, 515px', 'id'=>'w-node-_244ddb55-4d63-9020-a97b-368914a64fb5-e26d923d', 'loading'=>'lazy', 'srcset'=>'','class'=>'image-8'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function clientsection()
	{
	    $html.= html_writer::start_tag('section',array('id'=>'clients','class'=>'clients section-bg mr-n3'));
	    $html.= html_writer::start_tag('div',array('class'=>'container'));
	    $html.= html_writer::start_tag('div',array('class'=>'row'));
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-2 mb-4 col-md-4 col-6 d-flex align-items-center justify-content-center'));
	    $html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Final-Logo.png','class'=>'img-responsive'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-2 ml-5 col-md-4 col-6 d-flex align-items-center justify-content-center'));
	    $html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Sarvagnya.png','class'=>'img-responsive'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-2 ml-5 col-md-4 col-6 d-flex align-items-center justify-content-center'));
	    $html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/SBIT.png','class'=>'img-fluid'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-2 ml-5 col-md-4 col-6 d-flex align-items-center justify-content-center'));
	    $html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Resonance.png','class'=>'img-fluid'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-2 ml-5 col-md-4 col-6 d-flex align-items-center justify-content-center'));
	    $html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/SVM.png','class'=>'img-fluid'));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('section');
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    echo $html;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
 		


	/*function featurescard()
	{
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'scheduling-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'features-section'));
		$html.= html_writer::start_tag('div',array('class'=>'page-padding-4'));
		$html.= html_writer::start_tag('div',array('class'=>'container-larg'));
		$html.= html_writer::start_tag('div',array('class'=>'layout-3-col'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--1 w-inline-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-easy-40.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature1','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--2 w-inline-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-dashboard-layout-48.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature2','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--2 w-inline-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-video-call-on-protable-laptop-over-a-web-messenger-24.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature3','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');

		$html.= html_writer::start_tag('div',array('class'=>'layout-3-col'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--1 w-inline-block','id'=>'compiler'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-code-file-48.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature4','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--2 w-inline-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-chat-48.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature5','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'feature-card is--3 w-inline-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/icons8-certification-64.png','loading'=>'lazy', 'alt'=>'icon', 'class'=>'feature-card-svg'));
		$html.=html_writer::tag('h3',get_string('feature6','local_customhomepage'),array('class'=>'text-size-medium margin-bottom'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'text-size-regular margin-bottom'));
		$html.= html_writer::start_tag('div',array('class'=>'card-link-wrapper'));
		$html.= html_writer::div('Learn More','card-link');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/arrow1.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'link-arrow'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		echo $html;
	}
	function service1(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('class'=>'hero-heading-left wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-wrapper-2'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
		$html.=html_writer::tag('h1',get_string('service','local_customhomepage'),array('class'=>'heading-11'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'margin-bottom-24px-2'));
		$html.= html_writer::tag('a',get_string('details','local_customhomepage'),array('href'=>'#','class'=>'button-primary-2 w-button'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','title'=>'Become a Tutor','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2','class'=>'images'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');	
		$html.= html_writer::end_tag('section');
		echo $html;
	}
	
	
	function service2(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('class'=>'hero-heading-right-2 wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-wrapper-2'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','title'=>'Corporate Learning','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'learning','class'=>'hero-split'));
		$html.=html_writer::tag('h1',get_string('service2','local_customhomepage'),array('class'=>'heading-7'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'margin-bottom-24px-2'));
		$html.= html_writer::tag('a',get_string('details','local_customhomepage'),array('href'=>'#','class'=>'button-primary w-button'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
		echo $html;
	}


	function service3(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('class'=>'hero-heading-left wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-wrapper-2'));
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
		$html.=html_writer::tag('h1',get_string('service3','local_customhomepage'),array('class'=>'heading-12'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'margin-bottom-24px-2'));
		$html.= html_writer::tag('a',get_string('details','local_customhomepage'),array('href'=>'#','class'=>'button-primary-2 w-button'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','title'=>'Sell Material Comntent','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2','class'=>'images'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
		echo $html;
	}
	
	
	
	function clients(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'page-padding-5'));
		$html.= html_writer::start_tag('div',array('class'=>'container-large'));
		$html.= html_writer::start_tag('div',array('class'=>'trial-wrapper-2'));
		$html.= html_writer::start_tag('div',array('class'=>'div-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Final-Logo.png','loading'=>'lazy', 'alt'=>'image','width'=>'250', 'height'=>'250','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 45vw,250px','alt'=>'clientimg','class=>image-3' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'div-block-2'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Sarvagnya.png','loading'=>'lazy', 'width'=>'200', 'height'=>'50','alt'=>'clientimg','class'=>'image-4' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'div-block-3'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/SBIT.png','loading'=>'lazy', 'width'=>'100', 'height'=>'100','alt'=>'clientimg','class'=>'image-5' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'div-block-4'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/Resonance.png','loading'=>'lazy', 'width'=>'200', 'height'=>'50','alt'=>'clientimg','class'=>'image-6' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'div-block-5'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/SVM.png','loading'=>'lazy', 'width'=>'150', 'height'=>'50','alt'=>'clientimg','class'=>'image-7' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		echo $html;
	}
	
	
	function benefits(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('id'=>'About-us','class'=>'doctors-section pdr'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small'));
		$html.= html_writer::start_tag('div',array('class'=>'doctors-title-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('data-w-id'=>'948ed373-65db-4003-dea7-34b9a7a7fb72', 'style'=>'opacity:0','class'=>'doctors-heading-wrapper'));
		$html.= html_writer::tag('h1',get_string('heading1','local_customhomepage'),array('class'=>'heading-large text-color-darkblue'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('data-w-id'=>'a80043e9-f2c4-ad02-f12f-ae4ad10279a8', 'style'=>'opacity:0','class'=>'doctors-text-wrapper'));
		$html.= html_writer::div(get_string('default','local_customhomepage'),array('class'=>'text-regular'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'doctors-details-card'));
		$html.= html_writer::start_tag('div',array('class'=>'w-layout-grid doctors-details-grid'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-d873581d-fc5d-29b3-d834-ee2cf2315e4c-e26d923d','data-w-id'=>'d873581d-fc5d-29b3-d834-ee2cf2315e4c', 'style'=>'opacity:0','class'=>'doctors-image-wrapper'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-1.47.52-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 1919px) 93vw, 1113px','alt'=>'image1','class'=>'doctors-image' ));
		$html.=html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_1e7f478e-1d9c-17ac-622c-2f5f854d7206-e26d923d','class'=>'doctors-info-wrapper'));
		$html.= html_writer::div('Benefit1','text-regular bold',array('data-w-id'=>'7f65ab10-240c-bfb3-73a1-77cb8555dba7', 'style'=>'opacity:0'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('data-w-id'=>'77360bf4-5ca5-4634-7586-f6c6fb9247e9', 'style'=>'opacity:0','class'=>'doctors-name-wrapper'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('data-w-id'=>'159c43d9-3693-c450-882b-499344e14a51', 'style'=>'opacity:0','class'=>'paragraph-regular'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'w-layout-grid doctors-details-grid-2'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-ded612a4-46d4-99db-02a7-32afc84e759e-e26d923d','class'=>'doctors-info-wrapper-2'));
		$html.= html_writer::div('Benefit2','text-regular bold',array('data-w-id'=>'ded612a4-46d4-99db-02a7-32afc84e75a3', 'style'=>'opacity:0'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('data-w-id'=>'ded612a4-46d4-99db-02a7-32afc84e75a0', 'style'=>'opacity:0','class'=>'doctors-name-wrapper'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('data-w-id'=>'ded612a4-46d4-99db-02a7-32afc84e75a5', 'style'=>'opacity:0','class'=>'paragraph-regular'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-ded612a4-46d4-99db-02a7-32afc84e759c-e26d923d','data-w-id'=>'ded612a4-46d4-99db-02a7-32afc84e759c', 'style'=>'opacity:0','class'=>'doctors-image-wrapper'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 1919px) 93vw, 1113px','alt'=>'image2','class'=>'doctors-image' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'w-layout-grid doctors-details-grid'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_538f78b5-9027-f577-2623-8d2460522513-e26d923d','class'=>'doctors-image-wrapper'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','loading'=>'lazy','style'=>'opacity:0','data-w-id'=>'538f78b5-9027-f577-2623-8d2460522514','sizes'=>'(max-width: 1919px) 93vw, 1113px','alt'=>'image3','class'=>'doctors-image' ));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_538f78b5-9027-f577-2623-8d2460522515-e26d923d','class'=>'doctors-info-wrapper'));
		$html.= html_writer::div('Benefit3','text-regular bold',array('data-w-id'=>'ded612a4-46d4-99db-02a7-32afc84e75a3', 'style'=>'opacity:0'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('data-w-id'=>'538f78b5-9027-f577-2623-8d2460522517', 'style'=>'opacity:0','class'=>'doctors-name-wrapper'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('data-w-id'=>'538f78b5-9027-f577-2623-8d246052251c', 'style'=>'opacity:0','class'=>'paragraph-regular'));
	
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		echo $html;
	}
	
	
	function courses(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('class'=>'team-slider wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::tag('h2',get_string('heading2','local_customhomepage'),array('class'=>'centered-heading'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'centered-subheading'));
		$html.= html_writer::start_tag('div',array('data-delay'=>'5000', 'data-animation'=>'slide','class'=>'team-slider-wrapper w-slider', 'data-autoplay'=>'true', 'data-easing'=>'ease', 'data-hide-arrows'=>'false', 'data-disable-swipe'=>'false', 'data-autoplay-limit'=>'0', 'data-nav-spacing'=>'12','data-duration'=>'500','data-infinite'=>'false'));
		$html.= html_writer::start_tag('div',array('class'=>'w-slider-mask'));
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course1','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By KP Academy','text-block');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course2','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By Chandu','text-block-2');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course3','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By Raj','text-block-3');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course4','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By Lorem','');
		$html.= html_writer::start_tag('div',array('class'=>'arrow-embed w-embed'));
		$html.= html_writer::start_tag('svg',array('width'=>'20','height'=>'20', 'viewbox'=>'0 0 20 20','fill'=>'none','xmlns'=>'http://www.w3.org/2000/svg'));
		$html.= html_writer::start_tag('path',array('fill-rule'=>'evenodd','clip-rule'=>'evenodd', 'd'=>'M11.72 15L16.3472 10.357C16.7732 9.92932 16.7732 9.23603 16.3472 8.80962L11.72 4.16667L10.1776 5.71508L12.9425 8.4889H4.16669V10.6774H12.9425L10.1776 13.4522L11.72 15Z', 'fill'=>'currentColor'));
		$html.= html_writer::end_tag('path');
		$html.= html_writer::end_tag('svg');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course5','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By Lorem','');
		$html.= html_writer::start_tag('div',array('class'=>'arrow-embed w-embed'));
		$html.= html_writer::start_tag('svg',array('width'=>'20','height'=>'20', 'viewbox'=>'0 0 20 20','fill'=>'none','xmlns'=>'http://www.w3.org/2000/svg'));
		$html.= html_writer::start_tag('path',array('fill-rule'=>'evenodd','clip-rule'=>'evenodd', 'd'=>'M11.72 15L16.3472 10.357C16.7732 9.92932 16.7732 9.23603 16.3472 8.80962L11.72 4.16667L10.1776 5.71508L12.9425 8.4889H4.16669V10.6774H12.9425L10.1776 13.4522L11.72 15Z', 'fill'=>'currentColor'));
		$html.= html_writer::end_tag('path');
		$html.= html_writer::end_tag('svg');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slide-wrapper w-slide'));
		$html.= html_writer::start_tag('div',array('class'=>'team-block'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/WhatsApp-Image-2022-08-01-at-2.08.14-PM.jpeg','loading'=>'lazy','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 44vw, (max-width: 991px) 43vw, (max-width: 1919px) 28vw, 282px', 'alt'=>'image','class'=>'team-member-image-two' ));
		$html.= html_writer::start_tag('div',array('class'=>'team-block-info'));
		$html.= html_writer::tag('h3',get_string('course6','local_customhomepage'),array('class'=>'team-member-name-two'));
		$html.= html_writer::tag('p',get_string('default','local_customhomepage'),array('class'=>'team-member-text'));
		$html.= html_writer::start_tag('a',array('href'=>'#','class'=>'text-link-arrow w-inline-block'));
		$html.= html_writer::div('By Lorem','');
		$html.= html_writer::start_tag('div',array('class'=>'arrow-embed w-embed'));
		$html.= html_writer::start_tag('svg',array('width'=>'20','height'=>'20', 'viewbox'=>'0 0 20 20','fill'=>'none','xmlns'=>'http://www.w3.org/2000/svg'));
		$html.= html_writer::start_tag('path',array('fill-rule'=>'evenodd','clip-rule'=>'evenodd', 'd'=>'M11.72 15L16.3472 10.357C16.7732 9.92932 16.7732 9.23603 16.3472 8.80962L11.72 4.16667L10.1776 5.71508L12.9425 8.4889H4.16669V10.6774H12.9425L10.1776 13.4522L11.72 15Z', 'fill'=>'currentColor'));
		$html.= html_writer::end_tag('path');
		$html.= html_writer::end_tag('svg');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		$html.= html_writer::start_tag('div',array('class'=>'team-slider-arrow w-slider-arrow-left'));
		$html.= html_writer::start_tag('div',array('class'=>'w-icon-slider-left'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'team-slider-arrow w-slider-arrow-right'));
		$html.= html_writer::start_tag('div',array('class'=>'w-icon-slider-right'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'team-slider-nav w-slider-nav w-slider-nav-invert w-round'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('section');
		$html.= html_writer::end_tag('div');
		echo $html;
	}
	
	
	function contact(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('id'=>'contact','class'=>'consultation-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small'));
		$html.= html_writer::start_tag('div',array('class'=>'consultation-title-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'margin-bottom'));
		$html.= html_writer::start_tag('div',array('data-w-id'=>'885d79f3-787b-fcc5-33e5-5628b47b9aca','style'=>'opacity:0','class'=>'consultation-heading-wrapper'));
		$html.= html_writer::tag('h3',get_string('botomtext','local_customhomepage'),array('class'=>'heading-large text-color-darkblue'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('data-w-id'=>'885d79f3-787b-fcc5-33e5-5628b47b9acd','style'=>'opacity:0','class'=>'consultation-text-wrapper'));
		$html.= html_writer::tag('a','contact',array('href'=>'#','class'=>'button-2 w-button'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
			
		echo $html;
	}*/
	    function benefitsection(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('section',array('id'=>'benefits','class'=>'benefits'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'section-title aos-init aos-animate','data-aos'=>'fade-up'));
		$html.= html_writer::tag('h2','Grow Your Business',array('class'=>''));
		$html.= html_writer::tag('p','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',array('class'=>''));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'row no-gutters'));
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '01' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Advertising',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '02' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Designing',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '03' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Online Classes',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '04' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Material Content',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '05' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Lorem Ipsum',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 content-item'));
	    $html .= html_writer::start_span('') . '06' . html_writer::end_span();
	    $html.= html_writer::tag('h4','Lorem Ipsum',array('class'=>''));
	    $html.= html_writer::tag('p','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',array('class'=>''));
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('div');
	    $html.= html_writer::end_tag('section');
	    echo $html;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function homefooter(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('footer',array('id'=>'footer'));
		$html.= html_writer::start_tag('div',array('class'=>'footer-top'));
		$html.= html_writer::start_tag('div',array('class'=>'container'));
		$html.= html_writer::start_tag('div',array('class'=>'row'));
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-3 col-md-6 footer-contact'));
		$html.=html_writer::tag('a','Logo',array('href'=>$CFG->wwwroot));
		$html.=html_writer::tag('p','Lorem ipsum dolor sit',array(''));
		$html.=html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-2 col-md-6 footer-links'));
		$html.= html_writer::tag('h4','Useful Links',array('class'=>''));
		$html.= html_writer::start_tag('ul');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Home',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','About',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Services',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Terms of service',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Privacy policy',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::end_tag('ul');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-3 col-md-6 footer-links'));
		$html.= html_writer::tag('h4','Our Services',array('class'=>''));
		$html.= html_writer::start_tag('ul');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Lorem ipsum',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Lorem ipsum',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Lorem ipsum',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Lorem ipsum',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::start_tag('li');
		$html.= html_writer::tag('i','',array('class'=>'bx bx-chevron-right'));
		$html.= html_writer::tag('a','Lorem ipsum',array('class'=>''));
		$html.= html_writer::end_tag('li');
		$html.= html_writer::end_tag('ul');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'col-lg-4 col-md-6 footer-newsletter'));
		$html.= html_writer::tag('h4','Join Us Now',array('class'=>''));
		$html.= html_writer::tag('p','Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt .',array('class'=>''));
		$html.= html_writer::start_tag('form',array('action-method'=>'post'));
		$html.= html_writer::start_tag('input',array('type'=>'email','name'=>'email'));
		$html.= html_writer::start_tag('input',array('type'=>'submit','value'=>'Send'));
		$html.= html_writer::end_tag('form');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		/*$html.=html_writer::tag('script','',array('src'=>'https://code.jquery.com/jquery-3.3.1.slim.min.js','integrity'=>'sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo','crossorigin'=>'anonymous'));
		//$html.=html_writer::tag('script','',array('src'=>'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script'));
		$html.=html_writer::tag('script','',array('src'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'));
		$html.=html_writer::tag('script','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/js/javascript.js','type'=>'text/javascript'));
		$html.=html_writer::tag('script','',array('src'=>'https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=62e3a4795bac5280846d9239','type'=>'text/javascript','integrity'=>'sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=','crossorigin'=>'anonymous'));*/
		$html.=html_writer::end_tag('body');
		$html.=html_writer::end_tag('html');
		echo $html;
	}
	
	/*function homefooter(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'footer-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small mrbr'));
		$html.= html_writer::start_tag('div',array('class'=>'w-layout-grid footer-grid'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_6e5b8beb-9481-3cc8-8fb6-9e5f71a1da28-e26d923d','class'=>'footer-logo-wrapper'));
		$html.= html_writer::start_tag('a',array('href'=>'#','aria-current'=>'page','class'=>'footer-logo-link w-inline-block w--current'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/CampusTutr.png','loading'=>'lazy','sizes'=>'(max-width: 537px) 93vw, 500px', 'alt'=>'logo','class'=>'footer-logo' ));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-b2b946a8-822a-4730-7350-bf3d2562f2ac-e26d923d','class'=>'footer-page-links-wrapper'));
		$html.= html_writer::tag('a',get_string('home','local_customhomepage'),array('href'=>$CFG->wwwroot,'aria-current'=>'page','class'=>'footer-page-links w--current'));
		$html.= html_writer::tag('a',get_string('services','local_customhomepage'),array('href'=>'#','class'=>'footer-page-links'));
		$html.= html_writer::tag('a',get_string('aboutus','local_customhomepage'),array('href'=>'#','class'=>'footer-page-links'));
		$html.= html_writer::tag('a',get_string('contact','local_customhomepage'),array('href'=>'#','class'=>'footer-page-links no-margin'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-eab1cc32-0e79-b9b4-0d5d-1a9075cb050b-e26d923d','class'=>'footer-contact-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'footer-email-wrapper'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/main.svg','loading'=>'lazy', 'alt'=>'icon', 'class'=>'email-icon'));
		$html.= html_writer::tag('a','support@Campustutr.com',array('href'=>'#','class'=>'footer-email-link'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'footer-phone-link'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/main_1.svg','loading'=>'lazy','width'=>'16','alt'=>'icon', 'class'=>'contact-icon'));
		$html.= html_writer::tag('a','',array('href'=>'#','class'=>'footer-contact-link'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		
		
		
		$html.=html_writer::tag('script','',array('src'=>'https://code.jquery.com/jquery-3.3.1.slim.min.js','integrity'=>'sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo','crossorigin'=>'anonymous'));
		//$html.=html_writer::tag('script','',array('src'=>'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script'));
		$html.=html_writer::tag('script','',array('src'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'));
		$html.=html_writer::tag('script','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/js/javascript.js','type'=>'text/javascript'));
		$html.=html_writer::tag('script','',array('src'=>'https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=62e3a4795bac5280846d9239','type'=>'text/javascript','integrity'=>'sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=','crossorigin'=>'anonymous'));
		$html.=html_writer::end_tag('body');
		$html.=html_writer::end_tag('html');
		echo $html;
	}*/



}

