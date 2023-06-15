$(function(){
    $body = $('body');

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