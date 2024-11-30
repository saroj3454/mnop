<?php 
class Webhookdata{
	  public $fileurl;
	function __construct(){
		$this->fileurl=FILE_URL;	
	 add_action( 'wp_headlibrary',[ $this, 'headerlibrary' ] );
	 add_action( 'footer_library', [ $this,'footerlibrarycdn']);
	 add_action( 'init', [ $this,'register_my_menus'] );
	 add_action( 'init', [ $this,'register_my_menus'] );
	 add_action('head_content',[ $this,'headcontentdata']);
	 add_filter( 'get_custom_logo',[ $this,'change_logo_class'] );
	 add_action( 'widgets_init', [ $this,'header_content_section_init']);
	}
	function headerlibrary(){
		$data='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/bootstrap.min.css">';
		$data.='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/custom.css">';
		$data.='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/slick.css">';
		$data.='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/jquery.mmenu.all.css">';
		$data.='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/style.css">';
		$data.='<link rel="stylesheet" type="text/css" href="'.$this->fileurl.'css/responsive.css">';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/Barlow-Medium.woff2" as="font" crossorigin>';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/Barlow-Regular.woff2" as="font" crossorigin>';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/BarlowBold.woff2" as="font" crossorigin>';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/BarlowSemiBold.woff2" as="font" crossorigin>';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/GilroyMedium.woff2" as="font" crossorigin>';
		$data.='<link rel="preload" type="font/woff2" href="'.$this->fileurl.'fonts/GilroyRegular.woff2" as="font" crossorigin>';
		$data.='<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">';
		echo $data;
	}
	function footerlibrarycdn(){
	  	$data='<script  src="'.$this->fileurl.'js/jquery-3.5.1.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/bootstrap.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/slick.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/smk-accordion.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/userscript.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/jquery.session.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/jquery.mCustomScrollbar.concat.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/jquery.mmenu.all.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/wow.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/scrollIt.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/scrollIt.min.js" as="script"></script>';
	  	$data.='<script  src="'.$this->fileurl.'js/custom.js" as="script"></script>';

	  		$data.='';

	  echo $data;
	}
	function register_my_menus() {
	  register_nav_menus(
	    array(
	      'web-menu' => __( 'Web Menu' ),
	      'footer-menu' => __( 'Footer Quick Links' )
	     )
	   );
 	}
   function headcontentdata(){
	 get_template_part( 'template-parts/headcontentdata', 'none' );
   }
   function change_logo_class( $html ) {
	    $html = str_replace( 'custom-logo', 'img-responsive', $html );
	    return $html;
	}
   function header_content_section_init(){
    register_sidebar(
        array(
            'name'          => esc_html__( 'Header Contect Section', 'oawa' ),
            'id'            => 'header_content',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '</div>
    </div>',
        )
    );
   }


}
 $webhook=new Webhookdata();