<?php class Enquiryuser{

		function __construct(){
				$this->fileurl=FILE_URL;	
				add_action('admin_menu', [ $this,'my_enquirymenu']);
			}


		function my_enquirymenu() {
		  $position="3";
		 // add_menu_page('Enquiry', 'Enquiry', 'manage_options', 'enquirydashboard', 'all_enquiry','dashicons-admin-customizer', $position );
		 // add_submenu_page("enquirydashboard","GLS Lunch Orders","GLS Lunch Orders","manage_options",'enquirydashboard');
    add_menu_page('Enquiry', 'Enquiry', 'manage_options', 'all_enquiry', array( $this, 'enquirydashboard'),'dashicons-admin-customizer', $position);
    add_submenu_page('all_enquiry', 'All Enquiry', 'All Enquiry', 'manage_options', 'all_enquiry');
    add_submenu_page('all_enquiry', 'Today Enquiry', 'Today Enquiry', 'manage_options', 'today_enquiry',[$this,'today_enquirydata']);
    add_submenu_page('all_enquiry', 'Mail Setting', 'Mail Setting', 'manage_options', 'enquiry_email_data',[$this,'enquiry_email_setting']);
    add_submenu_page('all_enquiry', 'Instructor Program', 'Instructor Program', 'manage_options', 'all_program',[$this,'all_program_function']);
		}
		function all_program_function(){
 		global $wpdb;
 		
 		if(!empty($_POST['workshopbtn'])){
 			$datap=array();
 			$datap['workshopname']=$_POST['workshopname'];
 			$datap['workshop_link']=$_POST['workshop_link'];
 			$datap['workshop_price']=$_POST['workshop_price'];
 			$datap['pid']=$_POST['pid'];
 			if(!empty($_POST['wid'])){
 				$datap['updatedtime']=time();
				$data_where = array('id' => $_POST['wid']);
				$wpdb->update($wpdb->prefix.'enquiry_workshop', $datap, $data_where);
				$statusrespnsone="<b>'".$_POST['workshopname']."</b>' Updated Sucessfully";
 			}else{
 				$datap['createdtime']=time();
 				$sendata=$wpdb->insert($wpdb->prefix.'enquiry_workshop',$datap);
 				$statusrespnsone="<b>'".$_POST['workshopname']."</b>' Inserted Sucessfully";
 			}
 		}
			if(!empty($_GET['deleteid'])){
			$strdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['deleteid']."'");
				if(!empty($strdata)){
				$statusrespnsone="<b>'".$strdata->programname."</b>' Deleted Sucessfully";		
				$wpdb->delete($wpdb->prefix."enquiry_program", array('id'=>$_GET['deleteid']));
				$wpdb->delete($wpdb->prefix."enquiry_workshop", array('pid'=>$_GET['deleteid']));
				}
			}

			if(!empty($_GET['wdeleteid'])){
			$strdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_workshop where `id`='".$_GET['wdeleteid']."'");
				if(!empty($strdata)){
				$statusrespnsone="<b>'".$strdata->workshop."</b>' Deleted Sucessfully";		
				$wpdb->delete($wpdb->prefix."enquiry_workshop", array('id'=>$_GET['wdeleteid']));
				}
			}


if(!empty($_GET['wstatusid'])){
 		$strdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_workshop where `id`='".$_GET['wstatusid']."'");
 			   if($strdata->status=='1'){
 			   	$view="Draft";
 			   	$vstatus='0';
 			   }else{
 			   	$view="Public";
 			   	$vstatus='1';
 			   }
 			   $data_where = array('id' => $_GET['wstatusid']);
				$wpdb->update($wpdb->prefix.'enquiry_workshop',array('status'=>$vstatus,'updatedtime'=>time()), $data_where);
				$statusrespnsone="<b>'".$strdata->workshopname."</b>' ".$view." Sucessfully";
 		}


 		if(!empty($_GET['statusid'])){
 		$strdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['statusid']."'");
 			   if($strdata->status=='1'){
 			   	$view="Draft";
 			   	$vstatus='0';
 			   }else{
 			   	$view="Public";
 			   	$vstatus='1';
 			   }
 			   $data_where = array('id' => $_GET['statusid']);
				$wpdb->update($wpdb->prefix.'enquiry_program',array('status'=>$vstatus,'updatedtime'=>time()), $data_where);
				$statusrespnsone="<b>'".$strdata->programname."</b>' ".$view." Sucessfully";
 		}


 		if(!empty($_POST['programbtn'])){
 			$datap=array();
 			$datap['programname']=$_POST['programname'];
 			$datap['programprice']=$_POST['programprice'];
 			$datap['programlink']=$_POST['programlink'];
 			$datap['sub_program']=$_POST['sub_program'];
 			if(!empty($_POST['programid'])){
 				$datap['updatedtime']=time();
				$data_where = array('id' => $_POST['programid']);
				$wpdb->update($wpdb->prefix.'enquiry_program', $datap, $data_where);
				$status="<b>'".$_POST['programname']."</b>' Updated Sucessfully";
 			}else{
 				$datap['createdtime']=time();
 				$sendata=$wpdb->insert($wpdb->prefix.'enquiry_program',$datap);
 				$status="<b>'".$_POST['programname']."</b>' Inserted Sucessfully";
 			}
 		}
			


			?>
			 <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <style>
        #adminmenuwrap {
            height: 100% !important;
        }
        
        .previous {
            border-radius: 4px 0px 0px 4px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        .next {
            border-radius: 0px 4px 4px 0px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        #example_paginate span {
            cursor: pointer;
            color: #fff !important;
            padding: 6px 15px !important;
            margin-left: 10px !important;
        }
#error{
color: #e00;
    font-size: 16px;
}

ul.enqyy li {
    display: inline-block;
    margin-right: 6px;
}ul.do li {
    display: inline-block;
padding-left: 5px;
    padding-right: 5px;
}
.notif{
	background: #2ac639;
    color: white;
    width: 35%;
    font-weight: 700;
    text-align: left;
}
i.fa.fa-pencil {
    color: #1956df;
}
i.fa.fa-eye {
    color: #000000;
}
i.fa.fa-trash {
    color: red;
}
i.fa.fa-eye-slash {
    color: #a79a9a;
}
 </style>
 
<div class="row">

	<div class="col-md-12 bg-light text-right">
			<?php if(!empty($_GET['workshop'])){

$srdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['pid']."'");

			 ?>
<a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&addedworkshop=added&pid=".$_GET['pid']); ?>">
		<button type="button" class="btn" style="background: #2ac639;color: white;">
			<i class="fa fa-plus" aria-hidden="true"></i> Added <?php echo $srdata->sub_program; ?>
		</button>
		</a>

		<a href="<?php echo site_url("/wp-admin/admin.php?page=all_program");?>">
		<button type="button" class="btn" style="color: black;
    border: 1px solid black;">
			<i class="fa fa-eye" aria-hidden="true"></i> Show All Program
		</button>
		</a>


		<?php } ?>

		<a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&added_program='show'"); ?>">
		<button type="button" class="btn btn-primary">
			<i class="fa fa-plus" aria-hidden="true"></i> Add Program
		</button>
		</a>

	

	</div>

</div>
<?php 
if(!empty($statusrespnsone)){ ?>
			
		<div class="row">
			<center>
			<div class="alert alert-primary notif" role="alert">
		  <?php echo $statusrespnsone; ?>

		</div>
			</center>
		</div>
<?php } ?>



