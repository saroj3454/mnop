  <?php require_once("../../../wp-config.php");
     global $wpdb;
     $table_name = $wpdb->prefix.'enquiry_emailsetting';
     $wpdb->query("UPDATE $table_name SET email='".$_POST['email']."',payment_price='".$_POST['pricedata']."',applynow_link='".$_POST['applynow_link']."',razorpay_link='".$_POST['razorpay_link']."' WHERE id='1'");