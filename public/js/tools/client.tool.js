$(function() {
	var $body = $("body");

	$body.on("click", ".addNewClient", function() {
		melisHelper.tabOpen(
			translations.tr_meliscommerce_clients_add_client,
			"fa fa-user-plus",
			"0_id_meliscommerce_client_page",
			"meliscommerce_client_page",
			{ clientId: 0 },
			"id_meliscommerce_clients_list_page"
		);
	});

	//removes modal elements when clicking outside
	$body.on("click", function(e) {
		var $comModalCon = $(
				"#id_meliscommerce_client_modal_address_form_container"
			),
			$comModalAddCon = $(
				"#id_meliscommerce_client_modal_contact_address_form_container"
			);

		if ($(e.target).hasClass("modal")) {
			$comModalCon.modal("hide");
			$comModalAddCon.modal("hide");
			$comModalCon.modal("hide");
		}
	});

	$body.on("click", ".viewCleintInfo", function() {
		var $this = $(this),
			clientId = $this.parents("tr").attr("id"),
			dataString = new Array();

		dataString.push({
			name: "clientId",
			value: clientId,
		});

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/getClientContactName",
			data: dataString,
			dataType: "json",
			encode: true,
			cache: false,
		})
			.done(function(data) {
				$("#saveClientContact").removeAttr("disabled");

				if (data.success) {
					var navTabsGroup = "id_meliscommerce_clients_list_page";

					melisHelper.tabOpen(
						data.clientContactName,
						"fa fa-user",
						clientId + "_id_meliscommerce_client_page",
						"meliscommerce_client_page",
						{ clientId: clientId },
						navTabsGroup
					);
				} else {
					melisHelper.melisKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
				}
			})
			.fail(function() {
				$("#saveClientContact").removeAttr("disabled");
				alert(translations.tr_meliscore_error_message);
			});
	});

	$body.on("click", ".addNewContact", function() {
		var $this = $(this),
			clientId = $this.data("clientid");

		$(".addNewContact").attr("disabled", "disabled");

		// initialation of local variable
		zoneId = "id_meliscommerce_client_modal_contact_form";
		melisKey = "meliscommerce_client_modal_contact_form";
		modalUrl = "/melis/MelisCommerce/MelisComClient/renderClientModal";

		// requesitng to create modal and display after
		melisHelper.createModal(
			zoneId,
			melisKey,
			false,
			{ clientId: clientId },
			modalUrl,
			function() {
				$(".addNewContact").removeAttr("disabled");
			}
		);
	});

	$body.on("click", "#saveClientContact", function() {
		var $this = $(this),
			clientId = $this.data("clientid");
		emailList = [];

		// serialize the new array and send it to server
		dataString = $("#melisCommerceClientContactFormModal").serializeArray();

		dataString.push({
			name: "clientId",
			value: clientId,
		});

		$("#" + activeTabId)
			.find(".client-contact-tab-content form")
			.each(function(index, element) {
				emailList.push(
					$(this)
						.find("#cper_email")
						.val()
				);
			});

		dataString.push({
			name: "emailList",
			value: emailList,
		});

		dataString = $.param(dataString);

		$("#saveClientContact").attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/addClientContact",
			data: dataString,
			dataType: "json",
			encode: true,
			cache: false,
		})
			.done(function(data) {
				$("#saveClientContact").removeAttr("disabled");

				if (data.success) {
					$("#" + clientId + "_client_contact_tab_nav").append(
						data.clientContactDom.tabNav
					);
					$("#" + clientId + "_client_contact_tab_content").append(
						data.clientContactDom.tabContent
					);
					$("#nav_" + data.clientContactDom.tabId).tab("show");
					$("#id_meliscommerce_client_modal_contact_form_container").modal(
						"hide"
					);
				} else {
					melisHelper.melisKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
					melisCoreTool.highlightErrors(
						data.success,
						data.errors,
						"melisCommerceClientContactFormModal"
					);
				}
			})
			.fail(function() {
				$("#saveClientContact").removeAttr("disabled");
				alert(translations.tr_meliscore_error_message);
			});
	});

	$body.on("click", ".addNewContactAddress", function() {
		var $this = $(this),
			clientId = $this.data("clientid"),
			tabId = $this.data("tabid");

		$(".addNewContactAddress").attr("disabled", "disabled");

		// initialation of local variable
		zoneId = "id_meliscommerce_client_modal_contact_address_form";
		melisKey = "meliscommerce_client_modal_contact_address_form";
		modalUrl = "/melis/MelisCommerce/MelisComClient/renderClientModal";

		// requesitng to create modal and display after
		melisHelper.createModal(
			zoneId,
			melisKey,
			false,
			{ clientId: clientId, tabId: tabId },
			modalUrl,
			function() {
				$(".addNewContactAddress").removeAttr("disabled");
			}
		);
	});

	$body.on("click", "#saveClientContactAddress", function() {
		var $this = $(this),
			clientId = $this.data("clientid");
		tabId = $this.data("tabid");

		// serialize the new array and send it to server
		dataString = $(
			"#melisCommerceClientContactAddressFormModal"
		).serializeArray();

		dataString.push({
			name: "clientId",
			value: clientId,
		});

		dataString.push({
			name: "tabId",
			value: tabId,
		});

		dataString = $.param(dataString);

		$("#saveClientContactAddress").attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/addClientContactAddress",
			data: dataString,
			dataType: "json",
			encode: true,
			cache: false,
		})
			.done(function(data) {
				$("#saveClientContactAddress").removeAttr("disabled");

				if (data.success) {
					$("#" + tabId + "_contact_address").append(
						data.clientContactAddressDom.accordionContent
					);
					$("#nav_" + data.clientContactAddressDom.contactAddressId).click();
					$(
						"#id_meliscommerce_client_modal_contact_address_form_container"
					).modal("hide");
				} else {
					melisHelper.melisKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
					melisCoreTool.highlightErrors(
						data.success,
						data.errors,
						"melisCommerceClientContactAddressFormModal"
					);
				}
			})
			.fail(function() {
				$("#saveClientContactAddress").removeAttr("disabled");
				alert(translations.tr_meliscore_error_message);
			});
	});

	$body.on("click", ".addNewAddress", function() {
		var $this = $(this),
			clientId = $this.data("clientid");

		$(".addNewAddress").attr("disabled", "disabled");

		// initialation of local variable
		zoneId = "id_meliscommerce_client_modal_address_form";
		melisKey = "meliscommerce_client_modal_address_form";
		modalUrl = "/melis/MelisCommerce/MelisComClient/renderClientModal";

		// requesitng to create modal and display after
		melisHelper.createModal(
			zoneId,
			melisKey,
			false,
			{ clientId: clientId },
			modalUrl,
			function() {
				$(".addNewAddress").removeAttr("disabled");
			}
		);
	});

	$body.on("click", "#saveClientAddress", function() {
		var $this = $(this),
			clientId = $this.data("clientid");

		// serialize the new array and send it to server
		dataString = $("#melisCommerceClientAddressFormModal").serializeArray();

		dataString.push({
			name: "clientId",
			value: clientId,
		});

		dataString = $.param(dataString);

		$("#saveClientAddress").attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/addClientAddress",
			data: dataString,
			dataType: "json",
			encode: true,
		})
			.done(function(data) {
				$("#saveClientAddress").removeAttr("disabled");

				if (data.success) {
					$("#" + clientId + "_client_address_tab_nav").append(
						data.clientAddressDom.tabNav
					);
					$("#" + clientId + "_client_address_tab_content").append(
						data.clientAddressDom.tabContent
					);
					$("#nav_add_" + data.clientAddressDom.addressId).tab("show");
					$("#id_meliscommerce_client_modal_address_form_container").modal(
						"hide"
					);
				} else {
					melisHelper.melisKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
					melisCoreTool.highlightErrors(
						data.success,
						data.errors,
						"melisCommerceClientAddressFormModal"
					);
				}
			})
			.fail(function() {
				$("#saveClientAddress").removeAttr("disabled");
				alert(translations.tr_meliscore_error_message);
			});
	});

	$body.on("click", ".deleteClientCotactAddress", function() {
		var $this = $(this),
			addressId = $this.data("addressid"),
			addressAccordionId = $this.data("addressaccordionid"),
			isNewAdded = $this.data("isnewadded");

		// deletion confirmation
		melisCoreTool.confirm(
			translations.tr_meliscommerce_clients_common_label_yes,
			translations.tr_meliscommerce_clients_common_label_no,
			translations.tr_meliscommerce_client_delete_address,
			translations.tr_meliscommerce_client_delete_address_confirm_msg,
			function() {
				// Checking if Contact Address is not new entry
				if (isNewAdded == 0) {
					// Address id added to Form Deleted Client Address
					$("#deletedClientAddress").append(
						"<input name='deletedaddresses[]' value='" + addressId + "'>"
					);
				}
				// Removing Address Content from the list of Contact Addresses
				$("#" + addressAccordionId + "_contact_address_content").remove();
			}
		);
	});

	$body.on("click", ".deleteClientAddress", function() {
		var $this = $(this),
			addressId = $this.data("addressid"),
			tabClass = $this.data("tabclass"),
			isNewAdded = $this.data("isnewadded");

		// deletion confirmation
		melisCoreTool.confirm(
			translations.tr_meliscommerce_clients_common_label_yes,
			translations.tr_meliscommerce_clients_common_label_no,
			translations.tr_meliscommerce_client_delete_address,
			translations.tr_meliscommerce_client_delete_address_confirm_msg,
			function() {
				// Checking if Contact Address is not new entry
				if (isNewAdded == 0) {
					// Address id added to Form Deleted Client Address
					$("#deletedClientAddress").append(
						"<input name='deletedaddresses[]' value='" + addressId + "'>"
					);
				}

				if ($("." + tabClass + ":first").hasClass("active")) {
					if (
						$("." + tabClass + ":first")
							.next()
							.find("a").length
					) {
						// Activate next tab after removing current active tab
						$("." + tabClass + ":first")
							.next()
							.find("a")
							.tab("show");
					} else if (
						$("." + tabClass + ":first")
							.prev()
							.find("a")
					) {
						// Activate previous tab after removing current active tab
						$("." + tabClass + ":first")
							.prev()
							.find("a")
							.tab("show");
					}
				}
				// Removing Address Content from the list of Contact Addresses
				$("." + tabClass).remove();
			}
		);
	});

	$body.on("click", ".clientMainContact", function() {
		var $this = $(this),
			clientId = $this.data("clientid"),
			tabId = $this.data("tabid");

		if ($this.hasClass("fa-star-o")) {
			// Set other OFF
			$(
				"#" + clientId + "_client_contact_tab_content .clientMainContact"
			).removeClass("fa-star");
			$(
				"#" + clientId + "_client_contact_tab_content .clientMainContact"
			).addClass("fa-star-o");

			// Activate current star
			$this.addClass("fa-star");
			$this.removeClass("fa-star-o");

			// Set other form main contact input to zero (0)
			$("#" + clientId + "_client_contact_tab_content")
				.find('form input[name="cper_is_main_person"]')
				.val(0);

			// Set current switch main contact input to one (1)
			$("#" + tabId)
				.find("form input[name='cper_is_main_person']")
				.val(1);
		} else {
			// Deactivate current star
			$this.removeClass("fa-star");
			$this.addClass("fa-star-o");
			$("#" + tabId)
				.find("form input[name='cper_is_main_person']")
				.val(0);
		}
	});

	$body.on("switch-change", ".clientContactStatus", function() {
		var $this = $this,
			clientId = $this.data("clientid"),
			tabId = $this.data("tabid");

		if ($this.find(".switch-animate").hasClass("switch-on")) {
			// Set back On the current switch
			$this
				.find(".switch-animate")
				.removeClass("switch-off")
				.addClass("switch-on");
			// Set current switch input to one (1)
			$("#" + tabId)
				.find("form input[name='cper_status']")
				.val(1);
		} else {
			$("#" + tabId)
				.find("form input[name='cper_status']")
				.val(0);
		}
	});

	$body.on("switch-change", ".modalClientContactStatus", function() {
		var $this = $(this),
			clientId = $this.data("clientid"),
			$formModal = $("#melisCommerceClientContactFormModal"),
			$formModalStatus = $formModal.find("input[name='cper_status']");

		if ($this.find(".switch-animate").hasClass("switch-on")) {
			// Set back On the current switch
			$this
				.find(".switch-animate")
				.removeClass("switch-off")
				.addClass("switch-on");

			// Set current switch input to one (1)
			$formModalStatus.val(1);
		} else {
			$formModalStatus.val(0);
		}
	});

	$body.on("click", ".deleteClientContactAddress", function() {
		var $this = $(this),
			tabClass = $this.data("tabclass");

		// deletion confirmation
		melisCoreTool.confirm(
			translations.tr_meliscommerce_clients_common_label_yes,
			translations.tr_meliscommerce_clients_common_label_no,
			translations.tr_meliscommerce_client_delete_new_contact,
			translations.tr_meliscommerce_client_delete_new_contact_confirm_msg,
			function() {
				var $clientContFirst = $("." + tabClass + "_client_contact:first"),
					$clientCont = $("." + tabClass + "_client_contact");

				if ($clientContFirst.hasClass("active")) {
					if ($clientContFirst.next().find("a").length) {
						// Activate next tab after removing current active tab
						$clientContFirst
							.next()
							.find("a")
							.tab("show");
					} else if ($clientContFirst.prev().find("a")) {
						// Activate previous tab after removing current active tab
						$clientContFirst
							.prev()
							.find("a")
							.tab("show");
					}
				}

				// Removing Address Content from the list of Contact Addresses
				$clientCont.remove();
			}
		);
	});

	$body.on("click", ".saveClientInfo", function() {
		var $this = $(this),
			clientId = $this.data("clientid"),
			dataString = new Array();

		dataString = $("#" + clientId + "_id_meliscommerce_client_page form")
			.not(".clientContactForm, .clientContactAddressForm, .clientAddressForm")
			.serializeArray();

		// Serializing Client Contact Data
		$(
			"#" + clientId + "_id_meliscommerce_client_page form.clientContactForm"
		).addClass(clientId + "_clientContactForm");
		$("." + clientId + "_clientContactForm").each(function() {
			var $this = $(this),
				tabId = $this.data("tabid"),
				clientContactForm = $this.serializeArray();

			$.each(clientContactForm, function() {
				dataString.push({
					name: "clientContacts[" + tabId + "][" + this.name + "]",
					value: this.value,
				});
			});

			// Serializing Client Contact Adddresses Data
			$("#" + tabId + "_contact_address form").each(function() {
				var $this = $(this),
					contactAddressId = $this.data("contactaddressid"),
					clientContactAddressForm = $this.serializeArray();

				$.each(clientContactAddressForm, function() {
					dataString.push({
						name:
							"clientContacts[" +
							tabId +
							"][contact_address][" +
							contactAddressId +
							"][" +
							this.name +
							"]",
						value: this.value,
					});
				});
			});
		});

		// Serializing Client Addresses Data
		$(
			"#" + clientId + "_id_meliscommerce_client_page form.clientAddressForm"
		).addClass(clientId + "_clientAddressForm");
		$("." + clientId + "_clientAddressForm").each(function() {
			var $this = $(this),
				addressId = $this.data("addressid"),
				clientAddressFrom = $this.serializeArray();

			$.each(clientAddressFrom, function() {
				dataString.push({
					name: "clientAddresses[" + addressId + "][" + this.name + "]",
					value: this.value,
				});
			});
		});

		dataString.push({
			name: "clientId",
			value: clientId,
		});

		var clientStatus = 0;

		if ($("#" + clientId + "_cli_status input").is(":checked")) {
			clientStatus = 1;
		}

		dataString.push({
			name: "cli_status",
			value: clientStatus,
		});

		var file = $this
			.closest(".tab-pane")
			.find('#id_meliscommerce_clients_company_form input[type="file"]')
			.prop("files")[0];
		var form_data = new FormData();

		if (file !== undefined) {
			form_data.append("ccomp_logo", file);
		}

		$.each(dataString, function(key, input) {
			form_data.append(input.name, input.value);
		});

		$this.attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/saveClient",
			data: form_data,
			cache: false,
			contentType: false,
			processData: false,
		})
			.done(function(data) {
				if (data.success) {
					var navTabsGroup = "id_meliscommerce_clients_list_page";

					melisHelper.tabClose(clientId + "_id_meliscommerce_client_page");
					melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					melisHelper.tabOpen(
						data.clientContactName,
						"fa fa-user",
						data.clientId + "_id_meliscommerce_client_page",
						"meliscommerce_client_page",
						{ clientId: data.clientId },
						navTabsGroup
					);
					melisHelper.zoneReload(
						"id_meliscommerce_clients_list_content",
						"meliscommerce_clients_list_content"
					);
				} else {
					melisClientKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
					clientHighlightErrors(
						data.success,
						data.errors,
						activeTabId + " form"
					);
				}

				melisCore.flashMessenger();
			})
			.fail(function() {
				alert(translations.tr_meliscore_error_message);
			})
			.always(() => {
				$this.removeAttr("disabled");
			});
	});

	$body.on("click", ".clientOrderView", function() {
		var $this = $(this),
			orderId = $this.closest("tr").attr("id"),
			orderRef = $this
				.closest("tr")
				.find("td:nth-child(2)")
				.text(),
			navTabsGroup = "id_meliscommerce_order_list_page";

		// Open parent tab
		melisHelper.tabOpen(
			translations.tr_meliscommerce_orders_Orders,
			"fa fa fa-cart-plus fa-2x",
			"id_meliscommerce_order_list_page",
			"meliscommerce_order_list_page"
		);

		var alreadyOpen = $(
			"body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']"
		);

		// check if it exists
		var checkOrders = setInterval(function() {
			if (alreadyOpen.length) {
				melisHelper.tabOpen(
					orderRef,
					"fa fa fa-cart-plus fa-2x",
					orderId + "_id_meliscommerce_orders_page",
					"meliscommerce_orders_page",
					{ orderId: orderId },
					navTabsGroup
				);
				clearInterval(checkOrders);
			}
		}, 500);
	});

	$body.on("click", ".clientOrderListRefresh", function() {
		var $this = $(this),
			clientId = $this.data("clientid");

		melisHelper.zoneReload(
			clientId + "_id_meliscommerce_client_page_tab_orders",
			"meliscommerce_client_page_tab_orders",
			{ clientId: clientId, activateTab: true }
		);
	});

	$body.on("click", ".clientsExport", function() {
		if (!melisCoreTool.isTableEmpty("clientListTbl")) {
			// initialation of local variable
			zoneId = "id_meliscommerce_client_list_content_export_form";
			melisKey = "meliscommerce_client_list_content_export_form";
			modalUrl =
				"/melis/MelisCommerce/MelisComClientList/renderClientListModal";

			// requesitng to create modal and display after
			melisHelper.createModal(
				zoneId,
				melisKey,
				false,
				{},
				modalUrl,
				function() {
					melisCoreTool.done(this);
				}
			);
		}
	});

	$body.on("click", "#exportClients", function() {
		var button = $(this),
			formValues = button
				.closest("#id_meliscommerce_client_list_content_export_form")
				.find("form")
				.serializeArray(),
			target = "id_meliscommerce_client_list_content_export_form";

		melisCoreTool.pending(button);

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClientList/clientsExportValidate",
			data: formValues,
			dataType: "json",
			encode: true,
		}).done(function(data) {
			if (!data.success) {
				melisHelper.melisKoNotification(
					data.textTitle,
					data.textMessage,
					data.errors
				);
				melisCoreTool.highlightErrors(0, data.errors, target);

				$(".date_start")
					.prev("label")
					.css("color", "#686868");
				$(".date_end")
					.prev("label")
					.css("color", "#686868");

				$.each(data.errors, function(key, error) {
					if (key == "date_start") {
						$(".date_start")
							.prev("label")
							.css("color", "red");
					}
					if (key == "date_end") {
						$(".date_end")
							.prev("label")
							.css("color", "red");
					}
				});
			} else {
				melisCoreTool.exportData(
					"/melis/MelisCommerce/MelisComClientList/clientsExportToCsv"
				);
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
		});

		melisCoreTool.done(button);
	});

	$body.on("click", ".shopping_cart", function() {
		var $this = $(this),
			href = $this.attr("href"),
			$tabContent = $(href);

		if (melisCore.screenSize < 768) {
			$tabContent.find(".clientOrderListRefresh").trigger("click");
		}
	});

	// client group filter
	$body.on("change", "#clientsGroupSelect", function() {
		$("#clientListTbl")
			.DataTable()
			.ajax.reload();
	});

	$body.on("click", ".delete-client-email", function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $emailContainer = $(this).closest(".client-email-dropdown");
		var $ul = $(this).closest("ul");
		var $li = $(this).closest("li");
		var id = $(this).data("id");

		melisCoreTool.confirm(
			translations.tr_meliscommerce_clients_common_label_yes,
			translations.tr_meliscommerce_clients_common_label_no,
			translations.tr_meliscommerce_clients_delete_email,
			translations.tr_meliscommerce_clients_delete_email_message,
			function() {
				$.ajax({
					type: "POST",
					url: "/melis/MelisCommerce/MelisComClient/deleteClientPersonEmail",
					data: {
						cpmail_id: id,
					},
					dataType: "json",
					encode: true,
				}).done(function(data) {
					if (data.success == 1) {
						$li.remove();

						if ($ul.find("li").length == 1) {
							$emailContainer.remove();
						}

						melisCore.flashMessenger();
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
				});
			}
		);
	});

	$body.on("click", ".client-email-dropdown-item", function(e) {
		e.preventDefault();
		$(this)
			.closest(".form-group")
			.find("input")
			.val(
				$(this)
					.text()
					.trim()
			);
	});

	$body.on("mouseenter", ".client-email-dropdown", function() {
		$(this).addClass("show");
		$(this)
			.find(".client-email-dropdown-content")
			.addClass("show");
	});

	$body.on("mouseleave", ".client-email-dropdown", function() {
		$(this).removeClass("show");
		$(this)
			.find(".client-email-dropdown-content")
			.removeClass("show");
	});
});

