$(function() {
	var $body = $("body");
	
		// add alert email recipients
		$body.on('click', '.addStockAlertEmail', function() {
			var stockAlert 			= $(this),
				userId 				= stockAlert.closest('div').find('.stockAlertSelect').val(),
				selectEmail 		= stockAlert.closest('div').find('.stockAlertSelect option:selected'),
				emailArea 			= stockAlert.closest('.email-alert-area').find('.email_area'),
				recipient 			= '';
			
				recipient += '<span class="alert-email-values" data-seaid="" data-userid="' + userId + '" data-alertemail="' + selectEmail.text() + '">';
				recipient += 	'<span class="ab-attr">';
				recipient += 		'<span class="alert-email-value-email">';
				recipient +=			selectEmail.text();
				recipient += 		'</span>';
				recipient += 		'<i class="alert-email-remove fa fa-times"></i>';
				recipient += 	'</span>';
				recipient += '</span>';
			
				if ( userId != '' ) {
					selectEmail.remove();
					emailArea.append(recipient);
					stockAlert.closest('.email-alert-area').find('.noAlertRecipients').hide();
				}
		});
		
		// remove email recipients
		$body.on('click', '.alert-email-values .alert-email-remove', function(){
			var alertEmail 	= $(this),
				recipient 	= alertEmail.closest('.alert-email-values'),
				userId 		= recipient.data('userid'),
				email 		= recipient.data('alertemail'),
				selectEmail = alertEmail.closest('.email-alert-area').find('.stockAlertSelect'),
				selected 	= selectEmail.val();
			
				selectEmail.append($('<option>', {
					value: userId,
					text: email
				}));
				
				var my_options = alertEmail.closest('.email-alert-area').find('.stockAlertSelect option');
				
					// sort the options
					my_options.sort(function(a,b) {
						if (a.value > b.value) return 1;
						if (a.value < b.value) return -1;
						return 0
					})

					selectEmail.empty().append( my_options );
					selectEmail.val(selected);
					recipient.remove();
		});
		
		// saves the main settings
		$body.on('click', '.saveSettings', function() {
			var $this 			= $(this),
				pageContainer 	= $this.closest('.container-level-a');
			
				melisCommerceSettings.submitStockAlertSettings(pageContainer);
		});
})

var melisCommerceSettings = (function(window) {
	
	function submitStockAlertSettings(pageContainer) {
		var stockAlertForm 	= $(pageContainer).find('#id_meliscommerce_settings_tabs_content_main_details_left form'),
			dataString 		= [],
			url 			= 'melis/MelisCommerce/MelisComSettings/saveSettings',
			emails 			= $(pageContainer).find('.alert-email-values'),
			ctr 			= 0,
			sea_id 			= '',
			sea_email 		= '',
			sea_user_id 	= '';

			Array.prototype.push.apply(dataString,$(stockAlertForm).serializeArray());
			
			emails.each(function(){
				var $this = $(this);

					sea_id 		= $this.data('seaid');
					sea_email 	= $this.data('alertemail');
					sea_user_id = $this.data('userid');
					
					dataString.push({ name : 'recipients['+ctr+'][sea_id]', value : sea_id });
					dataString.push({ name : 'recipients['+ctr+'][sea_email]', value : sea_email });
					dataString.push({ name : 'recipients['+ctr+'][sea_user_id]', value : sea_user_id });

					ctr++
			});
			
			melisCommerce.postSave(url, dataString, function(data) {		
				if ( data.success ) {
					melisHelper.tabClose("id_meliscommerce_settings_page");
					melisHelper.tabOpen(translations.tr_meliscommerce_settings, 'fa fa-wrench', 'id_meliscommerce_settings_page', 'meliscommerce_settings_page', {});
					melisHelper.melisOkNotification( data.textTitle, data.textMessage );
				}
				else {
					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_settings_page");
				}	
				melisCore.flashMessenger();	
			}, function(data) {
				alert( translations.tr_meliscore_error_message );
			});
	}
	
	return {
		submitStockAlertSettings : submitStockAlertSettings
	}

})(window);