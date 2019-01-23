window.loadAssocVariantList = function(data, tblSettings) {
    // get varaint Id from table data
	var variantId = $("#" + tblSettings.sTableId ).data("variantid");
	data.variantId = variantId;
}

$(function() {

    var body = $("body");
	
    body.on("click", ".btnAssocVAssign", function() {
    	var variantId = $(this).data("variantid");
    	var productId = $(this).data("productid");
    	var parentTable = $(this).parents('.tableAssocVariantList2');
    	var tableId = parentTable.attr("id");
    	var currentVariantId = parentTable.data("variantid");
    	var parentContainer = $(this).parents(".variant-assoc-product-variant-list");
    	
    	melisCommerce.disableAllTabs();
    	
    	$.ajax({
		    type        : 'POST',
		    url         : '/melis/MelisCommerce/MelisComAssociateVariant/assignVariant',
		    data		: {assignVariantid : variantId, assignToVariantId: currentVariantId},
		    dataType    : 'json',
		    encode		: true,
		}).success(function(data){
			
		    if(data.success) {
		        melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
		        if(parentContainer.length){
		    		var zoneId = parentContainer.attr("id");
		    		var melisKey = parentContainer.attr("data-melisKey");
		    		var search = $("#"+tableId+"_filter input[type='search']").val();
		    		
		        	melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
		    	}
		        
		        melisHelper.melisOkNotification(data.textTitle, data.textMessage );
		    
		    }else{
		        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
		    }
		    
		    melisCommerce.enableAllTabs();
		    melisCore.flashMessenger();
		    
		}).error(function(){
			melisCommerce.enableAllTabs();
		    alert( translations.tr_meliscore_error_message );
		});
    });

    body.on("click", ".removeAssoc", function() {
        var varId = $(this).closest('tr').attr('id');
        var parentTable = $(this).parents('.tableAssocVariantList1');
        var currentVariantId = parentTable.data("variantid");
        var productId = $(this).closest('tr').attr("data-productid");
         
        melisCoreTool.pending(".removeAssociation");
        melisCommerce.disableAllTabs();
        $.ajax({
            type        : 'POST',
            url         : '/melis/MelisCommerce/MelisComAssociateVariant/removeAssociation',
            data		: {assignedVariantId : varId, variantId: currentVariantId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){

            if(data.success) {
            	
                melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
                
                var prdVariantsContainer = $("#"+productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants");
                
                if(prdVariantsContainer.length){
		    		var zoneId = prdVariantsContainer.attr("id");
		    		var melisKey = prdVariantsContainer.attr("data-melisKey");
		    		var prdVariantsTableId = $(this).parents('.tableAssocVariantList2').attr("id");
		    		var search = $("#"+prdVariantsTableId+"_filter input[type='search']").val();
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
        }).error(function(){
            alert( translations.tr_meliscore_error_message );
        });
    });


    body.on("click", ".refreshVarList", function() {
        var varId = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : varId});
    });

    body.on("click", ".refreshAssocVarList", function() {
        var varId =$(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : varId});
    });

    body.on("click", ".btnAVVV1", function() {
        var productId = $(this).parents("tr").children().eq(2).find("span").data().prodId;
        var sku = $(this).parents("tr").children().eq(3).find("span").data().sku;
        melisCommerce.disableAllTabs();
        var variantId = $(this).closest('tr').attr('id');
        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.enableAllTabs();
    });

    body.on("click", ".btnAVVV2", function() {
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
        } else {
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

    var curVarId = activeTabId.split("_")[0];
    var assocTableSearch = $("input[type='search'][aria-controls='tableAssocVariantList1_" + curVarId + "']");
    var varTableSearch   = $("input[type='search'][aria-controls='tableAssocVariantList2_" + curVarId + "']");

    assocTableSearch.keyup(function () {
        // Filter on the column (the index) of this element
        //oTable1.fnFilterAll(this.value);
    });
    //("#tableAssocVariantList2_58").fnFilterAll("test");
    
    
    // This event will create extra row on DataTable as Product Variant List container
    body.on('click', '.showPrdVariants', function () {
    	
    	var parentTable = $(this).parents('.tableAssocVariantList2');
    	var tableId = parentTable.attr("id");
    	var tableInstance = eval("$"+tableId);
    	var currentVariantId = parentTable.data("variantid");
    	
        var tr = $(this).closest('tr');
        var row = tableInstance.row( tr );
        
        // Getting the product Id from the row Id
        var productId = tr.attr("id");
        
        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass("shown");
        }else{
        	// Extra row Attributes
        	var zoneId = productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants";
        	var melisKey = "meliscommerce_avar_product_variants";
        	// Extra row Container
        	var variantContainer = '<div class="variant-assoc-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'" style="height:50px"></div>';
            // Open this row
            row.child(variantContainer).show();
            tr.addClass("shown");
            
            var search = $("#"+tableId+"_filter input[type='search']").val();
            
            $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
            // Reloading the Product variant container,
            // this process will request to server for variant list depend on ProductId
            melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
        }
    });

});