window.clientHighlightErrors = function(success, errors, divContainer) {
	// if all form fields are error color them red
	if (success === 0) {
		if (divContainer !== "") {
			$("#" + divContainer + " .form-group label").css("color", "#686868");
		}

		$.each(errors, function(key, error) {
			if ("form" in error) {
				$.each(this.form, function(fkey, fvalue) {
					$("#" + fvalue + " .form-control[name='" + key + "']")
						.prev("label")
						.css("color", "red");
				});
			} else {
				if (divContainer !== "") {
					$("#" + divContainer + " .form-control[name='" + key + "']")
						.prev("label")
						.css("color", "red");
				}
			}
		});
	}
	// remove red color for correctly inputted fields
	else {
		$("#" + divContainer + " .form-group label").css("color", "#686868");
	}
};

function melisClientKoNotification(title, message, errors, closeByButtonOnly) {
	if (typeof closeByButtonOnly === "undefined")
		closeByButtonOnly = "closeByButtonOnly";

	closeByButtonOnly !== "closeByButtonOnly"
		? (closeByButtonOnly = "overlay-hideonclick")
		: (closeByButtonOnly = "");

	var errorTexts = "<h3>" + melisHelper.melisTranslator(title) + "</h3>";

	errorTexts += "<h4>" + melisHelper.melisTranslator(message) + "</h4>";

	$.each(errors, function(key, error) {
		if (key !== "label") {
			errorTexts +=
				'<p class="modal-error-cont"><b>' +
				(errors[key]["label"] == undefined
					? errors["label"] == undefined
						? key
						: errors["label"]
					: errors[key]["label"]) +
				": </b>  ";
			// catch error level of object
			try {
				$.each(error, function(key, value) {
					if (key !== "label" && key !== "form") {
						$errMsg = "";
						if (value instanceof Object) {
							$errMsg = value[0];
						} else {
							$errMsg = value;
						}
						errorTexts +=
							'<span><i class="fa fa-circle"></i>' + $errMsg + "</span>";
					}
				});
			} catch (Tryerror) {
				if (key !== "label" && key !== "form") {
					errorTexts +=
						'<span><i class="fa fa-circle"></i>' + error + "</span>";
				}
			}
			errorTexts += "</p>";
		}
	});

	var div = "<div class='melis-modaloverlay " + closeByButtonOnly + "'></div>";

	div +=
		"<div class='melis-modal-cont KOnotif'>  <div class='modal-content'>" +
		errorTexts +
		" <span class='btn btn-block btn-primary'>" +
		translations.tr_meliscore_notification_modal_Close +
		"</span></div> </div>";

	$body.append(div);
}

