<?php
/*
Plugin Name:  Custom Attributes
Description:  This plugin using For oawa theme.
Version:      1.0
Author:       Lds(Saroj,Sushil) 
Author URI:   https://www.ldsengineers.com/
*/
ob_start();
// If this file is called directly, abort. 
if ( ! defined( 'WPINC' ) ) {
die;
}
//for theme file
define('FILE_URL',site_url().'/wp-content/themes/oawa/assest/');
//web hook library,menu,header css library
include_once( plugin_dir_path( __FILE__ ) . 'classes/classes-web-hook.php');
//web home data
include_once( plugin_dir_path( __FILE__ ) . 'classes/classes-web-front.php');
//Web header all enquiry form
include_once( plugin_dir_path( __FILE__ ) . 'classes/classes-web-enquiry.php');
include_once( plugin_dir_path( __FILE__ ) . 'classes/classes-user-auth.php');

include_once( plugin_dir_path( __FILE__ ) . 'classes/classes-enquiry.php');


function hstngr_register_widget() {
register_widget( 'hstngr_widget' );
register_widget( 'hstngr_multiopt_widget' );
register_widget( 'oawa_startdiv');
register_widget( 'oawa_enddiv');
register_widget( 'oawa_button');
register_widget( 'oawa_readmore');
}



class oawa_button extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'oawa_button',
    // widget name
    __('OAWA Button', ' hstngr_widget_domain'),
    // widget description
    array( 'description' => __( 'form button', 'hstngr_widget_domain' ), )
    );
  }


  public function widget( $args, $instance ) {
     $htmlbutton=( ! empty( $instance['htmlbutton'] ) ) ? $instance['htmlbutton']  : '';
     $inlinecode=( ! empty( $instance['inlinecode'] ) ) ? $instance['inlinecode']  : '';
     $buttontype=( ! empty( $instance['buttontype'] ) ) ? $instance['buttontype']  : '';
     $innerclass=( ! empty( $instance['innerclass'] ) ) ? $instance['innerclass']  : '';

      //if title is present
      if(!empty($htmlbutton)){

	      	if(!empty($innerclass)){
	      		$divhtmlbuttonopen="<div class='$innerclass'>";
	      		$divhtmlbuttonclose="</div>";
	      	}else{
	      		$divhtmlbuttonopen="";
	      		$divhtmlbuttonclose="";
	      	}
              if($buttontype=='submit'){
                echo $divhtmlbuttonopen."<input type='submit' value='$htmlbutton' $inlinecode>".$divhtmlbuttonclose;
              }
              if($buttontype=='redirect'){
                echo "<a $inlinecode>$divhtmlbuttonopen $htmlbutton $divhtmlbuttonclose</a>";
              }
              if($buttontype=="allpagebutton"){
              	echo "<button type='button' $inlinecode>$divhtmlbuttonopen $htmlbutton $divhtmlbuttonclose</button>";
              } 
              if($buttontype=="redirectbutton"){
              	echo "<a $inlinecode><button type='button'>$divhtmlbuttonopen $htmlbutton $divhtmlbuttonclose</button></a>";
              }

      }
    }
  public function form( $instance ) {
  
    if ( isset( $instance[ 'htmlbutton' ] ) ){
       $htmlbutton = $instance[ 'htmlbutton' ]; 
    }else{
    $htmlbutton = "";
    }

    if( isset( $instance[ 'inlinecode' ] ) ){
       $inlinecode = $instance[ 'inlinecode' ]; 
    }else{
    $inlinecode = "";
    }

    if(isset( $instance[ 'buttontype' ] ) ){
       $buttontype = $instance[ 'buttontype' ]; 
    }else{
    $buttontype = "";
    }
     if(isset( $instance[ 'innerclass' ] ) ){
       $innerclass = $instance[ 'innerclass' ]; 
    }else{
    $innerclass = "";
    }


  
    ?>
    

    <p>
    <label for="<?php echo $this->get_field_id( 'htmlbutton' ); ?>"><?php _e( 'Button Name ' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'htmlbutton' ); ?>" name="<?php echo $this->get_field_name( 'htmlbutton' ); ?>" type="text" value="<?php echo esc_attr( $htmlbutton ); ?>" />
    </p>


    <p>
    <label for="<?php echo $this->get_field_id( 'inlinecode' ); ?>"><?php _e( 'Inline Attribute' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'inlinecode' ); ?>" name="<?php echo $this->get_field_name( 'inlinecode' ); ?>" type="text" value="<?php echo esc_attr( $inlinecode ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'buttontype' ); ?>"><?php _e( 'Button Type:' ); ?></label>
    
    <select class="widefat" id="<?php echo $this->get_field_id( 'buttontype' ); ?>" name="<?php echo $this->get_field_name( 'buttontype' ); ?>">
      <option value="submit" <?php if($buttontype=="submit"){ echo "selected"; } ?>>Submit</option>
      <option value="redirect" <?php if($buttontype=="redirect"){ echo "selected"; } ?>>Redirect</option>
      <option value="allpagebutton" <?php if($buttontype=="allpagebutton"){ echo "selected"; } ?>>All Page Button</option>
      <option value="redirectbutton" <?php if($buttontype=="redirectbutton"){ echo "selected"; } ?>>Redirect Page Button</option>
    </select>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'innerclass' ); ?>"><?php _e( 'Title Inner Class' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'innerclass' ); ?>" name="<?php echo $this->get_field_name( 'innerclass' ); ?>" type="text" value="<?php echo esc_attr( $innerclass ); ?>" />
    </p>

   
   
    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['htmlbutton'] = ( ! empty( $new_instance['htmlbutton'] ) ) ? $new_instance['htmlbutton'] : '';
    $instance['inlinecode'] = ( ! empty( $new_instance['inlinecode'] ) ) ? $new_instance['inlinecode'] : '';
    $instance['buttontype'] = ( ! empty( $new_instance['buttontype'] ) ) ? $new_instance['buttontype'] : '';
    $instance['innerclass'] = ( ! empty( $new_instance['innerclass'] ) ) ? $new_instance['innerclass'] : '';
    return $instance;
  }


}

