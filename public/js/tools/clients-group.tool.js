window.clientsGroupTableCallBack = function()
{
    var tbody = $("#tableClientsGroupsList tbody");
    var tr = tbody.find("tr[data-cgroup-id='1']");
    //remove delete button on general group
    tr.find("a.btnDeleteClientGroup").addClass("hidden");
};