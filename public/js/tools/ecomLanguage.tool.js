$(function(){
	var body = $("body");
	var zoneId = "id_meliscommerce_language_list_page_content_modal_form";
	var melisKey = 'meliscommerce_language_list_page_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComLanguage/renderLanguageListPageModalContainer';
	
	
	body.on("click", ".btnEcomLangDelete", function() {
		var id = $(this).parent().parent().attr('id');
		melisCoreTool.pending(".btnEcomLangDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_language_delete, 
			translations.tr_meliscore_tool_language_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComLanguage/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	     }).success(function(data){
	    	    	 	melisCoreTool.pending(".btnEcomLangDelete");
		    	    	if(data.success) {
		    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
		    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
		    	    		
		    	    	}
		    	    	else {
		    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
		    	    	}
		    	    	melisCoreTool.done(".btnEcomLangDelete");
		    	    	melisCore.flashMessenger();
	    	     }).error(function(){
	    	    	 alert( translations.tr_meliscore_error_message );
	    	     });
		});
		melisCoreTool.done(".btnEcomLangDelete");
	});
	
	body.on("click", ".btnEditComLang", function() {
		melisCoreTool.pending(".btnEditComLang");
		var id = $(this).parent().parent().attr('id');
		melisHelper.createModal(zoneId, melisKey, false, {langId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComLang");
		});
		
	});
	
	
	body.on("click", "#btnComAddLang", function() {
		melisCoreTool.pending("#btnComAddLang");
		melisHelper.createModal(zoneId, melisKey, false, {langId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddLang");
		});
		
	});
	
	body.on("click", "#btnComSaveLang", function() {
		var dataString = $("form#ecomlanguageform").serializeArray();
		saveType = $("form#ecomlanguageform").data("savetype");
		var status = $("#elang_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}
		dataString.push({
			name : 'elang_status',
			value: saveStatus
		});
		dataString.push({
			name : 'saveType',
			value: saveType
		});

		dataString = $.param(dataString);
		melisCoreTool.pending("#btnComSaveLang");
		melisCommerce.postSave('/melis/MelisCommerce/MelisComLanguage/save', dataString, function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage, '#72af46');
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_language_list_page_content_modal_form form#ecomlanguageform");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveLang");
		}, function() {
			melisCoreTool.done("#btnComSaveLang");
		});
	});
	
	
});