class oawa_enddiv extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'oawa_enddiv',
    // widget name
    __('html Code Write Div', ' hstngr_widget_domain'),
    // widget description
    array( 'description' => __( 'Code Write Div', 'hstngr_widget_domain' ), )
    );
  }


  public function widget( $args, $instance ) {
     $codewrite=( ! empty( $instance['codewrite'] ) ) ? $instance['codewrite']  : '';
    //if title is present
    if(!empty($codewrite)){
      echo $codewrite;
    }

    
    }
  public function form( $instance ) {
  
    if ( isset( $instance[ 'codewrite' ] ) ){
       $codewrite = $instance[ 'codewrite' ]; 
    }else{
    $codewrite = "";
    }
  
    ?>
    

    <p>
    <label for="<?php echo $this->get_field_id( 'codewrite' ); ?>"><?php _e( 'Code Write / End Div ' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'codewrite' ); ?>" name="<?php echo $this->get_field_name( 'codewrite' ); ?>" type="text" value="<?php echo esc_attr( $codewrite ); ?>" />
    </p>

   
   
    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['codewrite'] = ( ! empty( $new_instance['codewrite'] ) ) ? $new_instance['codewrite'] : '';
    return $instance;
  }


}


class oawa_startdiv extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'oawa_startdiv',
    // widget name
    __('Start Div', ' hstngr_widget_domain'),
    // widget description
    array( 'description' => __( 'Start  Div', 'hstngr_widget_domain' ), )
    );
  }


  public function widget( $args, $instance ) {
    $classname=( ! empty( $instance['classname'] ) ) ? strip_tags( $instance['classname'] ) : '';
    $id=( ! empty( $instance['id'] ) ) ? strip_tags( $instance['id'] ) : '';
    //if title is present
    if (!empty( $classname ) && ! empty($id)){
      ?>
      <div class="<?php echo $classname; ?>" id="<?php echo $id; ?>">
      <?php 
      }else{
        ?>
        <div class="<?php echo $classname; ?>" id="<?php echo $id; ?>">
        <?php
      } 
    }
  public function form( $instance ) {
    

    if ( isset( $instance[ 'classname' ] ) ){
       $classname = $instance[ 'classname' ]; 
    }else{
    $classname = "";
    }
    if ( isset( $instance[ 'id' ] ) ){
       $id = $instance[ 'id' ]; 
    }else{
    $id = "";
    }

   

    ?>
    

    <p>
    <label for="<?php echo $this->get_field_id( 'classname' ); ?>"><?php _e( 'Class Name:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'classname' ); ?>" name="<?php echo $this->get_field_name( 'classname' ); ?>" type="text" value="<?php echo esc_attr( $classname ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Id:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
    </p>
   
    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['classname'] = ( ! empty( $new_instance['classname'] ) ) ? $new_instance['classname'] : '';
    $instance['id'] = ( ! empty( $new_instance['id'] ) ) ? $new_instance['id'] : '';

    return $instance;
  }


}








