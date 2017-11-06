$(function(){
	$('body').on("click", ".addNewOrder", function(){
		var navTabsGroup = "id_meliscommerce_order_list_page";
        melisHelper.tabOpen(translations.tr_meliscommerce_order_checkout_title, 'fa fa fa-plus fa-2x', 'id_meliscommerce_order_checkout', 'meliscommerce_order_checkout', '', navTabsGroup);
    });
	// Add event listener for opening and closing details
	// This event will create extra row on DataTable as Product Variant List container
    $('body').on('click', '.orderCheckoutProduListViewVariant', function () {
        var tr = $(this).closest('tr');
        var row = $orderCheckoutProductListTbl.row( tr );
        // Getting the product Id from the row Id
        var productId = tr.attr("id");
        
        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass("shown");
        }else{
        	// Extra row Attributes
        	var zoneId = productId+"_id_meliscommerce_order_checkout_product_variant_list";
        	var melisKey = "meliscommerce_order_checkout_product_variant_list";
        	// Extra row Container
        	var variantContainer = '<div class="checkout-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'"></div>';
        	
            // Open this row
            row.child(variantContainer).show();
            tr.addClass("shown");
            
            $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
            // Reloading the Product variant container,
            // this process will request to server for variant list depend on ProductId
            melisHelper.zoneReload(zoneId, melisKey, {productId: productId});
        }
    });
    
    // Checkout country event
    // This Process will create a Session variable on for Country Id that will use for processing Checkout
    $("body").on("change", "#orderCheckoutCountries", function(){
    	$countryId = $(this).val();
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'countryId',
    		value : $countryId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/orderCheckoutSetCountry",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				var zoneId = "id_meliscommerce_order_checkout_content";
	        	var melisKey = "meliscommerce_order_checkout_content";
				melisHelper.zoneReload(zoneId, melisKey);
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
		});
    });
    
    // Adding variant to Basket List
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
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
		});
    });
    
    // Changing the Quantity by typing the number of the quantity of the variant in Basket List
    $('body').on('change', '.orderBasketVariantQty', function () {
    	var variantId = $(this).data("variantid");
    	var varQty = parseInt($(this).data("quantity"));
    	var variantQty = parseInt($(this).val());
    	// Checking the last Variant qunatity and the New Quantity
    	if(varQty < variantQty){
    		// Adding Variant quantity
    		updateVariantbasket("add", variantId, variantQty);
    	}else{
    		// Deducting Variant quantity
    		updateVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Binding Variant quantity input to Numeric characters only
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
    
    // Variant quantity + (plus) button
    // This action will add 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.qty-plus', function () {
    	var variantId = $(this).data("variantid");
    	variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) + 1;
    	$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    	updateVariantbasket("add", variantId, variantQty);
    });
    
    // Variant quantity - (minus) button
    // This action will deduct 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.qty-minus', function () {
    	var variantId = $(this).data("variantid");
    	$varQty = $("#"+variantId+"_orderBasketVariantQty").val();
    	
    	if(parseInt($varQty) > 0){
    		variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) - 1;
    		$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    		updateVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Checkout First step Next button
    // This action will validate if the basket has Content, else this action will show a message
    $('body').on('click', '.orderCheckoutFirstStepBtn', function () {
    	
    	$(".orderCheckoutFirstStepBtn").button("loading");
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/checkBasket",
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$($(".orderCheckoutFirstStepBtn").data("tabid")).tab("show");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			$(".orderCheckoutFirstStepBtn").button("reset");
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			$(".#orderCheckoutFirstStepBtn").button("reset");
		});
    });
    
    // Next button event
    $('body').on('click', '.orderCheckoutNextStep', function () {
    	var body = $("html, body");
    	body.stop().animate({scrollTop:0}, '500', 'swing');
    });
    
    // Preview button, activating previews step of the checkout steps
    $('body').on('click', '.orderCheckoutPrevStep', function () {
		$($(this).data("tabid")).tab("show");
		
		if($(this).data("tabid") == '#id_meliscommerce_order_checkout_select_addresses_step_nav'){
			// Zone reload Checkout Addresses
			melisHelper.zoneReload('id_meliscommerce_order_checkout_billing_address','meliscommerce_order_checkout_billing_address')
			melisHelper.zoneReload('id_meliscommerce_order_checkout_delivery_address','meliscommerce_order_checkout_delivery_address')
		}
		
		var body = $("html, body");
		body.stop().animate({scrollTop:0}, '500', 'swing');
    });
    
    // Selecting Contact on Checkout Second Step
    $('body').on('click', '.orderCheckoutSelectContact', function () {
    	
    	var btn = $(this);
    	var tr = $(this).closest('tr');
    	// Getting the contactId from the row id attribute
    	var contactId = tr.attr("id");
    	var nxtTabid = $(this).data("tabid");
    	
    	btn.attr('disabled', true);
    	
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'contactId',
    		value : contactId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/selectContact",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				
				$(nxtTabid).tab("show");
				
				setTimeout(function(){ 
					melisHelper.zoneReload('id_meliscommerce_order_checkout_product_bakset', 'meliscommerce_order_checkout_product_bakset');
					melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address");
					melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address");
				}, 300);
				
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    });
    
    // Refresh button for Contact List table
    $('body').on('click', '.orderCheckoutContactListRefresh', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_choose_contact_step_content", "meliscommerce_order_checkout_choose_contact_step_content");
    });
    
    // Selecting Checkout Billing Address
    $('body').on('change', '#orderCheckoutBillingSelect', function () {
    	var clientAddId = $(this).val();
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address", {cadd_id: clientAddId, action: 1});
    });
    
    $("body").on("change", "#order-checkout-billing-address-same", function(){
    	if($(this).is(":checked")){
    		$("#order-checkout-billing-form-zone").addClass("hidden");
    	}else{
    		$("#order-checkout-billing-form-zone").removeClass("hidden");
    	}
    });
    
    // Selecting Checkout Delivery Address
    $('body').on('change', '#orderCheckoutDeliverySelect', function () {
    	var clientAddId = $(this).val();
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address", {cadd_id: clientAddId});
    });
    // Create new Billing Address button by clearing the Form fields for address
    $('body').on('click', '#orderCheckoutCreateBillingAdd', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address", {emptyBillingAddress : 1});
    });
    // Create new Billing Address button by clearing the Form fields for address
    $('body').on('click', '#orderCheckoutCreateDeliveryAdd', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address", {emptyDeliveryAddress : 1});
    });
    // Checkout Addresses validations
    $('body').on('click', '.orderCheckoutValidateAddresses', function () {
    	
    	var btn = $(this);
    	var dataString = new Array;
    	var nxtTabid = $(this).data("tabid");
    	
    	// Serializing Delivery address form
    	$("#id_meliscommerce_order_checkout_delivery_address form").each(function(){
    		var billingAddressFrom = $(this).serializeArray();
    		$.each(billingAddressFrom, function(){
    			dataString.push({
    				name: 'delivery['+this.name+']',
					value: this.value
    			});
    		});
    	});
    	
    	if($('#deliveryAddressOrderCheckoutForm').hasClass('hidden')){
    		dataString.push({
				name: 'delivery[noSelected]',
				value: true
			});
    	}
    	
    	// Serializing Billing address form
    	$("#id_meliscommerce_order_checkout_billing_address form").each(function(){
    		var billingAddressFrom = $(this).serializeArray();
    		$.each(billingAddressFrom, function(){
    			dataString.push({
    				name: 'billing['+this.name+']',
					value: this.value
    			});
    		});
    	});
    	
    	if($('#billingAddressOrderCheckoutForm').hasClass('hidden')){
    		dataString.push({
				name: 'billing[noSelected]',
				value: true
			});
    	}
    	
    	var sameAddress = 0;
    	if($("#order-checkout-billing-address-same").is(":checked")){
    		sameAddress = 1;
    	}
    	
    	dataString.push({
			name: 'billing[sameAddress]',
			value: sameAddress
		});
    	
    	btn.attr('disabled', true);
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/selectAddresses",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$(nxtTabid).tab("show");
				
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_basket','meliscommerce_order_checkout_summary_basket');
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_billing_address','meliscommerce_order_checkout_summary_billing_address');
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_delivery_address','meliscommerce_order_checkout_summary_delivery_address');
				
			}else{
				melisHelper.melisMultiKoNotification(data.textTitle, data.textMessage, data.errors);
				melisHelper.highlightMultiErrors(data.success, data.errors,  activeTabId+" form");
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    	
    });
    
    // validating Coupon code
    $("body").on("click", "#orderCheckoutValidateCoupon", function(){
    	var couponCode = $("#orderCheckoutCouponCode").val();
    	if(couponCode != ''){
    		melisHelper.zoneReload("id_meliscommerce_order_checkout_summary_basket", "meliscommerce_order_checkout_summary_basket", {couponCode: couponCode});
    	}
    });
    
    // Deleting validated coupon
    $("body").on("click", ".orderValidCoupons i", function(){
    	var couponCode = $(this).closest('.orderValidCoupons').data('couponcode');
    	if(couponCode != ''){
    		melisHelper.zoneReload("id_meliscommerce_order_checkout_summary_basket", "meliscommerce_order_checkout_summary_basket", {removeCoupon: couponCode});
    	}
    });
    
    // Changing the Quantity by typing the number of the quantity of the variant in Basket List in Summary Step
    $('body').on('change', '.orderSummaryBasketVariantQty', function () {
    	var variantId = $(this).data("variantid");
    	var varQty = parseInt($(this).data("quantity"));
    	var variantQty = parseInt($(this).val());
    	// Checking the last Variant qunatity and the New Quantity
    	if(varQty < variantQty){
    		// Adding Variant quantity
    		updateSummaryVariantbasket("add", variantId, variantQty);
    	}else{
    		// Deducting Variant quantity
    		updateSummaryVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Binding Variant quantity input to Numeric characters only
    $('body').on("keydown", ".orderSummaryBasketVariantQty", function (e) {
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
    
    // Variant quantity + (plus) button
    // This action will add 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.summary-qty-plus', function () {
    	var variantId = $(this).data("variantid");
    	variantQty = parseInt($("#"+variantId+"_orderSummaryBasketVariantQty").val()) + 1;
    	$("#"+variantId+"_orderSummaryBasketVariantQty").val(variantQty);
		updateSummaryVariantbasket("add", variantId, variantQty);
    });
    
    // Variant quantity - (minus) button
    // This action will deduct 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.summary-qty-minus', function () {
    	var variantId = $(this).data("variantid");
    	$varQty = $("#"+variantId+"_orderSummaryBasketVariantQty").val();
    	
    	if(parseInt($varQty) > 0){
    		variantQty = parseInt($("#"+variantId+"_orderSummaryBasketVariantQty").val()) - 1;
    		$("#"+variantId+"_orderSummaryBasketVariantQty").val(variantQty);
    		updateSummaryVariantbasket("deduct", variantId, variantQty);
    	}
    });
    // Confirming Client basket button
    $('body').on('click', '.orderCheckoutConfirmSummary', function () {
    	var btn = $(this);
    	var nxtTabid = $(this).data("tabid");
    	
    	var dataString = new Array;
    	dataString.push({
    		name : 'couponCode',
    		value : ($("#orderCheckoutCouponCode").length) ? $("#orderCheckoutCouponCode").val() : ''
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/confirmOrderCheckoutSummary",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$(nxtTabid).tab("show");
				
				melisHelper.zoneReload("id_meliscommerce_order_checkout_payment_step_content", "meliscommerce_order_checkout_payment_step_content");
				
			}else{
				melisHelper.melisMultiKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    });
    
    $('body').on('click', '.orderCheckoutConfirmPayment', function () {
    	var btn = $(this);
    	var nxtTabid = $(this).data("tabid");
    	$(nxtTabid).tab("show");
    	
    	var zoneId = "id_meliscommerce_order_checkout_confirmation_step";
		var melisKey = "meliscommerce_order_checkout_confirmation_step";
		melisHelper.zoneReload(zoneId, melisKey, {activateTab : true});
		
		melisHelper.zoneReload('id_meliscommerce_order_list_content_table', 'meliscommerce_order_list_content_table');
    });
    
    $('body').on('change', '#orderCheckoutCouponCode', function () {
    	if($(this).val() == ''){
    		$(this).parent('.input-group').next().fadeOut('slow');
    	}
    });
});

window.productNextButtonState = function(){
	
	var nextButton = $(".orderCheckoutFirstStepBtn");
	$.ajax({
        type        : "POST", 
        url         : "/melis/MelisCommerce/MelisComOrderCheckout/checkBasket",
        dataType    : "json",
        encode		: true
	}).done(function(data) {
		if(data.success) {
			nextButton.attr('disabled', false);
			nextButton.attr('title', '');
		}else{
			nextButton.attr('disabled', true);
			nextButton.attr('title', translations.tr_meliscommerce_order_checkout_product_basket_empty);
		}
	}).fail(function(){});
}

// This method will update the Summary step basket list
window.updateSummaryVariantbasket = function(action, variantId, variantQty){
	var zoneId = "id_meliscommerce_order_checkout_summary_basket";
	var melisKey = "meliscommerce_order_checkout_summary_basket";
	
	var couponCode = $("#orderCheckoutCouponCode").val();
	if(couponCode == ''){
		couponCode = null;
	}
	melisHelper.zoneReload(zoneId, melisKey, {action: action, variantId : variantId, variantQty : variantQty, couponCode : couponCode});
	// this will also update the basket list at First step
	setTimeout(function(){ 
		melisHelper.zoneReload("id_meliscommerce_order_checkout_product_bakset", "meliscommerce_order_checkout_product_bakset");
	}, 3000);
	
}
// This method will update the Basket list at First Step
window.updateVariantbasket = function(action, variantId, variantQty){
	var zoneId = "id_meliscommerce_order_checkout_product_bakset";
	var melisKey = "meliscommerce_order_checkout_product_bakset";
	melisHelper.zoneReload(zoneId, melisKey, {action: action, variantId : variantId, variantQty : variantQty});
}

window.initCheckoutSelectContactTable = function(){
	$('.checkoutSelectContactOrderHeader').attr('title', translations.tr_meliscommerce_checkout_tbl_cper_num_orders);
}
