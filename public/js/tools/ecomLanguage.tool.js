$(function(){
	var body = $("body");
	var zoneId = "id_meliscommerce_language_list_page_content_modal_form";
	var melisKey = 'meliscommerce_language_list_page_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComLanguage/renderLanguageListPageModalContainer';
	
	$(document).on("submit", "form#ecomlanguageform", function(e) {
		saveType = $(this).data("savetype");
		var formData = new FormData($(this)[0]);
		var status = $("#elang_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}

		formData.append("elang_status", saveStatus);
		formData.append("saveType", saveType);

		melisCoreTool.pending("#btnComSaveLang");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComLanguage/save',
			data : formData,
			processData : false,
			cache       : false,
			contentType : false,
			dataType    : 'json',
		}).done(function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_language_list_page_content_modal_form form#ecomlanguageform");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveLang");
		}).error(function(xhr) {
			console.log(xhr);
			melisCoreTool.done("#btnComSaveLang");
		});
		e.preventDefault();
	});


	body.on("click", ".btnEcomLangDelete", function() {
		var id = $(this).parents("tr").attr("id");
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
		    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
		    	    	}
		    	    	else {
		    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
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
		var id = $(this).parents("tr").attr("id");
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
});