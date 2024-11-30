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
                that._loadstate();            
                this.$PAGE.on('change','[allstate]',function(){ 
                     var dataid=$(this).val();
                     var action=$('[action]').val();
                    that._loadcity(dataid,action);
                });

                this.$PAGE.on('change','[action]',function(){ 
                     var action=$(this).val();
                     var stateid=$('[allstate]').val();
                    that._loadcity(stateid,action);
                });
                this.$PAGE.on('click','.examcitystatus',function(){ 
                     var dataid=$(this).attr('exam-city');
                     var name=$(this).attr('exam-name');
                     var action=$('#action').val();
                     if (confirm(name+' are you sure status update ?')) {
                    that._statusupdate(dataid,action);
                     }
                });
                 this.$PAGE.on('click','.districtstatus',function(){ 
                     var dataid=$(this).attr('exam-district');
                     var name=$(this).attr('exam-district-name');
                     var action=$('#action').val();
                     if (confirm(name+' are you sure status update ?')) {
                    that._statusupdate(dataid,action);
                     }
                });
                 this.$PAGE.on('click','.citydelete',function(){ 
                     var dataid=$(this).attr('exam-city');
                     var name=$(this).attr('exam-name-delete');
                     var action=$('#action').val();
                     if (confirm(name+' are you sure status Delete ?')) {
                    that._statusdelete(dataid,action);
                     }
                });
                this.$PAGE.on('click','.editmodal',function(){ 
                    var datascid=$(this).attr('datascid');
                    $('#secondid').val(datascid);
                    that._loadthirdcategory(datascid,$(this).attr('datathid'));
                $('#myModal').modal('show'); 
                }); 
                $("#inputsearch").on("search", function(evt){
                    if($(this).val().length > 0){
                        // the search is being executed
                    }else{
                        // user clicked reset
                        $("#datashow table tbody tr").show();
                    }
                });
                 this.$PAGE.on('keyup','#inputsearch',function(){ 
                    // $("#search").on("keyup", function() {
                    var searchText = $(this).val().toLowerCase();;
                    // console.log(value);
                        $("#datashow table tbody tr").each(function(index) {
                        if($(this).text().toLowerCase().indexOf(searchText) === -1){
                               $(this).hide();
                            }else{
                               $(this).show(); 
                               }  
                           if($('#datashow table tbody tr:not([style*="display: none"]').length ===0){
                             $('#datashow table tbody').append('<tr id="recordnot"><td colspan="2"> Record Not Available</td></tr>');
                           }else{
                            $('#datashow table tbody #recordnot').remove();
                           }


                        })


                    });


                // this.$PAGE.on('change','#actual-btn',function(){
                
                //  that._user_profile_image(e,this);
                // });
               
            }
        },{
                key: "_statusdelete",   // Update data
                value: function (dataid,action) {
                    var that = this;
                       that._APICall(
                                that._prepareRequest(
                                    "statusdelete",
                                    {
                                        dataid:dataid,action:action

                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        $('#sucessmessage').show();
                                         // $('#d'+dataid).addClass(result.data.icon);
                                         $('#row'+dataid).remove();
                                         setInterval(function () { $('#sucessmessage').hide(); }, 1000);
                                            
                                           // console.log(result.data.icon);
                                           console.log('#d'+dataid);
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
                key: "_loadcity",   // Update data
                value: function (stateid,action) {
                    var that = this;

                       that._APICall(
                                that._prepareRequest(
                                    "loadCity",
                                    {
                                        stateid:stateid,action:action

                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                         $('#datashow').html(result.data);
                            
                                           
                                       }
                                }
                            )

                }
            },{
                key: "_loadstate",   // Update data
                value: function (first_id) {
                    var that = this;
                       that._APICall(
                                that._prepareRequest(
                                    "loadState",
                                    {
                                        first_id:first_id
                                       
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                              
                                           // $('#seoid').val(result.data.seoid);
                                           //  $('#seotitle').val(result.data.title);
                                           //  $('#url').val(result.data.slug);
                                           //  $('#seokeywords').val(result.data.keywords);
                                           //  $('#seoauthor').val(result.data.author);
                                           $('[allstate]').html(result.data);
                                           
                                       }
                                }
                            )

                }
            },
        {
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
                key: "_loadthirdslug",   // Update data
                value: function (catid) {
                	var that = this;
                   	   that._APICall(
                                that._prepareRequest(
                                    "thirdcategoryslug",//dev01.admin@elearnified.com
                                    {
                                        catid: catid
                                    }
                                    ),
                                    function (result) {

                                       if (result.status == "1") {
                                        console.log(result.data);
                                            // $('#thirdid').html(result.data.slug);
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
                key: "_loadthirdcategory",   // Update data
                value: function (catid,thirdid) {
                	var that = this;
                   	   that._APICall(
                                that._prepareRequest(
                                    "thirdcategory",//dev01.admin@elearnified.com
                                    {
                                        catid: catid,
                                        thirdid:thirdid


                                    }
                                    ),
                                    function (result) {
                                       if (result.status == "1") {
                                            $('#thirdid').html(result.data);
                                            that._loadthirdslug($('#thirdid').val());   
                                       }
                                }
                            )

                }
            },{
                key: "_loadseodata",   // Update data
                value: function () {
                    $(document).ready(function () {
  $('#example').DataTable();
});
                    

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
        t("[city]").each(function () {
            (new seodashboardalldata).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);



// $(document).ready(function () {
//     
// });