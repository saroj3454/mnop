  <?php require_once("../../../wp-config.php");

  if($_REQUEST['action']=="programdata"){
global $wpdb;
$strdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['program']."'");
  	 $wdata=$wpdb->get_results("select * from ".$wpdb->prefix."enquiry_workshop where `pid`='".$_GET['program']."' and `status`='1' order by id desc");	
  	 if(count($wdata)>0){
?>
 <div class="label">Select <?php echo $strdata->sub_program; ?></div>
        <div class="select-wrapp">
            <select class="select" name="workshops" id="workshops" required>
       <?php   
foreach ($wdata as $wvalue) { ?>
<option value="<?php echo $wvalue->workshopname; ?>" <?php if(!empty($_GET['workshop'])){ if($wvalue->workshopname==$_GET['workshop']){ echo "selected='true'"; } } ?> ><?php echo $wvalue->workshopname; ?></option>
            <?php } ?>
                </select>
              </div>
          		 <p id="error_Register_qualification"></p>
 		</div>

<?php }
  }