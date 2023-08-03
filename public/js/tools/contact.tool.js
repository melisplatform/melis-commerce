$(function(){
    $body = $('body');

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

    $body.on("click", "#saveClientContact", function() {
        var $this = $(this),
            clientId = $this.data("clientid"),
        // emailList = [],
        // serialize the new array and send it to server
        dataString = $("#melisCommerceClientContactFormModal").serializeArray();

        dataString.push({
            name: "clientId",
            value: clientId
        });

        dataString = $.param(dataString);

        $("#saveClientContact").attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: "/melis/MelisCommerce/MelisComContact/saveContact",
            data: dataString,
            dataType: "json",
            encode: true,
            cache: false,
        })
            .done(function(data) {
                $("#saveClientContact").removeAttr("disabled");
                if (data.success) {
                    $("#id_meliscommerce_client_modal_contact_form_container").modal("hide");
                    $("#contactList").DataTable().ajax.reload();

                    melisHelper.melisOkNotification(data.textTitle, data.textMessage);
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

    $body.on("click", ".contactEdit", function() {
        var $this = $(this),
            contactId = $this.parents("tr").attr("id"),
            dataString = new Array();

        dataString.push({
            name: "contactId",
            value: contactId,
        });

        $.ajax({
            type: "POST",
            url: "/melis/MelisCommerce/MelisComContact/getContactName",
            data: dataString,
            dataType: "json",
            encode: true,
            cache: false,
        })
            .done(function(data) {
                $("#saveClientContact").removeAttr("disabled");

                if (data.success) {
                    var navTabsGroup = "id_meliscommerce_contact_list_page";

                    melisHelper.tabOpen(
                        data.clientContactName,
                        "fa fa-user",
                        contactId + "_id_meliscommerce_contact_page",
                        "meliscommerce_contact_page",
                        { contactId: contactId },
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

    $body.on("click", ".cper_type", function(){
        var contactId = activeTabId.split("_")[0];
        var form = $(this).closest("#"+contactId+"_contactForm");
        if($(this).val() == 'company'){
            form.find("#cper_civility").closest(".form-group").addClass("d-none");
            form.find("#cper_name").closest(".form-group").addClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").addClass("d-none");
            form.find("#cper_job_service").closest(".form-group").addClass("d-none");
            form.find("#cper_job_title").closest(".form-group").addClass("d-none");
            //change firstname label to company
            form.find("#cper_firstname").closest(".form-group").find("label").html(translations.tr_meliscommerce_contact_common_company +" "+"<sup>*</sup>");
        } else{
            form.find("#cper_civility").closest(".form-group").removeClass("d-none");
            form.find("#cper_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_service").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_title").closest(".form-group").removeClass("d-none");
            //change firstname label to company
            form.find("#cper_firstname").closest(".form-group").find("label").html(translations.tr_meliscommerce_contact_firstname +" "+"<sup>*</sup>");
        }
    });

    $body.on("click", "#melisCommerceClientContactFormModal .cper_type", function(){
        var form = $("#melisCommerceClientContactFormModal");
        if($(this).val() == 'company'){
            form.find("#cper_civility").closest(".form-group").addClass("d-none");
            form.find("#cper_name").closest(".form-group").addClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").addClass("d-none");
            form.find("#cper_job_service").closest(".form-group").addClass("d-none");
            form.find("#cper_job_title").closest(".form-group").addClass("d-none");
            //change firstname label to company
            form.find("#cper_firstname").closest(".form-group").find("label").html(translations.tr_meliscommerce_contact_common_company +" "+"<sup>*</sup>");
        } else{
            form.find("#cper_civility").closest(".form-group").removeClass("d-none");
            form.find("#cper_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_service").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_title").closest(".form-group").removeClass("d-none");
            //change firstname label to company
            form.find("#cper_firstname").closest(".form-group").find("label").html(translations.tr_meliscommerce_contact_firstname +" "+"<sup>*</sup>");
        }
    });


    $body.on("click", ".accountContactListRefresh", function() {
        var $this = $(this),
            accountid = $this.data("accountid");

        melisHelper.zoneReload(
            accountid + "_id_meliscommerce_client_page_tab_contact",
            "meliscommerce_client_page_tab_contact",
            { clientId: accountid, activateTab: true }
        );
    });

    $body.on("click", ".addContactAddress", function() {
        var $this = $(this),
            contactId = $this.data("contactid"),
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
            { contactId: contactId, tabId: tabId },
            modalUrl,
            function() {
                $(".addNewContactAddress").removeAttr("disabled");
            }
        );
    });

    $body.on("click", "#saveClientContactAddress", function() {
        var $this = $(this),
            contactId = $this.data("contactid"),
            tabId = $this.data("tabid");

        // serialize the new array and send it to server
        var dataString = $(
            "#melisCommerceClientContactAddressFormModal"
        ).serializeArray();

        dataString.push({
            name: "contactId",
            value: contactId
        });

        dataString.push({
            name: "tabId",
            value: tabId
        });

        dataString = $.param(dataString);

        $("#saveClientContactAddress").attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: "/melis/MelisCommerce/MelisComContact/addContactAddress",
            data: dataString,
            dataType: "json",
            encode: true,
            cache: false
        })
            .done(function(data) {
                $("#saveClientContactAddress").removeAttr("disabled");

                if (data.success) {
                    melisHelper.melisOkNotification(
                        data.textTitle,
                        data.textMessage
                    );
                    $(
                        "#id_meliscommerce_client_modal_contact_address_form_container"
                    ).modal("hide");

                    melisHelper.zoneReload(contactId+'_id_meliscommerce_contact_page_content_tab_address', 'meliscommerce_contact_page_content_tab_address', {contactId:contactId, reload: true});
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

    $body.on("click", ".deleteContactAddress", function(){
        var $this   = $(this),
            addressId    = $this.data("addressid"),
            contactId    = $this.data("contactid");

        melisCoreTool.confirm(
            translations.tr_meliscore_common_yes,
            translations.tr_meliscore_common_no,
            translations.tr_meliscommerce_contact_page_content_tab_address_delete,
            translations.tr_meliscommerce_contact_page_content_tab_address_delete_msg,
            function() {
                $.ajax({
                    type: "POST",
                    url: "/melis/MelisCommerce/MelisComContact/deleteContactAddress",
                    data: {addressId: addressId},
                    dataType: "json",
                    encode: true,
                    cache: false
                }).done(function(data){
                    if(data.success){
                        melisHelper.melisOkNotification(
                            data.textTitle,
                            data.textMessage
                        );
                        melisHelper.zoneReload(contactId+'_id_meliscommerce_contact_page_content_tab_address', 'meliscommerce_contact_page_content_tab_address', {contactId:contactId, reload: true});
                    }else{
                        melisHelper.melisKoNotification(
                            data.textTitle,
                            data.textMessage,
                            data.errors
                        );
                    }
                });
            }
        );
    });

    $body.on("click", ".refreshAsocc", function() {
        var $this = $(this),
            contactid = $this.data("contactid");

        melisHelper.zoneReload(
            contactid+"_id_meliscommerce_contact_page_content_tab_association",
            "meliscommerce_contact_page_content_tab_association",
            { contactId: contactid, activateTab: true }
        );
    });

    $body.on("click", ".contactAccountLink", function(){
        var input = $("#"+activeTabId+" input.link-account-data");
        var contactId = input.data("contactid");
        var accountId = input.data("accountid");
        $.ajax({
            'url': '/melis/MelisCommerce/MelisComContact/linkContactAccount',
            'data': {accountId: accountId, contactId: contactId},
            'type': 'POST'
        }).done(function(data){
            if(data.success){
                // $("#"+data.accountId+"_accountContactList").DataTable().ajax.reload();
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                melisHelper.zoneReload(
                    contactId + "_id_meliscommerce_contact_page_content_tab_association",
                    "meliscommerce_contact_page_content_tab_association",
                    { contactId: contactId, activateTab: true }
                );
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
            }
        });
    });

    $body.on("click", ".updateDefaultAccount", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var $this = $(this);
        var cprId = current_row.attr("data-cprid");
        var contactId = $this.data("contactid");
        var data = $this.data("vdata");

        $.ajax({
            'url': '/melis/MelisCommerce/MelisComContact/updateDefaultAccount',
            'data': {contactId: contactId, cprId: cprId, cpr_default_client: data},
            'type': 'POST'
        }).done(function(data){
            if(data.success){
                $("#"+contactId+"_contactAssocAccountList").DataTable().ajax.reload();
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
            }
        });
    });

    $body.on("click", ".contactAccountUnlink", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var contactId = current_row.attr("data-contactid");
        var accountId = current_row.attr("id");

        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_contact_unlink_account,
            translations.tr_meliscommerce_contact_unlink_account_msg,
            function() {
                $.ajax({
                    'url': '/melis/MelisCommerce/MelisComContact/unlinkAccountContact',
                    'data': {accountId: accountId, contactId: contactId},
                    'type': 'POST'
                }).done(function(data){
                    if(data.success){
                        $("#"+contactId+"_contactAssocAccountList").DataTable().ajax.reload();
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.error);
                    }
                });
            }
        );
    });

    $body.on("click", ".saveContactPage", function(){
        var $this = $(this),
            contactId = $this.data("contactid");

        var dataString = $("#" + contactId + "_id_meliscommerce_contact_page_content_tab_information form")
            .serializeArray();

        // Serializing Client Contact Adddresses Data
        $("#" + contactId + "_id_meliscommerce_contact_page_content_tab_address form").each(function() {
            var $this = $(this),
                contactAddressId = $this.data("contactaddressid"),
                clientContactAddressForm = $this.serializeArray();

            $.each(clientContactAddressForm, function() {
                dataString.push({
                    name:
                    "contactAddress[" +
                    contactAddressId +
                    "][" +
                    this.name +
                    "]",
                    value: this.value
                });
            });
        });

        var cperStatus = 0;
        if ($("#" + contactId + "_cper_status input").is(":checked")) {
            cperStatus = 1;
        }
        dataString.push({
            name: "cper_status",
            value: cperStatus
        });

        $.ajax({
            type: "POST",
            url: "/melis/MelisCommerce/MelisComContact/saveContact",
            data: dataString,
            dataType: "json",
            encode: true,
            cache: false
        }).done(function(data) {
            $(".saveContactPage").removeAttr("disabled");
            if (data.success) {
                $("#contactList").DataTable().ajax.reload();
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                melisHelper.tabClose(contactId + "_id_meliscommerce_contact_page");
                melisHelper.tabOpen(
                    data.clientContactName,
                    "fa fa-user",
                    contactId + "_id_meliscommerce_contact_page",
                    "meliscommerce_contact_page",
                    { contactId: contactId },
                    "id_meliscommerce_contact_list_page"
                );
            } else {
                melisHelper.melisKoNotification(
                    data.textTitle,
                    data.textMessage,
                    data.errors
                );
                melisHelper.highlightMultiErrors(data.success, data.errors, "#"+contactId+"_contactForm")
            }
        })
        .fail(function() {
            $("#saveClientContact").removeAttr("disabled");
            alert(translations.tr_meliscore_error_message);
        });
    });

    $body.on("change", "#contactAccountSelect", function(){
        $("#contactList").DataTable().ajax.reload();
    });

    $body.on("click", ".contactsExport", function() {
        if (!melisCoreTool.isTableEmpty("contactList")) {
            // initialation of local variable
            zoneId = "id_meliscommerce_contact_list_export_contacts_form";
            melisKey = "meliscommerce_contact_list_export_contacts_form";
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

    $body.on("click", "#exportContacts", function(e){
        e.preventDefault();

        var _this = $(this);
        var filters = {};

        var data = $("form#contact-list-export-contacts").serializeArray();
        filters['accountId'] = $("#contactAccountSelect").val();
        filters['search'] = $("#contactList_filter input[type='search']").val();

        $.each(data, function(key, val){
            filters[val.name] = val.value;
        });

        $.ajax({
            url: "/melis/MelisCommerce/MelisComContact/exportContacts",
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
            $("#id_meliscommerce_contact_list_export_contacts_form_container").modal('hide');
        }).fail(function(){
            alert(translations.tr_meliscore_error_message);
        });
    });

    $body.on("click", ".contactsImport", function() {
        if (!melisCoreTool.isTableEmpty("contactList")) {
            // initialation of local variable
            zoneId = "id_meliscommerce_contact_list_import_contacts_form";
            melisKey = "meliscommerce_contact_list_import_contacts_form";
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

    //test contact imports
    $body.on("click", "#importContacts", function(e){
        var form = $("#contact-list-import-contacts");
        var formData = new FormData(form[0]);
        var type = $(this).attr("data-action");

        $.ajax({
            type: 'POST',
            url: '/melis/MelisCommerce/MelisComContact/validateContactImportsForm',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                // _this.attr('disabled', true);s
            }
        }).done(function (data) {
            if(data.success){
                importContacts(formData, "/melis/MelisCommerce/MelisComContact/importContacts");
            }else{
                melisHelper.melisKoNotification(data.title, data.message, data.errors);
                melisHelper.highlightMultiErrors(data.success, data.errors, "#contact-list-import-contacts");
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

        $("#contact-list-import-contacts").hide();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                updateProgressValue(20);
                $("#importContacts").attr("disabled", true);
            }
        }).done(function (data) {
            updateProgressValue(90);
            setTimeout(function(){
                updateProgressValue(100);
                if(data.success){
                    title.text(data.textMessage);
                    $('#contactList').DataTable().ajax.reload();
                    //hide modal
                    $("#id_meliscommerce_contact_list_import_contacts_form_container").modal("hide");
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
                    $("#importContacts").attr("disabled", true);
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
        $(".contacts-import-progress prog_percent").text(val);

        $("div#contactsImportProgressBar").attr("arial-valuenow", val)
            .css("width", val + "%")
            .parent().parent().parent().removeClass("hidden");
    }
});
window.setClientId = function(d){
    d.clientId = activeTabId.replace('_id_meliscommerce_client_page','');
};

window.setContactId = function(d){
    d.contactId = activeTabId.replace('_id_meliscommerce_contact_page','');
};

window.initAccountAutoSuggest = function($element)
{
    let options = {
        url: function(searchPhrase) {
            var contactId = $($element).attr("data-contactid");

            return "/melis/MelisCommerce/MelisComContact/fetchAllAccount?phrase="+searchPhrase+"&contactId="+contactId;
        },
        getValue: function(element) {
            return element.cli_name;
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
                $($element).attr("data-accountid", data.cli_id);
            }
        }
    };

    $($element).easyAutocomplete(options);
};

window.contactAssociatedAccountCallback = function () {
    var contactId = activeTabId.replace('_id_meliscommerce_contact_page','');
    $("#" + contactId + "_contactAssocAccountList tbody tr").each(function () {
        var $this = $(this),
            isDefault = $this.data("isdefault");

        if (isDefault == 1) {
            //change button style
            $this.find("button.updateDefaultAccount").removeClass("btn-info");
            $this.find("button.updateDefaultAccount").addClass("btn-danger");
            $this.find("button.updateDefaultAccount").attr("title", translations.tr_meliscommerce_contact_remove_default);
            $this.find("button.updateDefaultAccount").data("vdata", 0);
            //change icon
            $this.find(".ico-set-default").removeClass("fa-check");
            $this.find(".ico-set-default").addClass("fa-times");
        }
    });
};

window.contactListTableDataFunction = function(d)
{
    d.accountId = $("#contactAccountSelect").val();
};