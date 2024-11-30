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
class block_elearnified_profile_image extends block_base {

    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_elearnified_profile_image');
    }


    /**
     * Build the block content.
     */
    function get_content() {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }
        $PAGE->requires->jquery();
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/elearnified_profile_image/js/profile.js'));
        $sql = "SELECT * FROM {user}  WHERE id=? ";
        $userData = $DB->get_record_sql($sql, array($USER->id));
        $userpicture = new user_picture(core_user::get_user($USER->id));
        $userpicture->size = 150; // Size f1.
        $profileimageurl = $userpicture->get_url($PAGE)->out(false);
        $alldata['username']=$userData->username;
        $alldata['photo']=$profileimageurl;
        $alldata['registrationdate']=date('d M Y',$userData->timecreated);

        $this->content = new stdClass();
        $imageuplaoder = $OUTPUT->render_from_template('block_elearnified_profile_image/index', array('userphotos' => $alldata));
        $this->content->text = '<div imageuploadercontainer>'.$imageuplaoder.'</div>';
        return $this->content;

  }

    public function hide_header() {
        return true;
    }
}
