function _seodashboard(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _seodashboarddata = function () {
    function t(t, e) {
        for (var s = 0; s < e.length; s++) {
            var n = e[s];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }
    return function (e, s, n) {
        return s && t(e.prototype, s), n && t(e, n), e
    }
}(),
    seodashboardalldata = function () {
        function t() {
            _seodashboard(this, t)
        }
        return _seodashboarddata(t, [{
            key: "init",
            value: function (t, e) {
                this.jQuery = $ = t,
                    this.$PAGE = e,
                    this.jCall = null,
                    this.ajaxHTML = null,
                    this._initListeners()
            }
        }, {
            key: "_initListeners",
            value: function () {
                this.$PAGE = this.jQuery("body");
                var that = this;
         this.$PAGE.on('click','#registerbutton',function(ev){        
         ev.preventDefault();
            that._validateform();
        });
         $(".formclass").on("keyup", "[name]", function(ev) {
            ev.preventDefault();
             var keyName = $(this).attr('name');
              var fieldData = $(this).val();
              var ef_password = "";
            var cf_password = "";
               var regx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
              var fname = /^[a-z]([-']?[a-z]+)*( [a-z]([-']?[a-z]+)*)+$/;
             if (keyName == "fullname" && fieldData != "") {
                    if(fname.test(fieldData)) { 
                         $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                    }else{
                        $('#' + keyName+"error").html('Enter Full Name');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                    }
                 }

                if (keyName == "username" && fieldData != "") {
    
                        $.ajax({
                    url:"https://preparetest.com/local/seodashboard/ajaxsign.php",
                    type: "POST",
                    data:{action:'signupusernamevalidate',username:fieldData},
                    success: function (response) {
                        var obj=JSON.parse(JSON.stringify(response));
                        if(obj.avl=='avl'){
                            $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                        }else{
                            $('#' + keyName+"error").html('Username already exists');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                        }
                    }
                    
                    });                      
                             
                }

            
             if (keyName == "email" && fieldData != "") {
                    if(regx.test(fieldData)) { 
                        $.ajax({
                    url:"https://preparetest.com/local/seodashboard/ajaxsign.php",
                    type: "POST",
                    data:{action:'signupusernamevalidate',username:fieldData},
                    success: function (response) {
                        var obj=JSON.parse(JSON.stringify(response));
                        if(obj.avl=='avl'){
                            $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                        }else{
                            $('#' + keyName+"error").html('Email id already exists');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                        }
                    }
                    
                    }); 
                    }else{                          
                    $('#'+keyName+"error").html('Invalid Email id');
                    $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                    $('#' + keyName+"error").removeClass('hide');
                    $('#' + keyName+"error").addClass('showw');
                    $('#'+keyName+"error").show();
                    }     
               }

                if (keyName == "password" && fieldData != "") {
                    $('#passworderror').html('');
                     $('.formclass input[name="password"]').css('border', '1px solid transparent');
                            $('#passworderror').addClass('hide');
                            $('#passworderror').removeClass('showw');
                            $('#passworderror').hide();
                  ef_password = fieldData;
                }
                if (keyName == "confirm_password" && fieldData != "") {
                  cf_password = fieldData;
                }


                if (ef_password != "" && cf_password != "") {
                  if (ef_password != cf_password) {
                    $('.formclass input[name="confirm_password"]').css('border', '1px solid red');
                    $('#confirm_passworderror').html('Password do not match');
                    $('#confirm_passworderror').removeClass('hide');
                    $('#confirm_passworderror').addClass('showw');
                    $('#confirm_passworderror').show();
                  } else {

                    $('#passworderror').html('');
                           


                            $('#confirm_passworderror').html('');
                            $('.formclass input[name="confirm_password"]').css('border', '1px solid transparent');
                            $('#confirm_passworderror').addClass('hide');
                            $('#confirm_passworderror').removeClass('showw');
                            $('#confirm_passworderror').hide();
                  }
        }
          
         });
          $(".formclass").on("focus", "[name]", function(ev) {
            var keyName = $(this).attr('name');
            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
            $('.formclass input[name="' + keyName + '"]').css('background', '#E7FDF0');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
            });
                
                
               
            }
        },{
                key: "_validateform",   // Update data
                value: function (email) {
                   
            
            var formKey = {};
            var f_name = "";
            var mail_address = "";
            var ef_password = "";
            var cf_password = "";
            
            var formClass = $(".formclass");
            formClass.find("[name]").each(function(e) {
                var keyName = $(this).attr('name');
              var fieldData = $(this).val();
              var regx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
              var fname = /^[a-z]([-']?[a-z]+)*( [a-z]([-']?[a-z]+)*)+$/;
              formKey[keyName] = fieldData;
          if (fieldData != '') {

                 if (keyName == "fullname" && fieldData != "") {
                    if(fname.test(fieldData)) { 
                         $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                    }else{
                        $('#' + keyName+"error").html('Enter Full Name');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                    }
                 }

                if (keyName == "username" && fieldData != "") {
    
                        $.ajax({
                    url:"https://preparetest.com/local/seodashboard/ajaxsign.php",
                    type: "POST",
                    data:{action:'signupusernamevalidate',username:fieldData},
                    success: function (response) {
                        var obj=JSON.parse(JSON.stringify(response));
                        if(obj.avl=='avl'){
                            $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                        }else{
                            $('#' + keyName+"error").html('Username already exists');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                        }
                    }
                    
                    });                      
                             
                }

            
             if (keyName == "email" && fieldData != "") {
                    if(regx.test(fieldData)) { 
                        $.ajax({
                    url:"https://preparetest.com/local/seodashboard/ajaxsign.php",
                    type: "POST",
                    data:{action:'signupusernamevalidate',username:fieldData},
                    success: function (response) {
                        var obj=JSON.parse(JSON.stringify(response));
                        if(obj.avl=='avl'){
                            $('#' + keyName+"error").html('');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
                            $('#' + keyName+"error").addClass('hide');
                            $('#' + keyName+"error").removeClass('showw');
                            $('#'+keyName+"error").hide();
                        }else{
                            $('#' + keyName+"error").html('Email id already exists');
                            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                            $('#' + keyName+"error").removeClass('hide');
                            $('#' + keyName+"error").addClass('showw');
                            $('#'+keyName+"error").show();
                        }
                    }
                    
                    }); 
                    }else{                          
                    $('#'+keyName+"error").html('Invalid Email id');
                    $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
                    $('#' + keyName+"error").removeClass('hide');
                    $('#' + keyName+"error").addClass('showw');
                    $('#'+keyName+"error").show();
                    }     
               }

                if (keyName == "password" && fieldData != "") {
                    $('#passworderror').html('');
                     $('.formclass input[name="password"]').css('border', '1px solid transparent');
                            $('#passworderror').addClass('hide');
                            $('#passworderror').removeClass('showw');
                            $('#passworderror').hide();
                  ef_password = fieldData;
                }
                if (keyName == "confirm_password" && fieldData != "") {
                  cf_password = fieldData;
                }


                if (ef_password != "" && cf_password != "") {
                  if (ef_password != cf_password) {
                    $('.formclass input[name="confirm_password"]').css('border', '1px solid red');
                    $('#confirm_passworderror').html('Password do not match');
                    $('#confirm_passworderror').removeClass('hide');
                    $('#confirm_passworderror').addClass('showw');
                    $('#confirm_passworderror').show();
                  } else {

                    $('#passworderror').html('');
                           


                            $('#confirm_passworderror').html('');
                            $('.formclass input[name="confirm_password"]').css('border', '1px solid transparent');
                            $('#confirm_passworderror').addClass('hide');
                            $('#confirm_passworderror').removeClass('showw');
                            $('#confirm_passworderror').hide();
                  }
        }


            // $('#' + keyName+"error").html('');
            // $('.formclass input[name="' + keyName + '"]').css('border', '1px solid transparent');
            // $('#' + keyName+"error").addClass('hide');
            // $('#' + keyName+"error").removeClass('showw');
            // $('#'+keyName+"error").hide();

            } else {
            $('#' + keyName+"error").html('Field cannot be empty');
            $('.formclass input[name="' + keyName + '"]').css('border', '1px red solid');
            $('#' + keyName+"error").removeClass('hide');
            $('#' + keyName+"error").addClass('showw');
            $('#'+keyName+"error").show();
          }

            });   
                if($('.error').hasClass('showw')){
                    console.log('ddd');

      }else{
 $('form#registerform').submit();
      }

                }
            },
        {
                key: "_validateemail",   // Update data
                value: function (email) {
                    var that = this;
                       that._APICall(
                                that._prepareRequest(
                                    "validateemail",
                                    {
                                        useremail:email

                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        console.log(result);
                                        
                                       }
                                }
                            )

                }
            },{
                key: "_validateusername",   // Update data
                value: function (username) {
                    var that = this;
                       that._APICall(
                                that._prepareRequest(
                                    "validusername",
                                    {
                                        username:username

                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        console.log(result);
                                        
                                       }
                                }
                            )

                }
            },
        {
                key: "_statusupdate",   // Update data
                value: function (dataid,action) {
                    var that = this;
                       that._APICall(
                                that._prepareRequest(
                                    "statusupdate",
                                    {
                                        dataid:dataid,action:action

                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        
                                         $('#d'+dataid).addClass(result.data.icon);
                                         $('#d'+dataid).removeClass(result.data.iconremove);
                                            
                                           console.log(result.data.icon);
                                           console.log('#d'+dataid);
                                       }
                                }
                            )

                }
            },{
            key: "_prepareRequest",
            value: function (wsfunction, data) {
                if (this.applang) {
                    data.lang = this.applang;
                }
                try {
                    data['sesskey'] = M.cfg.sesskey;
                } catch (err) {
                    data = [];
                    data['sesskey'] = M.cfg.sesskey;
                }
                var returndata = {
                    "wsfunction": wsfunction,
                    "wsargs": data
                }
                if (this.logintoken) {
                    returndata.wstoken = this.logintoken;
                }
                return JSON.stringify(returndata);
            }
        }, {
            key: "_APICall",
            value: function (requestdata, success) {
                if (this.jCall) {
                    // this.jCall.abort();
                }
                this.jCall = $.ajax({
                    "url": M.cfg.wwwroot + "/local/seodashboard/ajax.php",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": requestdata,
                    beforeSend: function () {
                        //console.log("request beforeSend");
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.code != 200) {
                            // displayToast(data.error.title, data.error.message, "error");
                        } else {
                            success(data);
                        }
                    }, error: function () {
                        //console.log("request error");
                        return null;
                    }, complete: function () {
                        //console.log("request complete");
                    }
                });
            }
        }]), t
    }();
!function (t) {
    t(function () {
        t("[auth]").each(function () {
            (new seodashboardalldata).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);



// $(document).ready(function () {
//     
// });