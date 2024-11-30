// <?php require_once(dirname(__FILE__) . '/../../config.php');
//  function urlslug($string) {
//         $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
//         return $slug;
//      }
//      $data=$DB->get_records_sql("SELECT * FROM `mo_exam_state`");
//           foreach ($data as $key) {
//             $insertdata=new stdClass();
//                   $insertdata->id=$key->id;
//                   $insertdata->slug=urlslug($key->state_title);
//                   $DB->update_record('exam_state',$insertdata,true);
//           }
				