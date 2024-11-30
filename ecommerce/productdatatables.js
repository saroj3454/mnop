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
    return _createProductClass(t, [
/**
 * @param string init \
 * @return array*/
    {
        key: "init",
        value: function(t, e) {
        	this.jQuery = $ = t,
        	this.$form = e,
            this.jCall = null,
            this.ajaxHTML = null,
            this.dataTable = null,
            this.dataTablesearch = null,
            this.dataTableselectall = null,
            this.saveandnewproduct = null,
            this.saveproduct = null,
            this.discardaddnew = null,
            this._initListeners()
        }
    }, 
/**
 * @param string _initListeners \
 * @return array*/
    {
        key: "_initListeners",
        value: function() {
        	this.$form.append("<div class=\"manageproductmics\"></div>")
        	this.ajaxHTML = this.$form.find(".manageproductmics");
            this.dataTable = this.$form.find("#productDatatable");
            this.dataTablesearch = this.$form.find("#datatable-search");
            this.dataTableselectall = this.$form.find("[rowallselected]");
            var that = this;
            this._mapDatatable();
            this.$form.on("keyup", "#datatable-search", function (e) {
                that.dataTable.dataTable().fnFilter(this.value);
            }),
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
                that._confirm(this);
                // that._deleteProduct(this);
            }),
            this.$form.on("click", "#deletemultiproduct", function(e) {
                var allids = [];
                that.dataTable.find("[rowselected]").each(function(){
                    if(that.jQuery(this).prop("checked")){
                        allids.push(that.jQuery(this).data("id"));
                    }                        
                })
                if(allids.length > 0){
                    that._confirmmultidelete(this, allids.join(","));
                } else {
                    alert("Must select atlease 1 product");
                }
                // that._deleteProduct(this);
            }),
            this.$form.on("click", "[rowallselected]", function(e) {
                if(that.jQuery(this).prop("checked")){
                    that.dataTable.find("[rowselected]").prop("checked", true);
                } else {
                    that.dataTable.find("[rowselected]").prop("checked", false);
                }
            }),
            this.$form.on("focus", "[name]", function(e) {
                that.jQuery(this).removeClass("required-error");
            })
        }
    }, 
/**
 * @param string _mapDatatable \
 * @return array*/
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
                "dom": '<"datatable-wrapper"Bt<"pull-left"l><"pull-right"rip>>',
                buttons: [
            {
                extend: 'collection',
                text: '<i class="la la-file-download"></i> <span class="export-label">EXPORT</span>',
                className: 'dttable-exports',
                buttons: [
                    'pdf',
                    'csv',
                    'print',
                    'copy',
                ],
            }
        ],
				'responsive' : 'true',
				'keys' : 'true',
				'serverSide' : 'true',
				// columnDefs: [
				//    { orderable: false, targets: -1 }
				// ],                
                "columns": [
                    {"name": "1", "orderable": false},
                    {"name": "2", "orderable": false},
                    {"name": "3", "orderable": true},
                    {"name": "4", "orderable": true},
                    {"name": "5", "orderable": true},
                    {"name": "6", "orderable": true},
                    {"name": "7", "orderable": false},
                    {"name": "8", "orderable": true},
                    {"name": "9", "orderable": false}
                ],
			});    
            // this.dataTable.buttons( 0, null ).container().prependTo(
            //     ".datatable-controls"
            // );    
		} 
    }, 
/**
 * @param string _openAddProduct \
 * @return array*/
    {
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
    }, 
