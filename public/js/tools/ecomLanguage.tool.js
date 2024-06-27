$(function(){
	var $body 		= $("body"),
		zoneId 		= "id_meliscommerce_language_list_page_content_modal_form",
		melisKey 	= 'meliscommerce_language_list_page_content_modal_form',
		modalUrl 	= '/melis/MelisCommerce/MelisComLanguage/renderLanguageListPageModalContainer';

		//removes modal elements when clicking outside
		$body.on("click", function (e) {
			if ( $(e.target).hasClass('modal') ) {
				$('#id_meliscommerce_language_list_page_content_modal_form_container').modal('hide');
			}
		});
		
		$(document).on("submit", "form#ecomlanguageform", function(e) {
			var $this 		= $(this),
				formData 	= new FormData( $this[0] ),
				status 		= $("#elang_status").parent().hasClass("switch-on"),
				saveStatus 	= 0;

				saveType = $this.data("savetype");
			
				if ( status ) {
					saveStatus = 1;
				}

				formData.append("elang_status", saveStatus);
				formData.append("saveType", saveType);

				melisCoreTool.pending("#btnComSaveLang");
				$.ajax({
					type 		: 'POST',
					url  		: '/melis/MelisCommerce/MelisComLanguage/save',
					data 		: formData,
					processData : false,
					cache       : false,
					contentType : false,
					dataType    : 'json'
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
				}).fail(function(xhr) {
					melisCoreTool.done("#btnComSaveLang");
					alert( translations.tr_meliscore_error_message );
				});

				e.preventDefault();
		});

		$body.on("click", ".btnEcomLangDelete", function() {
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

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
							encode		: true
						}).done(function(data) {
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
						}).fail(function(){
							alert( translations.tr_meliscore_error_message );
						});
				});
				melisCoreTool.done(".btnEcomLangDelete");
		});
		
		$body.on("click", ".btnEditComLang", function() {
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

				melisCoreTool.pending(".btnEditComLang");
				melisHelper.createModal(zoneId, melisKey, false, {langId: id, saveType : "edit"},  modalUrl, function() {
					melisCoreTool.done(".btnEditComLang");
				});
		});
		
		$body.on("click", "#btnComAddLang", function() {
			melisCoreTool.pending("#btnComAddLang");
			melisHelper.createModal(zoneId, melisKey, false, {langId: null, saveType : "new"},  modalUrl, function() {
				melisCoreTool.done("#btnComAddLang");
			});
		});
});