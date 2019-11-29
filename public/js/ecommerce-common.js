var pUniqueId = [];
var melisCommerce = (function(window) {
    
    function pendingZoneStart(zoneId) {
        $("#"+zoneId).append('<div id="loader" class="overlay-loader"><img class="loader-icon spinning-cog" src="/MelisCore/assets/images/cog12.svg" data-cog="cog12"></div>');
    }

    function pendingZoneDone(zoneId) {
        $("#"+zoneId+" .loader-icon").removeClass("spinning-cog").addClass("shrinking-cog");
        setTimeout(function() {
            $("#"+zoneId+" #loader").remove();
        }, 500);
    }

    function initTooltipTable() {
        $(".tooltipTable").each(function() {
            var $this = $(this);
                $this.qtip({
                    content: {
                        text: $(this).next('.tooltiptext')
                    },
                    overwrite: false,
                    style: {
                        classes: 'qtip-tipsy qtip-shadow',
                        width: "auto",
                    },
                    hide: {
                        fixed: true,
                        delay: 300,
                        event: "mouseleave"
                    },
                    position: {
                        target: 'mouse',
                        adjust: {
                            mouse: false
                        },
                        my: 'center center',
                        at: 'center center',
                        //container : false,
                    },
    			    /* adjust:{
    		          screen: true,
    			    }, */
                });
        });
    }

    function initTooltipVarTable() {
        $(".tooltipTableVar").each(function() {
            var $this = $(this);

                $this.qtip({
                    content: {
                        text: $(this).next('.tooltiptext')
                    },
                    overwrite: false,
                    style: {
                        classes: 'qtip-tipsy qtip-shadow',
                        width: "auto",
                    },
                    hide: {
                        fixed: true,
                        delay: 300,
                        event: "mouseleave"
                    },
                    position: {
                        target: 'mouse',
                        adjust: {
                            mouse: false,
                        },
                        my: 'center center',
                        at: 'center center',
                    },
                });
        });
    }

    function openProductPage(productId, productName, navTabsGroup, callback) {
        melisHelper.tabOpen(melisCore.escapeHtml(productName), "icon-shippment", productId+"_id_meliscommerce_products_page", "meliscommerce_products_page",  { productId: productId }, navTabsGroup );

        if ( callback && typeof( callback ) === "function" ) {
            callback();
        }
    }

    function closeCurrentProductPage() {
        melisHelper.tabClose(melisCommerce.getCurrentProductId() + "_id_meliscommerce_products_page");
    }

    function getCurrentProductId() {
        return activeTabId.split("_")[0];
    }

    function reloadCurrentProdPage(prodId) {
        var productId = melisCommerce.getCurrentProductId();

            if ( prodId != null ) {
                productId = prodId;
            }

            melisCoreTool.clearForm("productTextTypeForm");
            melisHelper.zoneReload(productId+"_id_meliscommerce_products_page","meliscommerce_products_page", {productId : productId});
    }

    function initTinyMCE() {
        var locale = "";

        if ( melisLangId == "en_EN" ) {
            locale = "en";
        }
        else {
            locale = melisLangId;
        }
        setTimeout(function() {
            tinymce.init({
                mode: "specific_textareas",
                //elements: "textarea",
                editor_selector: "mceEditor",
                language: locale,
                height: 200,
                plugins: [
                    //[contextmenu, textcolor, colorpicker] this plugin is already built in the core editor as of TinyMCE v. 5
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste template'
                ],
                menubar: false,
                toolbar: 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | code',
            });
        }, 1000);
    }


    function initCommerceTable(table, ajaxUrl, columns, filterBarDom, bulkDom) {
        $(table).DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthMenu: [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
            pageLength: 10,
            ajax: {
                url: ajaxUrl,
                type: "POST",
            },
            "order": [[ 1, "asc" ]],
            "columns": columns,
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 },
                { "responsivePriority": 2, "targets": -1 },
            ],
            "language" : {
                url: "/melis/MelisCore/Language/getDataTableTranslations",
            },
			/* "responsive": true, */
            "dom": '<"bulk-action"><"filter-bar fl">rtip',
            "drawCallback": function( settings ) {
                $(this).css('width','100%');
            }
        });
        $(".filter-bar").html(filterBarDom);
        $(".bulk-action").html(bulkDom);
    }

    function postSave(ajaxUrl, ajaxData, successCallBack, errorCallBack) {
        $.ajax({
            type        : 'POST',
            url         : ajaxUrl,
            data		: ajaxData,
            dataType    : 'json',
            encode		: true
        }).done(function(data) {
            successCallBack(data);
        }).fail(function() {
            errorCallBack();
        });
    }

    function getDocFormType() {
        var formType = "";

            formType = $("#"+activeTabId).find("div.ecom-doc-container").data("form-type");

            return formType;
    }

    function setUniqueId(id) {
        if ( id == null ) {
            $.ajax({
                type        : 'POST',
                url         : '/melis/MelisCommerce/MelisComDocument/setUniqueId',
                data        : {id: id},
                dataType    : 'json',
                encode		: true
            }).done(function(data){
                pUniqueId[activeTabId] = data.id;
            }).fail(function() {
                alert( translations.tr_meliscore_error_message );
            });
        }
        else {
            pUniqueId[activeTabId] = id;
        }
    }

    function getUniqueId() {
        return pUniqueId[activeTabId];
    }

    function disableTab(tabId) {
        $("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','none').parent().css("cursor", "not-allowed");
    }

    function enableTab(tabId) {
        $("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','auto').parent().css("cursor", "pointer");
    }

    function disableAllTabs() {
        $.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
            var tabId = $(v).data("id");

                disableTab(tabId);
        });

        // disable navigation too
        $.each($("ul.sideMenu"), function(i ,v) {
            $(v).css('pointer-events','none').parent().css("cursor", "not-allowed");
        });
    }

    function enableAllTabs() {
        $.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
            var tabId = $(v).data("id");

                enableTab(tabId);
        });

        $.each($("ul.sideMenu"), function(i ,v) {
            $(v).css('pointer-events','none').css('pointer-events','auto').parent().css("cursor", "pointer");
        });
    }

    function accordionToggle(event) {
        var target  = event.target,
            id      = $(target).attr("href");

            $(id).collapse('toggle');
    }

    //order-checkout-steps
    function switchOrderTab( tabId ) {
        var $tab        = $(tabId),
            $navTab     = $(tabId+"[data-toggle='tab']"),
            $navTabLi   = $navTab.closest("li");

            // tab content
            if ( tabId === 'id_meliscommerce_order_checkout_payment_step_nav' ) {
                var paymentTab = $(tabId).attr("href");
                    $(paymentTab).tab("show");
            } 
            else {
                $tab.tab("show");
            }

            // tabsbar
            $navTab.removeClass("hidden");
            $navTabLi.siblings().removeClass("active");
            $navTabLi.addClass("active");
    }

    return {
        pendingZoneStart        : pendingZoneStart,
        pendingZoneDone         : pendingZoneDone,
        initTooltipTable        : initTooltipTable,
        initTooltipVarTable     : initTooltipVarTable,
        initCommerceTable       : initCommerceTable,
        openProductPage         : openProductPage,
        postSave                : postSave,
        getCurrentProductId     : getCurrentProductId,
        closeCurrentProductPage : closeCurrentProductPage,
        reloadCurrentProdPage   : reloadCurrentProdPage,
        initTinyMCE             : initTinyMCE,
        getDocFormType          : getDocFormType,
        setUniqueId             : setUniqueId,
        getUniqueId             : getUniqueId,
        enableTab               : enableTab,
        disableTab              : disableTab,
        disableAllTabs          : disableAllTabs,
        enableAllTabs           : enableAllTabs,
        accordionToggle         : accordionToggle,
        switchOrderTab          : switchOrderTab
    }

})(window);

setInterval(function() {
    melisCommerce.enableAllTabs();
}, 10000);

$(function() {
    var $body = $('body');
        /* 
         * Triggers accordion toggle manually data-target="#1_accordion" not triggering
         * same goes to modal
         * https://github.com/twbs/bootstrap/issues/29129
         */
        
        $body.on("click", ".accordion-toggle", melisCommerce.accordionToggle );

        /**
         * Export modal checkbox
         */
        $body.on("click", ".cb-cont input[type=checkbox]", function() {
            $(this).parent().find(".cbmask-inner").toggleClass("cb-active");
        });
});