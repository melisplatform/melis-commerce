$(document).ready(function() {
	var body = $("body");
	
	// coupon list - refreshes the order list table
	body.on("click", ".couponListRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
	});
	
	// coupon list - refreshes the order list table
	body.on("click", ".couponAssignedClientListRefresh", function(){
		var couponId = activeTabId.split("_")[0];
		melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
	});
	
	// coupon list - refreshes the coupon list table
	body.on("click", ".couponClientListRefresh", function(){
		var parentId = $(this).parents().eq(5).attr('id');;
		var meliskey = $(this).parents().eq(5).data('meliskey');
		var couponId = activeTabId.split("_")[0];
		melisHelper.zoneReload(parentId, meliskey, { couponId : couponId });
	});
	
	// coupon list - opens a blank coupon page for adding
 	body.on("click", ".addNewCoupon", function(){
		melisHelper.tabOpen(translations.tr_meliscommerce_coupon_list_add_coupon, 'fa fa-ticket', '0_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page');
	});
	
 	body.on("click", ".removeCouponFromClient", function(){
 		melisCoreTool.pending(this);
 		var clientId = $(this).closest('tr').attr('id');
 		var dataString = [];
 		var ccliId = $(this).closest('td').prev().find('a').data('ccli_id');
 		dataString.push({ name : 'ccli_client_id', value: clientId });
 		dataString.push({ name : 'method', value : 'remove' });
 		dataString.push({ name : 'ccliId', value : ccliId });
 		couponManagement(dataString);
		melisCoreTool.done(this);
 	});
 	
 	body.on("click", ".addCouponToClient", function(){
 		melisCoreTool.pending(this);
 		var clientId = $(this).closest('tr').attr('id');
 		var dataString = [];
 		dataString.push({ name : 'ccli_client_id', value: clientId });
 		dataString.push({ name : 'method', value : 'add'});
 		couponManagement(dataString); 		
		melisCoreTool.done(this);
 	});
 	
 	function couponManagement(dataString){ 		
 		var couponId = activeTabId.split("_")[0];
 		var url = 'melis/MelisCommerce/MelisComCoupon/couponClientManagement';
 		dataString.push({ name: 'ccli_coupon_id', value : couponId});
 		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){
				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details", "meliscommerce_coupon_tabs_content_assign_details", { couponId : couponId });;
				melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
				melisCore.flashMessenger();
				
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
 		$('#'+couponId+"_id_meliscommerce_coupon_tabs_content_assign_details").addClass('active');
 	}
 	
 	body.on("click", ".saveCoupon", function(){
 		melisCoreTool.pending(this);
 		var couponId = activeTabId.split("_")[0];
 		var forms = $(this).closest('.container-level-a').find('form');
 		var url = 'melis/MelisCommerce/MelisComCoupon/saveCouponData';
 		var dataString = [];
 		var len;
		var ctr = 0;
		// serialize each form
		forms.each(function(){
			var i = 0;
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
				dataString.push({  name: pre+'['+i+']['+data[j].name+']', value : data[j].value});
			}
			i++;
			ctr++;
		});
		dataString.push({name : 'couponId', value : couponId});
		// serialize each switch
		
		$('#'+activeTabId+' .make-switch div').each(function(){
			var field = 'switch['+$(this).find('input').attr('name')+']';
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
				melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
				couponTabOpen(translations.tr_meliscommerce_coupon_page+' '+data.chunk.coup_code, data.chunk.couponId);				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");				
				melisCore.flashMessenger();				
			}else{
				
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, couponId+"_id_meliscommerce_coupon_page");
				$(".couponEnd").prev("label").css("color","#686868");
				$.each( data.errors, function( key, error ) {
					if( key == 'coup_date_valid_end'){
						$(".couponEnd").prev("label").css("color","red");
					}
					if( key == 'values'){
						$("#" + couponId+"_id_meliscommerce_coupon_page" + " .form-control[name='coup_percentage']").prev("label").css("color","red");
						$("#" + couponId+"_id_meliscommerce_coupon_page" + " .form-control[name='coup_discount_value']").prev("label").css("color","red");
					}
				});
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
 	});
 	
	// coupon list - opens specific order for editing
	body.on("click", ".couponInfo", function() {
		var couponId   = $(this).closest('tr').attr('id');
		var couponCode  = $(this).closest('tr').find("td:nth-child(3)").text();
		var tabName = couponId;
		if(couponCode.length > 0){
			tabName = couponCode;
		}
		couponTabOpen(translations.tr_meliscommerce_coupon_page+' '+tabName, couponId);
	});
	
	// coupon list - deletes the coupon
	body.on("click", ".couponDelete", function(){ 
		var couponId   = $(this).closest('tr').attr('id');
		var url = 'melis/MelisCommerce/MelisComCouponList/deleteCoupon';
		var dataString = [];
		dataString.push({
			name : 'couponId',
			value: couponId,
		});
		melisCoreTool.pending(this);
		
		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			translations.tr_meliscommerce_coupon_list_page_coupon, 
			translations.tr_meliscommerce_coupon_delete_confirm,
			function(){
				melisCommerce.postSave(url, dataString, function(data){
					if(data.success){				
						melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
						melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
						melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
					}else{
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
					}		
					melisCore.flashMessenger();	
				}, function(data){
					console.log(data);
				})
			
		});
		
		melisCoreTool.done(this);
	});
	
	// coupon - remove assigned coupon from client
	body.on("click", ".couponAssignedDelete", function(){ 
		var clientId   = $(this).closest('tr').attr('id');
		var couponId   = $(this).closest('tr').data('couponid');
		var url = 'melis/MelisCommerce/MelisComCoupon/deleteAssignedCoupon';
		var dataString = [];
		dataString.push({
			name : 'couponId',
			value: couponId,
		});
		dataString.push({
			name : 'clientId',
			value: clientId,
		});
		melisCoreTool.pending(this);
		
		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			translations.tr_meliscommerce_coupon_list_page_coupon, 
			translations.tr_meliscommerce_coupon_delete_confirm_remove,
			function(){
				melisCommerce.postSave(url, dataString, function(data){
					if(data.success){				
						melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
						melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details", "meliscommerce_coupon_tabs_content_assign_details", { couponId : couponId });;
						melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
						
					}else{
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
					}		
					melisCore.flashMessenger();	
				}, function(data){
					console.log(data);
				})
			
		});
		$('#'+couponId+"_id_meliscommerce_coupon_tabs_content_assign_details").addClass('active');
		melisCoreTool.done(this);
	});
	
	function couponTabOpen(ordername, id){
		melisHelper.tabOpen(ordername, 'fa fa-ticket', id+'_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page', { couponId : id});
	}
});
window.initCouponClient = function(data, tblSettings) {
	var couponId = $("#" + tblSettings.sTableId ).data("couponid");
	data.couponId = couponId;	
}