<?php if(!empty($_GET['added_program'])){ ?>
<?php
	$idhtml="";
	 $srdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['id']."'");
	 if(!empty($_GET['id'])){
	 	$idhtml="<input type='hidden' name='programid' value='".$srdata->id."'>";
	 	echo "<h3><b>".$srdata->programname."</b> - Update Program</h3>";
	}else{
		echo "<h3>Added Program</h3>";	
	}
		
		if(!empty($status)){ ?>
			
		<div class="row">
			<center>
			<div class="alert alert-primary notif" role="alert">
		  <?php echo $status; ?>

		</div>
			</center>
		</div>
	<?php } ?>
	
	
<br>
	
		<form method="post">

			<div class="row page_height">
			<div class="col-md-2">
			<label class="control-label" for="url">Program Name </label>
			</div>    
			<div class="col-md-6">
			<input type="text" value="<?php if(!empty($srdata->programname)){ echo $srdata->programname; } ?>" size="45" name="programname" placeholder="Program Name" />
			</div> 
			<div class="col-md-4"> </div>
		</div>
		<br>
		<div class="row page_height">
			<div class="col-md-2">
			<label class="control-label" for="url">Program Price </label>
			</div>    
			<div class="col-md-6">
			<input type="text" value="<?php if(!empty($srdata->programprice)){ echo $srdata->programprice; } ?>" size="45" name="programprice" placeholder="Program Price" />
			</div> 
			<div class="col-md-4"> </div>
		</div>
		<br>
		<div class="row page_height">
			<div class="col-md-2">
			<label class="control-label" for="url">Program Link </label>
			</div>    
			<div class="col-md-6">
			<input type="text" value="<?php if(!empty($srdata->programlink)){ echo $srdata->programlink; } ?>" size="45" name="programlink" placeholder="Program Link" />
			</div> 
			<div class="col-md-4"> </div>
		</div>
<br>
<div class="row page_height">
			<div class="col-md-2">
			<label class="control-label" for="url">Sub Program Title</label>
			</div>    
			<div class="col-md-6">
			<input type="text" value="<?php if(!empty($srdata->sub_program)){ echo $srdata->sub_program; } ?>" size="45" name="sub_program" placeholder="Sub Program Title" />
			</div> 
			<div class="col-md-4"> </div>
		</div>
<br>

<?php echo $idhtml; ?>

		<div class="row page_height">
			<div class="col-md-2">
			</div>	  
			<div class="col-md-1">
				<button class="btn btn-primary  addhostdtl" style="width: 84px;"  type="submit" value="programbtn" name="programbtn"  id="program">Save</button>

			</div> 
			<div class="col-md-5">
				<a href='<?php echo site_url("/wp-admin/admin.php?page=all_program"); ?>'><button class="btn btn-primary  addhostdtl" style="background: red;color: white;" type="button" >Cancel</button></a>

			</div>
			<div class="col-md-4"></div>
		</div>
		</form>



<?php } ?>

 
 <?php if(empty($_GET['workshop'])){
?>
	<h3>All Programs</h3>

	<div class="divider"></div>

	<div class="row">
	    <div class="col-md-12">
	        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                    <th>S No.</th>
	                   <th>Program Name</th>
	                    <th>Program Price</th>
	                    <th>Program Link</th>
	                    <th>Sub Program Title</th>
	                    <th>Action</th>
	                  
	                    
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                     <th>S No.</th>
	                    <th>Program Name</th>
	                    <th>Program Price</th>
	                    <th>Program Link</th>
	                    <th>Sub Program Title</th>
	                   
	                    <th>Action</th>
	                </tr>
	            </tfoot>
	            <tbody>
	            
	   <?php 
	$i=1;
	    $prdata=$wpdb->get_results("select * from ".$wpdb->prefix."enquiry_program order by id desc");
	   foreach ($prdata as $pvalue) { ?>
	                <tr>
	 					<td><?php echo $i++; ?></td>
	                    <td>
	                    	<?php if(!empty($pvalue->sub_program)){ ?>
	                    	<a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&pid=".$pvalue->id); ?>"><?php echo $pvalue->programname; ?></a>
	                    		<?php }else{
	                    			echo $pvalue->programname;
	                    		} ?>
	                    	</td>
	                    <td><?php echo $pvalue->programprice; ?></td>
	                    <td><?php echo $pvalue->programlink; ?></td>
	                    <td><?php echo $pvalue->sub_program; ?></td>

	            
	                    <td>
	                  <ul class="do">
	                <li> <a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&added_program=show&id=".$pvalue->id); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> | </li>
	                    <li> <a onclick="return confirm('Are you sure you want to status update - <?php echo $pvalue->programname; ?>')" href="<?php echo site_url("/wp-admin/admin.php?page=all_program&statusid=".$pvalue->id); ?>">
	                    	<i class="fa <?php  if($pvalue->status=='1'){ echo"fa-eye";}else{ echo "fa-eye-slash";} ?>" aria-hidden="true"></i>
	                    </a> |
	                     </li>
	                    	 <li> <a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&deleteid=".$pvalue->id); ?>" onclick="return confirm('Are you sure you want to Delete - <?php echo $pvalue->programname; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
	</ul>

	                    </td> 
	                </tr>
	             <?php  } ?>

	            </tbody>
	        </table>
	    </div>
	</div>
<?php

 	
 }else{

 	 $srdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$_GET['pid']."'");
?>
<?php if(!empty($_GET['addedworkshop'])){ ?>

	<?php $inputhtml="";

	if(!empty($_GET['wid'])){
       			$inputhtml='<input type="hidden" name="wid" value="'.$_GET['wid'].'"/>';
       		$wdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_workshop where `id`='".$_GET['wid']."'");	

       			$wv="'".$wdata->workshopname."' Update";


       	}else{
       		$wv="Added"; 
       	} ?>

<h3><b><?php echo $srdata->programname; ?></b> - <?php echo $wv; ?> <?php echo $srdata->sub_program; ?></h3>
<br>
<form method="post">

<div class="divider"></div>

<div class="row">
    <div class="col-md-10">
    	
    		 <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url"><?php echo $srdata->sub_program; ?> Name</label>
                </div>	  
                <div class="col-md-6">

       <input type="text" value="<?php echo $wdata->workshopname; ?>" size='45'  name="workshopname" id="emailiddata" df="" data-role="tagsinput" placeholder="<?php echo $srdata->sub_program; ?> Name" />



       <input type="hidden" name='pid' value="<?php echo $srdata->id; ?>"/>

       <?php echo $inputhtml; ?>

       </div> 



                <div class="col-md-4"> </div>
                </div>
                <br>
                 <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url"><?php echo $srdata->sub_program; ?> Link</label>
                </div>	  
                <div class="col-md-6">

       <input type="text" value="<?php echo $wdata->workshop_link; ?>" size='45'  name="workshop_link" id="emailiddata" df="" data-role="tagsinput" placeholder="<?php echo $srdata->sub_program; ?> Link" />
       </div> 
                <div class="col-md-4"> </div>
                </div>
<br>


    <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url"><?php echo $srdata->sub_program; ?> Price</label>
                </div>	  
                <div class="col-md-6">

       <input type="text" value="<?php echo $wdata->workshop_price; ?>" size='45'  name="workshop_price" id="emailiddata" df="" data-role="tagsinput" placeholder="<?php echo $srdata->sub_program; ?> Price" />
       </div> 
                <div class="col-md-4"> </div>
                </div>
<br>


                <div class="row page_height">
			<div class="col-md-2">
			</div>	  
			<div class="col-md-1">
				<button class="btn btn-primary  addhostdtl" style="width: 82px;" type="submit" value="workshopbtn" name="workshopbtn"  id="program">Save</button>

			</div> 
			<div class="col-md-5">
				<a href='<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&pid=".$srdata->id); ?>'><button class="btn btn-primary  addhostdtl" style="background: red;color: white;" type="button" >Cancel</button></a>

			</div>
			<div class="col-md-4"></div>
		</div>
		</form>
		<br>

<?php } ?>