window.initClientStatus = function() {
	$("#cli_status").bootstrapSwitch();
};

window.initClientOrderList = function(data, tblSettings) {
	// get Category Id from table data
	clientId = $("#" + tblSettings.sTableId).data("clientid");
	data.clientId = clientId;
	data.osta_id = $(
		"#" +
			clientId +
			"_id_meliscommerce_client_page_tab_orders .orderFilterStatus"
	).val();
	data.startDate = $("#" + clientId + "_tableClientOrderList").data(
		"dStartDate"
	);
	data.endDate = $("#" + clientId + "_tableClientOrderList").data("dEndDate");

	var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';

	if (tblSettings.iDraw > 1) {
		dateSelectionContent =
			translations.tr_meliscore_datepicker_select_date +
			icon +
			"<span class='sdate'>" +
			dStartDate +
			" - " +
			dEndDate +
			'</span> <b class="caret"></b>';
		$(
			"#" +
				clientId +
				"_tableClientOrderList_wrapper .dt_orderdatepicker .dt_dateInfo"
		).html(dateSelectionContent);
	}

	dStartDate = "";
	dEndDate = ""; //clear date when Prospects page is reloaded
};

//table client custom title on icon
window.initClientListTitle = function() {
	$("#" + clientId + "_tableClientOrderList .icon-shippment")
		.parent("th")
		.attr("title", translations.tr_meliscommerce_clients_list_col_products);
	$("#" + clientId + "_tableClientOrderList .fa-usd")
		.parent("th")
		.attr("title", translations.tr_meliscommerce_clients_list_col_price_title);
};

