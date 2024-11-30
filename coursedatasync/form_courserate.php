<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');


class courserate extends moodleform {

       /**
     * Form definition.
     */
    function definition() {
        global $DB,$CFG, $PAGE;

        $mform    = $this->_form;

        $heading= (!empty('edit')) ? 'blockeditsettings' : 'blockaddsettings';

        
        $mform->addElement('hidden', 'id','');
  
if(!empty($_GET['id'])){
$topbardata = $DB->get_record('blocks_custom_courserate',array('id'=>$_GET['id']));

    $startrate5=$topbardata->startrate5;
    $startrate4=$topbardata->startrate4;
    $startrate3=$topbardata->startrate3;
    $startrate2=$topbardata->startrate2;
    $startrate1=$topbardata->startrate1;  
    if(empty($topbardata->startrate1)){
    $startrate1="";
    }
    if(empty($topbardata->startrate2)){
    $startrate2="";
    }
    if(empty($topbardata->startrate3)){
    $startrate3="";
    }
    if(empty($topbardata->startrate4)){
    $startrate4="";
    }
    if(empty($topbardata->startrate5)){
    $startrate5="";
    }


$r=startratingdisplay($topbardata->userrated,$startrate5,$startrate4,$startrate3,$startrate2,$startrate1);
 $avgpercentdata=$r['percent'];
 $ratingdata=$r['rateing'];
$cdata=$DB->get_record("course", array("id"=>$topbardata->courseid));
$name=''.$cdata->fullname.' Course Rating and Enrolled Users update';
}else{
  $name= ' Course Rating and Enrolled Users';
}

            $mform->addElement('header', 'courseheader', $name );

            $mform->setExpanded('courseheader');
            $mform->addElement('html', '<br>'); 
             $selecttype['0']='Select Course';
            $coursedata = $DB->get_records("course", array("visible"=>1));
            foreach ($coursedata as $datavalue) {
            $selecttype[$datavalue->id]=$datavalue->fullname;
            }

            $mform->addElement('select', 'courseid','Course',$selecttype); 




//$mform->addRule('starrating', 'Enter only numeric Value', 'numeric', null, 'client');
// $mform->addRule('starrating', 'Invalid data', 'regex',"^\*{1,5}$");




$mform->addElement('text', 'userenrolled', 'Users Enrolled',array('class'=>'cuserenrolled f_clas'));
$mform->addRule('userenrolled', 'Enter only numeric Value', 'numeric', null, 'client');

$mform->addElement('text', 'userrated', 'Users Rated',array('class'=>'cuserrated f_clas') );
$mform->addRule('userrated', 'Enter only numeric Value', 'numeric', null, 'client');
$mform->addElement('html', '<div class="row"><div class="col-md-3"></div><div class="col-md-9"><div id="ratederror" style="color:red"></div></div></div>');


$mform->addElement('text', 'startrate5', 'Stars 5',array('placeholder'=>'Enter Percentage','class'=>'startrate5 d_clas'));
$mform->addRule('startrate5', 'Enter only numeric value', 'numeric', null, 'client');
$mform->addElement('text', 'startrate4', 'Stars 4',array('placeholder'=>'Enter Percentage','class'=>'startrate4 d_clas'));
$mform->addRule('startrate4', 'Enter only numeric value', 'numeric', null, 'client');
$mform->addElement('text', 'startrate3', 'Stars 3',array('placeholder'=>'Enter Percentage','class'=>'startrate3 d_clas'));
$mform->addRule('startrate3', 'Enter only numeric value', 'numeric', null, 'client');
$mform->addElement('text', 'startrate2', 'Stars 2',array('placeholder'=>'Enter Percentage','class'=>'startrate2 d_clas'));
$mform->addRule('startrate2', 'Enter only numeric value', 'numeric', null, 'client');
$mform->addElement('text', 'startrate1', 'Stars 1',array('placeholder'=>'Enter Percentage','class'=>'startrate1 d_clas'));
$mform->addRule('startrate1', 'Enter only numeric value', 'numeric', null, 'client');

if(!empty($ratingdata)){
$mform->addElement('html', '<div class="row" id="ratingdatad"><br><div class="dj1">Star Rating</div><div class="dj1"><div id="startratingdata">'.$ratingdata.'</div></div><div class="dj1">Visible Rating</div><div class="dj1"><div id="avragepercentdata">'.$avgpercentdata.'</div></div></div><br>');
}else{
    $mform->addElement('html', '<div class="row" id="ratingdatad" style="display:none"><br><div class="dj1">Star Rating</div><div class="dj1"><div id="startratingdata">'.$ratingdata.'</div></div><div class="dj1">Visible Rating</div><div class="dj1"><div id="avragepercentdata">'.$avgpercentdata.'</div></div></div><br>');
}


 $buttonarray[] = &$mform->createElement('submit', 'submit', 'Submit'); 
       $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
        
               
    }

   public function validation($data, $files) {
    $errors=array();
    $courseid=$data['courseid'];
    $userrated=$data['userrated'];
    $starrate5=$data['startrate5'];
    $starrate4=$data['startrate4'];
    $starrate3=$data['startrate3'];
    $starrate2=$data['startrate2'];
    $starrate1=$data['startrate1'];
    if(empty($userrated)){
        $errors['userrated']="Please Enter users rated";
    }
    if(empty($courseid)){
        $errors['courseid']="Please select course";
    }
    if($userrated>$data['userenrolled']){
        $errors['userrated']="Please Enter in value equal to less than the Users Enrolled";
    }


     $maximum=$userrated*5;
     $star5value=5*($userrated*($starrate5/100));
     $star4value=4*($userrated*($starrate4/100));
     $star3value=3*($userrated*($starrate3/100));
     $star2value=2*($userrated*($starrate2/100));
     $star1value=1*($userrated*($starrate1/100));

$allvalue=$star5value+$star4value+$star3value+$star2value+$star1value;

$starratevalue=$starrate5+$starrate4+$starrate3+$starrate2+$starrate1;

if($starratevalue>100){
    $errors['startrate5']="Please enter valid percent";
    $errors['startrate4']="Please enter valid percent";
    $errors['startrate3']="Please enter valid percent";
    $errors['startrate2']="Please enter valid percent";
    $errors['startrate1']="Please enter valid percent";
}

if($starratevalue<100){
    $errors['startrate5']="Please enter valid percent";
    $errors['startrate4']="Please enter valid percent";
    $errors['startrate3']="Please enter valid percent";
    $errors['startrate2']="Please enter valid percent";
    $errors['startrate1']="Please enter valid percent";
}



 $percent=$allvalue/$maximum*100;
 // echo $rateingdata=$percent*5/100;

// die();
        return $errors;
    }


}