<h3><b><?php echo $srdata->programname; ?></b> - All <?php echo $srdata->sub_program; ?></h3>
<br>
	<div class="divider"></div>

	<div class="row">
	    <div class="col-md-12">
	        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                    <th>S No.</th>
	                   <th><?php echo $srdata->sub_program; ?> Name</th>
	   				<th><?php echo $srdata->sub_program; ?> Link</th>
	   				<th><?php echo $srdata->sub_program; ?> Price</th>
	                    <th>Action</th>
	                  
	                    
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                     <th>S No.</th>
	                    <th><?php echo $srdata->sub_program; ?> Name</th>
	                    <th><?php echo $srdata->sub_program; ?> Link</th>
	                    <th><?php echo $srdata->sub_program; ?> Price</th>
	                     <th>Action</th>
	                    
	                </tr>
	            </tfoot>
	            <tbody>
	            
	   <?php 
	$i=1;
	    $prdata=$wpdb->get_results("select * from ".$wpdb->prefix."enquiry_workshop where `pid`='".$srdata->id."' order by id desc");
	   foreach ($prdata as $pvalue) { ?>
	                <tr>
	 					<td><?php echo $i++; ?></td>
	                   
	                    <td><?php echo $pvalue->workshopname; ?></td>
	                    <td><?php echo $pvalue->workshop_link; ?></td>
	                    <td><?php echo $pvalue->workshop_price; ?></td>
	                  

	            
	                    <td>
	                  <ul class="do">
	                <li> <a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&addedworkshop=added&wid=".$pvalue->id."&pid=".$srdata->id); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> | </li>
	                    <!-- <li> <a onclick="return confirm('Are you sure you want to status update - <?php echo $pvalue->workshopname; ?>')" href="<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&wstatusid=".$pvalue->id."&pid=".$srdata->id); ?>">
	                    	<i class="fa <?php  if($pvalue->status=='1'){ echo"fa-eye";}else{ echo "fa-eye-slash";} ?>" aria-hidden="true"></i>
	                    </a> |
	                     </li> -->
	                    	 <li> <a href="<?php echo site_url("/wp-admin/admin.php?page=all_program&workshop=show&wdeleteid=".$pvalue->id."&pid=".$srdata->id); ?>" onclick="return confirm('Are you sure you want to Delete - <?php echo $pvalue->workshopname; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
	</ul>

	                    </td> 
	                </tr>
	             <?php  } ?>

	            </tbody>
	        </table>
	    </div>
	</div>



<?php

 } ?>



    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">

    


    	$(document).ready(function () {
	const myTimeout = setTimeout(myGreeting, 5000);

	function myGreeting() {
	  $(".notif").css("display", "none");
	}

         $('#typeenquiry').change(function() {
     var typedata = $(this).val();
     window.location.href = "<?php echo site_url("/wp-admin/admin.php?page=all_enquiry&enquirytype="); ?>"+typedata;
    
   });


        $('#example').DataTable({
        "iDisplayLength": 20
    });
    });
    </script>
    
		<?php }



		function enquiry_email_setting(){

		global $wpdb;
     $table_name = $wpdb->prefix.'enquiry_emailsetting';
    $emaildata=$wpdb->get_row("select * from $table_name where `id`='1'");

			?>
			<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">


   <style>
        #adminmenuwrap {
            height: 100% !important;
        }
        
        .previous {
            border-radius: 4px 0px 0px 4px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        .next {
            border-radius: 0px 4px 4px 0px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        #example_paginate span {
            cursor: pointer;
            color: #fff !important;
            padding: 6px 15px !important;
            margin-left: 10px !important;
        }
#error{
color: #e00;
    font-size: 16px;
}
/* bootstrap-tagsinput.css file - add in local */

.bootstrap-tagsinput {
  background-color: #fff;
  border: 1px solid #ccc;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  border-radius: 4px;
  max-width: 100%;
  line-height: 22px;
  cursor: text;
}
.bootstrap-tagsinput input {
  border: none;
  box-shadow: none;
  outline: none;
  background-color: transparent;
  padding: 0 6px;
  margin: 0;
  width: auto;
  max-width: inherit;
}
.bootstrap-tagsinput.form-control input::-moz-placeholder {
  color: #777;
  opacity: 1;
}
.bootstrap-tagsinput.form-control input:-ms-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput input:focus {
  border: none;
  box-shadow: none;
}
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color: white;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .tag [data-role="remove"]:after {
  content: "x";
  padding: 0px 2px;
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
.row.page_height {
    margin-top: 20px;
}
    </style>
 <h3>Email setting </h3>
 <form method="post">

<div class="divider"></div>

<div class="row">
    <div class="col-md-10">
    	
    		 <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Email-id</label>
                </div>	  
                <div class="col-md-6">

       <input type="text" value="<?php echo $emaildata->email; ?>"  name="emaildata" id="emailiddata" df="" data-role="tagsinput" placeholder="Add Email-id" />
       </div> 



                <div class="col-md-4"> </div>
                </div>


<div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Crypto Razorpay Link</label>
                </div>    
                <div class="col-md-6">

       <input type="text" value="<?php echo $emaildata->razorpay_link; ?>" size="45" name="razorpay_link" id="razorpay_link" placeholder=" Added Razorpay Link" />
       </div> 
                <div class="col-md-4"> </div>
                </div>



<br>
       <div class="row page_height">
                <div class="col-md-2">
                </div>	  
                <div class="col-md-6">
               <button class="btn btn-primary  addhostdtl" type="button"  id="emailsetting">Save</button>
                
                </div> 
                <div class="col-md-4"></div>
            </div>
	

    </div>
</div>
</form>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#emailsetting").click(function(){
            //var str=$("#a1").getVal();        
           
           var email=$('#emailiddata').val();
           var pricedata=$('#pricedata').val();
           var applynow_link=$('#applynow_link').val();
           var razorpay_link  =$('#razorpay_link  ').val();
            $.ajax({
      type: 'POST',
      url: "<?php echo site_url('/wp-content/plugins/customattributes/email_setting.php'); ?>",
    data: "email="+email+"&pricedata="+pricedata+"&applynow_link="+applynow_link+"&razorpay_link="+razorpay_link,
      dataType: "text",
      success: function(resultData) { alert("Sucessfully Updated") }
});


        });
    });
</script>
<script type="text/javascript">
	
	// bootstrap-tagsinput.js file - add in local

