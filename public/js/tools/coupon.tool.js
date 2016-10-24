$(document).ready(function() {
	var body = $("body");
	
	// coupon list - refreshes the order list table
	body.on("click", ".couponListRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
	});
	
	// coupon list - refreshes the coupon list table
	body.on("click", ".couponClientListRefresh", function(){
		var couponId = activeTabId.split("_")[0];
		melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details_table", "meliscommerce_coupon_tabs_content_assign_details_table", { couponId : couponId });
	});
	
	// coupon list - opens a blank coupon page for adding
 	body.on("click", ".addNewCoupon", function(){
		melisHelper.tabOpen(translations.tr_meliscommerce_coupon_page, 'fa fa-gift', '0_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page');
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
				melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details_table", "meliscommerce_coupon_tabs_content_assign_details_table", { couponId : couponId });		
				melisCore.flashMessenger();
				
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
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
	
	function couponTabOpen(ordername, id){
		melisHelper.tabOpen(ordername, 'fa fa-gift', id+'_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page', { couponId : id});
	}
});
window.initCouponClient = function(data, tblSettings) {
	var couponId = activeTabId.split("_")[0];
	data.couponId = couponId;	
}

window.drawCouponClient = function() {
	console.log('tesadsa');
}

