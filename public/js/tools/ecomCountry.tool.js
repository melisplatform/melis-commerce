$(function() {
	var body = $("body");
	var zoneId = "id_meliscommerce_country_list_page_content_modal_form";
	var melisKey = 'meliscommerce_country_list_page_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComCountry/renderCountryListPageModalContainer';
	
	body.on("click", "#btnComAddCountry", function() {
		melisCoreTool.pending("#btnComAddCountry");
		melisHelper.createModal(zoneId, melisKey, false, {ctryId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddCountry");
		});
	});

	//removes modal elements when clicking outside
	body.on("click", function (e) {
		if ($(e.target).hasClass('modal')) {
			$('#id_meliscommerce_country_list_page_content_modal_form_container').modal('hide');
		}
	});
	
	body.on("click", ".btnEditComCountry", function() {
		melisCoreTool.pending(".btnEditComCountry");
		var id = $(this).parents("tr").attr("id");
		melisHelper.createModal(zoneId, melisKey, false, {ctryId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComCountry");
		});
		
	});

	$(document).on("submit", "form#ecomCountryform", function(e) {
		saveType = $(this).data("savetype");
		var formData = new FormData($(this)[0]);
		var status = $("#ctry_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}

		formData.append("ctry_status", saveStatus);
		formData.append("saveType", saveType);

		melisCoreTool.pending("#btnComSaveCountry");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComCountry/save',
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
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_country_list_page_content_modal_form form#ecomCountryform");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveCountry");
		}).error(function(xhr) {
			melisCoreTool.done("#btnComSaveCountry");
		});
		e.preventDefault();
	});
	
	body.on("click", ".btnComCountryDelete", function() {
		var id = $(this).parents("tr").attr("id");
		melisCoreTool.pending(".btnComCountryDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_country_delete_country, 
			translations.tr_meliscommerce_country_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComCountry/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	    }).success(function(data){
    	    	 	melisCoreTool.pending(".btnComCountryDelete");
	    	    	if(data.success) {
	    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
	    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
	    	    	}
	    	    	else {
	    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
	    	    	}
	    	    	melisCoreTool.done(".btnComCountryDelete");
	    	    	melisCore.flashMessenger();
	    	    }).error(function(){
	    	    	alert( translations.tr_meliscore_error_message );
	    	    });
		});
		melisCoreTool.done(".btnComCountryDelete");
	});
});