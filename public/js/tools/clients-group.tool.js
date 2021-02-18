$(function(){
    var $body 		= $("body");

    $body.on("click", "#btnAddClientGroup", function() {
        var modalUrl = '/melis/MelisCore/MelisGenericModal/emptyGenericModal';
        melisHelper.createModal("id_meliscommerce_clients_group_tool_content_modal", "meliscommerce_clients_group_tool_content_modal", false, {}, modalUrl);
    });

    /**
     * Open modal to update record
     */
    $body.on("click", ".btnEditClientGroup", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var groupId = current_row.data("cgroup-id");
        var modalUrl = '/melis/MelisCore/MelisGenericModal/emptyGenericModal';
        melisHelper.createModal("id_meliscommerce_clients_group_tool_content_modal", "meliscommerce_clients_group_tool_content_modal", false, {"groupId":groupId}, modalUrl);
    });

    $body.on("click", ".btnSaveClientsGroup", function(){
        var _this = $(this);
        var groupId = _this.data("group-id");

        melisCoreTool.pending(".btnSaveClientsGroup");
        var data = $("form#clientsGroupForm").serializeArray();
        //include group id
        data.push({name: "groupId", value: groupId});
        $.ajax({
            type 		: 'POST',
            url  		: '/melis/MelisCommerce/MelisComClientsGroupController/saveClientsGroup',
            data 		: data,
        }).done(function(data) {
            if(data.success){
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                $("#tableClientsGroupsList").DataTable().ajax.reload();
                $("#id_meliscommerce_clients_group_tool_content_modal_container").modal("hide");
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisHelper.highlightMultiErrors(data.success, data.errors, "#clientsGroupForm");
            }

            melisCore.flashMessenger();
            melisCoreTool.done(".btnSaveClientsGroup");
        }).fail(function(xhr) {
            melisCoreTool.pending(".btnSaveClientsGroup");
            alert( translations.tr_meliscore_error_message );
        });
    });

    $body.on("click", ".btnDeleteClientGroup", function(){
        var current_row = $(this).parents('tr');//Get the current row
        if (current_row.hasClass('child')) {//Check if the current row is a child row
            current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
        }
        var groupId = current_row.data("cgroup-id");

        melisCoreTool.pending(".btnSaveClientsGroup");
        melisCoreTool.confirm(
            translations.tr_meliscore_common_yes,
            translations.tr_meliscore_common_no,
            translations.tr_meliscommerce_clients_group_delete_confirm_title,
            translations.tr_meliscommerce_clients_group_delete_confirm_msg,
            function() {
                $.ajax({
                    type 		: 'POST',
                    url  		: '/melis/MelisCommerce/MelisComClientsGroupController/deleteClientsGroup',
                    data 		: {"groupId" : groupId},
                }).done(function(data) {
                    if(data.success){
                        melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                        $("#tableClientsGroupsList").DataTable().ajax.reload();
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }

                    melisCore.flashMessenger();
                    melisCoreTool.pending(".btnDeleteClientGroup");
                }).fail(function(xhr) {
                    melisCoreTool.pending(".btnDeleteClientGroup");
                    alert( translations.tr_meliscore_error_message );
                });
            });
    });
});
window.clientsGroupTableCallBack = function()
{
    var tbody = $("#tableClientsGroupsList tbody");
    var tr = tbody.find("tr[data-cgroup-id='1']");
    //remove delete button on general group
    tr.find("a.btnDeleteClientGroup").addClass("hidden");
};