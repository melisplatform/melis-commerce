$(function() {
	var body = $("body");
	var zoneId = "id_meliscommerce_currency_content_modal_form";
	var melisKey = 'meliscommerce_currency_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComCurrency/renderCurrencyModalContainer';
	
	body.on("click", "#btnComAddCurrency", function() {
		melisCoreTool.pending("#btnComAddCurrency");
		melisHelper.createModal(zoneId, melisKey, false, {curId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddCurrency");
		});
	});
	
	body.on("click", ".btnEditComCurrency", function() {
		melisCoreTool.pending(".btnEditComCurrency");
		var id = $(this).parent().parent().attr('id');
		melisHelper.createModal(zoneId, melisKey, false, {curId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComCurrency");
		});
	});
	
	body.on("click", "#btnComSaveCurrency", function() {
		var dataString = $("form#ecomCurrencyForm").serializeArray();
		saveType = $("form#ecomCurrencyForm").data("savetype");

		var status = $("#cur_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}
		dataString.push({
			name : 'cur_status',
			value: saveStatus
		});

		dataString.push({
			name : 'saveType',
			value: saveType
		});
		dataString = $.param(dataString);
		
		melisCoreTool.pending("#btnComSaveCurrency");
		melisCommerce.postSave('/melis/MelisCommerce/MelisComCurrency/save', dataString, function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_currency_content_modal_form form#ecomCurrencyForm");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveCurrency");
		}, function() {
			melisCoreTool.done("#btnComSaveCurrency");
		});
	});
	
	body.on("click", ".btnComCurrencyDelete", function() {
		var id = $(this).parent().parent().attr('id');
		melisCoreTool.pending(".btnComCountryDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_currency_delete_currency, 
			translations.tr_meliscommerce_currency_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComCurrency/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	     }).success(function(data){
	    	    	 	melisCoreTool.pending(".btnComCurrencyDelete");
		    	    	if(data.success) {
		    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
		    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
		    	    		
		    	    	}
		    	    	else {
		    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
		    	    	}
		    	    	melisCoreTool.done(".btnComCurrencyDelete");
		    	    	melisCore.flashMessenger();
	    	     }).error(function(){
	    	    	 alert( translations.tr_meliscore_error_message );
	    	     });
		});
		melisCoreTool.done(".btnComCurrencyDelete");
	});
	
	body.on("click", ".btnComCurrencyMakeDefault", function(){
		var id = $(this).parent().parent().attr('id');
		melisCoreTool.pending(".btnComCurrencyMakeDefault");
		$.ajax({
	        type        : 'POST', 
	        url         : '/melis/MelisCommerce/MelisComCurrency/setDefaultCurrency',
	        data		: {id : id},
	        dataType    : 'json',
	        encode		: true,
	    }).success(function(data){
    	    	if(data.success) {
    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
    	    	}else{
    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
    	    	}
    	    	melisCoreTool.done(".btnComCurrencyMakeDefault");
    	    	melisCore.flashMessenger();
	    }).error(function(){
	    	alert( translations.tr_meliscore_error_message );
	    	melisCoreTool.done(".btnComCurrencyMakeDefault");
	    });
	});
});

window.reInitTableEcomCurrency = function(){
	$('tr.defaultEcomCurrency').find(".btnComCurrencyDelete").remove();
	$('tr.defaultEcomCurrency').find(".btnComCurrencyMakeDefault").remove();
}