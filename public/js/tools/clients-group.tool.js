$(function(){
    var $body 		= $("body");

    $body.on("click", "#btnAddClientGroup", function() {
        var modalUrl = '/melis/MelisCore/MelisGenericModal/emptyGenericModal';
        melisHelper.createModal("id_meliscommerce_clients_group_tool_content_modal", "meliscommerce_clients_group_tool_content_modal", false, {}, modalUrl);
    });

    $body.on("click", ".btnSaveClientsGroup", function(){
        melisCoreTool.pending(".btnSaveClientsGroup");
        var data = $("form#clientsGroupForm").serializeArray();
        $.ajax({
            type 		: 'POST',
            url  		: '/melis/MelisCommerce/MelisComClientsGroupController/addClientsGroup',
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
            melisCoreTool.pending(".btnSaveClientsGroup");
        }).fail(function(xhr) {
            melisCoreTool.pending(".btnSaveClientsGroup");
            alert( translations.tr_meliscore_error_message );
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