/**
 * @param string _openstatusmessage \
 * @return array*/
    {
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
/**
 * @param string _addmorfile \
 * @return array*/
    {
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
    }, 
/**
 * @param string _openEditProduct \
 * @return array*/
    {
      key: "_openEditProduct",
        value: function(element) {
            let id = this.jQuery(element).data("id");
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
    }, 
/**
 * @param string _openViewProduct \
 * @return array*/
    {
        key: "_openViewProduct",
        value: function(element) {
            let id = this.jQuery(element).data("id");
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
    }, 
/**
 * @param string _confirmmultidelete \
 * @return array*/
    {
        key: "_confirmmultidelete",
        value: function(element, allids) {
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "confrmationbox",
                    {
                        itemid:allids,
                        heading:"Delete Products",
                        subheading:"Are you sure?",
                        description:"",
                        action:'deletemulti',
                        data:"",
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
    }, 
/**
 * @param string _confirm \
 * @return array*/
    {
        key: "_confirm",
        value: function(element) {
            let productid = this.jQuery(element).data("id");
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "confrmationbox",
                    {
                        itemid:productid,
                        heading:"Delete Product",
                        subheading:"Are you sure?",
                        description:"",
                        action:'delete',
                        data:"",
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
    }, 
/**
 * @param string _confirmed \
 * @return array*/
    {
        key: "_confirmed",
        value: function(element) {
            let confirmationbox = this.jQuery(element).closest(".conformationbox");
            var itemid = confirmationbox.find("[itemid]").val(),
            action = confirmationbox.find("[action]").val(),
            data = confirmationbox.find("[data]").val();
            switch(action) {
                case "delete":
                    this._deleteProduct(itemid);
                    break;
                case "deletemulti":
                    this._deleteProduct(itemid);
                    break;
            }
        }
    }, 
/**
 * @param string _deleteProduct \
 * @return array*/
    {
        key: "_deleteProduct",
        value: function(itemid) {
            var that = this;
            this._APICall(
                this._prepareRequest(
                    "deleteProduct",
                    {
                        productid:itemid
                    }
                ),
                function (result) {
                    this.jQuery(".manageproductmics").html("");
                    that.dataTable.dataTable().fnFilter(that.dataTablesearch.val()); 
                }
            );

        }
    }, 
/**
 * @param string _mapaddprodbtns \
 * @return array*/
    {
        key: "_mapaddprodbtns",
        value: function() {
            if(this.jQuery(".chosen-select").length){
                this.jQuery(".chosen-select").chosen({width: "100%"}); 
            }
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
            this.jQuery("[confirmed]").on("click", function(e) {
                that._confirmed(this); 
            });

        }
    }, 
/**
 * @param string _imagechanged \
 * @return array*/
    {
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
                    fileselector.addClass("active");
                    imgview.attr('src', e.target.result);
                    imagedata.attr('value', e.target.result);
                    that._addmorfile();
                }
                reader.readAsDataURL(element.files[0]);
            }
        }
    }, 
/**
 * @param string _imageremoved \
 * @return array*/
    {
        key: "_imageremoved",
        value: function(event, element) {
            let filemanager = this.jQuery(element).closest("[filemanager]");
            this.jQuery(element).closest(".fileselector_container").remove();
            let totalselector = filemanager.find(".fileselector_container");
            if(!totalselector){
                this._addmorfile();
            }
        //     fileselector.find("[selectedimage]").attr('src', "");   
        //     fileselector.find("[name=\"image\"]").attr('value', "");   
        //     fileselector.removeClass("active");
        }
    },
/**
 * @param string _productDefaultImage \
 * @return array*/
    {
        key: "_productDefaultImage",
        value: function() {
            var that=this;
            let default_image=0;
            let filemanager = this.jQuery("[filemanager]");
            filemanager.find("[name=\"default_image\"]").each(function(item){
                if(that.jQuery(this).prop('checked')){
                    default_image=item
                }else{

                }

            });
            return default_image;
          /*  this.jQuery(element).closest('.product-img').prevAll("[name=" + element.name + "]").length;
           console.log('image-index',default_image_index);
           console.log('element.name',element.name);*/
        //     fileselector.find("[selectedimage]").attr('src', "");   
        //     fileselector.find("[name=\"image\"]").attr('value', "");   
        //     fileselector.removeClass("active");
        }
    }, 
/**
 * @param string _showImage \
 * @return array*/
    {
        key: "_showImage",
        value: function(src, target) {
            var fr=new FileReader();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function(e) { target.src = this.result; };
            src.addEventListener("change",function() {
                // fill fr with image data    
                fr.readAsDataURL(src.files[0]);
            });
        }
        }, 
/**
 * @param string _saveproduct \
 * @return array*/
        {
            key: "_saveproduct",   // Update data
            value: function (startnew) {
                let that = this;
                let $addform = this.jQuery(".add-product-details");
                var $formdata = {};
                $formdata["images"] = [];
                $formdata["imagesname"] = [];
                var required_fields=false;            
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
                    if(that.jQuery(this).hasClass('required') &&  elval==""){
                        required_fields=true;
                        that.jQuery(this).addClass('required-error');
                    }
                });
                if(required_fields==true){
                    return false;
                }else{
                    $addform.find("[name]").removeClass('required-error'); 
                }
                $formdata["image"]=this._productDefaultImage();
            
            this._APICall(
                this._prepareRequest(
                    "saveNewProduct",
                    $formdata
                ),
                function (result) {
                    that.dataTable.dataTable().fnFilter("");                    
                    if(startnew){
                        that.dataTable.dataTable().fnFilter(that.dataTablesearch.val());
                        that._openAddProduct();
                    } else {
                        that.dataTable.dataTable().fnFilter(that.dataTablesearch.val());
                        that._discardaddnew();
                    }
                }
            );
           
        }
    }, 
/**
 * @param string _saveandnewproduct \
 * @return array*/
    {
        key: "_saveandnewproduct",
        value: function() {
        }
    }, 
/**
 * @param string _discardaddnew \
 * @return array*/
    {
        key: "_discardaddnew",
        value: function() {
            this.jQuery(".manageproductmics").html("");
        }
    }, 
/**
 * @param string _prepareRequest \
 * @return array*/
    {
        key: "_prepareRequest",
        value: function(wsfunction, data) {
            if(this.applang){
                data.lang = this.applang;
            }
            try {
                data['sesskey'] = M.cfg.sesskey;    
            } catch(err){
                data = [];
                data['sesskey'] = M.cfg.sesskey;    
            }
            var returndata = {
                "wsfunction":wsfunction,
                "wsargs":data
            }
            // if(this.logintoken){
            //     returndata.wstoken = this.logintoken;
            // }
            return JSON.stringify(returndata);
        }
    }, 
/**
 * @param string _APICall \
 * @return API request */
    {
        key: "_APICall",

        value: function(requestdata, success) {
              var that=this;
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
                    //console.log("request beforeSend");
                    that.jQuery('.preloader').show();
                },
                success: function (data, textStatus, jqXHR) {
                    if(data.code != 200){
                         // displayToast(data.error.title, data.error.message, "error");
                    } else {
                        success(data);
                    }
                },error: function(){
                    that.jQuery('.preloader').hide();
                    return null;
                },complete: function(){
                    that.jQuery('.preloader').hide();
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