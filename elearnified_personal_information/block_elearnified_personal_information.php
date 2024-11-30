<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
class block_elearnified_personal_information extends block_base
{

  /**
   * Start block instance.
   */
  function init()
  {
    $this->title = get_string('pluginname', 'block_elearnified_personal_information');
  }


  /**
   * Build the block content.
   */
  function get_content()
  {
    global $CFG, $PAGE, $USER, $DB, $OUTPUT;

    if ($this->content !== NULL) {
      return $this->content;
    }
    $PAGE->requires->jquery();
    $PAGE->requires->jquery_plugin('ui');
    $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/elearnified_personal_information/js/personal_information.js'));
    $this->content = new stdClass();
    $country = get_string_manager()->get_list_of_countries();
    $default_country[''] = get_string('selectacountry');
    $country = array_merge($default_country, $country);
    $userrecord = $DB->get_record('user', array('id' => $USER->id));


    $companydata = $DB->get_record_sql("SELECT c.*,uic.name as companyshortname FROM {company_domains} as cd INNER JOIN {company} as c on cd.companyid=c.id INNER JOIN mdl_user_info_category as uic on c.profileid=uic.id INNER JOIN {company_users} as cu on cd.companyid=cu.companyid left join {department} as d ON c.id=d.company and d.parent='0' where c.suspended='0' AND cd.domain LIKE '%" . $_SERVER['SERVER_NAME'] . "%' and cu.userid='".$USER->id."'");
            if (!empty($companydata)) {
       
        $companyshortname = str_replace(" ", "",$companydata->companyshortname);
        $sql = "SELECT ud.*,ui.shortname  FROM `mdl_user_info_field` as ui INNER JOIN mdl_user_info_data as ud on ui.id=ud.fieldid where ui.shortname in('".$companyshortname."dob','".$companyshortname."houseno','".$companyshortname."zip','".$companyshortname."organisation','".$companyshortname."landmark','".$companyshortname."gender') and ud.userid='".$USER->id."'";
    $useraddata = $DB->get_records_sql($sql);
    $udata=array();

        foreach ($useraddata as $value) {
            if(!empty($value->shortname) && $value->shortname==$companyshortname.'dob'){
             // echo $udata['eldob']=$value->data;
               $udata['prcnhqtesteldob']=$value->data;

            }else{
              // $udata['eldob']="";
              // array_push($udata, array('eldob'=>''));

            }

            if(!empty($value->shortname) && $value->shortname==$companyshortname.'houseno'){
               $udata['prcnhqtestelHouseno']=$value->data;
            }else{
               // array_push($udata, array('elHouseno'=>""));
            }

            if(!empty($value->shortname) && $value->shortname==$companyshortname.'zip'){
              $udata['prcnhqtestelzip']=$value->data;
             
            }else{
              // array_push($udata, array('elzip'=>""));
            }

            if(!empty($value->shortname) && $value->shortname==$companyshortname.'organisation'){
              $udata['prcnhqtestelorganisation']=$value->data;
              
            }else{
              // array_push($udata, array('elorganisation'=>""));
            }


           if(!empty($value->shortname) && $value->shortname==$companyshortname.'landmark'){
            $udata['prcnhqtestellandmark']=$value->data;
            
            }else{
               // array_push($udata, array('ellandmark'=>""));
            }


            if(!empty($value->shortname) && $value->shortname==$companyshortname.'gender'){
                  $udata['prcnhqtestelgender']=$value->data;
               }
                
        }


if(!empty($udata['prcnhqtestelzip'])){
$elzip=$udata['prcnhqtestelzip'];
}

if(!empty($udata['prcnhqtestellandmark'])){
$ellandmark=$udata['prcnhqtestellandmark'];
}
if(!empty($udata['prcnhqtestelHouseno'])){
$elHouseno=$udata['prcnhqtestelHouseno'];
}

if(!empty($udata['prcnhqtesteldob'])){
$eldob=$udata['prcnhqtesteldob'];
}
  }

// echo "SELECT *  FROM {company_users} WHERE `userid` ='".$USER->id."'";
// die();
  $orgdata=$DB->get_record_sql("SELECT *  FROM {company_users} WHERE userid ='".$USER->id."'");
  if(!empty($orgdata)){
    $elorganisation=$orgdata->departmentid;
      if($orgdata->managertype!=0){
         $required="required";
          $readonly="";
          $disbledfield="";
      }else{
        $required="";
        $readonly="readonly";
        $disbledfield="disabled='true'";  
      }
  }
$orgData=$DB->get_records_sql("SELECT d.* FROM {department} as d INNER JOIN {company_domains} as cd on d.company=cd.companyid where cd.domain LIKE '%".$_SERVER['SERVER_NAME']."%' order by d.id asc");
 $orgchilddata="";
    foreach ($orgData as $orgvalue) {

      if ($orgvalue->id == $elorganisation) {
        $selectedo = "selected='true'";
      } else {
        $selectedo = "";
      }
      $orgchilddata.= "<option value='".$orgvalue->id."' ".$selectedo.">".$orgvalue->name."</option>";
    }
   

    $countrydata = "";
    foreach ($country  as $key => $value) {
      if ($key == $userrecord->country) {
        $selected = "selected='true'";
      } else {
        $selected = "";
      }

      $countrydata .= "<option " . $selected . " value='" . $key . "'>" . $value . " </option>";
    }


    if(!empty($udata['prcnhqtestelgender'])){    
        if($udata['prcnhqtestelgender']=='male'){
          $mselecte="selected='true'";
        }elseif($udata['prcnhqtestelgender']=='female'){
          $fselected="selected='true'";
        }
      $gender="<option value='male' ".$mselecte.">Male</option>
                    <option value='female' ".$fselected.">Female</option>";
    }else{
         $gender="<option value='male'>Male</option>
                    <option value='female'>Female</option>";
      }






    $settings = array('countrydata' => $countrydata, 'userdata' => json_decode(json_encode($userrecord), TRUE),'elgender'=>$gender,'elzip'=>$elzip,'ellandmark'=>$ellandmark,'elHouseno'=>$elHouseno,'elorganisation'=>$orgchilddata,'eldob'=>$eldob,'required'=>$required,'readonly'=>$readonly,'disbledfield'=>$disbledfield);
    $this->content->text = $OUTPUT->render_from_template('block_elearnified_personal_information/index', $settings);
    return $this->content;
  }

  public function hide_header()
  {
    return true;
  }
}