(function ($) {
  "use strict";

  var defaultOptions = {
    tagClass: function(item) {
      return 'label label-info';
    },
    itemValue: function(item) {
      return item ? item.toString() : item;
    },
    itemText: function(item) {
      return this.itemValue(item);
    },
    itemTitle: function(item) {
      return null;
    },
    freeInput: true,
    addOnBlur: true,
    maxTags: undefined,
    maxChars: undefined,
    confirmKeys: [13, 44],
    delimiter: ',',
    delimiterRegex: null,
    cancelConfirmKeysOnEmpty: true,
    onTagExists: function(item, $tag) {
      $tag.hide().fadeIn();
    },
    trimValue: false,
    allowDuplicates: false
  };

  /**
   * Constructor function
   */
  function TagsInput(element, options) {
    this.itemsArray = [];

    this.$element = $(element);
    this.$element.hide();

    this.isSelect = (element.tagName === 'SELECT');
    this.multiple = (this.isSelect && element.hasAttribute('multiple'));
    this.objectItems = options && options.itemValue;
    this.placeholderText = element.hasAttribute('placeholder') ? this.$element.attr('placeholder') : '';
    this.inputSize = Math.max(1, this.placeholderText.length);

    this.$container = $('<div class="bootstrap-tagsinput"></div>');
    this.$input = $('<input type="text" placeholder="' + this.placeholderText + '"/>').appendTo(this.$container);

    this.$element.before(this.$container);

    this.build(options);
  }

  TagsInput.prototype = {
    constructor: TagsInput,

    /**
     * Adds the given item as a new tag. Pass true to dontPushVal to prevent
     * updating the elements val()
     */
    add: function(item, dontPushVal, options) {
      var self = this;

      if (self.options.maxTags && self.itemsArray.length >= self.options.maxTags)
        return;

      // Ignore falsey values, except false
      if (item !== false && !item)
        return;

      // Trim value
      if (typeof item === "string" && self.options.trimValue) {
        item = $.trim(item);
      }

      // Throw an error when trying to add an object while the itemValue option was not set
      if (typeof item === "object" && !self.objectItems)
        throw("Can't add objects when itemValue option is not set");

      // Ignore strings only containg whitespace
      if (item.toString().match(/^\s*$/))
        return;

      // If SELECT but not multiple, remove current tag
      if (self.isSelect && !self.multiple && self.itemsArray.length > 0)
        self.remove(self.itemsArray[0]);

      if (typeof item === "string" && this.$element[0].tagName === 'INPUT') {
        var delimiter = (self.options.delimiterRegex) ? self.options.delimiterRegex : self.options.delimiter;
        var items = item.split(delimiter);
        if (items.length > 1) {
          for (var i = 0; i < items.length; i++) {
            this.add(items[i], true);
          }

          if (!dontPushVal)
            self.pushVal();
          return;
        }
      }

      var itemValue = self.options.itemValue(item),
          itemText = self.options.itemText(item),
          tagClass = self.options.tagClass(item),
          itemTitle = self.options.itemTitle(item);

      // Ignore items allready added
      var existing = $.grep(self.itemsArray, function(item) { return self.options.itemValue(item) === itemValue; } )[0];
      if (existing && !self.options.allowDuplicates) {
        // Invoke onTagExists
        if (self.options.onTagExists) {
          var $existingTag = $(".tag", self.$container).filter(function() { return $(this).data("item") === existing; });
          self.options.onTagExists(item, $existingTag);
        }
        return;
      }

      // if length greater than limit
      if (self.items().toString().length + item.length + 1 > self.options.maxInputLength)
        return;

      // raise beforeItemAdd arg
      var beforeItemAddEvent = $.Event('beforeItemAdd', { item: item, cancel: false, options: options});
      self.$element.trigger(beforeItemAddEvent);
      if (beforeItemAddEvent.cancel)
        return;

      // register item in internal array and map
      self.itemsArray.push(item);

      // add a tag element

      var $tag = $('<span class="tag ' + htmlEncode(tagClass) + (itemTitle !== null ? ('" title="' + itemTitle) : '') + '">' + htmlEncode(itemText) + '<span data-role="remove"></span></span>');
      $tag.data('item', item);
      self.findInputWrapper().before($tag);
      $tag.after(' ');

      // add <option /> if item represents a value not present in one of the <select />'s options
      if (self.isSelect && !$('option[value="' + encodeURIComponent(itemValue) + '"]',self.$element)[0]) {
        var $option = $('<option selected>' + htmlEncode(itemText) + '</option>');
        $option.data('item', item);
        $option.attr('value', itemValue);
        self.$element.append($option);
      }

      if (!dontPushVal)
        self.pushVal();

      // Add class when reached maxTags
      if (self.options.maxTags === self.itemsArray.length || self.items().toString().length === self.options.maxInputLength)
        self.$container.addClass('bootstrap-tagsinput-max');

      self.$element.trigger($.Event('itemAdded', { item: item, options: options }));
    },

    /**
     * Removes the given item. Pass true to dontPushVal to prevent updating the
     * elements val()
     */
    remove: function(item, dontPushVal, options) {
      var self = this;

      if (self.objectItems) {
        if (typeof item === "object")
          item = $.grep(self.itemsArray, function(other) { return self.options.itemValue(other) ==  self.options.itemValue(item); } );
        else
          item = $.grep(self.itemsArray, function(other) { return self.options.itemValue(other) ==  item; } );

        item = item[item.length-1];
      }

      if (item) {
        var beforeItemRemoveEvent = $.Event('beforeItemRemove', { item: item, cancel: false, options: options });
        self.$element.trigger(beforeItemRemoveEvent);
        if (beforeItemRemoveEvent.cancel)
          return;

        $('.tag', self.$container).filter(function() { return $(this).data('item') === item; }).remove();
        $('option', self.$element).filter(function() { return $(this).data('item') === item; }).remove();
        if($.inArray(item, self.itemsArray) !== -1)
          self.itemsArray.splice($.inArray(item, self.itemsArray), 1);
      }

      if (!dontPushVal)
        self.pushVal();

      // Remove class when reached maxTags
      if (self.options.maxTags > self.itemsArray.length)
        self.$container.removeClass('bootstrap-tagsinput-max');

      self.$element.trigger($.Event('itemRemoved',  { item: item, options: options }));
    },

    /**
     * Removes all items
     */
    removeAll: function() {
      var self = this;

      $('.tag', self.$container).remove();
      $('option', self.$element).remove();

      while(self.itemsArray.length > 0)
        self.itemsArray.pop();

      self.pushVal();
    },

    /**
     * Refreshes the tags so they match the text/value of their corresponding
     * item.
     */
    refresh: function() {
      var self = this;
      $('.tag', self.$container).each(function() {
        var $tag = $(this),
            item = $tag.data('item'),
            itemValue = self.options.itemValue(item),
            itemText = self.options.itemText(item),
            tagClass = self.options.tagClass(item);

          // Update tag's class and inner text
          $tag.attr('class', null);
          $tag.addClass('tag ' + htmlEncode(tagClass));
          $tag.contents().filter(function() {
            return this.nodeType == 3;
          })[0].nodeValue = htmlEncode(itemText);

          if (self.isSelect) {
            var option = $('option', self.$element).filter(function() { return $(this).data('item') === item; });
            option.attr('value', itemValue);
          }
      });
    },

    /**
     * Returns the items added as tags
     */
    items: function() {
      return this.itemsArray;
    },

    /**
     * Assembly value by retrieving the value of each item, and set it on the
     * element.
     */
    pushVal: function() {
      var self = this,
          val = $.map(self.items(), function(item) {
            return self.options.itemValue(item).toString();
          });

      self.$element.val(val, true).trigger('change');
    },

    /**
     * Initializes the tags input behaviour on the element
     */
    build: function(options) {
      var self = this;

      self.options = $.extend({}, defaultOptions, options);
      // When itemValue is set, freeInput should always be false
      if (self.objectItems)
        self.options.freeInput = false;

      makeOptionItemFunction(self.options, 'itemValue');
      makeOptionItemFunction(self.options, 'itemText');
      makeOptionFunction(self.options, 'tagClass');

      // Typeahead Bootstrap version 2.3.2
      if (self.options.typeahead) {
        var typeahead = self.options.typeahead || {};

        makeOptionFunction(typeahead, 'source');

        self.$input.typeahead($.extend({}, typeahead, {
          source: function (query, process) {
            function processItems(items) {
              var texts = [];

              for (var i = 0; i < items.length; i++) {
                var text = self.options.itemText(items[i]);
                map[text] = items[i];
                texts.push(text);
              }
              process(texts);
            }

            this.map = {};
            var map = this.map,
                data = typeahead.source(query);

            if ($.isFunction(data.success)) {
              // support for Angular callbacks
              data.success(processItems);
            } else if ($.isFunction(data.then)) {
              // support for Angular promises
              data.then(processItems);
            } else {
              // support for functions and jquery promises
              $.when(data)
               .then(processItems);
            }
          },
          updater: function (text) {
            self.add(this.map[text]);
            return this.map[text];
          },
          matcher: function (text) {
            return (text.toLowerCase().indexOf(this.query.trim().toLowerCase()) !== -1);
          },
          sorter: function (texts) {
            return texts.sort();
          },
          highlighter: function (text) {
            var regex = new RegExp( '(' + this.query + ')', 'gi' );
            return text.replace( regex, "<strong>$1</strong>" );
          }
        }));
      }

      // typeahead.js
      if (self.options.typeaheadjs) {
          var typeaheadConfig = null;
          var typeaheadDatasets = {};

          // Determine if main configurations were passed or simply a dataset
          var typeaheadjs = self.options.typeaheadjs;
          if ($.isArray(typeaheadjs)) {
            typeaheadConfig = typeaheadjs[0];
            typeaheadDatasets = typeaheadjs[1];
          } else {
            typeaheadDatasets = typeaheadjs;
          }

          self.$input.typeahead(typeaheadConfig, typeaheadDatasets).on('typeahead:selected', $.proxy(function (obj, datum) {
            if (typeaheadDatasets.valueKey)
              self.add(datum[typeaheadDatasets.valueKey]);
            else
              self.add(datum);
            self.$input.typeahead('val', '');
          }, self));
      }

      self.$container.on('click', $.proxy(function(event) {
        if (! self.$element.attr('disabled')) {
          self.$input.removeAttr('disabled');
        }
        self.$input.focus();
      }, self));

        if (self.options.addOnBlur && self.options.freeInput) {
          self.$input.on('focusout', $.proxy(function(event) {
              // HACK: only process on focusout when no typeahead opened, to
              //       avoid adding the typeahead text as tag
              if ($('.typeahead, .twitter-typeahead', self.$container).length === 0) {
                self.add(self.$input.val());
                self.$input.val('');
              }
          }, self));
        }


      self.$container.on('keydown', 'input', $.proxy(function(event) {
        var $input = $(event.target),
            $inputWrapper = self.findInputWrapper();

        if (self.$element.attr('disabled')) {
          self.$input.attr('disabled', 'disabled');
          return;
        }

        switch (event.which) {
          // BACKSPACE
          case 8:
            if (doGetCaretPosition($input[0]) === 0) {
              var prev = $inputWrapper.prev();
              if (prev.length) {
                self.remove(prev.data('item'));
              }
            }
            break;

          // DELETE
          case 46:
            if (doGetCaretPosition($input[0]) === 0) {
              var next = $inputWrapper.next();
              if (next.length) {
                self.remove(next.data('item'));
              }
            }
            break;

          // LEFT ARROW
          case 37:
            // Try to move the input before the previous tag
            var $prevTag = $inputWrapper.prev();
            if ($input.val().length === 0 && $prevTag[0]) {
              $prevTag.before($inputWrapper);
              $input.focus();
            }
            break;
          // RIGHT ARROW
          case 39:
            // Try to move the input after the next tag
            var $nextTag = $inputWrapper.next();
            if ($input.val().length === 0 && $nextTag[0]) {
              $nextTag.after($inputWrapper);
              $input.focus();
            }
            break;
         default:
             // ignore
         }

        // Reset internal input's size
        var textLength = $input.val().length,
            wordSpace = Math.ceil(textLength / 5),
            size = textLength + wordSpace + 1;
        $input.attr('size', Math.max(this.inputSize, $input.val().length));
      }, self));

      self.$container.on('keypress', 'input', $.proxy(function(event) {
         var $input = $(event.target);

         if (self.$element.attr('disabled')) {
            self.$input.attr('disabled', 'disabled');
            return;
         }

         var text = $input.val(),
         maxLengthReached = self.options.maxChars && text.length >= self.options.maxChars;
         if (self.options.freeInput && (keyCombinationInList(event, self.options.confirmKeys) || maxLengthReached)) {
            // Only attempt to add a tag if there is data in the field
            if (text.length !== 0) {
               self.add(maxLengthReached ? text.substr(0, self.options.maxChars) : text);
               $input.val('');
            }

            // If the field is empty, let the event triggered fire as usual
            if (self.options.cancelConfirmKeysOnEmpty === false) {
               event.preventDefault();
            }
         }

         // Reset internal input's size
         var textLength = $input.val().length,
            wordSpace = Math.ceil(textLength / 5),
            size = textLength + wordSpace + 1;
         $input.attr('size', Math.max(this.inputSize, $input.val().length));
      }, self));

      // Remove icon clicked
      self.$container.on('click', '[data-role=remove]', $.proxy(function(event) {
        if (self.$element.attr('disabled')) {
          return;
        }
        self.remove($(event.target).closest('.tag').data('item'));
      }, self));

      // Only add existing value as tags when using strings as tags
      if (self.options.itemValue === defaultOptions.itemValue) {
        if (self.$element[0].tagName === 'INPUT') {
            self.add(self.$element.val());
        } else {
          $('option', self.$element).each(function() {
            self.add($(this).attr('value'), true);
          });
        }
      }
    },

    /**
     * Removes all tagsinput behaviour and unregsiter all event handlers
     */
    destroy: function() {
      var self = this;

      // Unbind events
      self.$container.off('keypress', 'input');
      self.$container.off('click', '[role=remove]');

      self.$container.remove();
      self.$element.removeData('tagsinput');
      self.$element.show();
    },

    /**
     * Sets focus on the tagsinput
     */
    focus: function() {
      this.$input.focus();
    },

    /**
     * Returns the internal input element
     */
    input: function() {
      return this.$input;
    },

    /**
     * Returns the element which is wrapped around the internal input. This
     * is normally the $container, but typeahead.js moves the $input element.
     */
    findInputWrapper: function() {
      var elt = this.$input[0],
          container = this.$container[0];
      while(elt && elt.parentNode !== container)
        elt = elt.parentNode;

      return $(elt);
    }
  };

  /**
   * Register JQuery plugin
   */
  $.fn.tagsinput = function(arg1, arg2, arg3) {
    var results = [];

    this.each(function() {
      var tagsinput = $(this).data('tagsinput');
      // Initialize a new tags input
      if (!tagsinput) {
          tagsinput = new TagsInput(this, arg1);
          $(this).data('tagsinput', tagsinput);
          results.push(tagsinput);

          if (this.tagName === 'SELECT') {
              $('option', $(this)).attr('selected', 'selected');
          }

          // Init tags from $(this).val()
          $(this).val($(this).val());
      } else if (!arg1 && !arg2) {
          // tagsinput already exists
          // no function, trying to init
          results.push(tagsinput);
      } else if(tagsinput[arg1] !== undefined) {
          // Invoke function on existing tags input
            if(tagsinput[arg1].length === 3 && arg3 !== undefined){
               var retVal = tagsinput[arg1](arg2, null, arg3);
            }else{
               var retVal = tagsinput[arg1](arg2);
            }
          if (retVal !== undefined)
              results.push(retVal);
      }
    });

    if ( typeof arg1 == 'string') {
      // Return the results from the invoked function calls
      return results.length > 1 ? results : results[0];
    } else {
      return results;
    }
  };

  $.fn.tagsinput.Constructor = TagsInput;

  /**
   * Most options support both a string or number as well as a function as
   * option value. This function makes sure that the option with the given
   * key in the given options is wrapped in a function
   */
  function makeOptionItemFunction(options, key) {
    if (typeof options[key] !== 'function') {
      var propertyName = options[key];
      options[key] = function(item) { return item[propertyName]; };
    }
  }
  function makeOptionFunction(options, key) {
    if (typeof options[key] !== 'function') {
      var value = options[key];
      options[key] = function() { return value; };
    }
  }
  /**
   * HtmlEncodes the given value
   */
  var htmlEncodeContainer = $('<div />');
  function htmlEncode(value) {
    if (value) {
      return htmlEncodeContainer.text(value).html();
    } else {
      return '';
    }
  }

  /**
   * Returns the position of the caret in the given input field
   * http://flightschool.acylt.com/devnotes/caret-position-woes/
   */
  function doGetCaretPosition(oField) {
    var iCaretPos = 0;
    if (document.selection) {
      oField.focus ();
      var oSel = document.selection.createRange();
      oSel.moveStart ('character', -oField.value.length);
      iCaretPos = oSel.text.length;
    } else if (oField.selectionStart || oField.selectionStart == '0') {
      iCaretPos = oField.selectionStart;
    }
    return (iCaretPos);
  }

  /**
    * Returns boolean indicates whether user has pressed an expected key combination.
    * @param object keyPressEvent: JavaScript event object, refer
    *     http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
    * @param object lookupList: expected key combinations, as in:
    *     [13, {which: 188, shiftKey: true}]
    */
  function keyCombinationInList(keyPressEvent, lookupList) {
      var found = false;
      $.each(lookupList, function (index, keyCombination) {
          if (typeof (keyCombination) === 'number' && keyPressEvent.which === keyCombination) {
              found = true;
              return false;
          }

          if (keyPressEvent.which === keyCombination.which) {
              var alt = !keyCombination.hasOwnProperty('altKey') || keyPressEvent.altKey === keyCombination.altKey,
                  shift = !keyCombination.hasOwnProperty('shiftKey') || keyPressEvent.shiftKey === keyCombination.shiftKey,
                  ctrl = !keyCombination.hasOwnProperty('ctrlKey') || keyPressEvent.ctrlKey === keyCombination.ctrlKey;
              if (alt && shift && ctrl) {
                  found = true;
                  return false;
              }
          }
      });

      return found;
  }

  /**
   * Initialize tagsinput behaviour on inputs and selects which have
   * data-role=tagsinput
   */
  $(function() {
    $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
  });
})(window.jQuery);

