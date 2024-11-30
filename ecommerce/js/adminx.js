console.log("Admin js console.");
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
prromocodeController = function() {
    function t() {
        _classPromocodeCallCheck(this, t)
    }
    return _createpromocodeClass(t, [{
        key: "init",
        value: function(t, e) {
            console.log("promocode-manage initiated"),
        	this.jQuery = $ = t,
        	this.$form = e,
            this.jCall = null,
            this.ajaxHTML = null,
            this.dataTable = null,
            this.saveandnewpromocode = null,
            
            this._initListeners()
        }
    }, {
        key: "_initListeners",
        value: function() {
        	this.$form.append("<div class=\"manageproductmics\"></div>")
        	this.ajaxHTML = this.$form.find(".manageproductmics");
            this.dataTable = this.$form.find(".promocode-manage");
            console.log("_initListeners", M);
            var that = this;
        	this.$form.on("click", "#model_addpromocode", function(e) {
        		that._openPromoCode();
        	}),
           
            this.$form.on("click", "[editpromocode]", function(e) {
                that._openEditpromocode(this);
            }),
            this.$form.on("click", "[viewpromocode]", function(e) {
                that._openViewpromocode(this);
            }),
            this.$form.on("click", "[deletepromocode]", function(e) {
            console.log("deletepromocode", this);
                that._deletepromocode(this);
            }),
            console.log("_initListeners");
        }
    }, {
        key: "_openAddpromocode",
        value: function() {
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "openAddpromocode",
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
    },  {
      key: "_openEditPromocode",
        value: function(element) {
            let id = this.jQuery(element).data("id");
            console.log("id- ", id);
            var that = this;
              this._APICall(
                this._prepareRequest(
                   "openpromocode",
                    { 
                        id:id
                    }     
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
        key: "_openViewPromocode",
        value: function(element) {
            let id = this.jQuery(element).data("id");
            console.log("id- ", id);
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "openViewPromocode",
                    {
                        id:id
                    }
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
        key: "_deletePromocode",
        value: function(element) {
            let productid = this.jQuery(element).data("id");
            console.log("promoid- ", promoid);
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "deletePromocode",
                    {
                        promoid:promoid
                    }
                ),
                function (result) {
                    this.jQuery("[id$='-search']").trigger("keyup");
                    console.log(result);
                    alert(result.data);
                }
            );

        }
    }, {
        key: "_mapaddpromobtns",
        value: function() {
            console.log("_mapaddpromobtns");
            var that = this;
        	this.jQuery("[savepromocode]").on("click", function(e) {
        		that._savepromocode(0);
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
                
                $addform.find("[promoid]").each(function (index) {
                    let elname = that.jQuery(this).attr("promoid");
                    let elval = that.jQuery(this).val();
                    if(elname == "popular"){
                        elval = that.jQuery(this).is(":checked")?1:0;
                        $formdata[elname] = elval;
                  
                    console.log("element", that.jQuery(this));
                    console.log("element val", that.jQuery(this).val());
                };
                
            
            this._APICall(
                this._prepareRequest(
                    "saveNewPromocode",
                    $formdata
                ),
                function (result) {
                    this.jQuery("[id$='-search']").trigger("keyup");
                    if(startnew){
                        that._openAddProduct();
                    } else {
                        that._discardaddnew();
                    }
                }
            );
            console.log("_savePromocod", $formdata);
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
    }, {
        key: "_APICall",
        value: function(requestdata, success) {
            console.log("requestdata- ", requestdata)
            if(this.jCall){
                this.jCall.abort();
            }
            this.jCall = $.ajax({
                "url": M.cfg.wwwroot+"/local/ecommerce/ajax.php",
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
        t("#product-manage").each(function() {
            (new productController).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);