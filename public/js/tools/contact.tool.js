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
            url: "/melis/MelisCommerce/MelisComContact/addContact",
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
            "id_meliscommerce_contact_page_content_tab_association_content_list",
            "meliscommerce_contact_page_content_tab_association_content_list",
            { contactId: contactid, activateTab: true }
        );
    });
});
window.setClientId = function(d){
    d.clientId = activeTabId.replace('_id_meliscommerce_client_page','');
};

window.setContactId = function(d){
    d.contactId = activeTabId.replace('_id_meliscommerce_contact_page','');
};