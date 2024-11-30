<?php 
require_once("../../config.php");
require_once($CFG->libdir.'/filelib.php');
 global $PAGE, $CFG,$DB;
$path="https://secure.gravatar.com/avatar/?s=450&d=mm&r=g&profile_url=https://webdev.digiface.org/author/";
//$path="https://webdev.digiface.org/wp-content/uploads/2022/02/depositphot-aspect-ratio-242-242-300x300.jpg?v=d41d8cd98f00b204e9800998ecf8427e";

$imgData = base64_encode(file_get_contents($path));

$src = 'data:image/png;base64,' .$imgData;


// Echo out a sample image
 echo '<img src="'.$src.'">';

$userid="189";
$wsfiledata_decoded = file_get_contents($path);
$fs = get_file_storage();

$context = context_user::instance($userid, MUST_EXIST);
$user = core_user::get_user($userid, 'id, picture', MUST_EXIST);
$newpicture = $user->picture;
        // Get file_storage to process files.
        $userdata=$DB->get_record('user',array('id'=>$userid));

        if (!empty($userdata->picture)) {
            // The user has chosen to delete the selected users picture.
            $fs->delete_area_files($context->id, 'user', 'icon'); // Drop all images in area.
            $newpicture = 0;
        }
  $fileinfo = array('contextid' =>$context->id, 
                                        'component' => 'user',
                                        'filearea' => 'icon',     // usually = table name
                                        'itemid' => '0',               // usually = ID of row in table
                                        'filepath' => "/",           // any path beginning and ending in /
                                        'filename' =>'f1.jpg'); // any filename
if ($newpicture != $user->picture) {
$fs->create_file_from_string($fileinfo, $wsfiledata_decoded);


$imagedata=$DB->get_record_sql("SELECT * FROM {files} where `contextid`='".$context->id."' and `component`='user' and  `filearea`='icon' and `itemid`='0' and `filepath`='/' and `filename`='f1.jpg'");
if(!empty($imagedata)){
$DB->set_field('user', 'picture',$imagedata->id, array('id' => $userid));
}

}
 
// echo "dddd";
// echo "<pre>";
// print_r($data);



// echo $data->id;

// echo $userid="189";
