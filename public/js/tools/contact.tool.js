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
        var clientId = activeTabId.split("_")[0];
        var form = $(this).closest("#"+clientId+"_contactForm");
        if($(this).val() == 'company'){
            form.find("#cper_civility").closest(".form-group").addClass("d-none");
            form.find("#cper_name").closest(".form-group").addClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").addClass("d-none");
            form.find("#cper_job_service").closest(".form-group").addClass("d-none");
            form.find("#cper_job_title").closest(".form-group").addClass("d-none");
        } else{
            form.find("#cper_civility").closest(".form-group").removeClass("d-none");
            form.find("#cper_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_middle_name").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_service").closest(".form-group").removeClass("d-none");
            form.find("#cper_job_title").closest(".form-group").removeClass("d-none");
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
        var accountId = current_row.attr("data-accountid");
        var contactId = current_row.attr("id");

        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_contact_unlink_account,
            translations.tr_meliscommerce_contact_unlink_account_msg,
            function() {
                $.ajax({
                    'url': '/melis/MelisCommerce/MelisComContact/unlinkAccountContact',
                    'data': {accountId: accountId, contactId: contactId, type: "account"},
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
            }
        })
        .fail(function() {
            $("#saveClientContact").removeAttr("disabled");
            alert(translations.tr_meliscore_error_message);
        });
    });
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