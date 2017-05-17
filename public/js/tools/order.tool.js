$(document).ready(function() {
	
	var body = $("body");

	//removes modal elements when clicking outside
	body.on("click", function (e) {
		if ($(e.target).hasClass('modal')) {
			$('#id_meliscommerce_order_list_content_status_form_container').modal('hide');
			$('#id_meliscommerce_order_modal_content_shipping_form_container').modal('hide');
		}
	});

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
	
	// order list - refreshes the order list table
	body.on("click", ".orderListRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
		if($('#'+activeTabId).data('pageid') == 'coupon'){
			var couponId = activeTabId.split("_")[0];
			melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
		}		
	});
	
	// order status list - refreshes the order status table
	body.on("click", ".orderStatusRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
	});
	
	// order list - toggles the status form modal
	body.on("click", ".addNewOrderStatus", function(){
		var statusId = $(this).closest('tr').attr('id');
//		melisCoreTool.pending(this);
		// initialation of local variable
		zoneId = 'id_meliscommerce_order_status_form';
		melisKey = 'meliscommerce_order_status_form';
		modalUrl = '/melis/MelisCommerce/MelisComOrderStatus/renderOrderStatusModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {ostaId : statusId}, modalUrl, function(){
//    		melisCoreTool.done(this);
    	});
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
		var statusId = $(this).closest('.container-level-a').find('.selectedStatus').data('statusid');
		var reference = $(this).closest('.container-level-a').find('input[name=ord_reference]').val();
		var orderStats = $(this).data('orderstatus');
		
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
		dataString.push({name: 'order[0][ord_status]', value : statusId});
		dataString.push({name : 'lastStatus', value : orderStats});
		
		if(statusId == 5 && orderStats != 5){
			melisCoreTool.confirm(
				translations.tr_meliscommerce_order_common_label_yes,
				translations.tr_meliscommerce_order_common_label_no,
				reference, 
				translations.tr_meliscommerce_order_save_status_canceled_confirm,
				function(){
					melisCommerce.postSave(url, dataString, function(data){
						if(data.success){
							melisHelper.tabClose(  orderId + "_id_meliscommerce_orders_page");
							orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+data.chunk.order.ord_reference, data.chunk.order.ord_id);				
							melisHelper.melisOkNotification( data.textTitle, data.textMessage );
							melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
							melisCore.flashMessenger();
							
							// Relload Client Order list if id exist
							if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
								melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
							}
						}else{
							melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
							melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
						}			
					}, function(data){
						console.log(data);
					});
			});
		}else{
			melisCommerce.postSave(url, dataString, function(data){
				if(data.success){
					melisHelper.tabClose(  orderId + "_id_meliscommerce_orders_page");
					orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+data.chunk.order.ord_reference, data.chunk.order.ord_id);				
					melisHelper.melisOkNotification( data.textTitle, data.textMessage );
					melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
					melisCore.flashMessenger();
					
					// Relload Client Order list if id exist
					if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
						melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
					}
				}else{
					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
				}			
			}, function(data){
				console.log(data);
			});
		}		
		
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
			if(data.success){				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
				melisHelper.zoneReload( orderId+"_id_meliscommerce_orders_content_tabs_content_messages_details", "meliscommerce_orders_content_tabs_content_messages_details", { "orderId" : orderId});
				
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
			}		
			// melisCore.flashMessenger();
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
	});
	// order list - toggles the status form modal
	body.on("click", ".updateListStatus", function(){ 
		var orderId = $(this).data('orderid');
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
		var status = $('#id_meliscommerce_order_list_content_status_form select[name="ord_status"]').val();
		var reference = translations.tr_meliscommerce_orders_Order + ' ' +$(this).data('reference');
		
		melisCoreTool.pending(this);		
		
		if(status == 5){
			melisCoreTool.confirm(
				translations.tr_meliscommerce_order_common_label_yes,
				translations.tr_meliscommerce_order_common_label_no,
				reference, 
				translations.tr_meliscommerce_order_save_status_canceled_confirm,
				function(){
					melisCommerce.postSave(url, dataString, function(data){
						if(data.success){;				
							melisHelper.melisOkNotification( data.textTitle, data.textMessage );
							melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
							// Relload Client Order list if id exist
							if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
								melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
							}
							
							//reload coupon if active
							if($('#'+activeTabId).data('pageid') == 'coupon'){
								var couponId = activeTabId.split("_")[0];
								melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
							}
							
							melisCore.flashMessenger();
						}else{
							melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
							melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_list_content_status_form");
						}			
					}, function(data){
						console.log(data);
					});
			});
		}else{
			melisCommerce.postSave(url, dataString, function(data){
				if(data.success){;				
					melisHelper.melisOkNotification( data.textTitle, data.textMessage );
					melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
					// Relload Client Order list if id exist
					if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
						melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
					}
					
					//reload coupon if active
					if($('#'+activeTabId).data('pageid') == 'coupon'){
						var couponId = activeTabId.split("_")[0];
						melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
					}
					
					melisCore.flashMessenger();
				}else{
					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_list_content_status_form");
				}			
			}, function(data){
				console.log(data);
			});
		}
		
		
		melisCoreTool.done(this);
		$("#id_meliscommerce_order_list_content_status_form_container").modal("hide");
	});
	// order page - saves the new shipping
	body.on("click", "#saveOrderShipping", function(){
		var forms = $(this).closest('#id_meliscommerce_order_modal_content_shipping_form').find('form');
		var url = 'melis/MelisCommerce/MelisComOrder/saveOrder';
		var ord_reference = $('#'+activeTabId+' input[name=ord_reference]').val();
		var ord_status = $('#'+activeTabId+' .selectedStatus').data('statusid');
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
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
				// Relload Client Order list if id exist
				if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
					melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
				}
				$('#id_meliscommerce_order_modal_content_shipping_form_container').modal('hide');
				melisHelper.zoneReload(orderId+"_id_meliscommerce_orders_content_tabs_content_shipping_details", "meliscommerce_orders_content_tabs_content_shipping_details", {"orderId": orderId});
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors,"id_meliscommerce_order_modal_content_shipping_form");
				$("#id_meliscommerce_order_modal_content_shipping_form .shippingDate").prev("label").css("color","#686868");
				$.each( data.errors, function( key, error ) {
					if( key == 'oship_date_sent'){
						$("#id_meliscommerce_order_modal_content_shipping_form .shippingDate").prev("label").css("color","red");
					}					
				});
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
		
	});
	
	body.on("click", ".mainOrderStatus", function(){
		$(this).parent().find('.mainOrderStatus').each(function(){
			$(this).removeClass('selectedStatus');
			var button = $(this).find('span');
			var statusColor = button.css('border-top-color');
			button.css('color', statusColor);
			button.css('background','#fff');
		})
		$(this).addClass('selectedStatus');
		var button = $(this).find('span');
		var statusColor = button.css('border-top-color');
		button.css('color', '#fff');
		button.css('background', statusColor);
		
	});
	
	function orderTabOpen(ordername, id){
		melisHelper.tabOpen(ordername, 'fa fa fa-cart-plus fa-2x', id+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : id});
	}
	 
	$("body").on('apply.daterangepicker', ".dt_orderdatepicker", function(ev, picker) {
		// reload table
		var tableId = $(this).parents().eq(5).find('table').attr('id');
		$("#"+tableId).data('dStartDate', dStartDate)
		$("#"+tableId).data('dEndDate', dEndDate)
		$("#"+tableId).DataTable().ajax.reload();
	});
	
	$("body").on('change', '.orderFilterStatus',function(){
		var tableId = $(this).parents().eq(6).find('table').attr('id');
		$("#"+tableId).DataTable().ajax.reload();
    });

	body.on("click", ".variantInfo", function(){
		var variantId   = $(this).closest('tr').data('variantid');
		var variantName = $(this).closest('tr').data('sku');;
		var productId   = $(this).closest('tr').data('productid');
		melisCommerce.disableAllTabs();
		melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
		melisCommerce.setUniqueId(variantId);
		melisCommerce.enableAllTabs();
	});	
	
	body.on("click", ".ordersExport", function() {
		if(!melisCoreTool.isTableEmpty("tableOrderList")) {
			
			// initialation of local variable
			zoneId = 'id_meliscommerce_order_list_content_export_form';
			melisKey = 'meliscommerce_order_list_content_export_form';
			modalUrl = '/melis/MelisCommerce/MelisComOrderList/renderOrderListModal';
			// requesitng to create modal and display after
	    	melisHelper.createModal(zoneId, melisKey, false, {}, modalUrl, function(){
	    		melisCoreTool.done(this);
	    	});
		}
	});
	
	body.on("click", "#exportOrders", function(){
		var button = $(this);
		var formValues = button.closest('#id_meliscommerce_order_list_content_export_form').find('form').serializeArray();
		var target = 'id_meliscommerce_order_list_content_export_form';
		melisCoreTool.pending(button);
		
		$.ajax({
		     type        : "POST", 
		     url         : "/melis/MelisCommerce/MelisComOrderList/ordersExportValidate",
		     data		: formValues,
		     dataType    : "json",
		     encode		: true
		}).done(function(data) {
			
			if(!data.success) {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);	
				melisCoreTool.highlightErrors(0, data.errors, target);
				$(".date_start").prev("label").css("color","#686868");
				$(".date_end").prev("label").css("color","#686868");
				$.each( data.errors, function( key, error ) {
					if( key == 'date_start'){
						$(".date_start").prev("label").css("color","red");
					}
					if( key == 'date_end'){
						$(".date_end").prev("label").css("color","red");
					}
				});
			}else{
				melisCoreTool.exportData('/melis/MelisCommerce/MelisComOrderList/ordersExportToCsv');
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
			}
		})
		
		melisCoreTool.done(button);	
	});
	
	// order status - saves the order status
	body.on("click", "#saveOrderStatusForm", function(){
		var saveButton = $(this);
		var statusId = saveButton.data('statusid');
		var statusDom = $('#id_meliscommerce_order_status_form_container .make-switch div').hasClass('switch-on');
		var forms  = $('#id_meliscommerce_order_status_form_container').find('form');
		var url = 'melis/MelisCommerce/MelisComOrderStatus/saveOrderStatus';
		var dataString = [];
		var len;
		var ctr = 0;
		var status = 0;
		
		if(statusDom){
			status = 1;
		}
		
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
				dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
			}	
			ctr++;
		});
		
		dataString.push({ name: 'statusId', value : statusId });
		dataString.push({ name: 'order_status[0][osta_status]', value : status });
		
		melisCoreTool.pending(this);
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){;				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
				melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
				$("#id_meliscommerce_order_status_form_container").modal("hide");
			}else{
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_status_form_container");
				$.each( data.errors, function( key, error ) {
					if( key == 'osta_color_code'){
						$(".osta_color_code").prev("label").css("color","red");
					}
				});
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);				
			}	
			melisCore.flashMessenger();
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
		
	});
	
	// order status - deletes the coupon
	body.on("click", ".orderStatusDelete", function(){ 
		var ostaId   = $(this).closest('tr').attr('id');
		var url = 'melis/MelisCommerce/MelisComOrderStatus/deleteOrderStatus';
		var dataString = [];
		dataString.push({
			name : 'ostaId',
			value: ostaId,
		});
		melisCoreTool.pending(this);
		
		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			translations.tr_meliscommerce_order_status_tool_leftmenu, 
			translations.tr_meliscommerce_order_status_delete_confirm,
			function(){
			melisCommerce.postSave(url, dataString, function(data){
				if(data.success){				
					melisHelper.melisOkNotification( data.textTitle, data.textMessage );
					melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
					melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
				}else{
					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);				
				}		
				melisCore.flashMessenger();	
			}, function(data){
				console.log(data);
			})
		});
		
		melisCoreTool.done(this);
	});
	
});
// table datafunction for basket
window.initOrderBasket = function(data, tblSettings) {
	var orderId = $("#" + tblSettings.sTableId ).data("orderid");
	data.orderId = orderId;	
}

