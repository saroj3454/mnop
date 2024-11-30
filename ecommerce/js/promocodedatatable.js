console.log("Promocode js console.");
function _classPromocodeCallCheck(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _createPromocodeClass = function() {
    function t(t, e) {
        for (var s = 0; s < e.length; s++) {
            var n = e[s];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }
    return function(e, s, n) {
        return s && t(e.prototype, s), n && t(e, n), e
    }
}(),
promocodeController = function() {
    function t() {
        _classPromocodeCallCheck(this, t)
    }
    return _createPromocodeClass(t, [{
        key: "init",
        value: function(t, e) {
            console.log("promocode-manage initiated"),
            this.jQuery = $ = t,
            this.$form = e,
            this.jCall = null,
            this.ajaxHTML = null,
            this.dataTable = null,
            this._initListeners()
        }
    }, {
        key: "_initListeners",
        value: function() {
    // this.$form.append("<div class=\"manageproductmics\"></div>")
    //         this.ajaxHTML = this.$form.find(".manageproductmics");
    //         this.dataTable = this.$form.find("#productDatatable");
    //         console.log("_initListeners", M);
      //        var that = this;
      // this._mapDatatable();
    //         this.$form.on("keyup", "#datatable-search", function (e) {
    //             that.dataTable.dataTable().fnFilter(this.value);
    //         }),
    //         this.$form.on("click", "#model_addproduct", function(e) {
    //             that._openAddProduct();
    //         }),

             var that = this;
            this.$form.append("<div class=\"manageproductmics\"></div>");
            this.ajaxHTML = this.$form.find(".manageproductmics");
            this.dataTable = this.$form.find("#promocodeDatatable");
            this._mapDatatable();
            this.$form.on("keyup", "#datatable-search", function (e) {
                that.dataTable.dataTable().fnFilter(this.value);
            }),

             this.$form.on("click", "#model_addpromocode", function(e) {
                that._openAddPromoCode();
            }),
            console.log("_initListeners");
        }
    }, {
        key: "_mapDatatable",
        value: function(wsfunction, data) {
            var options = {};
            try{
                if( this.dataTable.data('options') ){
                    options = this.dataTable.data('options');
                    options.sesskey = M.cfg.sesskey;
                }
            }catch (err){
                console.log(err);
            }
            this.dataTable.DataTable({
                'ajax' : {
                    'url' : M.cfg.wwwroot + '/blocks/elearnified_datatables_1/ajax.php',
                    'type' : 'GET',
                    'cache' : 'true',
                    'data' : options,
                },
                'responsive' : 'true',
                'keys' : 'true',
                'serverSide' : 'true',
                columnDefs: [
                   { orderable: false, targets: -1 }
                ],
                "dom": '<"datatable-wrapper"t<"pull-left"l><"pull-right"p>>',
            });        
        }    }, {  
        key: "_openAddPromoCode",
        value: function() {
            var that = this;
            this._APICall(
                this._prepareRequest(

                    "openAddPromoCode",
                    {}
                ),
                function (result) {
                    this.ajaxHTML = this.jQuery(".manageproductmics");
                    this.ajaxHTML.html(result.data);
                    var addprod = this.jQuery(".manageproductmics").find(".modal").modal();
                     that._mapaddprodbtns()
                }
            );
        }
    }, {
        key: "_openstatusmessage",
        value: function(element) {
            var that = this;
            let template = this.jQuery(element).data("template");
            let status = this.jQuery(element).data("status");
            this._APICall(
                this._prepareRequest(
                    "openstatusmessage",
                    {
                        template:template,
                        status:status
                    }
                ),
                function (result) {
                    this.ajaxHTML = this.jQuery(".manageproductmics");
                    this.ajaxHTML.append(result.data);
                    this.jQuery(".manageproductmics").find(".eventstatus").modal();
                }
            );
        }
    },

     {
        key: "_mapDatatable",
        value: function(wsfunction, data) {
            var options = {};
            try{
                if( this.dataTable.data('options') ){
                    options = this.dataTable.data('options');
                    options.sesskey = M.cfg.sesskey;
                }
            }catch (err){
                console.log(err);
            }
            this.dataTable.DataTable({
                'ajax' : {
                    'url' : M.cfg.wwwroot + '/blocks/elearnified_datatables_1/ajax.php',
                    'type' : 'GET',
                    'cache' : 'true',
                    'data' : options,
                },
                'responsive' : 'true',
                'keys' : 'true',
                'serverSide' : 'true',
                columnDefs: [
                   { orderable: false, targets: -1 }
                ],
                "dom": '<"datatable-wrapper"t<"pull-left"l><"pull-right"p>>',
            });        
        }
    }, {
        key: "_prepareRequest",
        value: function(wsfunction, data) {
            if(this.applang){
                data.lang = this.applang;
            }
            var returndata = {
                "wsfunction":wsfunction,
                "wsargs":data
            }
            if(this.logintoken){
                returndata.wstoken = this.logintoken;
            }
            return JSON.stringify(returndata);
        }
    },
    {
        key: "_mapaddprodbtns",
        value: function() {
            console.log("_mapaddprodbtns");
            var that = this;
            this.jQuery("[savepromocode]").on("click", function(e) {
                that._savepromocode(0);
            }),
           
            this.jQuery("[saveandnewpromocode]").on("click", function(e) {
                that._savepromocode(1);
            }),
            this.jQuery("[discardaddnew]").on("click", function(e) {
                that._discardaddnew();
            }),
            console.log("_mapaddprodbtns");

        }
    }, 
      {
            key: "_savepromocode",   // Update data
            value: function (startnew) {
                let that = this;
                let $addform = this.jQuery(".add-promocode-details");
                var $formdata = {};
                $formdata["promoid"] = [""];
                $formdata["type"] = [""];
                 $addform.find("[promoid]").each(function (index){

                     let elpromoid = that.jQuery(this).attr("promoid");
                     let elval = that.jQuery(this).val();
                      if(elpromoid == "popular"){
                        elval = that.jQuery(this).is(":checked")?1:0;
                        $formdata[elpromoid] = elval;
                    //  let elname = that.jQuery(this).attr("type");
                 
              }
                    // let elval = that.jQuery(this).val();
                   

                    console.log("element", that.jQuery(this));
                    console.log("element val", that.jQuery(this).val());
                });
                
            
            this._APICall(
                this._prepareRequest(
                    "saveNewPromocode",
                    $formdata
                ),
                function (result) {
                    this.jQuery("[id$='-search']").trigger("keyup");
                    if(startnew){
                        that._openAddPromoCode();
                    } else {
                        that._discardaddnew();
                    }
                }
            );
            console.log("_savepromocode", $formdata);
        }
    }, {
        key: "_saveandnewpromocode",
        value: function() {
            console.log("_saveandnewpromocode");
        }
    }, {
        key: "_discardaddnew",
        value: function() {
            this.jQuery(".manageproductmics").html("");
            console.log("_discardaddnew");
        }
    }, {
        key: "_prepareRequest",
        value: function(wsfunction, data) {
            if(this.applang){
                data.lang = this.applang;
            }
            var returndata = {
                "wsfunction":wsfunction,
                "wsargs":data
            }
            if(this.logintoken){
                returndata.wstoken = this.logintoken;
            }
            return JSON.stringify(returndata);
        }
    },
     {
        key: "_APICall",
        value: function(requestdata, success) {
            console.log("requestdata- ", requestdata)
            if(this.jCall){
                this.jCall.abort();
            }
            this.jCall = $.ajax({
                "url": M.cfg.wwwroot+"/local/ecommerce/ajaxpromocode.php",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": requestdata,
                beforeSend:function (){
                    console.log("request beforeSend");
                },
                success: function (data, textStatus, jqXHR) {
                    console.log("data- ", data)
                    console.log("textStatus- ", textStatus)
                    console.log("request success");
                    if(data.code != 200){
                         // displayToast(data.error.title, data.error.message, "error");
                    } else {
                        success(data);
                    }
                },error: function(){
                    console.log("request error");
                    return null;
                },complete: function(){
                    console.log("request complete");
                }
            });
        }
    }]), t
}();
!function(t) {
    t(function() {
        t("#promocode-manage").each(function() {
            (new promocodeController).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);