</script>
			<?php

		}



		 function enquirydashboard(){
			
        	?>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
   <style>
        #adminmenuwrap {
            height: 100% !important;
        }
        
        .previous {
            border-radius: 4px 0px 0px 4px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        .next {
            border-radius: 0px 4px 4px 0px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        #example_paginate span {
            cursor: pointer;
            color: #fff !important;
            padding: 6px 15px !important;
            margin-left: 10px !important;
        }
#error{
color: #e00;
    font-size: 16px;
}

ul.enqyy li {
    display: inline-block;
    margin-right: 6px;
}
    </style>
 <h3>All Enquiry List</h3>
 <div class="row">
  <center><div><ul class="enqyy">
    <li><b>Enquiry Filter : </b><li>
      <li>
    <form method="get"> 
    <select id="typeenquiry" name="type">
<option value="all" <?php  if($_GET['enquirytype']=="all"){ echo "selected"; }?> >All enquiry details</option>

<option value="payuser" <?php  if($_GET['enquirytype']=="payuser"){ echo "selected"; }?>>Confirm and pay user details</option>

<option value="applynow" <?php  if($_GET['enquirytype']=="applynow"){ echo "selected"; }?>>Apply now enquiry details</option>

<option value="registerfor_crpto_currency" <?php  if($_GET['enquirytype']=="registerfor_crpto_currency"){ echo "selected"; }?>>Crypto currency enquiry details</option>

  <option value="enquirynow" <?php  if($_GET['enquirytype']=="enquirynow"){ echo "selected"; }?>>Enquiry now details</option>
  <option value="brochure" <?php  if($_GET['enquirytype']=="brochure"){ echo "selected"; }?>>Brochure enquiry details</option>
  <option value="get_6monthsyllabus" <?php  if($_GET['enquirytype']=="get_6monthsyllabus"){ echo "selected"; }?>>Get 6 Month Syllabus enquiry details</option>
  <option value="contactus" <?php  if($_GET['enquirytype']=="contactus"){ echo "selected"; }?>>Contact us enquiry details</option>

