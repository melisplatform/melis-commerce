window.loadAssocVariantList = function(d) {
    variantId = activeTabId.split("_")[0];
    d.variantId = variantId;
}




$(function() {


    $("body").on("click", ".btnAssocVAssign", function() {
        var varId = $(this).closest('tr').attr('id');
        var assignToVarId = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisCoreTool.pending(".btnAssocVarAssign");
        melisCommerce.disableAllTabs();
        $.ajax({
            type        : 'POST',
            url         : '/melis/MelisCommerce/MelisComAssociateVariant/assignVariant',
            data		: {assignVariantid : varId, assignToVariantId: assignToVarId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){

            if(data.success) {
                melisHelper.zoneReload(assignToVarId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : assignToVarId});
                melisHelper.zoneReload(assignToVarId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : assignToVarId});
                melisHelper.melisOkNotification(data.textTitle, data.textMessage );
            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
            }
            melisCoreTool.done(".btnAssocVarAssign");
            melisCommerce.enableAllTabs();
            melisCore.flashMessenger();
        }).error(function(){
            alert( translations.tr_meliscore_error_message );
        });
    });

    $("body").on("click", ".removeAssoc", function() {
        var varId = $(this).closest('tr').attr('id');
        var tabVariantId = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisCoreTool.pending(".removeAssociation");
        melisCommerce.disableAllTabs();
        $.ajax({
            type        : 'POST',
            url         : '/melis/MelisCommerce/MelisComAssociateVariant/removeAssociation',
            data		: {assignedVariantId : varId, variantId: tabVariantId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){

            if(data.success) {
                melisHelper.zoneReload(tabVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : tabVariantId});
                melisHelper.zoneReload(tabVariantId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : tabVariantId});
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


    $("body").on("click", ".refreshVarList", function() {
        var varId = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : varId});
    });

    $("body").on("click", ".refreshAssocVarList", function() {
        var varId =$(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : varId});
    });

    $("body").on("click", ".btnAVVV1", function() {
        var productId = $(this).parents("tr").children().eq(2).find("span").data().prodId;
        var sku = $(this).parents("tr").children().eq(3).find("span").data().sku;
        melisCommerce.disableAllTabs();
        var variantId = $(this).closest('tr').attr('id');
        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.enableAllTabs();
    });

    $("body").on("click", ".btnAVVV2", function() {
        var productId = $(this).parents("tr").children().eq(3).find("span").data().prodId;
        var sku = $(this).parents("tr").children().eq(4).find("span").data().sku;
        melisCommerce.disableAllTabs();
        var variantId = $(this).closest('tr').attr('id');
        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.enableAllTabs();
    });


    var curVarId = activeTabId.split("_")[0];
    var assocTableSearch = $("input[type='search'][aria-controls='tableAssocVariantList1_" + curVarId + "']");
    var varTableSearch   = $("input[type='search'][aria-controls='tableAssocVariantList2_" + curVarId + "']");

    assocTableSearch.keyup(function () {
        // Filter on the column (the index) of this element
        //oTable1.fnFilterAll(this.value);
    });
    //("#tableAssocVariantList2_58").fnFilterAll("test");

});