window.initClientContactAddressForm = function() {
	var tabId = $("#saveClientContactAddress").data("tabid"),
		$addFormModal = $("#melisCommerceClientContactAddressFormModal");

	$addFormModal.find("#cadd_civility").val(
		$("#" + tabId + "_contact_form")
			.find("#cper_civility")
			.val()
	);
	$addFormModal.find("#cadd_firstname").val(
		$("#" + tabId + "_contact_form")
			.find("#cper_firstname")
			.val()
	);
	$addFormModal.find("#cadd_name").val(
		$("#" + tabId + "_contact_form")
			.find("#cper_name")
			.val()
	);
	$addFormModal.find("#cadd_middle_name").val(
		$("#" + tabId + "_contact_form")
			.find("#cper_middle_name")
			.val()
	);
};

window.companyLogoPreview = function(id, fileInput) {
	if (fileInput.files && fileInput.files[0]) {
		$("#" + activeTabId + " .client-company-preview").css("display", "");
		var reader = new FileReader();

		reader.onload = function(e) {
			$("#" + activeTabId + " " + id).attr("src", e.target.result);
		};

		reader.readAsDataURL(fileInput.files[0]);
	} else {
		if ($("#" + activeTabId + " " + id).data("img") !== "") {
			$("#" + activeTabId + " " + id).attr(
				"src",
				$("#" + activeTabId + " " + id).data("img")
			);
		} else {
			$("#" + activeTabId + " .client-company-preview").css("display", "none");
		}
	}
};

window.initClientsFilters = function(d) {
	//clients group filter
	if ($("#clientsGroupSelect").length) {
		d.cgroup_id = $("#clientsGroupSelect").val();
	}
};