add_action( 'widgets_init', 'hstngr_register_widget' );


class hstngr_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'hstngr_widget',
    // widget name
    __('Form Input Type Field', ' hstngr_widget_domain'),
    // widget description
    array( 'description' => __( 'Hostinger Widget Tutorial', 'hstngr_widget_domain' ), )
    );
  }


  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );



    // $name=( ! empty( $instance['name'] ) ) ? strip_tags( $instance['name'] ) : '';
    $required=( ! empty( $instance['required'] ) ) ? strip_tags( $instance['required'] ) : '';
    if(!empty($instance['name'])){
    	$name=str_replace(' ','_',strtolower(strip_tags($instance['name'])));
    }else{
		$name=str_replace(' ','_',strtolower(strip_tags($instance['title'])));
    }

 
    //if title is present
    if ( ! empty( $title ) ){
    	if(!empty($instance['innerclassdata'])){
    		$divopenclass="<div class='".$instance['innerclassdata']."'>";
    		$divcloseclass="</div>";
    	}else{
    		$divopenclass="";
    		$divcloseclass="";
    	}

if(!empty($instance['innerattri'])){
  $innerattri=$instance['innerattri'];
}


   if($instance['type']=='text'){
  
  echo $divopenclass; ?>
   <input type='text' name='<?php echo $name; ?>' placeholder='<?php echo $title; ?>' <?php echo $innerattri; ?> <?php if($required=='yes'){ echo'required';} ?>>
  <?php  
  echo $divcloseclass;
  }

  if($instance['type']=='email'){ 
 echo $divopenclass;
  	?>
 <input type='email' name='<?php echo $name; ?>' placeholder='<?php echo $title; ?>' <?php echo $innerattri; ?> <?php if($required=='yes'){ echo'required';} ?>>
  <?php
echo $divcloseclass;

    }

if($instance['type']=='textarea'){
	 echo $divopenclass;
      ?>
       <textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>" <?php echo $innerattri; ?> placeholder='<?php echo $title; ?>'  <?php if($required=='yes'){ echo'required';} ?> ></textarea>
      <?php
      echo $divcloseclass;
    }

    if($instance['type']=='phone'){ 
 echo $divopenclass;
  	?><input type='number' name='<?php echo $name; ?>' placeholder='<?php echo $title; ?>' <?php echo $innerattri; ?>  <?php if($required=='yes'){ echo'required';} ?>>
  <?php
echo $divcloseclass;

    }

    


  }

}
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ){
       $title = $instance[ 'title' ]; 
    }else{
    $title = __( 'Default Title', 'hstngr_widget_domain' );
    }

    if ( isset( $instance[ 'name' ] ) ){
       $name = $instance[ 'name' ]; 
    }


    if ( isset( $instance[ 'required' ] ) ){
       $required = $instance[ 'required' ]; 
    }else{
    $required = "no";
    }

    if ( isset( $instance[ 'type' ] ) ){
       $type = $instance[ 'type' ]; 
    }else{
    $type = "text";
    }
    if ( isset( $instance[ 'innerclassdata' ] ) ){
       $innerclassdata = $instance[ 'innerclassdata' ]; 
    }else{
    $innerclassdata = "";
    }


     if ( isset( $instance[ 'innerattri' ] ) ){
       $innerattri = $instance[ 'innerattri' ]; 
    }else{
    $innerattri = "";
    }


    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e( 'Name:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'required' ); ?>"><?php _e( 'Required Field:' ); ?></label>
    
    <select class="widefat" id="<?php echo $this->get_field_id( 'required' ); ?>" name="<?php echo $this->get_field_name( 'required' ); ?>">
      <option value="no" <?php if($required=="no"){ echo "selected"; } ?>>No</option>
      <option value="yes" <?php if($required=="yes"){ echo "selected"; } ?>>Yes</option>
    </select>
    </p>


    <p>
    <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Field Type:' ); ?></label>
    
    <select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
      <option value="text" <?php if($type=="text"){ echo "selected"; } ?>>Text</option>
      <option value="email" <?php if($type=="email"){ echo "selected"; } ?>>Email</option>
      <option value="textarea" <?php if($type=="textarea"){ echo "selected"; } ?>>Textarea</option>
      <option value="phone" <?php if($type=="phone"){ echo "selected"; } ?>>Phone</option>
    </select>
    </p>


   <p>
      <label for="<?php echo $this->get_field_id( 'innerclassdata' ); ?>">Inner Class</label>
      <textarea class="widefat" id="<?php echo $this->get_field_id( 'innerclassdata' ); ?>" name="<?php echo $this->get_field_name( 'innerclassdata' ); ?>" ><?php echo esc_attr( $innerclassdata ); ?></textarea>
    </p>

<p>
      <label for="<?php echo $this->get_field_id( 'innerattri' ); ?>">Inline Attribute</label>
      <textarea class="widefat" id="<?php echo $this->get_field_id( 'innerattri' ); ?>" name="<?php echo $this->get_field_name( 'innerattri' ); ?>" ><?php echo esc_attr( $innerattri ); ?></textarea>
    </p>



    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
    if(empty($new_instance['title'])){
      $name=strip_tags(str_replace(' ','_',strtolower($new_instance['title'])));
    }else{
      $name=strip_tags(str_replace(' ','_',strtolower($new_instance['name'])));
    }
    $instance['name']=$name;
    $instance['required'] = ( ! empty( $new_instance['required'] ) ) ? $new_instance['required'] : '';
    $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? $new_instance['type'] : '';
    $instance['innerclassdata'] = ( ! empty( $new_instance['innerclassdata'] ) ) ? $new_instance['innerclassdata'] : '';

    $instance['innerattri'] = ( ! empty( $new_instance['innerattri'] ) ) ? $new_instance['innerattri'] : '';

    return $instance;
  }


}