</select>
</form>
</li>
<div>

</center>
 </div>

 

   <?php
if(!empty($_GET['enquirytype'])){


                   if($_GET['enquirytype']=="applynow"){  
                      echo"<h4><b>All Apply now enquiry details :-</b></h4>";
                    }elseif ($_GET['enquirytype']=="registerfor_crpto_currency") {
                        echo "<h4><b>All Crypto currency enquiry details :-</b></h4>";
                    }
                    elseif ($_GET['enquirytype']=="enquirynow") {
                        echo "<h4><b>All Enquiry now details :-</b></h4>";
                    }else if($_GET['enquirytype']=="brochure"){
                        echo "<h4><b>All Brochure enquiry details :-</b></h4>";
                    }else if($_GET['enquirytype']=="get_6monthsyllabus"){
                         echo "<h4><b>All Get 6 Month Syllabus enquiry details :-</b></h4>";
                    }else if($_GET['enquirytype']=="contactus"){
                      echo "<h4><b>All Contact us enquiry details :-</b></h4>";
                    }
                    else if($_GET['enquirytype']=="all"){
                      echo "<h4><b>All enquiry details :-</b></h4>";
                    }
                     else if($_GET['enquirytype']=="payuser"){
                      echo "<h4><b>All Confirm and pay user details :-</b></h4>";
                    }

                  }else{
                    echo "<h4><b>All enquiry details :-</b></h4>";
                  }
 ?>

<div class="divider"></div>

