$(function(){
	
	$("body").on("click", ".addNewClient", function(){ 
		melisHelper.tabOpen(translations.tr_meliscommerce_clients_add_client, "fa fa-user-plus", "0_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:0});
	});
	
	$("body").on("click", ".viewCleintInfo", function(){ 
		var clientId = $(this).parents("tr").attr("id");
		
		
		dataString = new Array;
		
		dataString.push({
			name: 'clientId',
			value: clientId
		})
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComClient/getClientContactName",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveClientContact").button("reset");
			
			if(data.success){
				melisHelper.tabOpen(data.clientContactName, "fa fa-user", clientId+"_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:clientId});
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
			}
			
		}).fail(function(){
			
			$("#saveClientContact").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	
	});
	
	$("body").on("click", ".addNewContact", function(){ 
		var clientId = $(this).data('clientid');
		$(".addNewContact").button("loading");
		// initialation of local variable
		zoneId = 'id_meliscommerce_client_modal_contact_form';
		melisKey = 'meliscommerce_client_modal_contact_form';
		modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId}, modalUrl, function(){
    		$(".addNewContact").button("reset");
    	});
	});
	
	$("body").on("click", "#saveClientContact", function(){ 
		var clientId = $(this).data('clientid');
		
		// serialize the new array and send it to server
		dataString = $("#melisCommerceClientContactFormModal").serializeArray();
		
		dataString.push({
			name : 'clientId',
			value : clientId,
		});
		
		dataString = $.param(dataString);
		
		$("#saveClientContact").button("loading");
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComClient/addClientContact",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveClientContact").button("reset");
			
			if(data.success){
				$("#"+clientId+"_client_contact_tab_nav").append(data.clientContactDom.tabNav);
				$("#"+clientId+"_client_contact_tab_content").append(data.clientContactDom.tabContent);
				$('#nav_'+data.clientContactDom.tabId).tab('show');
				$("#id_meliscommerce_client_modal_contact_form_container").modal("hide");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientContactFormModal");
			}
			
		}).fail(function(){
			
			$("#saveClientContact").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	$("body").on("click", ".addNewContactAddress", function(){ 
		var clientId = $(this).data("clientid");
		var tabId = $(this).data("tabid");
		$(".addNewContactAddress").button("loading");
		
		// initialation of local variable
		zoneId = 'id_meliscommerce_client_modal_contact_address_form';
		melisKey = 'meliscommerce_client_modal_contact_address_form';
		modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
		// requesitng to create modal and display after
		melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId, tabId: tabId}, modalUrl, function(){
			$(".addNewContactAddress").button("reset");
		});
	});
	
	$("body").on("click", "#saveClientContactAddress", function(){ 
		var clientId = $(this).data("clientid");
		var tabId = $(this).data("tabid");
		
		// serialize the new array and send it to server
		dataString = $("#melisCommerceClientContactAddressFormModal").serializeArray();
		
		dataString.push({
			name : 'clientId',
			value : clientId,
		});
		
		dataString.push({
			name : 'tabId',
			value : tabId,
		});
		
		dataString = $.param(dataString);
		
		$("#saveClientContactAddress").button("loading");
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComClient/addClientContactAddress",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveClientContactAddress").button("reset");
			
			if(data.success){
				$("#"+tabId+"_contact_address").append(data.clientContactAddressDom.accordionContent);
				$('#nav_'+data.clientContactAddressDom.contactAddressId).click();
				$("#id_meliscommerce_client_modal_contact_address_form_container").modal("hide");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientContactAddressFormModal");
			}
			
		}).fail(function(){
			
			$("#saveClientContactAddress").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	$("body").on("click", ".addNewAddress", function(){ 
		var clientId = $(this).data("clientid");
		$(".addNewAddress").button("loading");
		
		// initialation of local variable
		zoneId = 'id_meliscommerce_client_modal_address_form';
		melisKey = 'meliscommerce_client_modal_address_form';
		modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
		// requesitng to create modal and display after
		melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId}, modalUrl, function(){
			$(".addNewAddress").button("reset");
		});
	});
	
	$("body").on("click", "#saveClientAddress", function(){ 
		var clientId = $(this).data("clientid");
		
		// serialize the new array and send it to server
		dataString = $("#melisCommerceClientAddressFormModal").serializeArray();
		
		dataString.push({
			name : 'clientId',
			value : clientId,
		});
		
		dataString = $.param(dataString);
		
		$("#saveClientAddress").button("loading");
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComClient/addClientAddress",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
		}).done(function(data) {
			
			$("#saveClientAddress").button("reset");
			
			if(data.success){
				$("#"+clientId+"_client_address_tab_nav").append(data.clientAddressDom.tabNav)
				$("#"+clientId+"_client_address_tab_content").append(data.clientAddressDom.tabContent)
				$("#nav_add_"+data.clientAddressDom.addressId).tab("show");
				$("#id_meliscommerce_client_modal_address_form_container").modal("hide");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientAddressFormModal");
			}
			
		}).fail(function(){
			
			$("#saveClientAddress").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	$("body").on("click", ".deleteClientCotactAddress", function(){ 
		var addressId = $(this).data("addressid");
		var addressAccordionId = $(this).data("addressaccordionid");
		var isNewAdded = $(this).data("isnewadded");
		
		// deletion confirmation
		melisCoreTool.confirm(
		translations.tr_meliscommerce_clients_common_label_yes,
		translations.tr_meliscommerce_clients_common_label_no,
		translations.tr_meliscommerce_client_delete_address, 
		translations.tr_meliscommerce_client_delete_address_confirm_msg, 
		function() {
			// Checking if Contact Address is not new entry
			if(isNewAdded == 0){
				// Address id added to Form Deleted Client Address
				$("#deletedClientAddress").append("<input name='deletedaddresses[]' value='"+addressId+"'>");
			}
			// Removing Address Content from the list of Contact Addresses
			$("#"+addressAccordionId+"_contact_address_content").remove();
		});
	});
	
	$("body").on("click", ".deleteClientAddress", function(){ 
		var addressId = $(this).data("addressid");
		var tabClass = $(this).data("tabclass");
		var isNewAdded = $(this).data("isnewadded");
		
		// deletion confirmation
		melisCoreTool.confirm(
		translations.tr_meliscommerce_clients_common_label_yes,
		translations.tr_meliscommerce_clients_common_label_no,
		translations.tr_meliscommerce_client_delete_address, 
		translations.tr_meliscommerce_client_delete_address_confirm_msg, 
		function() {
			// Checking if Contact Address is not new entry
			if(isNewAdded == 0){
				// Address id added to Form Deleted Client Address
				$("#deletedClientAddress").append("<input name='deletedaddresses[]' value='"+addressId+"'>");
			}
			
			if($("."+tabClass+":first").hasClass("active")){
				
				if($("."+tabClass+":first").next().find("a").length){
					// Activate next tab after removing current active tab
					$("."+tabClass+":first").next().find("a").tab("show");
				}else if($("."+tabClass+":first").prev().find("a")){
					// Activate previous tab after removing current active tab
					$("."+tabClass+":first").prev().find("a").tab("show");
				}
			}
			// Removing Address Content from the list of Contact Addresses
			$("."+tabClass).remove();
		});
	});
	
	$("body").on("switch-change", ".clientMainContact", function(){
		var clientId = $(this).data("clientid");
		var tabId = $(this).data("tabid");
		
		if($(this).find(".switch-animate").hasClass("switch-on")){
			// Set other OFF
			$("#"+clientId+"_client_contact_tab_content .make-switch").find('.switch-on').removeClass('switch-on').addClass('switch-off');
			// Set other form main contact input to zero (0)
			$("#"+clientId+"_client_contact_tab_content").find('form input[name="cper_is_main_person"]').val(0);
			// Set back On the current switch
			$(this).find(".switch-animate").removeClass('switch-off').addClass("switch-on");
			// Set current switch main contact input to one (1) 
			$("#"+tabId).find("form input[name='cper_is_main_person']").val(1);
		}else{
			$("#"+tabId).find("form input[name='cper_is_main_person']").val(0);
		}
	});
	
	$("body").on("click", ".deleteClientContactAddress", function(){ 
		var tabClass = $(this).data("tabclass");
		
		// deletion confirmation
		melisCoreTool.confirm(
		translations.tr_meliscommerce_clients_common_label_yes,
		translations.tr_meliscommerce_clients_common_label_no,
		translations.tr_meliscommerce_client_delete_new_contact, 
		translations.tr_meliscommerce_client_delete_new_contact_confirm_msg, 
		function() {
			
			if($("."+tabClass+"_client_contact:first").hasClass("active")){
				
				if($("."+tabClass+"_client_contact:first").next().find("a").length){
					// Activate next tab after removing current active tab
					$("."+tabClass+"_client_contact:first").next().find("a").tab("show");
				}else if($("."+tabClass+"_client_contact:first").prev().find("a")){
					// Activate previous tab after removing current active tab
					$("."+tabClass+"_client_contact:first").prev().find("a").tab("show");
				}
			}
			// Removing Address Content from the list of Contact Addresses
			$("."+tabClass+"_client_contact").remove();
		});
	});
	
	$("body").on("click", ".saveClientInfo", function(){
		var clientId = $(this).data("clientid");
		
		var dataString = new Array();
		
		dataString = $("#"+clientId+"_id_meliscommerce_client_page form").not(".clientContactForm, .clientContactAddressForm, .clientAddressForm").serializeArray();
		
		
		// Serializing Client Contact Data
		$("#"+clientId+"_id_meliscommerce_client_page form.clientContactForm").addClass(clientId+"_clientContactForm");
		$("."+clientId+"_clientContactForm").each(function(){
			var tabId = $(this).data("tabid");
			var clientContactForm = $(this).serializeArray();
			$.each(clientContactForm, function(){
				dataString.push({
					name: 'clientContacts['+tabId+']['+this.name+']',
					value: this.value
				});
			});
			
			// Serializing Client Contact Adddresses Data
			$("#"+tabId+"_contact_address form").each(function(){
				var contactAddressId = $(this).data("contactaddressid");
				var clientContactAddressForm = $(this).serializeArray();
				
				$.each(clientContactAddressForm, function(){
					dataString.push({
						name: 'clientContacts['+tabId+'][contact_address]['+contactAddressId+']['+this.name+']',
						value: this.value
					});
				});
			});
		});
		
		// Serializing Client Addresses Data
		$("#"+clientId+"_id_meliscommerce_client_page form.clientAddressForm").addClass(clientId+"_clientAddressForm");
		$("."+clientId+"_clientAddressForm").each(function(){
			var addressId = $(this).data("addressid");
			var clientAddressFrom = $(this).serializeArray();
			$.each(clientAddressFrom, function(){
				dataString.push({
					name: 'clientAddresses['+addressId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		dataString.push({
			name : 'clientId',
			value : clientId
		});
		
		var clientStatus = 0;
		if($('#'+clientId+'_cli_status input').is(':checked')){
			clientStatus = 1;
		}
		
		dataString.push({
			name : "cli_status",
			value: clientStatus
		});
		
		$(this).button("loading");
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComClient/saveClient",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
		}).done(function(data) {
			$(".saveClientInfo").button("reset");
			
			if(data.success){
				melisHelper.tabClose(clientId+"_id_meliscommerce_client_page");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
				melisHelper.tabOpen(data.clientContactName, "fa fa-user", data.clientId+"_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:data.clientId});
				melisHelper.zoneReload('id_meliscommerce_clients_list_content', 'meliscommerce_clients_list_content');
			}else{
//				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
//				melisCoreTool.highlightErrors(data.success, data.errors, "");
				highlightErrors(data.success, data.errors,  activeTabId+" form");
			}
			
			melisCore.flashMessenger();
		}).fail(function(){
			$(".saveClientInfo").button("reset");
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	$("body").on("click", ".clientOrderView", function(){
		var orderId   = $(this).closest('tr').attr('id');
		var orderRef  = $(this).closest('tr').find("td:nth-child(2)").text();
		melisHelper.tabOpen(orderRef, 'fa fa fa-cart-plus fa-2x', orderId+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : orderId});
	});
	
	$("body").on("click", ".clientOrderListRefresh", function(){
		var clientId = $(this).data("clientid");
		melisHelper.zoneReload(clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: clientId, activateTab:true});
	});
});

window.highlightErrors = function(success, errors, divContainer){
	
//	console.log(errors);
	// if all form fields are error color them red
	if(success === 0){
		
		if(divContainer !== ''){
			$("#" + divContainer + " .form-group label").css("color","#686868");
		}
		
		$.each( errors, function( key, error ) { 
			
			if("form" in error){
				$.each(this.form, function( fkey, fvalue ){
					$("#" + fvalue + " .form-control[name='"+key +"']").prev("label").css("color","red");
				});
			}else{
				if(divContainer !== ''){
					$("#" + divContainer + " .form-control[name='"+key +"']").prev("label").css("color","red");
				}
			}
		});
	}
	// remove red color for correctly inputted fields
	else{
		$("#" + divContainer + " .form-group label").css("color","#686868");
	}
}

function melisKoNotification(title, message, errors, closeByButtonOnly){
	
	( closeByButtonOnly !== 'closeByButtonOnly' ) ? closeByButtonOnly = 'overlay-hideonclick' : closeByButtonOnly = '';

	var errorTexts = '<h3>'+ title +'</h3>';
		errorTexts +='<h4>'+ message +'</h4>';
		$.each( errors, function( key, error ) {
			if(key !== 'label'){
				errorTexts += '<p class="modal-error-cont"><b>'+ (( errors[key]['label'] == undefined ) ? ((errors['label']== undefined) ? key : errors['label'] ) : errors[key]['label'] )+ ': </b>  ';
				// catch error level of object
				try {
					$.each( error, function( key, value ) {
						if(key !== 'label' && key !== 'form'){
							
							$errMsg = '';
							if(value instanceof Object){
								$errMsg = value[0];
							}else{
								$errMsg = value;
							}
							errorTexts += '<span><i class="fa fa-circle"></i>'+ $errMsg + '</span>';
						}
					});
				} catch(Tryerror) {
					if(key !== 'label' && key !== 'form'){
						 errorTexts +=  '<span><i class="fa fa-circle"></i>'+ error + '</span>';
					} 
				}	
				errorTexts += '</p>';
			}
		});
		
	var div = "<div class='melis-modaloverlay "+ closeByButtonOnly +"'></div>";
	div += "<div class='melis-modal-cont KOnotif'>  <div class='modal-content'>"+ errorTexts +" <span class='btn btn-block btn-primary'>"+ translations.tr_meliscore_notification_modal_Close +"</span></div> </div>";
	$body.append(div);
}

window.initClientStatus = function(){
	$('#cli_status').bootstrapSwitch();
}

window.initClientOrderList = function(data, tblSettings){
	
	// get Category Id from table data
	var clientId = $("#" + tblSettings.sTableId ).data("clientid");
	
	data.clientId = clientId;
}