window.initCouponOrder = function(data, tblSettings) {
	var couponId = $("#" + tblSettings.sTableId ).data("couponid");
	data.couponId = couponId;
	
	data.osta_id = $('#'+ couponId +'_id_meliscommerce_coupon_tabs_content_orders_details_table .orderFilterStatus').val();	
	data.startDate = $('#'+couponId+'_couponOrderList').data('dStartDate');
	data.endDate   = $('#'+couponId+'_couponOrderList').data('dEndDate');
	var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';
	
	if(tblSettings.iDraw > 1) {
		dateSelectionContent = translations.tr_meliscore_datepicker_select_date  + icon + "<span class='sdate'>" + dStartDate + ' - ' + dEndDate + '</span> <b class="caret"></b>';
		$('#'+couponId+'_couponOrderList_wrapper .dt_orderdatepicker .dt_dateInfo').html(dateSelectionContent);
	}
	dStartDate = ""; dEndDate = "";
}

window.drawCouponClient = function() {
	console.log('tesadsa');
}

window.initCheckUsedCoupon = function(){
	var btnDelete = $('#tableCouponList tr.couponUsed td').find(".couponDelete");
	btnDelete.remove();
}

window.initCheckClientUsedCoupon = function(tblSettings){
	var btnDelete = $('tr.couponAssigned td').find(".couponAssignedDelete");
	btnDelete.remove();
}

window.initMelisCommerceCouponTbl = function(){
	$('.commerce-coupon-percent').attr('title', translations.tr_meliscommerce_coupon_list_col_percent);
	$('.commerce-coupon-value').attr('title', translations.tr_meliscommerce_coupon_list_col_money);
}