<div class="row">
    <div class="col-md-12">
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Enquiry Type</th>
                    <th>Enquiry Date Time</th>
                    <th>User Details</th>
                  
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>S No.</th>
                    <th>Enquiry Type</th>
                    <th>Enquiry Date Time</th>

                    <th>User Details</th>
                </tr>
            </tfoot>
            <tbody>
             <?php global $wpdb;
             $enquirytypewhere="";
             if(!empty($_GET['enquirytype'])){
                if($_GET['enquirytype']=="all"){

                }elseif ($_GET['enquirytype']=="payuser") {
                  $enquirytypewhere=" where `type`='applynow' and `payment_confim`='1'";
                }
                else{

                  $enquirytypewhere=" where `type`='".$_GET['enquirytype']."'";
                }
              

             }
    $table_name = $wpdb->prefix.'equiryemail';
    $emaildata=$wpdb->get_results("select * from $table_name $enquirytypewhere order by id desc");
    $i=1;
    foreach ($emaildata as $userValue) {
      date_default_timezone_set('asia/kolkata');
      $en_time ="";
      if(!empty($userValue->time)){
      $en_time = date('M-d-Y h:i:s a',$userValue->time );  
      }

       ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php if($userValue->type=="applynow"){  
                      if($userValue->payment_confim=='1'){
                        echo "Confirm and pay user details";
                      }else{
                        echo"Apply now enquiry details";
                      }
                      

                    }elseif ($userValue->type=="registerfor_crpto_currency") {
                        echo "Crypto currency enquiry details";
                    }elseif ($userValue->type=="enquirynow") {
                        echo "Enquiry now details";
                    }else if($userValue->type=="brochure"){
                        echo "Brochure enquiry details";
                    }else if($userValue->type=="get_6monthsyllabus"){
                         echo "Get 6 Month Syllabus enquiry details";
                    }else if($userValue->type=="contactus"){
                      echo "Contact us enquiry details";
                    }?>
                    </td>
                    <td><?php echo  $en_time ; ?> </td>
                    
                    <td>

  <p style='font-size:14px;'><b>Name : </b><span><?php echo $userValue->firstname." ".$userValue->lastname; ?></span></p>

  <p style='font-size:14px;'><b>Email-id : </b><span><?php echo $userValue->emailid; ?></span></p>

<p style='font-size:14px;'><b>Phone : </b><span><?php echo $userValue->mobile; ?></span></p>

    <?php if(!empty($userValue->dob)){ ?>
   <p style='font-size:14px;'><b>Dob : </b><span><?php echo $userValue->dob; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->gender)){ ?>
   <p style='font-size:14px;'><b>Gender : </b><span><?php echo $userValue->gender; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->program)){ 

$programdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$userValue->program."'");

     	?>
   <p style='font-size:14px;'><b>Program : </b><span><?php echo $programdata->programname; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->workshop) && !empty($programdata->sub_program)){ ?>
   <p style='font-size:14px;'><b><?php echo $programdata->sub_program; ?> : </b><span><?php echo $userValue->workshop; ?></span></p>
    <?php } ?>

  <?php if(!empty($userValue->state)){ ?>
   <p style='font-size:14px;'><b>State : </b><span><?php echo $userValue->state; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->city)){ ?>
   <p style='font-size:14px;'><b>City : </b><span><?php echo $userValue->city; ?></span></p>
    <?php } ?>

 <?php if(!empty($userValue->work_experience)){ ?>
<p style='font-size:14px;'><b>Total Years of Experience : </b><span><?php echo $userValue->work_experience; ?></span></p>
 <?php } ?>
 <?php if(!empty($userValue->carrer)){ ?>
  <p style='font-size:14px;'><b>Career Area : </b><span><?php echo $userValue->carrer; ?></span></p>
   <?php } ?>
 <?php if(!empty($userValue->edu_institute_name)){ ?>
  <p style='font-size:14px;'><b>Name of Institute : </b><span><?php echo $userValue->edu_institute_name; ?></span></p>
   <?php } ?>
   <?php if(!empty($userValue->edu_passout_date)){ ?>
 <p style='font-size:14px;'><b>Month & Year of Graduation : </b><span><?php echo $userValue->edu_passout_date; ?></span></p>
  <?php } ?>
 <?php if(!empty($userValue->edu_degree)){ ?>
<p style='font-size:14px;'><b>Degree Level : </b><span><?php echo $userValue->edu_degree; ?></span></p>
 <?php } ?>



        <?php if(!empty($userValue->address1)){ ?>
   <p style='font-size:14px;'><b>Address 1 : </b><span><?php echo $userValue->address1; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->address2)){ ?>
   <p style='font-size:14px;'><b>Address 2 : </b><span><?php echo $userValue->address2; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->pincode)){ ?>
   <p style='font-size:14px;'><b>Pincode : </b><span><?php echo $userValue->pincode; ?></span></p>
    <?php } ?>

 <?php if(!empty($userValue->pay_price)){ ?>



   <p style='font-size:14px;'><b>Price : </b><span><?php echo $userValue->pay_price; ?></span></p>

    <?php } ?>


    <?php if(!empty($userValue->message)){ ?>
   <p style='font-size:14px;'><b>Comment : </b><span><?php echo $userValue->message; ?></span></p>
    <?php } ?>

















                    </td>
                </tr>
               <?php } ?>

            </tbody>
        </table>
    </div>
</div>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function () {

         $('#typeenquiry').change(function() {
     var typedata = $(this).val();
     window.location.href = "<?php echo site_url("/wp-admin/admin.php?page=all_enquiry&enquirytype="); ?>"+typedata;
    
   });


        $('#example').DataTable({
        "iDisplayLength": 20
    });
    });
    </script>
    <?php

		}

     function today_enquirydata(){
      
        ?>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
   <style>
        #adminmenuwrap {
            height: 100% !important;
        }
        
        .previous {
            border-radius: 4px 0px 0px 4px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        .next {
            border-radius: 0px 4px 4px 0px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        #example_paginate span {
            cursor: pointer;
            color: #fff !important;
            padding: 6px 15px !important;
            margin-left: 10px !important;
        }
#error{
color: #e00;
    font-size: 16px;
}

ul.enqyy li {
    display: inline-block;
    margin-right: 6px;
}
    </style>
 <h3>Today All Enquiry List</h3>
 <div class="row">
  <center><div><ul class="enqyy">
    <li><b>Today Enquiry Filter : </b><li>
      <li>
    <form method="get"> 
    <select id="typeenquiry" name="type">
  <option value="all" <?php  if($_GET['enquirytype']=="all"){ echo "selected"; }?> >All enquiry details</option>
  <option value="payuser" <?php  if($_GET['enquirytype']=="payuser"){ echo "selected"; }?>>Confirm and pay user details</option>

  <option value="applynow" <?php  if($_GET['enquirytype']=="applynow"){ echo "selected"; }?>>Apply now enquiry details</option>

  <option value="registerfor_crpto_currency" <?php  if($_GET['enquirytype']=="registerfor_crpto_currency"){ echo "selected"; }?>>Crypto currency enquiry details</option>
  
  <option value="enquirynow" <?php  if($_GET['enquirytype']=="enquirynow"){ echo "selected"; }?>>Enquiry now details</option>
  <option value="brochure" <?php  if($_GET['enquirytype']=="brochure"){ echo "selected"; }?>>Brochure enquiry details</option>
  <option value="get_6monthsyllabus" <?php  if($_GET['enquirytype']=="get_6monthsyllabus"){ echo "selected"; }?>>Get 6 Month Syllabus enquiry details</option>
  <option value="contactus" <?php  if($_GET['enquirytype']=="contactus"){ echo "selected"; }?>>Contact us enquiry details</option>

