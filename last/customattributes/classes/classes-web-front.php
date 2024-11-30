<?php 
class WebHomedata{
	  public $fileurl;
	function __construct(){
		$this->fileurl=FILE_URL;
	 add_action('front_data',[ $this,'front_data_display']);
	 add_action('widgets_init',[ $this,'front_slidersection']);
	 add_action('widgets_init',[ $this,'front_wealth_management']);
	 add_action('front_slider',[ $this,'front_sliderdisplay']);
	 add_action('front_wealth_management',[ $this,'front_wealth_managementdisplay']);
	}
	function front_data_display(){
		get_template_part( 'template-parts/home', 'none');
	}
	function front_slidersection(){
		register_sidebar(array(
            'name'          => esc_html__( 'Slider Section Form Section', 'oawa' ),
            'id'            => 'slider_data',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
	}
	function front_sliderdisplay(){
		if (is_active_sidebar( 'slider_data') ){
					echo "<section id='oawa_sl' class='oawa_fsl wa_fsl'>
					<div id='sub_oawa_sl' class='sub_oawa_fsl sub_wa_fsl'>";
            			dynamic_sidebar( 'slider_data' );
           		 echo "</div></section>";
            }
	}
	function front_wealth_management(){
		register_sidebar(array(
            'name'          => esc_html__( 'Wealth Management Home Page Section', 'oawa' ),
            'id'            => 'wealth_management_data',
            'description'   => esc_html__( 'Add widgets here.', 'oawa' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
	}
	function front_wealth_managementdisplay(){
			if (is_active_sidebar( 'wealth_management_data') ){
			echo "<section id='wealth_management' class='wealth_management container'>";
            dynamic_sidebar( 'wealth_management_data' );
            echo "</section>";
            }
	}
	




}
 $webhook=new WebHomedata();