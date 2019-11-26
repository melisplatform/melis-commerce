$(function() {
    var $body = $("body");

        // attribute list - refreshes the attribute list table
        $body.on("click", ".attributeListRefresh", function(){
            melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");
        });

        //removes modal elements when clicking outside
        $body.on("click", function (e) {
            if ( $(e.target).hasClass('modal') ) {
                $('#id_meliscommerce_attribute_value_modal_value_form_container').modal('hide');
            }
        });

        // attribute value - refreshes the attribute value table
        $body.on("click", ".attributeValueRefresh", function(){
            var attributeId = activeTabId.split("_")[0];

                melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
        });

        // attribute list - opens a blank attribute page for adding
        $body.on("click", ".addNewAttribute", function(){
            var attributeId = 0;

                attributeTabOpen(translations.tr_meliscommerce_attribute_page_new_attribute, attributeId);
        });

        // attribute list - opens specific attribute for editing
        $body.on("click", ".attributeInfo", function() {
            var $this           = $(this),
                attributeId     = $this.closest('tr').attr('id'),
                attributeName   = $this.closest('tr').find("td:nth-child(5)").text(),
                tabName         = attributeId;

                if ( attributeName.length > 0 ) {
                    tabName = attributeName;
                }

                attributeTabOpen(translations.tr_meliscommerce_attribute_page+' '+tabName, attributeId);
        });

        // attribute - opens the attribue list
        $body.on("click", ".attributeHeading a", function(){
            melisHelper.tabOpen(translations.tr_meliscommerce_attribute_list_page, 'fa fa-cubes','id_meliscommerce_attribute_list_page', 'meliscommerce_attribute_list_page');
        });

        // attribute - toggles the create new value form modal
        $body.on("click", ".addAttributeValue", function(){
            var attributeId = activeTabId.split("_")[0];

                melisCoreTool.pending(this);
                // initialation of local variable
                zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
                melisKey = 'meliscommerce_attribute_value_modal_value_form';
                modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
                // requesitng to create modal and display after
                melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId}, modalUrl, function() {

                });
                melisCoreTool.done(this);
        });

        // attribute - toggles the edit value form modal
        $body.on("click", ".attributeValueInfo", function(){
            var $this               = $(this),
                attributeId         = activeTabId.split("_")[0],
                attributeValueId    = $this.closest('tr').attr('id');

                melisCoreTool.pending(this);
                // initialation of local variable
                zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
                melisKey = 'meliscommerce_attribute_value_modal_value_form';
                modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
                // requesitng to create modal and display after
                melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId, 'attributeValueId' : attributeValueId}, modalUrl, function() {

                });
                melisCoreTool.done(this);
        });

        // attribute - deletes the attribute value
        $body.on("click", ".attributeValueDelete", function(){
            var $this               = $(this),
                attributeId         = activeTabId.split("_")[0],
                attributeValueId    = $this.closest('tr').attr('id'),
                url                 = 'melis/MelisCommerce/MelisComAttribute/deleteAttributeValue',
                dataString          = [];

                dataString.push({
                    name : 'attributeValueId',
                    value: attributeValueId,
                });
                
                melisCoreTool.pending(this);

                melisCoreTool.confirm(
                    translations.tr_meliscommerce_documents_common_label_yes,
                    translations.tr_meliscommerce_documents_common_label_no,
                    translations.tr_meliscommerce_attribute_value_delete_title,
                    translations.tr_meliscommerce_attribute_value_delete_confirm,
                    function(){
                        melisCommerce.postSave(url, dataString, function(data){
                            if(data.success){
                                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                                melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
                            }else{
                                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                            }
                            melisCore.flashMessenger();
                        }, function(data){
                            console.log(data);
                            alert( translations.tr_meliscore_error_message );
                        });
                    });

                melisCoreTool.done(this);
        });

        // order list - saves the attribute value
        $body.on("click", "#saveAttributeValue", function(){
            var $this       = $(this),
                attributeId = activeTabId.split("_")[0],
                forms       = $this.closest('#'+attributeId+'_id_meliscommerce_attribute_value_modal_value_form').find('form'),
                url         = 'melis/MelisCommerce/MelisComAttribute/saveAttributeValues',
                dataString  = [],
                len,
                ctr         = 0;

                forms.each(function(){
                    var $this   = $(this),
                        pre     = $this.attr('name'),
                        data    = $this.serializeArray();

                        len = data.length;
                        for(j=0; j<len; j++ ){
                            dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
                        }

                        ctr++;
                });

                dataString.push({ name: 'attributeId', value : attributeId });

                melisCoreTool.pending(this);
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){;
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
                        $("#id_meliscommerce_attribute_value_modal_value_form_container").modal("hide");
                    }else{
                        melisCoreTool.highlightErrors(data.success, data.errors, attributeId+"_id_meliscommerce_attribute_value_modal_value_form");
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                    alert( translations.tr_meliscore_error_message );
                });
                melisCoreTool.done(this);
        });

        // attribute - saves the attribute
        $body.on("click", ".attributeSave", function(){
            melisCoreTool.pending(this);

            var $this       = $(this),
                attributeId = activeTabId.split("_")[0],
                forms       = $this.closest('.container-level-a').find('form'),
                url         = 'melis/MelisCommerce/MelisComAttribute/saveAttribute',
                dataString  = [],
                len;
                ctr         = 0;

                forms.each(function(){
                    //serialize disabled array, temporary remove disable
                    var $this       = $(this),
                        disabled    = $this.find(':input:disabled').removeAttr('disabled'),
                        pre         = $this.attr('name'),
                        data        = $this.serializeArray();

                        len = data.length;
                        for(j=0; j<len; j++ ){
                            dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
                        }
                        disabled.attr('disabled','disabled');
                        ctr++;
                });

                dataString.push({
                    name: "attributeId",
                    value: attributeId
                });

                $('#'+activeTabId+' .make-switch div').each(function(){
                    var $this       = $(this),
                        field       = 'switch['+$this.find('input').attr('name')+']',
                        status      = $this.hasClass('switch-on'),
                        saveStatus  = 0;

                        if ( status ) {
                            saveStatus = 1;
                        }
                        
                        dataString.push({
                            name : field,
                            value: saveStatus
                        })
                });

                melisCommerce.postSave(url, dataString, function(data){
                    if ( data.success ) {;
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.tabClose(  attributeId + "_id_meliscommerce_attribute_page");
                        attributeTabOpen(translations.tr_meliscommerce_attribute_page+' '+data.chunk.tabName, data.chunk.attributeId);
                        melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                        melisCoreTool.highlightErrors(data.success, data.errors, attributeId+"_id_meliscommerce_attribute_page");
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                    alert( translations.tr_meliscore_error_message );
                });

                melisCoreTool.done(this);
        });

        // attribute list - deletes the attribute
        $body.on("click", ".attributeDelete", function(){
            var $this       = $(this),
                attributeId = $this.closest('tr').attr('id'),
                url         = 'melis/MelisCommerce/MelisComAttributeList/deleteAttribute',
                dataString  = [];

                dataString.push({
                    name : 'attributeId',
                    value: attributeId,
                });

                melisCoreTool.pending(this);

                melisCoreTool.confirm(
                    translations.tr_meliscommerce_documents_common_label_yes,
                    translations.tr_meliscommerce_documents_common_label_no,
                    translations.tr_meliscommerce_attribute_delete_title,
                    translations.tr_meliscommerce_attribute_delete_confirm,
                    function(){
                        melisCommerce.postSave(url, dataString, function(data){
                            if(data.success){
                                melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
                                melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");

                            }else{
                                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
                            }
                            melisCore.flashMessenger();
                        }, function(data){
                            console.log(data);
                            alert( translations.tr_meliscore_error_message );
                        })

                    });

                melisCoreTool.done(this);
        });

        function attributeTabOpen(tabName, id){
            var navTabsGroup = "id_meliscommerce_attribute_list_page";

                melisHelper.tabOpen(tabName, 'fa fa-cubes', id+'_id_meliscommerce_attribute_page', 'meliscommerce_attribute_page', { attributeId : id}, navTabsGroup);
        }
});

window.initAttributeValue = function(data, tblSettings) {
    var attributeId = $("#" + tblSettings.sTableId ).data("attributeid");
        data.attributeId = attributeId;
}

