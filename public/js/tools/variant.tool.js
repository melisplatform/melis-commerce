	
$(document).ready(function() {
	
	var body = $("body");
	
	body.on("click", ".editvariant", function(){
		var variantId   = $(this).closest('tr').attr('id');
		var variantName = $(this).closest('tr').find("td a").data("variantname");
		var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
		var prodTabId   = productId+"_id_meliscommerce_products_page";
		melisCommerce.disableAllTabs();
		melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
		melisCommerce.setUniqueId(variantId);
		melisCommerce.enableAllTabs();

	});
	
	body.on("click", ".add-variant", function(){
		var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
		melisCoreTool.processing();
		melisHelper.tabOpen(translations.tr_meliscommerce_variant_main_information_sku_new, 'icon-tag-2', 'id_meliscommerce_variants_page', 'meliscommerce_variants_page', { productId : productId, page : 'newvar'});
		melisCommerce.setUniqueId(0);
		melisCoreTool.processDone();
	});
		
	body.on("click", ".save-add-variant", function(){
		var variantPageId   = $(this).closest('.container-level-a').attr('id');
	});
	
	body.on("click", ".country-price-tab li a", function(){
		var textCountry = $(this).data('country');
		$('.country-price-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_prices_pricing);
	});
	
	body.on("click", ".country-stock-tab li a", function(){
		var textCountry = $(this).data('country');
		if(melisLangId == 'fr_FR'){
			$('.country-stock-label').text(translations.tr_meliscommerce_variant_tab_stocks_header + ' ' + textCountry );
		}else{
			$('.country-stock-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_stocks_header);
		}
	});
	
	$('body').on('click', '.productvariant-refresh', function(){
		var prodId = melisCommerce.getCurrentProductId();
		melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
	});
	
	body.on("click", ".deletevariant", function(){
		var del = this;
		var variantId   = $(del).closest('tr').attr('id');		
		var url = 'melis/MelisCommerce/MelisComVariant/deleteVariant';
		var dataString = [];
		dataString.push({
			name : 'var_id',
			value: variantId,
		});
		melisCoreTool.pending(del);
		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			translations.tr_meliscommerce_variants_delete_title, 
			translations.tr_meliscommerce_variants_delete_confirm,
			function(){
				melisCommerce.postSave(url, dataString, function(data){
					if(data.success){				
						melisHelper.melisOkNotification( data.textTitle, data.textMessage );
						melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});
						melisHelper.tabClose(  variantId + "_id_meliscommerce_variants_page");
					}else{
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);				
					}		
					melisCore.flashMessenger();	
				}, function(data){
					console.log(data);
				})
			
			});
		melisCoreTool.done(del);
		
	});
	
	body.on("click", ".save-variant", function(){
		melisCoreTool.pending(".save-variant");
		var url = 'melis/MelisCommerce/MelisComVariant/saveVariant';
		var prodId = $(this).closest('.container-level-a').data("prodid");
		var id = $(this).closest('.container-level-a').attr('id');
		var varId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		var fixVarId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10)+'_';
		var forms = $(this).closest('.container-level-a').find('form');
		var dataString = [];
		var len;
		var ctr;
		ctr = 0;
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
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
		
		$('#'+id).find('.make-switch div').each(function(){
			var field = 'variant[0]['+$(this).find('input').attr('name')+']';
			var status = $(this).hasClass('switch-on');
			var saveStatus = 0;
			if(status) {
				saveStatus = 1;
			}
			dataString.push({
				name : field,
				value: saveStatus
			})
		});
		
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){
				melisHelper.tabClose(  fixVarId + "id_meliscommerce_variants_page");
				melisHelper.tabOpen(data.chunk.varSku, 'icon-tag-2', data.chunk.variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : data.chunk.variantId, productId : prodId});
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
				melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
				melisCommerce.setUniqueId(data.chunk.variantId);
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);	
				var target = 'id_meliscommerce_variant_content';
				if(data.chunk.variantId){
					target = data.chunk.variantId + 'id_meliscommerce_variant_content'
				}
				
				melisCoreTool.highlightErrors(0, data.errors, target);
			}	
			melisCoreTool.done(".save-variant");
			melisCore.flashMessenger();
		}, function(data){
			console.log(data);
		});
		
	})	
	
	$("body").on("mouseenter mouseleave", ".toolTipVarHoverEvent", function(e) {
		
		  var variantId = $(this).data("variantid");
		  var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
		  var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
		  $.each($("table#variantTable"+variantId + " thead").nextAll(), function(i,v) {
			  $(v).remove();
		  });
		  $(loaderText).insertAfter("table#variantTable"+variantId + " thead");
			var xhr = $.ajax({
		        type        : 'POST', 
		        url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
		        data		: {variantId : variantId, productId : productId},
		        dataType    : 'json',
		        encode		: true,
		     }).success(function(data){
	    	 	 $("div.qtipLoader").remove();
			     if(data.content.length === 0) {
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

		     });
			if(e.type === "mouseout") {
				xhr.abort();
			}
	  });
	
});
//variant list table in product page
window.initProductVariant = function(data, tblSettings) {
	var prodId = $("#" + tblSettings.sTableId ).data("prodid");
	data.prodId = prodId	
}
window.variantLoaded = function() {
	var productId = $(".tab-pane#" + activeTabId).data("prodid");
	var prodTabId   = productId+"_id_meliscommerce_products_page";
	melisCommerce.enableTab(prodTabId);
}