class hstngr_multiopt_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'hstngr_multiopt_widget',
    // widget name
    __('Multi choice', ' hstngr_multiopt_widget'),
    // widget description
    array( 'description' => __( 'Hostinger Widget Tutorial', 'hstngr_multiopt_widget' ), )
    );
  }


  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    $options = ( ! empty( $instance['options'] ) ) ? strip_tags( $instance['options'] ) : '';
    $type = ( ! empty( $instance['type'] ) ) ? strip_tags( $instance['type'] ) : 'dropdown';
    echo $args['before_widget'];
    //if title is present
    $alloptions = explode("\n", $options);
    if ( ! empty( $title ) && sizeof($alloptions)>0 ){
      echo '<div class="AAAAAA">';
        echo '<p>'.$title.'</p>';
        echo '<div class="BBBBB">';
        if($type=="dropdown"){
          echo '<select name="'.$args['widget_id'].'">';
          foreach ($alloptions as $key => $opt) {
            $optval = explode(":", $opt);
            echo '<option value="'.$optval[0].'">'.(isset($optval[1])?$optval[1]:$optval[0]).'</option>';
          }
          echo '</select>';

        } else if($type=="checkbox"){
          echo '<div class="alloption">';
          foreach ($alloptions as $key => $opt) {
            $optval = explode(":", $opt);
            echo '<lavel for="'.$args['widget_id'].'_'.$key.'"> <input  name="'.$args['widget_id'].'[]" type="checkbox" id="'.$args['widget_id'].'_'.$key.'" value="'.$optval[0].'">'.(isset($optval[1])?$optval[1]:$optval[0]).'</lavel>';
          }
          echo '</div>';
        } else {
          echo '<div class="alloption">';
          foreach ($alloptions as $key => $opt) {
            $optval = explode(":", $opt);
            echo '<lavel for="'.$args['widget_id'].'_'.$key.'"> <input  name="'.$args['widget_id'].'" type="radio" id="'.$args['widget_id'].'_'.$key.'" value="'.$optval[0].'">'.(isset($optval[1])?$optval[1]:$optval[0]).'</lavel>';
          }
          echo '</div>';

        }
        echo '</div';

      echo '</div';
    }


  }


  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ){
       $title = $instance[ 'title' ]; 
    }else{
      $title = __( 'Default Title', 'hstngr_widget_domain' );
    }
    if ( isset( $instance[ 'options' ] ) ){
       $options = $instance[ 'options' ]; 
    }else{
      $options = "";
    }
    if ( isset( $instance[ 'type' ] ) ){
       $type = $instance[ 'type' ]; 
    }else{
      $type = "dropdown";
    }
    echo '<p>
      <label for="'.$this->get_field_id( 'title' ).'">'._e( 'Title:' ).'</label>
      <input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.esc_attr( $title ).'" />
    </p>
    <p>
      <label for="'.$this->get_field_id( 'type' ).'">Type</label>
      <select class="widefat" id="'.$this->get_field_id( 'type' ).'" name="'.$this->get_field_name( 'type' ).'" value="'.esc_attr( $type ).'">
        <option value="dropdown" '.(esc_attr( $type ) == 'dropdown'?' selected ':'').' >Dropdown</option>
        <option value="checkbox" '.(esc_attr( $type ) == 'checkbox'?' selected ':'').' >Multi Choice</option>
        <option value="radio" '.(esc_attr( $type ) == 'radio'?' selected ':'').' >Single Choice</option>
      </select>
    </p><p>
      <label for="'.$this->get_field_id( 'options' ).'">Options</label>
      <textarea class="widefat" id="'.$this->get_field_id( 'options' ).'" name="'.$this->get_field_name( 'options' ).'" >'.esc_attr( $options ).'</textarea>
    </p>';
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['options'] = ( ! empty( $new_instance['options'] ) ) ? strip_tags( $new_instance['options'] ) : '';
    $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : 'dropdown';

    return $instance;
  }


}



