console.log("Admin js console.");
function _classProductCallCheck(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _createProductClass = function() {
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
productController = function() {
    function t() {
        _classProductCallCheck(this, t)
    }
    return _createProductClass(t, [{
        key: "init",
        value: function(t, e) {
            console.log("product-manage initiated"),
        	this.jQuery = $ = t,
        	this.$form = e,
            this.jCall = null,
            this.ajaxHTML = null,
            this.dataTable = null,
            this.saveandnewproduct = null,
            this.saveproduct = null,
            this.discardaddnew = null,
            this._initListeners()
        }
    }, {
        key: "_initListeners",
        value: function() {
        	this.$form.append("<div class=\"manageproductmics\"></div>")
        	this.ajaxHTML = this.$form.find(".manageproductmics");
            this.dataTable = this.$form.find(".product-manage");
            console.log("_initListeners", M);
            var that = this;
        	this.$form.on("click", "#model_addproduct", function(e) {
        		that._openAddProduct();
        	}),
            this.$form.on("click", "#model_statusmessage", function(e) {
                that._openstatusmessage(this);
            }),
            this.$form.on("click", "[editproduct]", function(e) {
                that._openEditProduct(this);
            }),
            this.$form.on("click", "[viewproduct]", function(e) {
                that._openViewProduct(this);
            }),
            this.$form.on("click", "[deleteproduct]", function(e) {
            console.log("deleteproduct", this);
                that._deleteProduct(this);
            }),
            console.log("_initListeners");
        }
    }, {
        key: "_openAddProduct",
        value: function() {
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "openAddProduct",
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
    }, {
        key: "_addmorfile",
        value: function(element) {
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "addmorfile",
                    {}
                ),
                function (result) {
                    let filemanager = this.jQuery("[filemanager]");
                    filemanager.append(result.data),
                    that.jQuery(".fileselector [type=\"file\"]").on("change", function(e) {
                        that._imagechanged(e, this);
                    }),
                    that.jQuery(".fileselector .close").on("click", function(e) {
                        that._imageremoved(e, this);
                    });
                }
            );
        }
    }, {
      key: "_openEditProduct",
        value: function(element) {
            let id = this.jQuery(element).data("id");
            console.log("id- ", id);
            var that = this;
              this._APICall(
                this._prepareRequest(
                   "openAddProduct",
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
        key: "_openViewProduct",
        value: function(element) {
            let id = this.jQuery(element).data("id");
            console.log("id- ", id);
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "openViewProduct",
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
        key: "_deleteProduct",
        value: function(element) {
            let productid = this.jQuery(element).data("id");
            console.log("productid- ", productid);
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "deleteProduct",
                    {
                        productid:productid
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
        key: "_mapaddprodbtns",
        value: function() {
            console.log("_mapaddprodbtns");
            var that = this;
        	this.jQuery("[saveproduct]").on("click", function(e) {
        		that._saveproduct(0);
        	}),
            this.jQuery(".fileselector [type=\"file\"]").on("change", function(e) {
                that._imagechanged(e, this);
            }),
            this.jQuery(".fileselector .close").on("click", function(e) {
                that._imageremoved(e, this);
            }),
            this.jQuery("[addmorfile]").on("click", function(e) {
                that._addmorfile(e, this);
            }),
        	this.jQuery("[saveandnewproduct]").on("click", function(e) {
        		that._saveproduct(1);
        	}),
        	this.jQuery("[discardaddnew]").on("click", function(e) {
        		that._discardaddnew();
        	}),
            console.log("_mapaddprodbtns");

        }
    }, {
        key: "_imagechanged",
        value: function(event, element) {
            var that = this;
            let fileselector = this.jQuery(element).closest(".fileselector");
            var imgview = fileselector.find("[selectedimage]");    
            var imagedata = fileselector.find("[name=\"images\"]");    
            var imagesname = fileselector.find("[name=\"imagesname\"]");    
            if (element.files && element.files[0]) {
                imagesname.attr('value', element.files[0].name);
                var reader = new FileReader();
                reader.onload = function (e) {
                    console.log("reader.onload: ", e);
                    fileselector.addClass("active");
                    imgview.attr('src', e.target.result);
                    imagedata.attr('value', e.target.result);
                    that._addmorfile();
                }
                reader.readAsDataURL(element.files[0]);
            }
        }
    }, {
        key: "_imageremoved",
        value: function(event, element) {
            let filemanager = this.jQuery(element).closest("[filemanager]");
            this.jQuery(element).closest(".fileselector_container").remove();
            let totalselector = filemanager.find(".fileselector_container");
            console.log("totalselector- ", totalselector)
            if(!totalselector){
                this._addmorfile();
            }
        //     fileselector.find("[selectedimage]").attr('src', "");   
        //     fileselector.find("[name=\"image\"]").attr('value', "");   
        //     fileselector.removeClass("active");
        }
    }, {
        key: "_showImage",
        value: function(src, target) {
            var fr=new FileReader();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function(e) { target.src = this.result; };
            src.addEventListener("change",function() {
                // fill fr with image data    
                fr.readAsDataURL(src.files[0]);
            });
            console.log("_imagechanged");
        }
        }, {
            key: "_saveproduct",   // Update data
            value: function (startnew) {
                let that = this;
                let $addform = this.jQuery(".add-product-details");
                var $formdata = {};
                $formdata["images"] = [];
                $formdata["imagesname"] = [];
                $addform.find("[name]").each(function (index) {
                    let elname = that.jQuery(this).attr("name");
                    let elval = that.jQuery(this).val();
                    if(elname == "popular"){
                        elval = that.jQuery(this).is(":checked")?1:0;
                        $formdata[elname] = elval;
                    } else if (elname == "images" || elname == "imagesname") {
                        $formdata[elname].push(elval);
                    } else {
                        $formdata[elname] = elval;
                    }
                    console.log("element", that.jQuery(this));
                    console.log("element val", that.jQuery(this).val());
                });
                
            
            this._APICall(
                this._prepareRequest(
                    "saveNewProduct",
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
            console.log("_saveproduct", $formdata);
        }
    }, {
        key: "_saveandnewproduct",
        value: function() {
            console.log("_saveandnewproduct");
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