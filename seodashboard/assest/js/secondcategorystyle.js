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
                that._loadseodata();
                // that._loadthirdcategory($('#secondid').val());
                this.$PAGE.on('change','[filtercategory]',function(){
                window.location.href=  M.cfg.wwwroot + "/local/seodashboard/secondcategory.php?id="+$(this).val();         
                }); 
    //              this.$PAGE.on('change','[filtersubcategory]',function(){
    // window.location.href= M.cfg.wwwroot + "/local/seodashboard/allseodata.php?id="+that._loadurlparameter('id')+"&cid="+$(this).val();         
    //             }); 

    //         this.$PAGE.on('change','[filterthirdcategory]',function(){
    // window.location.href= M.cfg.wwwroot + "/local/seodashboard/allseodata.php?id="+that._loadurlparameter('id')+"&cid="+that._loadurlparameter('cid')+"&thid="+$(this).val();         
    //             }); 
                 
                    that._loadsecondcategory($('#secondid').val());   
                this.$PAGE.on('change','#secondid',function(){ 
                that._loadsecondcategory($(this).val());    
                }); 
                this.$PAGE.on('change','#thirdid',function(){ 
                that._loadthirdslug($(this).val());    
                });
                
                // this.$PAGE.on('click','.seostatus',function(){ 
                //      var dataid=$(this).attr('dataid');
                //      $("#seseostatus"+dataid).removeClass('fa-eye');
                //      $("#seseostatus"+dataid).addClass();

                // }); 

                this.$PAGE.on('click','.editmodal',function(){ 
                    var datascid=$(this).attr('dataid');
                    
                    $('#secondid').val(datascid);
                    that._loadsecondcategory(datascid);
                    // alert($(this).attr('datathid'));
                    //$('#thirdid').val();
                    // $('#thirdid').val($(this).attr('datathid'));
                $('#myModal').modal('show'); 
                }); 

                // this.$PAGE.on('change','#actual-btn',function(){
                
                //  that._user_profile_image(e,this);
                // });
               
            }
        },{
                key: "_loadurlparameter",   // Update data
                value: function (sParam) {
                    var sPageURL = window.location.search.substring(1),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;
                    for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');
                        if (sParameterName[0] === sParam) {
                            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                        }
                    }
                    return false;
                }
        },{
                key: "_loadsecondcategory",   // Update data
                value: function (catid) {
                	var that = this;
                   	   that._APICall(
                                that._prepareRequest(
                                    "secondcategorycslug",//dev01.admin@elearnified.com
                                    {
                                        catid: catid
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        console.log(result.data);
                                            $('#thirdid').html(result.data.slug);
                                            $('#seoid').val(result.data.seoid);
                                            $('#seotitle').val(result.data.title);
                                            $('#url').val(result.data.slug);
                                            $('#seokeywords').val(result.data.keywords);
                                            $('#seoauthor').val(result.data.author);
                                            $('#seodescription').html(result.data.description);
                                       }
                                }
                            )

                }
            },{
                key: "_loadseodata",   // Update data
                value: function (alldata) {
                    $('#example').DataTable();

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
        t("[secondcategory]").each(function () {
            (new seodashboardalldata).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);



// $(document).ready(function () {
//     
// });