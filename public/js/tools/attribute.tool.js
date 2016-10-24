$(document).ready(function() {
	var body = $("body");
	
	// attribute list - refreshes the attribute list table
	body.on("click", ".attributeListRefresh", function(){
		melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");
	});
	
	// attribute value - refreshes the attribute value table
	body.on("click", ".attributeValueRefresh", function(){
		var attributeId = activeTabId.split("_")[0];
		melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
	});
	
	// attribute list - opens a blank attribute page for adding
 	body.on("click", ".addNewAttribute", function(){
 		var attributeId = 0;
 		attributeTabOpen(translations.tr_meliscommerce_attribute_page, attributeId);
	});
 	
 	// attribute list - opens specific attribute for editing
	body.on("click", ".attributeInfo", function() {
		var attributeId   = $(this).closest('tr').attr('id');
		var attributeName  = $(this).closest('tr').find("td:nth-child(5)").text();
		var tabName = attributeId;
		if(attributeName.length > 0){
			tabName = attributeName;
		}
		attributeTabOpen(translations.tr_meliscommerce_attribute_page+' '+tabName, attributeId);
	});
	
	// attribute - opens the attribue list
 	body.on("click", ".attributeHeading a", function(){
 		melisHelper.tabOpen(translations.tr_meliscommerce_attribute_list_page, 'fa fa-cubes','id_meliscommerce_attribute_list_page', 'meliscommerce_attribute_list_page');
	});
 	
 	// attribute - toggles the create new value form modal
	body.on("click", ".addAttributeValue", function(){ 
		var attributeId = activeTabId.split("_")[0];
		melisCoreTool.pending(this);
		// initialation of local variable
		zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
		melisKey = 'meliscommerce_attribute_value_modal_value_form';
		modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId}, modalUrl, function(){
    		
    	});
    	melisCoreTool.done(this);
	});
	
	// attribute - toggles the create new value form modal
	body.on("click", ".attributeValueInfo", function(){ 
		var attributeId = activeTabId.split("_")[0];
		var attributeValueId   = $(this).closest('tr').attr('id');
		melisCoreTool.pending(this);
		// initialation of local variable
		zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
		melisKey = 'meliscommerce_attribute_value_modal_value_form';
		modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
		// requesitng to create modal and display after
    	melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId, 'attributeValueId' : attributeValueId}, modalUrl, function(){
    		
    	});
    	melisCoreTool.done(this);
	});
	
	
	// order list - saves the new order status
	body.on("click", "#saveAttributeValue", function(){
		var attributeId = activeTabId.split("_")[0];
		var forms  = $(this).closest('#'+attributeId+'_id_meliscommerce_attribute_value_modal_value_form').find('form');
		var url = 'melis/MelisCommerce/MelisComAttribute/saveAttributeStatus';
		var dataString = [];
		var len;
		var ctr = 0;
		
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
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
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_order_list_content_table", "meliscommerce_order_list_content_table");
				// Relload Client Order list if id exist
				if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
					melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
				}
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}			
		}, function(data){
			console.log(data);
		});
		melisCoreTool.done(this);
		$("#id_meliscommerce_order_list_content_status_form_container").modal("hide");
	});
	
	function attributeTabOpen(tabName, id){
		melisHelper.tabOpen(tabName, 'fa fa-cubes', id+'_id_meliscommerce_attribute_page', 'meliscommerce_attribute_page', { attributeId : id});
	}
});
window.initAttributeValue = function(data, tblSettings) {
	var attributeId = activeTabId.split("_")[0];
	data.attributeId = attributeId;	
}