class oawa_readmore extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'oawa_readmore',
    // widget name
    __('Read More Button', ' oawa_readmore'),
    // widget description
    array( 'description' => __( 'Read More', 'oawa_readmore' ), )
    );
  }


  public function widget( $args, $instance ) {
     $readmore=( ! empty( $instance['readmore'] ) ) ? $instance['readmore']  : '';
     $inline_attribute=( ! empty( $instance['inline_attribute'] ) ) ? $instance['inline_attribute']  : '';
    //if title is present
    if(!empty($readmore)){

      if(!empty($instance['readmoretype']="readmore_button_homepage")){
        ?>
        <div  <?php echo $inline_attribute; ?>><?php  echo $readmore; ?></div>



        <?php
      }

    



    }

    
    }
  public function form( $instance ) {
  
    if ( isset( $instance[ 'readmore' ] ) ){
       $readmore = $instance[ 'readmore' ]; 
    }else{
    $readmore = "";
    }

    if ( isset( $instance[ 'title_content' ] ) ){
       $title_content = $instance[ 'title_content' ]; 
    }else{
    $title_content = "";
    }

    if ( isset( $instance[ 'content' ] ) ){
       $content = $instance[ 'content' ]; 
    }else{
    $content = "";
    }

    if ( isset( $instance[ 'readmoretype' ] ) ){
       $readmoretype = $instance[ 'readmoretype' ]; 
    }else{
    $readmoretype = "";
    }

    if ( isset( $instance[ 'inline_attribute' ] ) ){
       $inline_attribute = $instance[ 'inline_attribute' ]; 
    }else{
    $inline_attribute = "";
    }



  
  
    ?>
    

    <p>
    <label for="<?php echo $this->get_field_id( 'readmore' ); ?>"><?php _e( 'Read More Button Name ' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'readmore' ); ?>" name="<?php echo $this->get_field_name( 'readmore' ); ?>" type="text" value="<?php echo esc_attr( $readmore ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'title_content' ); ?>"><?php _e( 'Title' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title_content' ); ?>" name="<?php echo $this->get_field_name( 'title_content' ); ?>" type="text" value="<?php echo esc_attr( $title_content ); ?>" />
    </p>
    <p>
 <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'content ' ); ?></label>
    <textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" style='width:100%'><?php echo esc_attr( $content ); ?></textarea>
   </p>

   <p>
    <label for="<?php echo $this->get_field_id( 'readmoretype' ); ?>"><?php _e( 'Read More Section Type:' ); ?></label>
    
    <select class="widefat" id="<?php echo $this->get_field_id( 'readmoretype' ); ?>" name="<?php echo $this->get_field_name( 'readmoretype' ); ?>">
      <option value=""> </option>
      <option value="readmore_button_homepage" <?php if($readmoretype=="readmore_button_homepage"){ echo "selected"; } ?>>Read More Wealth Management Section</option>
      
    
    </select>

 <p>
 <label for="<?php echo $this->get_field_id( 'inline_attribute' ); ?>"><?php _e( 'Inline Attribute ' ); ?></label>
    <input type='text' id="<?php echo $this->get_field_id( 'inline_attribute' ); ?>" name="<?php echo $this->get_field_name( 'inline_attribute' ); ?>" value="<?php echo esc_attr( $inline_attribute ); ?>">
   </p>




    </p>
   
   
    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['readmore'] = ( ! empty( $new_instance['readmore'] ) ) ? $new_instance['readmore'] : '';
    $instance['title_content'] = ( ! empty( $new_instance['title_content'] ) ) ? $new_instance['title_content'] : '';
    $instance['content'] = ( ! empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
    $instance['readmoretype'] = ( ! empty( $new_instance['readmoretype'] ) ) ? $new_instance['readmoretype'] : '';
    $instance['inline_attribute'] = ( ! empty( $new_instance['inline_attribute'] ) ) ? $new_instance['inline_attribute'] : '';
    return $instance;
  }


}

