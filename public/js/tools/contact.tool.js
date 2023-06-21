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
});
window.setClientId = function(d){
    d.clientId = activeTabId.replace('_id_meliscommerce_client_page','');
};