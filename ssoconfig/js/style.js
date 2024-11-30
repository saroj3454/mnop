 function Validate() {

        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("cpassword").value;
          if(password == ""){
             document.getElementById("perror").innerHTML="Password must not be empty";
            return false;
          } 
          if(confirmPassword == ""){
             document.getElementById("perror").innerHTML="Confirm Password must not be empty";
            return false;
          } 
        if (password != confirmPassword) {
          document.getElementById("perror").innerHTML="Passwords do not match.";
            return false;
        }
        document.getElementById("perror").innerHTML="";
        return true;
    }
  function Institution($value){
    var settings = {
    "url": plugin_ajax_object.ajax_url,
    "method": "POST",
    "timeout": 0,
    "headers": {
    "Content-Type": "application/json",
    "Cookie": "PHPSESSID=dolrvdvkdanothck1r0v2gr834"
    },
    "data": JSON.stringify({
    "id": $value
    }),
    };
      $.ajax(settings).done(function (response) {
            var myObj = JSON.parse(JSON.stringify(response));
            if(myObj[0].centres.length>0){       
              let centeroption = "";
              centeroption +="<option value=''>Select Center</option>";
              for (let i in myObj[0].centres) {          
                  centeroption +="<option value='"+myObj[0].centres[i].name+"'>"+myObj[0].centres[i].name+ "</option>";
                
              
              }
              $('#centre').html(centeroption);
          }
      });
  }

  function programedata($status){

   if($status=='Funded student with other DAAD scholarship' || $status=='Staff/Lecturers of other DAAD funded programme'){
      $('#programmefield').css('display','block');
        $('#programme').val("");
   }else{
     $('#programmefield').css('display','none');
     $('#programme').val("");
   }
  }

jQuery(document).ready(function(){ 
   Institution($('#institution').val());
  $('#centre').html("<option value=''>----</option>");
  $('#institution').on('change',function(){
    if($(this).val().length>0){
      console.log($(this).val().length);
      Institution($(this).val());
    }else{
      $('#centre').html("<option value=''>----</option>");
    }
  })
    /* $("#cpassword").on('keyup', function() {
          Validate();
     });
          $("#password").on('keyup', function() {
          Validate();
     });*/
programedata(status);
$("#user-status").on('change', function() {
 var status=$(this).val();
programedata(status);
 
});


});

//user registration valodation
jQuery(function(){
  jQuery.validator.addMethod("passwordCheck",
        function(value, element, param) {
            if (this.optional(element)) {
                return true;
            } else if (!/[A-Z]/.test(value)) {
                return false;
            } else if (!/[a-z]/.test(value)) {
                return false;
            } else if (!/[0-9]/.test(value)) {
                return false;
            }

            return true;
        },
        "Password must contain at least 6 characters, including UPPER/lowercase and numbers");
  jQuery('#user-registration-form').validate({
    debug: false,
    errorClass: "usererror",
    ignore: [], 
    rules:{
      fname:{
        required:true
      },
      lname:{
        required:true
      },
      email:{
        required:true,
        email:true
      },
      password:{
        required:true,
        passwordCheck:true,
        minlength:6
        
      },
      cpassword:{
        
        equalTo:"#password"
      },
      institution:{
        required:true
      },
      centre:{
        required:true
      },
      'user-status':{
        required:true
      },
      origin:{
        required:true
      },
      'current-country':{
        required:true
      },
      terms:{
        required:true
      },
      protection:{
        required:true
      }

    },
    messages:{
      fname:{
        required:"First name is required."
      },
      lname:{
        required:"Last name is required."
      },
      email:{
        required:"Email is required."
      },
      password:{
        required:"Password is required."
      },
      cpassword:{
        equalTo:" Enter Confirm Password Same as Password."
      },
      institution:{
        required:"Institution field is required."
      },
      centre:{
        required:"Centre field is required."
      },
      'user-status':{
        required:"User status field is required."
      },
      origin:{
        required:"Origin field is required."
      },
      'current-country':{
        required:"Current country field is required"
      },
      terms:{
        required:"Required"
      },
      protection:{
        required:"Required"
      }


    },
     errorPlacement: function(error, element) {
        if (element.attr("name") == "institution") {
          error.appendTo(".institutionerror");
      }
       else if (element.attr("name") == "password" || element.attr("name")=="cpassword") {
          error.appendTo("#perror");
      }
      else if (element.attr("name") == "fname" ) {
          error.appendTo("#fnameerror");
      }
      else if (element.attr("name") == "lname") {
          error.appendTo("#lnameerror");
      }
      else if (element.attr("name") == "terms") {
          error.appendTo(".tm_error");
      }
      else if (element.attr("name") == "protection") {
          error.appendTo(".pt_error");
      }
      else{
        error.insertAfter(element);
      }
    }
    
  });
  //show and hide error msg institution fields//institutionerror//perror
  $('body').on('change','#institution',function(){
      if($(this).val()!=""){
        $('.institutionerror').hide();
      }else{
        $('.institutionerror').show();
      }
  });
  $('#password').focus(function(){
     $('#cpassword-error').hide();
  });
   $('#cpassword').focus(function(){
    if($('#cpassword-error').text()==""){
        $('#cpassword-error').hide();
    }
  });
  $('#password').keyup(function(){
     $('#cpassword-error').hide();
  });
  $('body').on('keyup','#cpassword',function(){
      let pss=$('#password-error').text();
      let cpss=$('#cpassword-error').text();
     // alert('ok');
      //console.log('cpss',cpss);
      //console.log('pss',pss);
      if(pss=="" && cpss!=""){
        $('#cpassword-error').show();
      }else{
         $('#cpassword-error').hide();
      }
  });
});