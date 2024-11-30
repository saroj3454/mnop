function Validate() {

        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("cpassword").value;
          if(password == ""){
             document.getElementById("epassword").innerHTML="Password must not be empty";
            return false;
          }else{
             document.getElementById("epassword").innerHTML="";
          }
          if(confirmPassword == ""){
             document.getElementById("econfirmpassword").innerHTML="Confirm Password must not be empty";
            return false;
          }else{
            document.getElementById("econfirmpassword").innerHTML="";
          } 
        if (password != confirmPassword) {
          // console.log(password +''+ confirmPassword);
          // document.getElementById("epassword").innerHTML="New Password and Confirm Password Not Match";
          document.getElementById("econfirmpassword").innerHTML="New Password and Confirm Password Not Match";
            return false;
        }else{
           document.getElementById("epassword").innerHTML="";
          document.getElementById("econfirmpassword").innerHTML="";
        }
      
        return true;
    }


jQuery(document).ready(function(){ 
      $("#password").on('keyup', function() {
          Validate();
     });
          $("#cpassword").on('keyup', function() {
          Validate();
     });


});