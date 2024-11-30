<?php 
require_once("$CFG->libdir/formslib.php");
 
class cityform extends moodleform {
    //Add elements to form
    public function definition() {
       global $CFG, $DB, $PAGE, $USER; 	
       	$attributes=array('class' => 'option-select', 'title'=>'');
       	$valid=array('class'=>'custom-valid');
        

        $mform = $this->_form; // Don't forget the underscore!    
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'action');
        $mform->addElement('hidden', 'state_id');
        $mform->addElement('hidden', 'stateadd');
       // $mform->addElement('hidden', 'user_id');
         $state_title="";
            $city_title="";
        if($_GET['action']=='city')
        {
              if(!empty($_GET['id'])) {         
              $name="City Name";
              $select="Select District";
              $data=$DB->get_records_sql("SELECT d.*,s.state_title,s.id as state_id, c.name as city_title,c.id as city_id FROM `mo_exam_city` as c left join mo_exam_state as s on c.state_id=s.id left join mo_exam_district as d on c.state_id=d.state_id and d.district_status='Active' and d.deleted='1' where c.status='Active' and c.deleted='1' and c.id='".$_GET['id']."' ORDER BY c.name");
              //$data=$DB->get_records_sql("SELECT c.*,s.state_title FROM `mo_exam_city` as c left join mo_exam_state as s on c.stateid=s.id where c.status='Active' and c.deleted='1' ORDER BY c.name");
              foreach ($data as $value){
                $rdata[$value->id]=$value->district_title;
                $state_title=$value->state_title;
                $city_title=$value->city_title;
                $state_id=$value->state_id;
                $city_id=$value->city_id;
              }
              $ddata=$DB->get_record_sql("SELECT d.* FROM `mo_exam_city` as c left join mo_exam_district as d on c.districtid=d.id where c.id='".$_GET['id']."'");
              $action="Edit ".$state_title." City";
              $mform->addElement('html', '<div class="rowd"><div class="col-md-9d"></div><div class="col-md-3d"><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=city&district_id='.$ddata->id.'" class="btn bg-primary"> Add '.$ddata->district_title.' Other City</a><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&state_id='.$state_id.'" class="btn btn-primary"> Add '.$state_title.' Other District</a></div><div>');  

            }elseif (!empty($_GET['district_id'])) {
              $name="City Name";
              $select="Select District";
              $data=$DB->get_records_sql("SELECT ds.*,d.district_title as ddistrict_title,d.id as ddistrictid,s.state_title,s.id as stateid FROM `mo_exam_district`as d LEFT JOIN mo_exam_district as ds on d.state_id=ds.state_id and ds.district_status='Active' and ds.deleted='1' LEFT JOIN `mo_exam_state` as s on d.state_id=s.id WHERE d.id='".$_GET['district_id']."'");
              //$data=$DB->get_records_sql("SELECT c.*,s.state_title FROM `mo_exam_city` as c left join mo_exam_state as s on c.stateid=s.id where c.status='Active' and c.deleted='1' ORDER BY c.name");
              foreach ($data as $value){
                $rdata[$value->id]=$value->district_title;
                $state_title=$value->state_title;
                $ddistrict_title=$value->ddistrict_title;
                $stateid=$value->stateid;
                $ddistrictid=$value->ddistrictid;
              }
              $action="Add ".$ddistrict_title." City";
              $mform->addElement('html', '<div class="rowd"><div class="col-md-9d"></div><div class="col-md-3d"><a href="" class="btn bg-primary"> Add '.$ddistrict_title.' Other City</a><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&state_id='.$stateid.'" class="btn btn-primary"> Add '.$state_title.' Other District</a></div><div>'); 
            }
            else{
                 $data=$DB->get_records_sql("SELECT d.*,s.state_title FROM mo_exam_district as d left join mo_exam_state as s on d.state_id=s.id  and d.district_status='Active' and d.deleted='1' where s.id='".$_GET['state_id']."' ORDER BY d.district_title");
              //$data=$DB->get_records_sql("SELECT c.*,s.state_title FROM `mo_exam_city` as c left join mo_exam_state as s on c.stateid=s.id where c.status='Active' and c.deleted='1' ORDER BY c.name");
              foreach ($data as $value){
                $rdata[$value->id]=$value->district_title;
                $state_title=$value->state_title;
               
              }
              $action="Add ".$state_title." City";
              $name="City Name";
              $select="Select District";
             $mform->addElement('html', '<div class="row"><div class="col-md-9"></div> <div class="col-md-3"><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&state_id='.$_GET['state_id'].'" class="btn btn-primary" id="yui_3_17_2_1_1671035525572_411"> Add '.$state_title.' Other District</a></div><div>');    


            }

      }else{
           if(!empty($_GET['state_id'])){
          $name="District Name";
          $select="Select State";
          $data=$DB->get_records_sql("SELECT * FROM `mo_exam_state` as s  where s.status='Active' and s.deleted='1' ORDER BY s.state_title");
          foreach ($data as $value) {
            $rdata[$value->id]=$value->state_title;
          }
          $stdata=$DB->get_record_sql("SELECT * FROM `mo_exam_state` as s  where s.status='Active' and s.deleted='1' and s.id=".$_GET['state_id']."");

            $action="Add ".$stdata->state_title." District";
              // $mform->addElement('html', '<div class="rowd"><div class="col-md-9d"></div><div class="col-md-3d"><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=city" class="btn bg-primary"> Add '.$stdata->state_title.' Other City</a></div><div>'); 
          }elseif (!empty($_GET['id'])){
                $sdata=$DB->get_record_sql("SELECT s.id,s.state_title,d.id as district_id,d.district_title FROM `mo_exam_district` as d LEFT JOIN `mo_exam_state` as s ON d.state_id=s.id WHERE d.id='".$_GET['id']."'");
                // mo_exam_state
                 $name="District Name";
                 $select="Select State";
                 $mform->addElement('html', '<div class="rowd"><div class="col-md-9d"></div><div class="col-md-3d"><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=city&district_id='.$sdata->district_id.'" class="btn bg-primary"> Add '.$sdata->district_title.' Other City</a><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&state_id='.$sdata->id.'" class="btn btn-primary"> Add '.$sdata->state_title.' Other District</a></div><div>'); 

               $action="Edit ".$sdata->state_title." State ";
              $stdata=$DB->get_records_sql("SELECT * FROM `mo_exam_state` as s  where s.status='Active' and s.deleted='1' ORDER BY state_title");
              foreach ($stdata as $value) {
                $rdata[$value->id]=$value->state_title;
              }

            }
        }
     $mform->addElement('html', '<div class="headingdata"><h2>'.$action.'</h2><div>');

  // if($_GET['action']=='city'){
        $mform->addElement('select','actionid',$select, $rdata);
   // }
        $mform->addElement('text', 'name', $name); 
        $mform->addElement('text', 'slug', 'Slug'); 
        $mform->addElement('editor', 'description', 'Description');
        $mform->addElement('filepicker', 'image', get_string('file'), null,
                   array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Submit'); 
       $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
 
    
}