add_shortcode('all-courses','oawa_course_list');

function oawa_course_list(){
  defined( 'ABSPATH' ) || exit;
  global $product;
 
  get_template_part('template-parts/oawa_course_list',null, array('course'=>$product));
  exit();
}
add_action('init','pagefunction');
function pagefunction(){


}
add_shortcode('courses-detail','oawa_course_detail');
function oawa_course_detail(){
  defined( 'ABSPATH' ) || exit;
  global $product;
 
  get_template_part('template-parts/oawa_course_detail',null, array('course'=>$product));
  exit();
}


add_action('wp_head', 'hide_for_logged_in_users');

function hide_for_logged_in_users(){

if ( is_checkout() ) {

if (!is_user_logged_in() ) {
  wp_redirect(site_url('user-login'));
}

}
}

function additional_custom_styles() {
	if ( is_checkout() ) {
 wp_enqueue_style('checkoutpagecss', FILE_URL.'css/checkoutcustom.css' ,false, '1.0.0', 'all');
}
if (!is_user_logged_in() ) {
 echo ' <style>.logout-btn{display:none;}.moodle-login-btn{display:none;}</style>';
	}else{
		 echo '<style>.login-btns{display:none;}</style>';
	}
}
add_action( 'wp_enqueue_scripts', 'additional_custom_styles' );



// add_action('template_redirect', 'check_if_logged_in');
// function check_if_logged_in() {
//     if ( ! is_user_logged_in() && is_checkout() ) {
//         $url = site_url('/my-account/');
//         wp_redirect( $url );
//         exit;
//     }
// }

