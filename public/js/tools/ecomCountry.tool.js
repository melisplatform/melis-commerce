$(function() {
	var $body 		= $("body"),
		zoneId 		= "id_meliscommerce_country_list_page_content_modal_form",
		melisKey 	= 'meliscommerce_country_list_page_content_modal_form',
		modalUrl 	= '/melis/MelisCommerce/MelisComCountry/renderCountryListPageModalContainer';
	
		$body.on("click", "#btnComAddCountry", function() {
			melisCoreTool.pending("#btnComAddCountry");
			melisHelper.createModal(zoneId, melisKey, false, {ctryId: null, saveType : "new"},  modalUrl, function() {
				melisCoreTool.done("#btnComAddCountry");
			});
		});

		//removes modal elements when clicking outside
		/* $body.on("click", function(e) {
			if ( $(e.target).hasClass('modal') ) {
				// $('#id_meliscommerce_country_list_page_content_modal_form_container').modal('hide');
				melisCoreTool.hideModal("id_meliscommerce_country_list_page_content_modal_form_container");
			}
		}); */
		
		$body.on("click", ".btnEditComCountry", function() {
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

				melisCoreTool.pending(".btnEditComCountry");
				
				melisHelper.createModal(zoneId, melisKey, false, {ctryId: id, saveType : "edit"},  modalUrl, function() {
					melisCoreTool.done(".btnEditComCountry");
				});
		});

		$(document).on("submit", "form#ecomCountryform", function(e) {
			var $this 		= $(this),
				formData 	= new FormData( $this[0] ),
				status 		= $("#ctry_status").parent().hasClass("switch-on"),
				saveStatus 	= 0;

				saveType = $this.data("savetype");
			
				if ( status ) {
					saveStatus = 1;
				}

				formData.append("ctry_status", saveStatus);
				formData.append("saveType", saveType);

				melisCoreTool.pending("#btnComSaveCountry");
				$.ajax({
					type 		: 'POST',
					url  		: '/melis/MelisCommerce/MelisComCountry/save',
					data 		: formData,
					processData : false,
					cache       : false,
					contentType : false,
					dataType    : 'json'
				}).done(function(data) {
					if ( data.success ) {
						// $("div.modal").modal("hide");
						var modalId = $("div.modal").attr("id");
							if (modalId != "" && modalId != "undefined") {
								melisCoreTool.hideModal(modalId);
							}

						$("#" + activeTabId + " .melis-refreshTable").trigger("click");
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
						melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_country_list_page_content_modal_form form#ecomCountryform");
					}
					melisCore.flashMessenger();
					melisCoreTool.done("#btnComSaveCountry");
				}).fail(function(xhr) {
					melisCoreTool.done("#btnComSaveCountry");
					alert( translations.tr_meliscore_error_message );
				});

				e.preventDefault();
		});
		
		$body.on("click", ".btnComCountryDelete", function() {
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

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
							encode		: true
						}).done(function(data) {
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
						}).fail(function() {
							alert( translations.tr_meliscore_error_message );
						});
				});

				melisCoreTool.done(".btnComCountryDelete");
		});
});