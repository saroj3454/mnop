function _profileimage(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _createprofileimage = function () {
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
    profileimage = function () {
        function t() {
            _profileimage(this, t)
        }
        return _createprofileimage(t, [{
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
                this.$PAGE.on('change','#actual-btn',function(){
                
                 that._user_profile_image(e,this);
                });
               
            }
        },
        {
            key: "_user_profile_image",
            value: function (event,element) {
                var that = this;
                let file=element.files[0];
                var imagename=file.name;	
                var filedata = null;
          if (element.files && element.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        filedata = e.target.result;
                        console.log('data',filedata);
                     that.jQuery('#profile_image').attr('src', filedata);
                       that.jQuery('#profile_image_upload').show();
                        // imagedata.attr('value', e.target.result);
                        that._imagedata(filedata,imagename);
                    }
                    reader.readAsDataURL(element.files[0]);
              }


              
              
            }
        },{
                key: "_imagedata",   // Update data
                value: function (filedata,imagename) {
                     this._APICall(
                    this._prepareRequest(
                        'userchangeprofilephoto',
                        {
                            image : filedata,
                            imagename:imagename

                        }
                    ),
                    function(result){
                        if(result.status != 200){
                        	this.jQuery('#profile_image_upload').hide();
                        	this.jQuery('#profile_image').attr('src', result.data.imageurl);              
                        } else{
                            // this.jQuery('#profile_image').html(result)
                        }
                    }
                );

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
                    this.jCall.abort();
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
        t("[manageprofileimage]").each(function () {
            (new profileimage).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);