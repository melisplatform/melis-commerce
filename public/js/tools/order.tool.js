$(document).ready(function() {
	var body = $("body");
	// order list - opens specific order for editing
	body.on("click", ".orderInfo", function() {
		var orderId   = $(this).closest('tr').attr('id');
		var orderRef  = $(this).closest('tr').find("td:nth-child(2)").text();
		var tabName = '';
		if(orderRef.length > 0){
			tabName = orderRef;
		}else{
			tabName = orderId;
		}
		orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+tabName, orderId);
	});
	// order page - toggles the new message form
	body.on("click", ".addMessage", function(){
		$(this).closest('.container-level-a').find('.add-message').slideToggle();
	});
	
	body.on("click", ".addNewOrder", function(){
		melisHelper.tabOpen(translations.tr_meliscommerce_order_new_order, 'fa fa fa-plus fa-2x', 'id_meliscommerce_order_checkout', 'meliscommerce_order_checkout');
	});
	// order list - refreshes the order list table
	body.on("click", ".orderListRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_order_list_content_table", "meliscommerce_order_list_content_table");
		if($('#'+activeTabId).data('pageid') == 'coupon'){
			var couponId = activeTabId.split("_")[0];
			melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
		}		
	});
	// order page - refreshes the basket table
	body.on("click", ".orderBasketRefresh", function(){
		var id = $(this).closest('.container-level-a').attr('id');
		var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		melisHelper.zoneReload(orderId+"_id_meliscommerce_orders_content_tabs_content_baskets_details_list", "meliscommerce_orders_content_tabs_content_baskets_details_list", { "orderId" : orderId});
	});
	// order page - breadcrumbs
	body.on("click", ".orderList", function(){
		melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
	});
	// order page -saves the order
	body.on("click", ".saveOrder", function(){
		melisCoreTool.pending(".saveOrder");
		var url = 'melis/MelisCommerce/MelisComOrder/saveOrder';
		var id = $(this).closest('.container-level-a').attr('id');
		var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		var forms = $(this).closest('.container-level-a').find('form');
		var dataString = [];
		var len;
		var ctr = 0;
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
				dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
			}	
			ctr++;
		});
		dataString.push({name : 'orderId', value : orderId});
		
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){
				melisHelper.tabClose(  orderId + "_id_meliscommerce_orders_page");
				orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+data.chunk.ord_reference, data.chunk.ord_id);				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_order_list_content_table", "meliscommerce_order_list_content_table");				
				melisCore.flashMessenger();
				
				// Relload Client Order list if id exist
				if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
					melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
				}
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(".saveOrder");
		
	});
	// order page - saves the new created message
	body.on("click", ".add-order-message", function(){
		var id = $(this).closest('.container-level-a').attr('id');
		var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		var dataString = $(this).closest('div').find('form').serializeArray();
		var url = 'melis/MelisCommerce/MelisComOrder/saveOrderMessage';
		dataString.push({name : 'orderId', value : orderId});
		melisCoreTool.pending(this);
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){;				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload( orderId+"_id_meliscommerce_orders_content_tabs_content_messages_details", "meliscommerce_orders_content_tabs_content_messages_details", { "orderId" : orderId});
				
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
	});
	// order list - toggles the status form modal
	body.on("click", ".updateListStatus", function(){ 
		var orderId = $(this).closest('tr').attr('id');
		melisCoreTool.pending(this);
		// initialation of local variable
		zoneId = 'id_meliscommerce_order_list_content_status_form';
		melisKey = 'meliscommerce_order_list_content_status_form';
		modalUrl = '/melis/MelisCommerce/MelisComOrderList/renderOrderListModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {'orderId': orderId}, modalUrl, function(){
    		melisCoreTool.done(this);
    	});
	});
	// order page - toggles the shipping form modal
	body.on("click", ".addShipping", function(){ 
		var orderId = $(this).closest('tr').attr('id');
		melisCoreTool.pending(this);
		// initialation of local variable
		zoneId = 'id_meliscommerce_order_modal_content_shipping_form';
		melisKey = 'meliscommerce_order_modal_content_shipping_form';
		modalUrl = '/melis/MelisCommerce/MelisComOrder/renderOrderModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {'orderId': orderId}, modalUrl, function(){
    		
    	});
    	melisCoreTool.done(this);
	});
	// order list - saves the new order status
	body.on("click", "#saveOrderStatus", function(){
		var dataString = $(this).closest('#id_meliscommerce_order_list_content_status_form').find('form').serializeArray();
		var url = 'melis/MelisCommerce/MelisComOrderList/saveOrderStatus';
		melisCoreTool.pending(this);
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){;				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_order_list_content_table", "meliscommerce_order_list_content_table");
				// Relload Client Order list if id exist
				if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
					melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
				}
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
		$("#id_meliscommerce_order_list_content_status_form_container").modal("hide");
	});
	// order page - saves the new shipping
	body.on("click", "#saveOrderShipping", function(){
		var forms = $(this).closest('#id_meliscommerce_order_modal_content_shipping_form').find('form');
		var url = 'melis/MelisCommerce/MelisComOrder/saveOrder';
		var ord_reference = $('#'+activeTabId+' input[name=ord_reference]').val();
		var ord_status = $('#'+activeTabId+' select[name=ord_status] option:selected').val();
		var id = $('#'+activeTabId).attr('id');;
		var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		var dataString = [];
		var len;
		var ctr = 0;
		melisCoreTool.pending(this);
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
				dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
			}	
			ctr++;
		});
		dataString.push({name : 'order[0][ord_id]', value : orderId});
		dataString.push({name : 'order[0][ord_reference]', value : ord_reference});
		dataString.push({name : 'order[0][ord_status]', value : ord_status});
		dataString.push({name : 'orderId', value : orderId});
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){;				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				// Relload Client Order list if id exist
				if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
					melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
				}
				$('#id_meliscommerce_order_modal_content_shipping_form_container').modal('hide');
				melisHelper.zoneReload(orderId+"_id_meliscommerce_orders_content_tabs_content_shipping_details", "meliscommerce_orders_content_tabs_content_shipping_details", {"orderId": orderId});
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
		
	});
	
	function orderTabOpen(ordername, id){
		melisHelper.tabOpen(ordername, 'fa fa fa-cart-plus fa-2x', id+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : id});
	}
});
// table datafunction for basket
window.initOrderBasket = function(data, tblSettings) {
	var orderId = activeTabId.split("_")[0];
	data.orderId = orderId;	
} 