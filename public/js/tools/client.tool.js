$(function() {
	var $body = $("body");
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
			accountId = $this.parents("tr").attr("id"),
			dataString = new Array();

		dataString.push({
			name: "accountId",
			value: accountId
		});

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComClient/getAccountName",
			data: dataString,
			dataType: "json",
			encode: true,
			cache: false,
		})
			.done(function(data) {
				$("#saveClientContact").removeAttr("disabled");

				if (data.success) {
					var navTabsGroup = "id_meliscommerce_clients_list_page";

					if($("#id_meliscommerce_clients_list_page").length > 0) {
                        melisHelper.tabOpen(
                            data.accountName,
                            "fa fa-user",
                            accountId + "_id_meliscommerce_client_page",
                            "meliscommerce_client_page",
                            {clientId: accountId},
                            navTabsGroup
                        );
                    }else{
                        melisHelper.tabOpen(
                            translations.tr_meliscommerce_clients_Clients,
                            "fa fa-users fa-2x",
                            "id_meliscommerce_clients_list_page",
                            "meliscommerce_clients_list_page",
                            {},
                            null,
                            function(){
                                melisHelper.tabOpen(
                                    data.accountName,
                                    "fa fa-user",
                                    accountId + "_id_meliscommerce_client_page",
                                    "meliscommerce_client_page",
                                    {clientId: accountId},
                                    navTabsGroup
                                );
                            }
                        );
					}
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
		var $this = $(this),
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
						data.clientName,
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
			.always(function() {
				$this.removeAttr("disabled");
			});
	});

	$body.on("click", ".clientOrderView", function() {
		viewClientOrder($(this).closest("tr").attr("id"), $(this).closest("tr").find("td:nth-child(2) a").text());	

		// Open parent tab


		// check if it exists
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

    $body.on("click", ".clientsExportAccounts", function() {
        if (!melisCoreTool.isTableEmpty("clientListTbl")) {
            // initialation of local variable
            zoneId = "id_meliscommerce_client_list_export_accounts_form";
            melisKey = "meliscommerce_client_list_export_accounts_form";
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

    $body.on("click", "#exportAccounts", function(e){
        e.preventDefault();

        var _this = $(this);
        var filters = {};

        var data = $("form#client-list-export-accounts").serializeArray();
        filters['groupId'] = $("#clientsGroupSelect").val();
        filters['status'] = $("#clientsStatusSelect").val();
        filters['search'] = $("#clientListTbl_filter input[type='search']").val();

        $.each(data, function(key, val){
            filters[val.name] = val.value;
        });

        $.ajax({
            url: "/melis/MelisCommerce/MelisComClientList/exportAccounts",
            data: $.param(filters),
            type: "GET",
            beforeSend: function(){
                _this.attr("disabled", true);
            }
        }).done(function(data, status, request){
            var fileName = request.getResponseHeader("fileName");
            //decode utf-8
            fileName = decodeURIComponent(escape(fileName));
            var mime = request.getResponseHeader("Content-Type");
            var newContent = "";

            for (var i = 0; i < data.length; i++) {
                newContent += String.fromCharCode(data.charCodeAt(i) & 0xFF);
            }

            var bytes = new Uint8Array(newContent.length);

            for (var i = 0; i < newContent.length; i++) {
                bytes[i] = newContent.charCodeAt(i);
            }

            var blob = new Blob([bytes], {type: mime});
            saveAs(blob, fileName);

            _this.attr("disabled", false);
            $("#id_meliscommerce_client_list_export_accounts_form_container").modal("hide");
        }).fail(function(){
            alert(translations.tr_meliscore_error_message);
        });
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

    // client status filter
    $body.on("change", "#clientsStatusSelect", function() {
        $("#clientListTbl")
            .DataTable()
            .ajax.reload();
    });
    $body.on("mouseenter mouseout", ".clientOrderRefToolTipHoverEvent", function(e) {
	    var $this = $(this),
	        orderId = $this.data("orderid"),
	        orderRef = $(this).text(),
	        loaderText  = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
	        $("table#orderBasketTable"+orderId).attr('data-orderid', orderId);
	        $("table#orderBasketTable"+orderId).attr('data-ref', orderRef);
	        $(".thClassColId").attr("style", "");
	        $.each($("table#orderBasketTable"+orderId + " thead").nextAll(), function(i,v) {
	            $(v).remove();
	        });
	        $(loaderText).insertAfter("table#orderBasketTable"+orderId + " thead");
	        $.ajax({
	            type        : 'POST',
	            url         : '/melis/MelisCommerce/MelisComOrderList/getOrderBasketToolTip',
	            data		: {orderId : orderId},
	            dataType    : 'json',
	            encode		: true,
	        }).done(function(data){
	            $("div.qtipLoader").remove();
	            if(data.content.length === 0) {
	                $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
	            }
	            else {
	                $.each($("table#orderBasketTable"+orderId + " thead").nextAll(), function(i,v) {
	                    $(v).remove();
	                });
	                $.each(data.content.reverse(), function(i ,v) {
	                    $(v).insertAfter("table#orderBasketTable"+orderId + " thead")
	                });
	            }
	        }).fail(function() {
	            alert( translations.tr_meliscore_error_message );
	        });
	});
	$body.on("click", "[id^='orderBasketTable']", function() {
		viewClientOrder($(this).attr("data-orderid"), $(this).attr("data-ref"));		
	});

	$body.on("click", ".addNewClientOrder", function() {
		var navTabsGroup = "id_meliscommerce_order_list_page",
		 	clientId = $(this).attr("data-clientid");
		 	
		melisHelper.tabOpen(
			translations.tr_meliscommerce_orders_Orders,
			"fa fa fa-cart-plus fa-2x",
			"id_meliscommerce_order_list_page",
			"meliscommerce_order_list_page"
		);

		var alreadyOpen = $(
			"body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']"
		);

		var checkOrdersTab = setInterval(function() {
			if (alreadyOpen.length) {
				var oldClientIdInNewOrderTab = $('#id_meliscommerce_order_checkout').data('clientid');
				melisHelper.tabOpen(
					translations.tr_meliscommerce_order_checkout_title,
					"fa fa fa-plus fa-2x",
					"id_meliscommerce_order_checkout",
					"meliscommerce_order_checkout",
					{clientId: clientId},
					navTabsGroup,
					addNewOrderCallback(oldClientIdInNewOrderTab, clientId)
				);
				clearInterval(checkOrdersTab);
			}
		}, 500);		
	});

	$body.on("click", ".deleteClient", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var accountId = current_row.attr("id");

        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_client_delete_account,
            translations.tr_meliscommerce_client_delete_account_msg,
            function() {
				$.ajax({
					'url': '/melis/MelisCommerce/MelisComClient/deleteAccount',
					'data': {accountId: accountId},
					'type': 'POST'
				}).done(function(data){
					if(data.success){
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                        melisHelper.zoneReload('id_meliscommerce_clients_list_page', 'meliscommerce_clients_list_page');
					}else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
					}
				});
            }
        );
	});

	$body.on("click", ".accountContactUnlink", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var accountId = current_row.attr("data-accountid");
        var contactId = current_row.attr("id");

        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_client_unlink_contact,
            translations.tr_meliscommerce_client_unlink_contact_msg,
            function() {
                $.ajax({
                    'url': '/melis/MelisCommerce/MelisComClient/unlinkAccountContact',
                    'data': {accountId: accountId, contactId: contactId},
                    'type': 'POST'
                }).done(function(data){
                    if(data.success){
                        $("#"+data.accountId+"_accountContactList").DataTable().ajax.reload();
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                        if($("#id_meliscommerce_contact_list_page").length > 0){
                        	if($("#"+contactId+"_id_meliscommerce_contact_page_content_tab_association").length > 0) {
                                melisHelper.zoneReload(
                                    contactId + "_id_meliscommerce_contact_page_content_tab_association",
                                    "meliscommerce_contact_page_content_tab_association",
                                    {contactId: contactId, activateTab: true}
                                );
                                accountToolSelectedContact = contactId;
                                accountToolInitContactAutoSuggest = true;
                            }
                        }
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
                    }
                });
            }
        );
	});

	$body.on("click", ".accountContactLink", function(){
		var input = $("#"+activeTabId+" input.link-contact-data");
		var contactId = input.data("contactid");
		var accountId = input.data("accountid");
        $.ajax({
            'url': '/melis/MelisCommerce/MelisComClient/linkAccountContact',
            'data': {accountId: accountId, contactId: contactId},
            'type': 'POST'
        }).done(function(data){
            if(data.success){
                // $("#"+data.accountId+"_accountContactList").DataTable().ajax.reload();
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                melisHelper.zoneReload(
                    accountId + "_id_meliscommerce_client_page_tab_contact",
                    "meliscommerce_client_page_tab_contact",
                    { clientId: accountId, activateTab: true }
                );

                if($("#id_meliscommerce_contact_list_page").length > 0){
                	if($("#"+contactId+"_id_meliscommerce_contact_page_content_tab_association").length > 0) {
                        melisHelper.zoneReload(
                            contactId + "_id_meliscommerce_contact_page_content_tab_association",
                            "meliscommerce_contact_page_content_tab_association",
                            {contactId: contactId, activateTab: true}
                        );
                        accountToolSelectedContact = contactId;
                        accountToolInitContactAutoSuggest = true;
                    }
                }
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
            }
        });
	});

    $body.on("click", ".accountsImport", function() {
        if (!melisCoreTool.isTableEmpty("clientListTbl")) {
            // initialation of local variable
            zoneId = "id_meliscommerce_client_list_import_accounts_form";
            melisKey = "meliscommerce_client_list_import_accounts_form";
            modalUrl =
                "/melis/MelisCommerce/MelisComContact/renderContactListModal";

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

    $body.on("click", ".updateDefaultContact", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var $this = $(this);
        var carId = current_row.attr("data-carid");
        var accountId = $this.data("accountid");
        var data = $this.data("vdata");
        var contactId = current_row.attr("id");

        $.ajax({
            'url': '/melis/MelisCommerce/MelisComClient/updateDefaultContact',
            'data': {accountId: accountId, carId: carId, car_default_person: data},
            'type': 'POST'
        }).done(function(data){
            if(data.success){
                $("#"+accountId+"_accountContactList").DataTable().ajax.reload();
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                if($("#id_meliscommerce_contact_list_page").length > 0){
                    // if($("#"+contactId+"_id_meliscommerce_contact_page_content_tab_association").length > 0) {
                    //     melisHelper.zoneReload(
                    //         contactId + "_id_meliscommerce_contact_page_content_tab_association",
                    //         "meliscommerce_contact_page_content_tab_association",
                    //         {contactId: contactId, activateTab: true}
                    //     );
                    //     accountToolSelectedContact = contactId;
                    //     accountToolInitContactAutoSuggest = true;
                    // }else{//reload every thing
                        $("li[data-tool-id='id_meliscommerce_contact_list_page'] .nav-group-dropdown li").each(function () {
                        // $("#" + contactId + "_contactAssocAccountList tbody tr").each(function () {
                        	var tableContactId = $(this).data("tool-id").replace("_id_meliscommerce_contact_page","");
                            melisHelper.zoneReload(
                                tableContactId + "_id_meliscommerce_contact_page_content_tab_association",
                                "meliscommerce_contact_page_content_tab_association",
                                {contactId: tableContactId, activateTab: true},
								function(){
                                    accountToolSelectedContact = tableContactId;
                                    accountToolInitContactAutoSuggest = true;
								}
                            );
                        });
					// }
                }
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
            }
        });
    });

    //test contact imports
    $body.on("click", "#importAccounts", function(e){
        var form = $("#account-list-import-accounts");
        var formData = new FormData(form[0]);

        $.ajax({
            type: 'POST',
            url: '/melis/MelisCommerce/MelisComClient/validateAccountsImportsForm',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                // _this.attr('disabled', true);s
            }
        }).done(function (data) {
            if(data.success){
                importContacts(formData, "/melis/MelisCommerce/MelisComClient/importAccounts");
            }else{
                melisHelper.melisKoNotification(data.title, data.message, data.errors);
                melisHelper.highlightMultiErrors(data.success, data.errors, "#account-list-import-accounts");
            }

            // _this.attr('disabled', false);
        }).fail(function () {
            alert(translations.tr_meliscore_error_message);
        });

        e.preventDefault();
    });

    /**
     * Run import
     * @param data
     * @param url
     * @param type
     */
    function importContacts(data, url) {
        var resultsContainer = $(".test-results .results ul").empty();
        var title = $(".test-results .results p").empty();

        updateProgressValue(0);

        $("#account-list-import-accounts").hide();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                updateProgressValue(20);
                $("#importAccounts").attr("disabled", true);
            }
        }).done(function (data) {
            updateProgressValue(90);
            setTimeout(function(){
                updateProgressValue(100);
                if(data.success){
                    title.text(data.textMessage);
                    // $('#clientListTbl').DataTable().ajax.reload();
					melisHelper.zoneReload('id_meliscommerce_clients_list_page', 'meliscommerce_clients_list_page');
                    //hide modal
                    $("#id_meliscommerce_client_list_import_accounts_form_container").modal("hide");
                    //show notifications
                    melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                    // update flash messenger values
                    melisCore.flashMessenger();
                }else{
                    title.text(data.textMessage);
                    if(data.errors) {
                        $.each(data.errors, function (i, msg) {
                            resultsContainer.append("<li>" + msg + "</li>");
                        });
                    }
                    //disable import button
                    $("#importAccounts").attr("disabled", true);
                }
            }, 500);
        }).fail(function () {
            alert(translations.tr_meliscore_error_message);
        });
    }

    /**
     * Function to show progress
     * on importing pages
     *
     * @param val
     */
    function updateProgressValue(val) {
        $(".accounts-import-progress prog_percent").text(val);

        $("div#accountsImportProgressBar").attr("arial-valuenow", val)
            .css("width", val + "%")
            .parent().parent().parent().removeClass("hidden");
    }

    //to show td tooltip
    $("body").on("mouseover", "table tbody td", function(){
        if($(this).find("span.td-tooltip") != undefined){
            $(this).find("span.td-tooltip").removeClass("d-none").css("left", $(this).position().left + 87);
        }
    });
    $("body").on("mouseout", "table tbody td", function(){
        $(this).find("span.td-tooltip").addClass("d-none");
    });
});
function viewClientOrder(orderId, orderRef) {
	var navTabsGroup = "id_meliscommerce_order_list_page";
	melisHelper.tabOpen(
		translations.tr_meliscommerce_orders_Orders,
		"fa fa fa-cart-plus fa-2x",
		"id_meliscommerce_order_list_page",
		"meliscommerce_order_list_page"
	);
	var alreadyOpen = $(
		"body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']"
	);
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
}

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

                    $("#" + fvalue +" h4."+key)
                        .css("color", "red");
				});
			} else {
				if (divContainer !== "") {
					$("#" + divContainer + " .form-control[name='" + key + "']")
						.prev("label")
						.css("color", "red");

                    $("#" + divContainer + " h4."+key)
                        .css("color", "red");
				}
			}
		});
	}
	// remove red color for correctly inputted fields
	else {
		$("#" + divContainer + " .form-group label").css("color", "#686868");
        $("#" + divContainer + " h4")
            .css("color", "#686868");
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

//will reload the new order tab if the selected client id from the client tab is changed 
function addNewOrderCallback(oldClientIdInNewOrderTab, curClientId) {
	if (typeof oldClientIdInNewOrderTab !== 'undefined' && oldClientIdInNewOrderTab != curClientId) {		
		melisHelper.zoneReload('id_meliscommerce_order_checkout', 'meliscommerce_order_checkout', {clientId: curClientId},);
	} 
}

window.setClientId = function(d){
    d.clientId = (contactToolSelectedAccount != '') ? contactToolSelectedAccount : activeTabId.replace('_id_meliscommerce_client_page','');
};

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
    //clients status filter
    if ($("#clientsStatusSelect").length) {
        d.cli_status = $("#clientsStatusSelect").val();
    }else{
        d.cli_status = 1;//display the active clients if no filter
    }
};
window.initOrderToolTip = function () {
    $(".tooltipTable").each(function() {
        var $this = $(this);
        $this.qtip({
            content: {
                text: $(this).next(".tooltiptext"),
            },
            overwrite: false,
            style: {
                classes: "qtip-tipsy qtip-shadow",
                width: "auto",
            },
            hide: {
                fixed: true,
                delay: 300,
                event: "mouseleave",
            },
            position: {
                target: "mouse",
                adjust: {
                    mouse: false,
                },
                my: "center center",
                at: "center center",
            },
        });
    });
};
window.accountsTableCallback = function()
{
    var tbody = $("#clientListTbl tbody");
    var tr = tbody.find("tr[data-hasorder='1']");
    //remove delete button if client doesn't have order
    tr.each(function(){
        $(this).find("td").find(".deleteClient").addClass("d-none");
    });
};
window.accountAssocContactListTblCallback = function ()
{
	var accountId = activeTabId.replace('_id_meliscommerce_client_page', '');
    var tbody = $("#"+accountId+"_accountContactList tbody");
    var tr = tbody.find("tr");
    //if only one contact remain, remove the unlink button
    if($(tr).length == 1){
        $(tr).find("td").find(".accountContactUnlink").addClass("d-none");
	}

    var tr = tbody.find("tr[data-accountid='0']");
    //hide all unlink/set default button if creation of account
    tr.each(function(){
        $(this).find("td").find(".accountContactUnlink").addClass("d-none");
        $(this).find("td").find(".updateDefaultContact").addClass("d-none");
    });
	//hide search on creation of account
    $("#0_accountContactList_wrapper").find(".meliscommerce-account-contact-list-tbl-search").addClass("d-none");

    $("#" + accountId + "_accountContactList tbody tr").each(function () {
        var $this = $(this),
            isDefault = $this.data("isdefault"),
            isDefaultAccount = $this.data("isdefaultaccount");

        if (isDefault == 1) {
            //change button style
            $this.find("button.updateDefaultContact").removeClass("btn-info");
            $this.find("button.updateDefaultContact").addClass("btn-danger");
            $this.find("button.updateDefaultContact").attr("title", translations.tr_meliscommerce_contact_remove_default);
            $this.find("button.updateDefaultContact").data("vdata", 0);
            //change icon
            $this.find(".ico-set-default").removeClass("fa-check");
            $this.find(".ico-set-default").addClass("fa-times");

			//hide remove default
            $this.find("button.updateDefaultContact").addClass("d-none");
        }
        if(isDefault == 1 || isDefaultAccount == 1){
            //hide unlink for default contact
            $this.find("button.accountContactUnlink").addClass("d-none");
		}
    });
    contactToolSelectedAccount = '';
    contactToolInitAccountAutoSuggest = false;
};

window.initContactAutoSuggesst = function($element)
{
    let options = {
        url: function(searchPhrase) {
            var accountId = $($element).attr("data-accountid");

            return "/melis/MelisCommerce/MelisComContact/fetchAllContact?phrase="+searchPhrase+"&accountId="+accountId;
        },
        getValue: function(element) {
        	var name = element.cper_firstname;
        	if(element.cper_name != null){
        		name += " " + element.cper_name;
			}

            return name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "GET",
            data: {
                dataType: "json"
            }
        },
        requestDelay: 300,
        list: {
            maxNumberOfElements: 20,
            onChooseEvent: function(){
                var data = $($element).getSelectedItemData();
                $($element).attr("data-contactid", data.cper_id);

                //remove disable on select
				$("#"+activeTabId+" button.accountContactLink").attr("disabled", false);
			}
        }
    };

    $($element).easyAutocomplete(options);
};
