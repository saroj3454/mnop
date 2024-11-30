<?php class Homepage
{
	function __construct()
	{
		$this->htmlheader();
		$this->headertopbar();
		$this->aboutsection();
		$this->featurescard();
		$this->service1();
		$this->service2();
		$this->service3();
		$this->clients();
		$this->benefits();
		$this->courses();
		$this->contact();
		$this->homefooter();
	}
	function htmlheader()
	{
		global $DB, $OUTPUT,$CFG;
		$html=html_writer::start_tag('html',array('data-wf-page'=>'62e3a4795bac521de26d923d','data-wf-site'=>'62e3a4795bac5280846d9239'));
		$html.=html_writer::start_tag('head');
		$html.=html_writer::tag('title',get_string('homepage_title','local_customhomepage'));
		$html.=html_writer::start_tag('meta',array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
		$html.=html_writer::tag('script','',array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'));
		$html.=html_writer::tag('script','',array('src'=>'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'));
		 $html.=html_writer::start_tag('link',array('rel'=>'stylesheet','href'=>'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/style.css'));
		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/custom.css'));

		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>$CFG->wwwroot.'/local/customhomepage/assest/css/normalize.css'));


		$html.=html_writer::start_tag('link',array('rel'=>'stylesheet','type'=>'text/css','href'=>'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		$html.=html_writer::end_tag('head');
		$html.=html_writer::start_tag('body',array('class'=>'body'));
		echo $html;
	}
	function headertopbar()
	{
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'page-wrapper'));
		$html.= html_writer::start_tag('div',array('class'=>'header-section wf-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small nav-container'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-header-area'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-grid hide'));
		$html.= html_writer::start_tag('div',array('id'=>'w-node-dd95d815-212e-ff34-91cf-6a6cf9a344b6-e26d923d','class'=>'top-bar-email-section'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-email-link'));
		$html.= html_writer::tag('img','',array('src'=>'main.svg','loading'=>'lazy','alt'=>'image','class'=>'email-icon'));
		$html.=html_writer::tag('a',get_string('mail','local_customhomepage'),array('class'=>'email-link','href'=>'#'));

		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('id'=>'w-node-_8806bd3d-74a6-8b28-5aa6-1984df11d36e-e26d923d','class'=>'top-bar-contact-section'));
		$html.= html_writer::start_tag('div',array('class'=>'top-bar-contact-link'));
		$html.= html_writer::tag('img','',array('src'=>'main_1.svg','loading'=>'lazy','alt'=>'image_1','class'=>'contact-icon'));
		$html.=html_writer::tag('a',get_string('phnumber','local_customhomepage'),array('class'=>'contact-link','href'=>'#'));

		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');

		$html.= html_writer::start_tag('div',array('data-animation'=>'default','data-collapse'=>'medium', 'data-duration'=>'400','data-easing'=>'ease','data-easing2'=>'ease','role'=>'banner','class'=>'navbar w-nav'));
		$html.=html_writer::start_tag('a',array('class'=>'brand w-nav-brand w--current','aria-current'=>'page','href'=>$CFG->wwwroot));
		$html.= html_writer::start_tag('div',array('class'=>'logo'));
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/CampusTutr.png','loading'=>'lazy','width'=>'150','height'=>'150', 'sizes'=>'(max-width: 479px) 100vw, 150px',  'alt'=>'logo', 'class'=>'logo-image'));
		$html.= html_writer::end_tag('a');
		$html.= html_writer::start_tag('nav',array('role'=>'navigation','class'=>'nav-menu w-nav-menu'));
		$html.=html_writer::tag('a',get_string('home','local_customhomepage'),array('href'=>$CFG->wwwroot, 'aria-current'=>'page', 'class'=>'nav-link w-nav-link w--current'));
		$html.=html_writer::tag('a',get_string('services','local_customhomepage'),array('href'=>'#Services','class'=>'nav-link w-nav-link'));
		$html.=html_writer::tag('a',get_string('aboutus','local_customhomepage'),array('href'=>'#AboutUs','class'=>'nav-link w-nav-link'));
		$html.=html_writer::tag('a',get_string('contact','local_customhomepage'),array('href'=>'#Contact','class'=>'nav-link w-nav-link'));
		$html.= html_writer::start_tag('div',array('class'=>'header-button'));
		$html.=html_writer::tag('a',get_string('signup','local_customhomepage'),array('href'=>'#SignUp','class'=>'secondary-button w-button'));
		$html.= html_writer::end_tag('nav');
		$html.= html_writer::start_tag('div',array('class'=>'menu-button w-nav-button'));
		$html.= html_writer::start_tag('div', array('class'=>'w-icon-nav-menu'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		echo $html;
	}

	function aboutsection()
	{
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'main-wrapper'));
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
		$html.= html_writer::start_tag('div',array('class'=>'icon w-icon-dropdown-toggle'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::div('Our Services','dropdown-text',array('id'=>'services'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('nav',array('class'=>'dropdown-list w-dropdown-list'));
		$html.=html_writer::tag('a',get_string('service1','local_customhomepage'),array('href'=>'#Become a Tutor','class'=>'dropdown-link w-dropdown-link'));
		$html.=html_writer::tag('a',get_string('service2','local_customhomepage'),array('href'=>'#Corporate Learning','class'=>'dropdown-link w-dropdown-link'));
		$html.=html_writer::tag('a',get_string('service3','local_customhomepage'),array('href'=>'#Sell Material Content','class'=>'dropdown-link w-dropdown-link'));
		$html.= html_writer::end_tag('nav');
		$html.= html_writer::end_tag('div');
		$html.=html_writer::start_tag('a',array('class'=>'primary-button w-button'));
		$html.=html_writer::start_span('text-span-10') . 'JOIN NOW' . html_writer::end_span();
		$html.= html_writer::end_tag('a');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/About-image.jpeg','width'=>'515', 'height'=>'350', 'alt'=>'image1', 'sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 74vw, 515px', 'id'=>'w-node-_244ddb55-4d63-9020-a97b-368914a64fb5-e26d923d', 'loading'=>'lazy', 'srcset'=>'','class'=>'image-8'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		$html.= html_writer::end_tag('div');
		echo $html;
	}


	function featurescard()
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
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2'));
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
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2'));
		$html.= html_writer::end_tag('div');
		$html.= html_writer::start_tag('div',array('class'=>'hero-split'));
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
		$html.= html_writer::tag('img','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/image/graduation-g37d563ae2_1920.jpg','loading'=>'lazy', 'alt'=>'image','sizes'=>'(max-width: 479px) 100vw, (max-width: 767px) 94vw, (max-width: 991px) 92vw, (max-width: 1919px) 43vw, 432.375px','class'=>'shadow-two-2'));
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
		$html.= html_writer::start_tag('div',array('id'=>'About-us','class'=>'doctors-section'));
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
	}
	
	function homefooter(){
		global $DB, $OUTPUT,$CFG;
		$html.= html_writer::start_tag('div',array('class'=>'footer-section'));
		$html.= html_writer::start_tag('div',array('class'=>'container-small'));
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
		
		$html.=html_writer::tag('script','',array('src'=>$CFG->wwwroot.'/local/customhomepage/assest/js/javascript.js','type'=>'text/javascript'));
		$html.=html_writer::tag('script','',array('src'=>'https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=62e3a4795bac5280846d9239','type'=>'text/javascript','integrity'=>'sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=','crossorigin'=>'anonymous'));
		$html.=html_writer::end_tag('body');
		$html.=html_writer::end_tag('html');
		echo $html;
	}



}

$class = new Homepage();