</select>
</form>
</li>
<div>

</center>
 </div>

 

   <?php
if(!empty($_GET['enquirytype'])){


                   if($_GET['enquirytype']=="applynow"){  
                      echo"<h4><b>Today All Apply now enquiry details :-</b></h4>";
                    }elseif ($_GET['enquirytype']=="registerfor_crpto_currency") {
                        echo "<h4><b>Today Crypto currency enquiry details :-</b></h4>";
                    }elseif ($_GET['enquirytype']=="enquirynow") {
                        echo "<h4><b>Today All Enquiry now details :-</b></h4>";
                    }else if($_GET['enquirytype']=="brochure"){
                        echo "<h4><b>Today All Brochure enquiry details :-</b></h4>";
                    }else if($_GET['enquirytype']=="get_6monthsyllabus"){
                         echo "<h4><b>Today All Get 6 Month Syllabus enquiry details :-</b></h4>";
                    }else if($_GET['enquirytype']=="contactus"){
                      echo "<h4><b>Today All Contact us enquiry details :-</b></h4>";
                    }
                    else if($_GET['enquirytype']=="all"){
                      echo "<h4><b>Today All enquiry details :-</b></h4>";
                    }else if($_GET['enquirytype']=="payuser"){
                      echo "<h4><b>Today Confirm and pay user details :-</b></h4>";
                    }

                  }else{
                    echo "<h4><b>Today All enquiry details :-</b></h4>";
                  }
 ?>

<div class="divider"></div>

<div class="row">
    <div class="col-md-12">
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Enquiry Type</th>
                    <th>Enquiry Date Time</th>
                    <th>User Details</th>
                  
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>S No.</th>
                    <th>Enquiry Type</th>
                    <th>Enquiry Date Time</th>

                    <th>User Details</th>
                </tr>
            </tfoot>
            <tbody>
             <?php global $wpdb;
             date_default_timezone_set('asia/kolkata');
             $enquirytypewhere="";
             if(!empty($_GET['enquirytype'])){
                if($_GET['enquirytype']=="all"){

                }elseif ($_GET['enquirytype']=="payuser") {
                  $enquirytypewhere=" and `type`='applynow' and `payment_confim`='1'";
                }else{
                  $enquirytypewhere=" and `type`='".$_GET['enquirytype']."'";
                }
              

             }
    $table_name = $wpdb->prefix.'equiryemail';
    $emaildata=$wpdb->get_results("select * from $table_name where `date`='".date('Y-m-d')."' $enquirytypewhere order by id desc");
    $i=1;
    foreach ($emaildata as $userValue) {
      
      $en_time ="";
      if(!empty($userValue->time)){
      $en_time = date('M-d-Y h:i:s a',$userValue->time );  
      }

       ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php if($userValue->type=="applynow"){  
                      if($userValue->payment_confim=='1'){
                        echo "Confirm and pay user details";
                      }else{
                        echo"Apply now enquiry details";
                      }
                    }elseif ($userValue->type=="registerfor_crpto_currency") {
                        echo "Crypto currency enquiry details";
                    }elseif ($userValue->type=="enquirynow") {
                        echo "Enquiry now details";
                    }else if($userValue->type=="brochure"){
                        echo "Brochure enquiry details";
                    }else if($userValue->type=="get_6monthsyllabus"){
                         echo "Get 6 Month Syllabus enquiry details";
                    }else if($userValue->type=="contactus"){
                      echo "Contact us enquiry details";
                    }?>
                    </td>
                    <td><?php echo  $en_time ; ?> </td>
                    
                    <td>

  <p style='font-size:14px;'><b>Name : </b><span><?php echo $userValue->firstname." ".$userValue->lastname; ?></span></p>

  <p style='font-size:14px;'><b>Email-id : </b><span><?php echo $userValue->emailid; ?></span></p>

<p style='font-size:14px;'><b>Phone : </b><span><?php echo $userValue->mobile; ?></span></p>

    <?php if(!empty($userValue->dob)){ ?>
   <p style='font-size:14px;'><b>Dob : </b><span><?php echo $userValue->dob; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->gender)){ ?>
   <p style='font-size:14px;'><b>Gender : </b><span><?php echo $userValue->gender; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->program)){ 

$programdata=$wpdb->get_row("select * from ".$wpdb->prefix."enquiry_program where `id`='".$userValue->program."'");

     	?>
   <p style='font-size:14px;'><b>Program : </b><span><?php echo $programdata->programname; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->workshop) && !empty($programdata->sub_program)){ ?>
   <p style='font-size:14px;'><b><?php echo $programdata->sub_program; ?> : </b><span><?php echo $userValue->workshop; ?></span></p>
    <?php } ?>

  <?php if(!empty($userValue->state)){ ?>
   <p style='font-size:14px;'><b>State : </b><span><?php echo $userValue->state; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->city)){ ?>
   <p style='font-size:14px;'><b>City : </b><span><?php echo $userValue->city; ?></span></p>
    <?php } ?>


 <?php if(!empty($userValue->work_experience)){ ?>
<p style='font-size:14px;'><b>Total Years of Experience : </b><span><?php echo $userValue->work_experience; ?></span></p>
 <?php } ?>
 <?php if(!empty($userValue->carrer)){ ?>
  <p style='font-size:14px;'><b>Career Area : </b><span><?php echo $userValue->carrer; ?></span></p>
   <?php } ?>
 <?php if(!empty($userValue->edu_institute_name)){ ?>
  <p style='font-size:14px;'><b>Name of Institute : </b><span><?php echo $userValue->edu_institute_name; ?></span></p>
   <?php } ?>
   <?php if(!empty($userValue->edu_passout_date)){ ?>
 <p style='font-size:14px;'><b>Month & Year of Graduation : </b><span><?php echo $userValue->edu_passout_date; ?></span></p>
  <?php } ?>
 <?php if(!empty($userValue->edu_degree)){ ?>
<p style='font-size:14px;'><b>Degree Level : </b><span><?php echo $userValue->edu_degree; ?></span></p>
 <?php } ?>



        <?php if(!empty($userValue->address1)){ ?>
   <p style='font-size:14px;'><b>Address 1 : </b><span><?php echo $userValue->address1; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->address2)){ ?>
   <p style='font-size:14px;'><b>Address 2 : </b><span><?php echo $userValue->address2; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->pincode)){ ?>
   <p style='font-size:14px;'><b>Pincode : </b><span><?php echo $userValue->pincode; ?></span></p>
    <?php } ?>

     <?php if(!empty($userValue->pay_price)){ ?>
   <p style='font-size:14px;'><b>Price : </b><span><?php echo $userValue->pay_price; ?></span></p>
    <?php } ?>

    <?php if(!empty($userValue->message)){ ?>
   <p style='font-size:14px;'><b>Comment : </b><span><?php echo $userValue->message; ?></span></p>
    <?php } ?>

                    </td>
                </tr>
               <?php } ?>

            </tbody>
        </table>
    </div>
</div>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {

         $('#typeenquiry').change(function() {
     var typedata = $(this).val();
     window.location.href = "<?php echo site_url("/wp-admin/admin.php?page=today_enquiry&enquirytype="); ?>"+typedata;
    
   });


        $('#example').DataTable({
        "iDisplayLength": 20
    });
    });
    </script>
    <?php

    }

}









$webhook=new Enquiryuser();