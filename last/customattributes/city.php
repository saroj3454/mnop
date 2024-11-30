<?php require_once("../../../wp-config.php");
include get_theme_file_path('/courses/state.php'); 
if (isset($_GET)) {
    extract($_GET);
    $city=$statecity[$state];
    $html="<option value=''>Select City</option>";
    if (!empty($city)) {
        foreach ($city as  $value) {
           ?>
          
            <option value='<?php echo $value; ?>' 
                <?php if(!empty($_GET['city'])){ 
                        if($_GET['city']==$value){
                            echo "selected='true'";
                        }   
                    } ?>
             ><?php echo $value; ?></option>
             <?php 
        }
    }

    
    exit();
}