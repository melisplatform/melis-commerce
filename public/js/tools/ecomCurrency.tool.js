$(function() {
	var $body 		= $("body"),
		zoneId 		= "id_meliscommerce_currency_content_modal_form",
		melisKey 	= 'meliscommerce_currency_content_modal_form',
		modalUrl 	= '/melis/MelisCommerce/MelisComCurrency/renderCurrencyModalContainer';

		$body.on("switch-change", ".make-switch", function(e, data){
			var $this = $(this);

				//check the state of the switch
				if ( $this.find('#cur_status').length ) {
					if ( data.value === false ) {
						//get currency id
						var $form 		= $this.closest('form'),
							currencyId 	= $form.find('#id_cur_id').val(),
							$switch 	= $('.make-switch');

							//disable the switch
							$switch.bootstrapSwitch('setActive', false);

							//don't change the state of the switch
							$switch.bootstrapSwitch('toggleState', true, true);
							//disable save button
							melisCoreTool.pending('#btnComSaveCurrency');

							$.ajax({
								type	: 'POST',
								url		: '/melis/MelisCommerce/MelisComCurrency/getCountriesUsingCurrency',
								data	: {currencyId: currencyId},
								dataType: 'json',
								encode	: true
							}).done(function (data) {
								if ( data.countries.length > 0 ) {
									var countriesHtml = '<ul class="container">';

										$.each(data.countries, function (key, value) {
											countriesHtml += '<li>' + value.ctry_name + '</li>';
										});

										countriesHtml += '</ul>';

										//prompt
										BootstrapDialog.show({
											title: translations.tr_meliscommerce_currency,
											message: translations.tr_meliscommerce_currency_prompt_are_you_sure + '<br><br>' +
											translations.tr_meliscommerce_currency_prompt_is_used +
											'<br>' + countriesHtml,
											type: BootstrapDialog.TYPE_WARNING,
											closable: true,
											buttons: [{
												label: translations.tr_meliscore_common_no,
												cssClass: 'btn-danger float-left',
												action: function (dialog) {
													//callback
													//enable the switch back
													$switch.bootstrapSwitch('setActive', true);
													//enable save button
													melisCoreTool.done('#btnComSaveCurrency');
													dialog.close();
												}
											}, {
												label: translations.tr_meliscore_common_yes,
												cssClass: 'btn-success',
												action: function (dialog) {
													//callback
													//enable the switch back
													$switch.bootstrapSwitch('setActive', true);
													//change the state of the switch
													$switch.bootstrapSwitch('toggleState', true, true);
													//enable save button
													melisCoreTool.done('#btnComSaveCurrency');
													dialog.close();
												}
											}]
										});
								} 
								else {
									//enable the switch back
									$switch.bootstrapSwitch('setActive', true);
									//change the state of the switch
									$switch.bootstrapSwitch('toggleState', true, true);
									//enable save button
									melisCoreTool.done('#btnComSaveCurrency');
								}
							}).fail(function () {
								//enable the switch back
								$switch.bootstrapSwitch('setActive', true);

								//enable save button
								melisCoreTool.done('#btnComSaveCurrency');

								alert( translations.tr_meliscore_error_message );
							});
					}
				}
		});

		$body.on("click", "#btnComAddCurrency", function() {
			melisCoreTool.pending("#btnComAddCurrency");
			melisHelper.createModal(zoneId, melisKey, false, {curId: null, saveType : "new"},  modalUrl, function() {
				melisCoreTool.done("#btnComAddCurrency");
			});
		});

		//removes modal elements when clicking outside
		$body.on("click", function (e) {
			if ( $(e.target).hasClass('modal') ) {
				$('#id_meliscommerce_currency_content_modal_form_container').modal('hide');
			}
		});
		
		$body.on("click", ".btnEditComCurrency", function() {
			var $this 	= $(this);
				id 		= $this.parents("tr").attr("id");

				melisCoreTool.pending(".btnEditComCurrency");
				melisHelper.createModal(zoneId, melisKey, false, {curId: id, saveType : "edit"},  modalUrl, function() {
					melisCoreTool.done(".btnEditComCurrency");
				});
		});
		
		$body.on("click", "#btnComSaveCurrency", function() {
			var $formCurrency 	= $("form#ecomCurrencyForm"),
				dataString 		= $formCurrency.serializeArray(),
				saveType 		= $formCurrency.data("savetype"),
				status 			= $("#cur_status").parent().hasClass("switch-on"),
				saveStatus 	= 0;

				if ( status ) {
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
					if ( data.success ) {
						$("div.modal").modal("hide");
						$("#" + activeTabId + " .melis-refreshTable").trigger("click");
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
						melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_currency_content_modal_form form#ecomCurrencyForm");
					}
					melisCore.flashMessenger();
					melisCoreTool.done("#btnComSaveCurrency");
				}, function() {
					melisCoreTool.done("#btnComSaveCurrency");
					alert( translations.tr_meliscore_error_message );
				});
		});
		
		$body.on("click", ".btnComCurrencyDelete", function() {
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

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
							encode		: true
						}).done(function(data){
								melisCoreTool.pending(".btnComCurrencyDelete");
								if(data.success) {
									$("#" + activeTabId + " .melis-refreshTable").trigger("click");
									melisHelper.melisOkNotification(data.textTitle, data.textMessage);
								}
								else {
									melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
								}
								melisCoreTool.done(".btnComCurrencyDelete");
								melisCore.flashMessenger();
						}).fail(function() {
							alert( translations.tr_meliscore_error_message );
						});
				});
				melisCoreTool.done(".btnComCurrencyDelete");
		});
		
		$body.on("click", ".btnComCurrencyMakeDefault", function(){
			var $this 	= $(this),
				id 		= $this.parents("tr").attr("id");

				melisCoreTool.pending(".btnComCurrencyMakeDefault");
				$.ajax({
					type        : 'POST', 
					url         : '/melis/MelisCommerce/MelisComCurrency/setDefaultCurrency',
					data		: {id : id},
					dataType    : 'json',
					encode		: true
				}).done(function(data){
					if(data.success) {
						$("#" + activeTabId + " .melis-refreshTable").trigger("click");
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}else{
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					}
					melisCoreTool.done(".btnComCurrencyMakeDefault");
					melisCore.flashMessenger();
				}).fail(function(){
					melisCoreTool.done(".btnComCurrencyMakeDefault");
					alert( translations.tr_meliscore_error_message );
				});
		});
});

window.reInitTableEcomCurrency = function() {
	$('tr.defaultEcomCurrency').find(".btnComCurrencyDelete").remove();
	$('tr.defaultEcomCurrency').find(".btnComCurrencyMakeDefault").remove();
}