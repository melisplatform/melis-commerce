$(function() {
    var $body = $("body");

        $body.on("click", ".editvariant", function() {
            var $this       = $(this),
                variantId   = $this.closest('tr').attr('id'),
                variantName = $this.closest('tr').find("td a").data("variantname"),
                productId   = $this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,''),
                prodTabId   = productId+"_id_meliscommerce_products_page";

                melisCommerce.disableAllTabs();
                melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId}, prodTabId);
                melisCommerce.setUniqueId(variantId);
                melisCommerce.enableAllTabs();
        });

        $body.on("click", ".add-variant", function() {
            var $this       = $(this),
                productId   = $this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,''),
                prodTabId   = productId+"_id_meliscommerce_products_page";

                melisCoreTool.processing();
                melisHelper.tabOpen(translations.tr_meliscommerce_variant_main_information_sku_new, 'icon-tag-2', 'id_meliscommerce_variants_page', 'meliscommerce_variants_page', { productId : productId, page : 'newvar'}, prodTabId);
                melisCommerce.setUniqueId(0);
                melisCoreTool.processDone();
        });

        $body.on("click", ".save-add-variant", function() {
            var $this           = $(this),
                variantPageId   = $this.closest('.container-level-a').attr('id');
        });

        $body.on("click", ".country-price-tab li a", function() {
            var $this       = $(this),
                textCountry = $this.data('country'),
                textSymbol  = $this.data('symbol');

                if ( textSymbol != "" ) {
                    $(".cur-symbol").removeClass("fa fa-dollar").text(textSymbol).css("font-weight", "600");
                }
                else {
                    if(!$('.cur-symbol').hasClass("fa")){
                        $(".cur-symbol").empty().addClass("fa fa-dollar").removeAttr("style");
                    }
                }
                
                $('.country-price-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_prices_pricing);
        });

        $body.on("click", ".country-stock-tab li a", function() {
            var $this       = $(this),
                textCountry = $this.data('country');

                if ( melisLangId == 'fr_FR' ) {
                    $('.country-stock-label').text(translations.tr_meliscommerce_variant_tab_stocks_header + ' ' + textCountry );
                }
                else {
                    $('.country-stock-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_stocks_header);
                }
        });

        $body.on('click', '.productvariant-refresh', function() {
            var prodId = melisCommerce.getCurrentProductId();
            
                melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
        });

        $body.on("click", ".deletevariant", function() {
            var del         = this,
                variantId   = $(del).closest('tr').attr('id'),
                url         = 'melis/MelisCommerce/MelisComVariant/deleteVariant',
                dataString  = [];

                dataString.push({
                    name : 'var_id',
                    value: variantId
                });

                melisCoreTool.pending(del);

                melisCoreTool.confirm(
                    translations.tr_meliscommerce_documents_common_label_yes,
                    translations.tr_meliscommerce_documents_common_label_no,
                    translations.tr_meliscommerce_variants_delete_title,
                    translations.tr_meliscommerce_variants_delete_confirm,
                    function() {
                        melisCommerce.postSave(url, dataString, function(data){
                            if ( data.success ) {
                                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                                melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});
                                melisHelper.tabClose(  variantId + "_id_meliscommerce_variants_page");
                            }
                            else {
                                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                            }
                            melisCore.flashMessenger();
                        }, function(data) {
                            console.log(data);
                        })

                    });
                    
                melisCoreTool.done(del);
        });

        $body.on("click", ".save-variant", function(){
            melisCoreTool.pending(".save-variant");

            var $this       = $(this),
                url         = 'melis/MelisCommerce/MelisComVariant/saveVariant',
                prodId      = $this.closest('.container-level-a').data("prodid"),
                prodTabId   = prodId+"_id_meliscommerce_products_page",
                id          = $this.closest('.container-level-a').attr('id'),
                varId       = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10),
                fixVarId    = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10)+'_',
                forms       = $this.closest('.container-level-a').find('form'),
                dataString  = [],
                len,
                ctr;

                ctr = 0;

                forms.each(function() {
                    var $this   = $(this),
                        pre     = $this.attr('name'),
                        data    = $this.serializeArray();

                        len = data.length;

                        for (j=0; j<len; j++ ) {
                            dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
                        }

                        ctr++;
                });

                dataString.push({
                    name: "variantId",
                    value: varId
                });

                dataString = melisCommerceSeo.serializeSeo('variant', varId, dataString);

                dataString.push({name : 'variant[0][var_id]', value : varId}, {name : 'variant[0][var_prd_id]', value : prodId})

                $('#'+id).find('.make-switch div').each(function() {
                    var $this       = $(this),
                        field       = 'variant[0]['+$this.find('input').attr('name')+']',
                        status      = $this.hasClass('switch-on'),
                        saveStatus  = 0;
                    
                        if ( status ) {
                            saveStatus = 1;
                        }

                        dataString.push({
                            name : field,
                            value: saveStatus
                        })
                });

                melisCommerce.postSave(url, dataString, function(data) {
                    if ( data.success ) {
                        melisHelper.tabClose(  fixVarId + "id_meliscommerce_variants_page");
                        melisHelper.tabOpen(data.chunk.varSku, 'icon-tag-2', data.chunk.variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : data.chunk.variantId, productId : prodId}, prodTabId);
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
                        melisCommerce.setUniqueId(data.chunk.variantId);
                        melisCore.flashMessenger();
                    }
                    else {
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                        var target = 'id_meliscommerce_variant_content';

                            if ( data.chunk.variantId ) {
                                target = data.chunk.variantId + 'id_meliscommerce_variant_content'
                            }

                            melisCoreTool.highlightErrors(0, data.errors, target);
                    }
                    melisCoreTool.done(".save-variant");
                    melisCore.flashMessenger();
                }, function(data) {
                    console.log(data);
                });
        });

        $body.on("mouseenter mouseleave", ".toolTipVarHoverEvent", function(e) {
            var $this       = $(this),
                variantId   = $this.data("variantid"),
                productId   = $this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,''),
                loaderText  = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';

                $.each($("table#variantTable"+variantId + " thead").nextAll(), function(i,v) {
                    $(v).remove();
                });

                $(loaderText).insertAfter("table#variantTable"+variantId + " thead");

                $.ajax({
                    type        : 'POST',
                    url         : '/melis/MelisCommerce/MelisComProductList/getToolTip',
                    data		: {variantId : variantId, productId : productId},
                    dataType    : 'json',
                    encode		: true
                }).done(function(data) {
                    $("div.qtipLoader").remove();

                    if ( data.content.length === 0 ) {
                        $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
                    }
                    else {
                        // make sure tbody is clear
                        $.each($("table#variantTable"+variantId + " thead").nextAll(), function(i,v) {
                            $(v).remove();
                        });
                        $.each(data.content.reverse(), function(i ,v) {
                            $(v).insertAfter("table#variantTable"+variantId + " thead");
                        });
                    }
                }).fail(function() {
                    alert( translations.tr_meliscore_error_message );
                });
        });

        $body.on("click", ".updateVariantStatus", function() {
            var _this           = $(this),
                btnTitle        = '',
                obj             = {},
                val             = "",
                varIndicator    = "",
                linkClass       = "",
                variantId       = _this.closest('tr').attr('id'),
                varStatus       = _this.closest('tr').attr('var_status'),
                prodId          = _this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');

                if ( varStatus == 0 ) {
                    val = 1;
                    varIndicator = "text-success";
                    linkClass = "btn-danger";
                    btnTitle = translations.tr_meliscommerce_variants_deactivate_status_title;
                }
                else {
                    val = 0;
                    varIndicator = "text-danger";
                    linkClass = "btn-success";
                    btnTitle = translations.tr_meliscommerce_variants_activate_status_title;
                }

                obj.id = variantId;
                obj.var_status = val;

                $.ajax({
                    type: 'POST',
                    url : '/melis/MelisCommerce/MelisComVariantList/updateVariantStatus',
                    data: $.param(obj),
                    beforeSend: function() {
                        //disable the button
                        _this.addClass("disabled").attr("disabled", true);
                        //change icon to loader
                        _this.find(".variant-update-icon-rotate").removeClass(function (index, className) {
                            return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
                        }).addClass("fa-spinner fa-pulse fa-fw");
                    }
                }).done(function(data) {
                    if ( data.success ) {
                        //update var_status on tr
                        _this.closest('tr').attr("var_status", val);
                        //change the original icon
                        _this.find(".variant-update-icon-rotate").removeClass(function (index, className) {
                            return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
                        }).addClass("fa-arrow-circle-up");
                        //update style of the button
                        _this.removeClass(function (index, className) {
                            return (className.match (/(^|\s)btn-\S+/g) || []).join(' ');
                        }).addClass(linkClass);
                        //update the status indicator inside the row
                        _this.closest('tr').find(".var-status-indicator").removeClass(function (index, className) {
                            return (className.match (/(^|\s)text-\S+/g) || []).join(' ');
                        }).addClass(varIndicator);

                        //rotate the icon to indicate the status
                        _this.find(".variant-update-icon-rotate").toggleClass("down");

                        //check if variant is open to update it's status
                        if($("#"+variantId+"_id_meliscommerce_variant_tab_main_header_container").length){
                            melisHelper.zoneReload(variantId+"_id_meliscommerce_variant_tab_main_header_container", "meliscommerce_variant_tab_main_header_container", {"productId" : prodId, "variantId" : variantId});
                        }

                        //change btn title
                        _this.attr("title", btnTitle);
                    }
                    _this.removeClass("disabled").attr("disabled", false);
                }).fail(function() {
                    alert( translations.tr_meliscore_error_message );
                });
        });

        $body.on("click", ".tabs-label li a", function() {
            var $this           = $(this),
                href            = $this.attr("href"),
                tabVariants     = $this.closest("li").data("meliskey"),
                $tabVariants    = $this.closest("li").find("[data-meliskey='meliscommerce_products_page_content_tab_variants']");

                if ( tabVariants === 'meliscommerce_products_page_content_tab_variants' ) {
                    $(href).find(".refresh-attribute-lists").trigger("click");
                }

                console.log("tabVariants: ", tabVariants );
                console.log("refresh: ", $(href).find(".refresh-attribute-lists") );
        });
});

