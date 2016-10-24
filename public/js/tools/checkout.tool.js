$(function(){
	// Add event listener for opening and closing details
    $('body').on('click', '.orderCheckoutProduListViewVariant', function () {
        var tr = $(this).closest('tr');
        var row = $orderCheckoutProductListTbl.row( tr );
        var productId = tr.attr("id");
        
        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass("shown");
        }else{
        	var zoneId = productId+"_id_meliscommerce_order_checkout_product_variant_list";
        	var melisKey = "meliscommerce_order_checkout_product_variant_list";
        	var variantContainer = '<div class="checkout-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'"></div>';
        	
            // Open this row
            row.child(variantContainer).show();
            tr.addClass("shown");
            
            $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
            melisHelper.zoneReload(zoneId, melisKey, {productId: productId});
        }
    });
    
    $('body').on('click', '.orderCheckoutVariantAddBasket', function () {
    	var variantId = $(this).data('variantid');
    	
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'var_id',
    		value : variantId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/addBasket",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				var zoneId = "id_meliscommerce_order_checkout_product_bakset";
	        	var melisKey = "meliscommerce_order_checkout_product_bakset";
				melisHelper.zoneReload(zoneId, melisKey);
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
			}
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
		});
    });
    
    $('body').on('change', '.orderBasketVariantQty', function () {
    	var variantId = $(this).data("variantid");
    	var varQty = parseInt($(this).data("quantity"));
    	var varNewQty = parseInt($(this).val());
    	
    	var zoneId = "id_meliscommerce_order_checkout_product_bakset";
    	var melisKey = "meliscommerce_order_checkout_product_bakset";
    	
    	if(varQty < varNewQty){
    		melisHelper.zoneReload(zoneId, melisKey, {action: "add", variantId : variantId, variantQty : varNewQty});
    	}else{
    		melisHelper.zoneReload(zoneId, melisKey, {action: 'deduct', variantId : variantId, variantQty : varNewQty});
    	}
    });
    
    $('body').on("keydown", ".orderBasketVariantQty", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    $('body').on('click', '.qty-plus', function () {
    	var variantId = $(this).data("variantid");
    	
    	variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) + 1;
    	$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    	
    	var zoneId = "id_meliscommerce_order_checkout_product_bakset";
    	var melisKey = "meliscommerce_order_checkout_product_bakset";
		melisHelper.zoneReload(zoneId, melisKey, {action: "add", variantId : variantId, variantQty : variantQty});
    });
    
    $('body').on('click', '.qty-minus', function () {
    	var variantId = $(this).data("variantid");
    	$varQty = $("#"+variantId+"_orderBasketVariantQty").val();
    	
    	if(parseInt($varQty) > 0){
    		
    		variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) - 1;
    		
    		$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    		
    		var zoneId = "id_meliscommerce_order_checkout_product_bakset";
        	var melisKey = "meliscommerce_order_checkout_product_bakset";
    		melisHelper.zoneReload(zoneId, melisKey, {action: 'deduct', variantId : variantId, variantQty : variantQty});
    	}
    	
    	initOrderCheckoutFirstStepBtn();
    });
});

window.initOrderCheckoutFirstStepBtn = function({
	
	
	
	$("#orderCheckoutFirstStepBtn").attr('disabled', true);
});
