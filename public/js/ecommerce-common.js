var pUniqueId = [];

/* #### FIX DataTable issue in Tab #### */
$(document).ready(function(){
	$('body').on('a[data-toggle="tab"]','shown.bs.tab', function (e) {
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
	});		
});

var melisCommerce = (function(window) {

	function initTooltipTable() {
		$(".tooltipTable").each(function() {
			$(this).qtip({
				content: {
					text: $(this).next('.tooltiptext')
				},
				style: {
					classes: 'qtip-tipsy qtip-shadow',
					width: "auto",
				},
				hide: {
					fixed: true,
					delay: 300
				},
				position: {
					my: 'top center', 
					at: 'bottom center',
					//container : false,
				},
//			    adjust:{
//		          screen: true,
//			    },
			});
		});
	}
		
	function openProductPage(productId, productName)
	{
		melisHelper.tabOpen(productName, "icon-shippment", productId+"_id_meliscommerce_products_page", "meliscommerce_products_page",  { productId: productId } );
	}
	
	function closeCurrentProductPage() {
		melisHelper.tabClose(melisCommerce.getCurrentProductId() + "_id_meliscommerce_products_page");
	}
	
	function getCurrentProductId()
	{
		return activeTabId.split("_")[0];
	}
	
	function reloadCurrentProdPage(prodId) {
		var productId = melisCommerce.getCurrentProductId();
		if(prodId != null) {
			productId = prodId;
		}
		melisCoreTool.clearForm("productTextTypeForm");
		melisHelper.zoneReload(productId+"_id_meliscommerce_products_page","meliscommerce_products_page", {productId : productId});
	}

	function initTinyMCE()
	{
		console.log('done');
		var locale = "";

		if(melisLangId == "en_EN") {
			locale = "en";
		}
		else {
			locale = melisLangId;
		}
		setTimeout(function() {
			tinymce.init({
				mode: "specific_textareas",
				//elements: "textarea",
				editor_selector: "mceEditor",
				language: locale,
				  height: 200,
				  plugins: [
		    	    'advlist autolink lists link image charmap print preview anchor',
		    	    'searchreplace visualblocks code fullscreen',
		    	    'insertdatetime media table contextmenu paste template'
				  ],
				  menubar: false,
				  toolbar: 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | code',
			});
		}, 1000);
	}

	
	function initCommerceTable(table, ajaxUrl, columns, filterBarDom, bulkDom) {
		$(table).DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthMenu: [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
            pageLength: 10,
            ajax: {
                url: ajaxUrl,
                type: "POST",
           },
			"order": [[ 1, "asc" ]],
			"columns": columns,
			"columnDefs": [
				{ "responsivePriority": 1, "targets": 0 },
				{ "responsivePriority": 2, "targets": -1 },
			],
			"language" : {
				url: "/melis/MelisCore/Language/getDataTableTranslations",
			}, 
			/* "responsive": true, */
			"dom": '<"bulk-action"><"filter-bar fl">rtip',
			"drawCallback": function( settings ) {
				$(this).css('width','100%');
			}
		});
		$(".filter-bar").html(filterBarDom);					
		$(".bulk-action").html(bulkDom);
	}
	
	function postSave(ajaxUrl, ajaxData, successCallBack, errorCallBack) {
		$.ajax({
	        type        : 'POST', 
	        url         : ajaxUrl,
	        data		: ajaxData,
	        dataType    : 'json',
	        encode		: true,
	     }).success(function(data){
	    	successCallBack(data);
	     }).error(function(){
	    	 errorCallBack();
	     });
	}
	
	function getDocFormType() {
		var formType = "";
		formType = $("#"+activeTabId).find("div.ecom-doc-container").data("form-type");
		return formType;
	}
	
	function setUniqueId(id) {
		if(id == null) {
			$.ajax({
		        type        : 'POST', 
		        url         : '/melis/MelisCommerce/MelisComDocument/setUniqueId',
		        data : {id: id},
		        dataType    : 'json',
		        encode		: true,
		     }).success(function(data){
		    	//$("body").attr("data-uniq-id", data.id);
		    	pUniqueId[activeTabId] = data.id;
		     });
		}
		else {
			//$("body").attr("data-uniq-id", id);
			pUniqueId[activeTabId] = id;
		}
		
			
	}
	
	function getUniqueId() {
		return pUniqueId[activeTabId];
		//return $("body").attr("data-uniq-id"); 
	}
	
	function disableTab(tabId) {
		$("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','none').parent().css("cursor", "not-allowed");
	}
	
	function enableTab(tabId) {
		$("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','auto').parent().css("cursor", "pointer");
	}
	
	function disableAllTabs()
	{
		$.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
			var tabId = $(v).data("id");
			disableTab(tabId);
		});
		
		// disable navigation too
		$.each($("ul.sideMenu"), function(i ,v) {
			$(v).css('pointer-events','none').parent().css("cursor", "not-allowed");
		});
	}
	
	function enableAllTabs()
	{
		$.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
			var tabId = $(v).data("id");
			enableTab(tabId);
		});
		
		$.each($("ul.sideMenu"), function(i ,v) {
			$(v).css('pointer-events','none').css('pointer-events','auto').parent().css("cursor", "pointer");
		});
	}
	

	return {
		initTooltipTable: initTooltipTable,
		initCommerceTable : initCommerceTable,
		openProductPage : openProductPage,
		postSave: postSave,
		getCurrentProductId : getCurrentProductId,
		closeCurrentProductPage : closeCurrentProductPage,
		reloadCurrentProdPage : reloadCurrentProdPage,
		initTinyMCE : initTinyMCE,
		getDocFormType : getDocFormType,
		setUniqueId : setUniqueId,
		getUniqueId : getUniqueId,
		enableTab : enableTab,
		disableTab : disableTab,
		disableAllTabs : disableAllTabs,
		enableAllTabs : enableAllTabs
	}
	
})(window);