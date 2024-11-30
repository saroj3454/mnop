<?php require_once(dirname(__FILE__) . '/../../config.php');
 global $DB;

 function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
     }

// $rdata=$DB->get_records('searchda_categories');
// foreach ($rdata as $value) {
// 	$data->first_id=$value->id;
// 	$data->title=$value->title;
// 	$data->slug=urlslug(trim($value->title));
// 	$data->status=0;
// 	$data->createdtime=time();
//     $DB->insert_record('searchda_categories_firstseo',$data);
// }

// $rdata=$DB->get_records('searchda_secondc');
// foreach ($rdata as $value) {
// 	$data->first_id=$value->categoriesid;
// 	$data->second_id=$value->id;
// 	$data->title=$value->title;
// 	$data->slug=urlslug(trim($value->title));
// 	$data->status=0;
// 	$data->createdtime=time();
//     $DB->insert_record('searchda_categories_secondseo',$data);
// }


//      $rdata=$DB->get_records('searchda_third');
// foreach ($rdata as $value) {
// 	$data->th_id=$value->id;
// 	$data->title=$value->title;
// 	$data->slug=urlslug(trim($value->title));
// 	$data->status=0;
// 	$data->createdtime=time();
//     $DB->insert_record('searchda_categories_seo',$data);
// }