// add_filter( 'woocommerce_registration_redirect', 'redirect_after_login_or_registration_to_checkout_page' );
// add_filter( 'woocommerce_login_redirect', 'redirect_after_login_or_registration_to_checkout_page' );
// function redirect_after_login_or_registration_to_checkout_page() {
  
//     if ( ! WC()->cart->is_empty() ) {
//         return wc_get_page_permalink( 'checkout' );
//     } else {
//         return home_url();
//     }
// }

function onboarding_update_fields( $fields = array()) {
   // check if it's set to prevent notices being thrown
  


   $userdata=wp_get_current_user();
$first_name=get_user_meta($userdata->data->ID,'first_name',true);
$last_name=get_user_meta($userdata->data->ID,'last_name',true);

       // if all you want to change is the value, then assign ONLY the value
       $fields['billing']['billing_first_name']['default'] = $first_name;
       $fields['billing']['billing_last_name']['default'] = $last_name;
       $fields['billing']['billing_email']['default'] = $userdata->data->user_email;
     
   // you must return the fields array 
   return $fields;


}

add_filter( 'woocommerce_checkout_fields', 'onboarding_update_fields' );
add_action('admin_menu', 'my_menu');

function my_menu() {
  $position="2";
 add_menu_page('Moodle Admin Dashboard', 'Moodle Admin Dashboard', 'manage_options', 'admindashboard', 'add_syncconfig','dashicons-admin-tools', $position );
}
 function add_syncconfig(){
        global $wpdb;
         if(!empty(MoodleURL)){
              $curl = curl_init();

              curl_setopt_array($curl, array(
              CURLOPT_URL => MoodleURL.'/local/oawa_auth/admin_login.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{"admin":"oawa","userid":"2"} ',
              CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json',
              'Cookie: MoodleSession=b7f592nvlf0cmiunfr3u7pkbrg'
              ),
              ));
              $response = curl_exec($curl);
              curl_close($curl);
              //echo $response;
             wp_redirect(MoodleURL.'/local/oawa_auth/admin_session.php?token='.json_decode($response));
             exit(); 
         }
      }

add_shortcode('user_logout', 'wordpresslogout'); 
function wordpresslogout(){
  
    session_destroy();
    wp_clear_auth_cookie();
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_logout(); 
    wp_redirect(MoodleURL.'/local/oawa_auth/moodlelogout.php');

   

}
add_action('wp_logout', 'destroy_sessions');
function destroy_sessions(){
    session_destroy();
}
// add_action( 'wp_logout', 'redirect_after_logout');
// function redirect_after_logout(){
//     // Syncssodata::redirect_after_logout();
// }


add_action( 'woocommerce_before_order_notes', 'bbloomer_add_custom_checkout_field' );
  
function bbloomer_add_custom_checkout_field( $checkout ) {  
$userdata=wp_get_current_user();
$first_name=get_user_meta($userdata->data->ID,'first_name',true);
$last_name=get_user_meta($userdata->data->ID,'last_name',true);
 $post =json_encode([
    'username' =>$userdata->data->user_login,
    'password' => $userdata->data->user_pass,
    'email'   =>$userdata->data->user_email,
    'first_name'=>$first_name,
    'last_name'=>$last_name
]);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => MoodleURL.'/local/oawa_auth/uservalidate.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$post,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: MoodleSession=b7f592nvlf0cmiunfr3u7pkbrg'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$userid="";
 if(!empty(json_decode($response)))
 {
    $userid=json_decode($response);
 }


?>
<input type="hidden" name="userid" value="<?php echo $userid; ?>">
<?php

}

add_action('init', 'start_session', 1);
function start_session() {
if(!session_id()) {
session_start();
}
}
add_action( 'woocommerce_checkout_update_order_meta', 'bbloomer_save_new_checkout_field' );
  
function bbloomer_save_new_checkout_field( $order_id ) { 
    if (!empty($_POST['userid'])){
      update_post_meta($order_id,'userid', $_POST['userid'] );
    } 

}

add_action( 'template_redirect', 'unlisted_page_redirect' );
function unlisted_page_redirect()
{

    if( is_404())
    {
     
        wp_redirect( home_url( '/page-not-found/' ) );
        exit();
    }
}