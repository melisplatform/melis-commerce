window.loadAssocVariantList = function(data, tblSettings) {
    // get varaint Id from table data
    var variantId = $("#" + tblSettings.sTableId ).data("variantid");
    
	    data.variantId = variantId;
}

$(function() {
    var $body = $("body");
	
        $body.on("click", ".btnAssocVAssign", function() {
            var $this               = $(this),
                variantId           = $this.data("variantid"),
                productId           = $this.data("productid"),
                parentTable         = $this.parents('.tableAssocVariantList2'),
                tableId             = parentTable.attr("id"),
                currentVariantId    = parentTable.data("variantid"),
                parentContainer     = $this.parents(".variant-assoc-product-variant-list");
            
                melisCommerce.disableAllTabs();
                
                $.ajax({
                    type        : 'POST',
                    url         : '/melis/MelisCommerce/MelisComAssociateVariant/assignVariant',
                    data		: {assignVariantid : variantId, assignToVariantId: currentVariantId},
                    dataType    : 'json',
                    encode		: true
                }).done(function(data) {
                    if ( data.success ) {
                        melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
                        if ( parentContainer.length ) {
                            var zoneId      = parentContainer.attr("id"),
                                melisKey    = parentContainer.attr("data-melisKey"),
                                search      = $("#"+tableId+"_filter input[type='search']").val();
                            
                                melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
                        }
                        
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage );
                    
                    }
                    else {
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
                    }
                    
                    melisCommerce.enableAllTabs();
                    melisCore.flashMessenger();
                }).fail(function() {
                    melisCommerce.enableAllTabs();
                    alert( translations.tr_meliscore_error_message );
                });
        });

        $body.on("click", ".removeAssoc", function() {
            var $this               = $(this),
                varId               = $this.closest('tr').attr('id'),
                parentTable         = $this.parents('.tableAssocVariantList1'),
                currentVariantId    = parentTable.data("variantid"),
                productId           = $this.closest('tr').attr("data-productid");
            
                melisCoreTool.pending(".removeAssociation");
                melisCommerce.disableAllTabs();

                $.ajax({
                    type        : 'POST',
                    url         : '/melis/MelisCommerce/MelisComAssociateVariant/removeAssociation',
                    data		: {assignedVariantId : varId, variantId: currentVariantId},
                    dataType    : 'json',
                    encode		: true
                }).done(function(data) {
                    if ( data.success ) {                        
                        melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
                        
                        var prdVariantsContainer = $("#"+productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants");
                        
                            if ( prdVariantsContainer.length ) {
                                var $this               = $(this),
                                    zoneId              = prdVariantsContainer.attr("id"),
                                    melisKey            = prdVariantsContainer.attr("data-melisKey"),
                                    prdVariantsTableId  = $this.parents('.tableAssocVariantList2').attr("id"),
                                    search              = $("#"+prdVariantsTableId+"_filter input[type='search']").val();

                                    melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
                            }
                        
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                    }
                    else {
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    
                    melisCoreTool.done(".removeAssociation");
                    melisCore.flashMessenger();
                    melisCommerce.enableAllTabs();
                }).fail(function() {
                    alert( translations.tr_meliscore_error_message );
                });
        });

        $body.on("click", ".refreshVarList", function() {
            var $this = $(this),
                varId = $this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');

                melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : varId});
        });

        $body.on("click", ".refreshAssocVarList", function() {
            var $this = $(this),
                varId = $this.closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
                
                melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : varId});
        });

        $body.on("click", ".btnAVVV1", function() {
            var $this       = $(this),
                productId   = $this.parents("tr").children().eq(2).find("span").data().prodId,
                sku         = $this.parents("tr").children().eq(3).find("span").data().sku,
                variantId   = $this.closest('tr').attr('id');

                melisCommerce.disableAllTabs();
                melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
                melisCommerce.enableAllTabs();
        });

        $body.on("click", ".btnAVVV2", function() {
            var $this           = $(this),
                sku             = $this.data("variantsku"),
                productId       = $this.data("productid"),
                productName     = $this.closest("tr").prev("tr").find("td:nth-child(3)").text(),
                prodTabId       = productId+"_id_meliscommerce_products_page",
                navTabsGroup    = "id_meliscommerce_product_list_container",
                variantId       = $this.data("variantid");

            var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_product_list_container']");

                // check whether to open the product's tab and also its equivalent variant's page
                if ( alreadyOpen.length > 0 ) {
                    melisCommerce.disableAllTabs();
                    melisCommerce.openProductPage(productId, productName, navTabsGroup, function() {
                        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId }, prodTabId);
                        melisCommerce.setUniqueId(variantId);
                        melisCommerce.enableAllTabs();
                    });
                }
                else {
                    melisHelper.tabOpen("Products", "icon-shippment", "id_meliscommerce_product_list_container", "meliscommerce_product_list_container", '', navTabsGroup, function() {
                        melisCommerce.disableAllTabs();
                        melisCommerce.openProductPage(productId, productName, navTabsGroup, function() {
                            melisCommerce.setUniqueId(productId);
                            melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId }, prodTabId);
                            melisCommerce.setUniqueId(variantId);
                            melisCommerce.enableAllTabs();
                        });
                    });
                }
        });

        var curVarId            = activeTabId.split("_")[0],
            assocTableSearch    = $("input[type='search'][aria-controls='tableAssocVariantList1_" + curVarId + "']"),
            varTableSearch      = $("input[type='search'][aria-controls='tableAssocVariantList2_" + curVarId + "']");

            assocTableSearch.keyup(function () {
                // Filter on the column (the index) of this element
                //oTable1.fnFilterAll(this.value);
            });
            //("#tableAssocVariantList2_58").fnFilterAll("test");
        
        // This event will create extra row on DataTable as Product Variant List container
        $body.on("click", ".showPrdVariants", function() {
            var $this               = $(this),
                parentTable         = $this.parents('.tableAssocVariantList2'),
                tableId             = parentTable.attr("id"),
                tableInstance       = eval("$"+tableId),
                currentVariantId    = parentTable.data("variantid"),
                tr                  = $this.closest('tr'),
                row                 = tableInstance.row( tr ),            
                productId           = tr.attr("id"); // Getting the product Id from the row Id
            
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass("shown");
                }
                else {
                    // Extra row Attributes
                    var zoneId              = productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants",
                        melisKey            = "meliscommerce_avar_product_variants",
                        // Extra row Container
                        variantContainer    = '<div class="variant-assoc-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'" style="height:50px"></div>',
                        search              = $("#"+tableId+"_filter input[type='search']").val();

                        // Open this row
                        row.child(variantContainer).show();
                        tr.addClass("shown");
                        
                        $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
                        // Reloading the Product variant container,
                        // this process will request to server for variant list depend on ProductId
                        melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
                }
        });

        $body.on("click", ".tabs-label li a", function() {
            var $this   = $(this),
                href    = $this.attr("href"),
                aVarTab = $this.closest("li").data("meliskey");

                if ( aVarTab === 'meliscommerce_avar_tab' ) {
                    $(href).find(".refreshAssocVarList").trigger("click");
                }

                console.log("aVarTab: ", aVarTab);
                console.log("refreshAssocVarList: ", $(href).find(".refreshAssocVarList"));
        });
});