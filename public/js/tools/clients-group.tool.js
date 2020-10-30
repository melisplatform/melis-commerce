$(function(){
    var $body 		= $("body");
        // zoneId 		= "id_meliscommerce_clients_group_tool_content_modal",
        // melisKey 	= 'meliscommerce_clients_group_tool_content_modal',
        // modalUrl 	= '/melis/MelisCommerce/MelisComClientsGroup/renderClientsGroupToolContentModal';

    $body.on("click", "#btnAddClientGroup", function() {
        var modalUrl = '/melis/MelisCore/MelisGenericModal/emptyGenericModal';
        melisHelper.createModal("id_meliscommerce_clients_group_tool_content_modal", "meliscommerce_clients_group_tool_content_modal", false, {}, modalUrl);
        //
        // melisCoreTool.pending("#btnAddClientGroup");
        // melisHelper.createModal(zoneId, melisKey, false, {groupId: null, saveType : "new"},  modalUrl, function() {
        //     melisCoreTool.done("#btnAddClientGroup");
        // });
    });
});
window.clientsGroupTableCallBack = function()
{
    var tbody = $("#tableClientsGroupsList tbody");
    var tr = tbody.find("tr[data-cgroup-id='1']");
    //remove delete button on general group
    tr.find("a.btnDeleteClientGroup").addClass("hidden");
};