window.hideBasketButton = function (data, tblSettings){
	$('.variantInfo').each(function(){
		variantId = $(this).closest('tr').data('variantid');
		productId = $(this).closest('tr').data('productid');
		if(variantId == null || productId == null){
			$(this).hide();
		}
	});
}

//table order custom filter
window.initOrderList = function(data, tblSettings){
	
	data.osta_id = $('#id_meliscommerce_order_list_content_table .orderFilterStatus').val();	
	data.startDate = $("#tableOrderList").data('dStartDate');
	data.endDate   = $("#tableOrderList").data('dEndDate');	
	var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';
	
	if(tblSettings.iDraw > 1) {
		dateSelectionContent = translations.tr_meliscore_datepicker_select_date  + icon + "<span class='sdate'>" + dStartDate + ' - ' + dEndDate + '</span> <b class="caret"></b>';
		$('#tableOrderList_wrapper .dt_orderdatepicker .dt_dateInfo').html(dateSelectionContent);
	}
	dStartDate = ""; dEndDate = ""; //clear date when Prospects page is reloaded
}

//table order custom filter
window.initOrderListTitle = function(){
	$('#tableOrderList .icon-shippment').parent('th').attr('title', translations.tr_meliscommerce_order_list_col_products);
	$('#tableOrderList .fa-usd').parent('th').attr('title', translations.tr_meliscommerce_order_list_col_price_title);	
}

// order status table remove delete button for permanent status
window.initCheckPermStatus = function(tblSettings){
	var btnDelete = $('tr.primeStatus td').find(".orderStatusDelete");
	btnDelete.remove();
}