//variant list table in product page
window.initProductVariant = function(data, tblSettings) {
    var prodId = $("#" + tblSettings.sTableId ).data("prodid");
        
        data.prodId = prodId;
};

window.variantLoaded = function() {
    var $this       = $(this),
        productId   = $(".tab-pane#" + activeTabId).data("prodid"),
        prodTabId   = productId+"_id_meliscommerce_products_page";
        
        melisCommerce.enableTab(prodTabId);
};

window.checkVarStatus = function(){
    var productId   = '',
        btnTitle    = '';

        if ( $(".updateVariantStatus").closest('.container-level-a.active').attr('id') != undefined ) {
            productId = $(".updateVariantStatus").closest('.container-level-a.active').attr('id').replace(/[^0-9]/g,'');
        }
        else {
            productId = $(".save-variant").closest('.container-level-a.active').data("prodid");
        }

        $("#"+productId+"_tableProductVariantList tbody tr").each(function() {
            var $this   = $(this),
                status  = $this.attr('var_status');

                if ( status != undefined ) {
                    var $this       = $(this),
                        btnStyle    = "btn-danger",
                        icon        = $this.find(".variant-update-icon-rotate");

                        if ( status == 0 ) {
                            btnStyle = "btn-success";
                            btnTitle = translations.tr_meliscommerce_variants_activate_status_title;
                        }
                        else {
                            icon.toggleClass("down");
                            btnTitle = translations.tr_meliscommerce_variants_deactivate_status_title;
                        }

                        icon.closest("a").removeClass(function (index, className) {
                            return (className.match (/(^|\s)btn-\S+/g) || []).join(' ');
                        }).addClass(btnStyle).attr("title", btnTitle);
                }
        });
};