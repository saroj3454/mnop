function _classCheckoutCallCheck(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _createCheckoutClass = function () {
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
    checkoutController = function () {
        function t() {
            _classCheckoutCallCheck(this, t)
        }
        return _createCheckoutClass(t, [{
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
                this.$PAGE.on('click', '#submit', function (e) {
                    e.preventDefault();
                    that._personalinformation()
                });
                $('.form_personal_information').on('focus', '[name]', function () {
                    that.jQuery(this).removeClass('required-error').closest('.parent-class').find('.invalid-feedback').hide();
                }),
                this.$PAGE.on('focus','#dob', function (e) {
                            e.preventDefault();
                            $(this).prop("autocomplete", "off");
                        }),
                this.jQuery("#dob").datepicker({ dateFormat: 'dd/mm/yy', 
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1920:2022'
                });
            }
        },
        {
            key: "_personalinformation",
            value: function () {
                let that = this;
                let $addform = this.jQuery(".form_personal_information");
                let formdata = {};
                let errordata = {};
               let  required_fields = false;
               let old_password_flag=false;
              
                $addform.find("[name]").each(function (index) {
                    let eleval = that.jQuery(this).val();
                    let elename = that.jQuery(this).attr("name");
                    let thiselv = that.jQuery(this);
                    formdata[elename] = eleval;

                    if (that.jQuery(this).hasClass('required') && eleval == "") {
                        required_fields = true;
                        that.jQuery(this).addClass('required-error');
                        that.jQuery(this).closest('.parent-class').find('.invalid-feedback').show();
                        errordata['errors'] = that.jQuery(this).closest('.parent-class').find('.invalid-feedback').attr('[value]');
                    }
                    if (elename == 'email' && eleval != "") {
                        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(eleval)) {
                            that.jQuery(this).removeClass('required-error');
                            
                            that._APICall(
                                that._prepareRequest(
                                    "userEditemailvalidate",//dev01.admin@elearnified.com
                                    {
                                        email: eleval
                                    }
                                    ),
                                    function (result) {
                                        console.log('result-  -----', result);
                                        if (result.data.status == "1") {
                                              required_fields = true;
                                            $(thiselv).addClass('required-error').closest('.parent-class').find('.invalid-feedback').html(result.data.email).show();
                                    } else {
                                        $(thiselv).removeClass('required-error').closest('.parent-class').find('.invalid-feedback').hide();
                                    }
                                }
                            )

                        } else {
                            required_fields = true;
                            that.jQuery(this).addClass('required-error');
                            that.jQuery(this).closest('.parent-class').find('.invalid-feedback').show();

                        }
                    }

                    if (elename == 'oldpassword' && eleval != "") {
                        old_password_flag=true;
                        //  formdata[elename] = eleval;
                        // if (elename==oldpassword.test(eleval)) {
                        that.jQuery(this).removeClass('required-error');


                        that._APICall(
                            that._prepareRequest(
                                "useroldPasswordvalidate",//User-password
                                {
                                    oldpassword: eleval
                                }
                            ),
                            function (result) {
                                if (result.data.status == "1") {
                                     required_fields = true;
                                    $(thiselv).addClass('required-error').closest('.parent-class').find('.invalid-feedback').html(result.data.returnstatus).show();
                                } else {
                                    $(thiselv).removeClass('required-error').closest('.parent-class').find('.invalid-feedback').hide();
                                }
                            }
                        )

                        // } else {
                        //     required_fields = true;
                        //     that.jQuery(this).addClass('required-error');
                        //     that.jQuery(this).closest('.parent-class').find('.invalid-feedback').show();

                        // }
                    }

                    if(old_password_flag){
                        if(elename=='newpassword' && eleval =="" ){
                            required_fields = true;
                            that.jQuery(thiselv).addClass('required-error').closest('.parent-class').find('.invalid-feedback').show();

                        }
                    }
 
                });
                if (required_fields == true) {
                    return false;
                } else {
                    $($addform).find('[name]').removeClass('required-error');
                }
                    
                

                that._APICall(
                    that._prepareRequest(
                        "savepersonalinformation",
                        {
                            formdata,
                        }
                    ),
                    function (result) {
                      
                        if (result.code == 200) {
                                if(result.data.status==1){
                                         that.jQuery('#update-success').modal('show');
                                         that.jQuery('.form_personal_information').find('[name="oldpassword"]').val('');
                                        that.jQuery('.form_personal_information').find('[name="newpassword"]').val('');
                                }
                            


                        } else {
                        
                         

                        }
                    }
                )
            }
        },

        {
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
                    //this.jCall.abort();
                }
                this.jCall = $.ajax({
                    "url": M.cfg.wwwroot + "/local/ecommerce/ajax.php",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": requestdata,
                    beforeSend: function () {
                        console.log("request beforeSend");
                    },
                    success: function (data, textStatus, jqXHR) {

                        if (data.code != 200) {
                            success(data);
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
        t("[personalinformation]").each(function () {
            (new checkoutController).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);