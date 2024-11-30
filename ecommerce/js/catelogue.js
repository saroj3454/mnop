console.log("Catelogue js console.");
function _classCallCheck(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
}
var _createClass = function () {
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
    catelogueController = function () {
        function t() {
            _classCallCheck(this, t)
        }
        return _createClass(t, [{
            key: "init",
            value: function (t, e) {
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
            value: function () {
                this.$form.append("<div class=\"manageproductmics\"></div>")
                this.ajaxHTML = this.$form.find(".manageproductmics");
                this.$form.on("keyup", "[categogesearch]", function (e) {
                    console.log("aaaaaa");
                }),

                    console.log("_initListeners");
            }
        },
        {
            key: "_openViewPopup",
            value: function (element) {
                let id = this.jQuery(element).data("id");
                console.log("id- ", id);
                var that = this;
                this._APICall(
                    this._prepareRequest(
                        // "/var/www/html/elearnified1/blocks/elearnified_catalogue/templates/popdetail.mustache",
                        "openViewPopup",
                        {
                            id: id
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
            key: "_prepareRequest",
            value: function (wsfunction, data) {
                if (this.applang) {
                    data.lang = this.applang;
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
                console.log("requestdata- ", requestdata)
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
                        console.log("request beforeSend");
                    },
                    success: function (data, textStatus, jqXHR) {
                        console.log("data- ", data)
                        console.log("textStatus- ", textStatus)
                        console.log("request success");
                        if (data.code != 200) {
                            // displayToast(data.error.title, data.error.message, "error");
                        } else {
                            success(data);
                        }
                    }, error: function () {
                        console.log("request error");
                        return null;
                    }, complete: function () {
                        console.log("request complete");
                    }
                });
            }
        }]), t
    }();
!function (t) {
    t(function () {
        t("#page-product-catalog").each(function () {
            (new catelogueController).init(t, t(this))
        }), window.errors && window.errors.length && e.showMessage("Please correct the following errors:", window.errors)
    })
}(jQuery);
