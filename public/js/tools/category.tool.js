/* Category Sticky Top Start */
if( melisCore.screenSize >= 768){
	$(window).on('scroll click resize', function(e) {
		$("#id_meliscommerce_categories_category_header").css("width","100%");
		var stickyCatNav = $("#"+ activeTabId + ' #id_meliscommerce_categories_category');
		if(stickyCatNav.length){
			var position = stickyCatNav.position();
			if (position.top < ($(window).scrollTop() - 10)) {
				$("#id_meliscommerce_categories_category").addClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","66px");
				$("#saveCategory").css("margin-top","10px");
				$("#id_meliscommerce_categories_category_header").width($("#id_meliscommerce_categories_list").width());
			} else {
				$("#id_meliscommerce_categories_category").removeClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","0");
				$("#saveCategory").css("margin-top","0");
			}
		}		
	});
}
/* Category Sticky Top End */

var categoryOpeningItemFlag = true;
$(function(){
	
	$("body").on("click", ".addCategory", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		var catTree = $('#categoryTreeView').jstree(true);
		
		var catSelected = catTree.get_selected();
		var catFatherId = '';
		if(catSelected.length >= 1){
			catFatherId = catSelected[0];
		}
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: catFatherId});
		melisCommerce.setUniqueId(0);
	});
	
	$("body").on("click", ".addCatalog", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: -1});
		melisCommerce.setUniqueId(0);
	});
	
	$("body").on("click", "#saveCategory", function(){ 
		
		
		$(this).button("loading");
		
		var catId = $(this).data('catid');
		var dataString = new Array;
		
		// Serialize Forms of Category Panel
		dataString = $("#id_meliscommerce_categories_category form").not(".category_"+catId+"_seo_form, .cat_trans_form").serializeArray();
		
		
		// Category Id
		dataString.push({
			name : "cat_id",
			value: catId
		});
		
		dataString = melisCommerceSeo.serializeSeo('category', catId, dataString);
		
		// Category Parent Id
		var catFatherId = $(this).data('catfatherid');
		dataString.push({
			name : "cat_father_cat_id",
			value: catFatherId
		});
		
		// Category Status
		var catStatus = 0;
		if($('input[name="cat_status"]').is(':checked')){
			catStatus = 1;
		}
		
		dataString.push({
			name : "cat_status",
			value: catStatus
		});
		
		// Category Transalations
		$("form.cat_trans_form").each(function(){
			langLocale = $(this).data("locale");
			langId = $(this).data("langid");
			
			// convert the serialized form values into an array
			catDataString = $(this).serializeArray();
			
			$.each(catDataString, function(){
				dataString.push({
					name : 'cat_trans['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		// serialize the new array and send it to server
		dataString = $.param(dataString);
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComCategory/saveCategory",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveCategory").button("reset");
			
			if(data.success) {
				$("#categoryTreeViewPanel").collapse("show");
				
				$("body").animate({
			        scrollTop: 0
			    }, 1000); 
				
				melisCore.flashMessenger();
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
				
				var catTree = $('#categoryTreeView').jstree(true);
				// Get Current Url of the category Tree view
				var realUrl = catTree.settings.core.data.url;
				
				// selected Category Id/Node
				var selectedNode = '';
				if(catId==0){
					// New Category Created
					var nodeData = catTree.get_node(catFatherId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = '';
					
					nodeParents.push(catFatherId);
					
					if(typeof nodeData === "object"){
						if(nodeData.parents.length>1){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
						}
					}
					
					nodeParentsStr = "&openStateParent="+nodeParents.join();
					
					selectedNode = data.cat_id;
					
				}else{
					// Category exist
					var nodeData = catTree.get_node(catId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = ''; 
					
					if(nodeData !== false){
						if(nodeData.parents.length>1 ){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
							nodeParentsStr = "&openStateParent="+nodeParents.join();
						}
						
						catTree.get_node(catId).state.selected = true;
					}
					
					selectedNode = catId;
				}
				
				// Set JsTree Url with Selected Node and Open State Nodes
				catTree.settings.core.data.url = realUrl+"&selected="+selectedNode+nodeParentsStr;
				// Deselect selected node
				catTree.deselect_all();
				// Remove Node Highllight
				$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
				//refresh Category view
				catTree.refresh();
				// Rollback the real/default url
				catTree.settings.core.data.url = realUrl;
				
	    		var zoneId = 'id_meliscommerce_categories_category';
	    		var melisKey = 'meliscommerce_categories_category';
	    		melisHelper.zoneReload(zoneId, melisKey, {catId : selectedNode});
	    		melisCommerce.setUniqueId(selectedNode);
	    		
	    		// Highlighting the node
	    		$("#categoryTreeView #"+selectedNode+" div").first().addClass("jstree-wholerow-clicked");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_categories_category_form_transalations");
			}
			
			melisCore.flashMessenger();
			
		}).fail(function(){
			
			$("#saveCategory").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	// Category Tree Languages Dropdown
	$("body").on("click", ".category-tree-view-lang li a", function(){
		categoryOpeningItemFlag = false;
		var langText = $(this).text();
		var langLocale = $(this).data('locale');
		$('.cat-tree-view-languages span.filter-key').text(langText);		
		$("#categoryTreeView").data('langlocale',langLocale);
		$("#categoryTreeView").jstree('destroy');
		initCategoryTreeView();
	});
		
	// Search Input
	$("body").on("keyup", "#categoryTreeViewSearchInput", function(e){ 
		categoryOpeningItemFlag = false;
		
		var searchString = $(this).val().trim();
		var searchResult = $('#categoryTreeView').jstree('search', searchString);
		
		setTimeout(function(){ 
			if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
				$("#searchNoResult").removeClass('hidden');
				$("#searchNoResult").find("strong").text(searchString);
			}else{
				$("#searchNoResult").addClass('hidden');
			}
		}, 1500);
		
	});
	
	$("body").on('keyup keypress', '#categoryTreeViewSearchForm', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
		}
	});
	
	// Clear Input Search
	$("body").on("click", "#clearSearchInputBtn", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Toggle Buttons for Category Tree View
	$("body").on("click", "#expandCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("open_all");
	});
	$("body").on("click", "#collapseCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("close_all");
	});
	
	// Refrech Category Tree View
	$("body").on("click", "#refreshCategoryTreeView", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		catTree.deselect_all();
		catTree.refresh();
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Category Information Form Countries Custom Checkboxes
	$("body").on("click", ".ecom-coutries-checkbox", function(evt){
		
		if($(this).find('.fa').hasClass('fa-check-square-o')){ // unchecking category Checkbox
			if(!$(this).find('.fa').hasClass("check-all")){
				$(this).find('.fa').removeClass('fa-check-square-o');
				$(this).find('.fa').addClass('fa-square-o');
				$(this).find('input[type="checkbox"]').removeAttr('checked');
			}
		}else{ // Checking Category Checkboxes
			if($(this).find('.fa').hasClass("check-all")){ // Unchecking "All" Checkbox
				$(".ecom-coutries-checkbox").find('.fa').removeClass('fa-check-square-o');
				$(".ecom-coutries-checkbox").find('.fa').addClass('fa-square-o');
				$(".ecom-coutries-checkbox").find('input[type="checkbox"]').removeAttr('checked');
			}else{ // Checking "All" Checkbox
				$(".ecom-coutries-checkbox").find('.check-all').removeClass('fa-check-square-o');
				$(".ecom-coutries-checkbox").find('.check-all').addClass('fa-square-o');
				$(".ecom-coutries-checkbox").find('.check-all').next('input[type="checkbox"]').removeAttr('checked'); // Unchecking "All" Checkbox
			}
			
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square-o');
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}
		
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Category Information Form Status, Switch Plugin
	$("body").on("switch-change", "#cat_status", function(event, state) {
		if(state.value == true){
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}else{
			$(this).find('input[type="checkbox"]').removeAttr('checked');
		}
	});
	
	$("body").on("click", ".categoryProductsRefresh", function(event, state) {
		var catId = $("#categoryProductListTbl").data("catid");
		melisHelper.zoneReload('id_meliscommerce_categories_category_tab_products','meliscommerce_categories_category_tab_products',{catId:catId,activateTab:true});
	});
	
	// Category Products Remove Button
	$("body").on("click", ".categoryProductsRemove", function(){
		
		var pcatId = $(this).parents("tr").attr("id");
		var catId = $("#categoryProductListTbl").data("catid");
		
		var parentId = $("#saveCategory").data("catfatherid");
		
		var deleteTitle = translations.tr_meliscommerce_categories_category_product_remove;
    	var deleteMessage = translations.tr_meliscommerce_categories_category_product_remove_confirm_msg;
    	if(parentId == '-1'){
    		deleteTitle = translations.tr_meliscommerce_categories_catalog_product_remove;
        	deleteMessage = translations.tr_meliscommerce_categories_catalog_product_remove_confirm_msg;
    	}
		
		// deletion confirmation
		melisCoreTool.confirm(
		translations.tr_meliscommerce_categories_common_label_yes,
		translations.tr_meliscommerce_categories_common_label_no,
		deleteTitle, 
		deleteMessage, 
		function() {
			
			var dataString = new Array;
			
			dataString.push({
				name : "pcat_id",
				value: pcatId
			});
			
			dataString.push({
				name : "parent_id",
				value: parentId
			});
			
			dataString.push({
				name : "cat_id",
				value: catId
			});
			
	        dataString = $.param(dataString);
	        
			$.ajax({
 		        type        : "POST", 
 		        url         : "/melis/MelisCommerce/MelisComCategory/removeCategoryProduct",
 		        data		: dataString,
 		        dataType    : "json",
 		        encode		: true
 			}).done(function(data) {
 				if(data.success) {
 					melisHelper.zoneReload('id_meliscommerce_categories_category_tab_products','meliscommerce_categories_category_tab_products',{catId:catId,activateTab:true});
 				}else{
 					alert( translations.tr_meliscore_error_message );
 				}
 				melisCore.flashMessenger();
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
 			}).fail(function(){
 				alert( translations.tr_meliscore_error_message );
 			});
		});
	});
	
	// Category Products View Button
	$("body").on("click", ".categoryProductsView", function(){
		var productId   = $(this).closest("tr").data("productid");
		//var productName = $(this).parents("tr").find(".toolTipHoverEvent").text();
		var productName = $(this).closest("tr").data("productname");
		melisCommerce.openProductPage(productId, productName);
		melisCommerce.setUniqueId(productId);
	});
	
	// Category Tree Double Click Item Action
	$("body").on("dblclick", ".jstree-node", function(evt){
		
		$("#categoryTreeViewPanel").collapse("hide");
		
		var catId = $(this).attr("id");
    	
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		
		// Highlighting the node
		$("#categoryTreeView #"+catId+" div").first().addClass("jstree-wholerow-clicked");
		melisCommerce.setUniqueId(catId);
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Open Single Node in JSTree
	$("body").on("click", ".cat-div .jstree-node .jstree-icon", function(){
		categoryOpeningItemFlag = true;
	});
	
    $("body").on("mouseenter mouseout", ".toolTipCatHoverEvent", function(e) {
      $(".thClassColId").attr("style", "");
  	  var productId = $(this).data("productid");
  	  var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
  	  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  		  $(v).remove();
  	  });
  	  $(loaderText).insertAfter("table#catProductTable"+productId + " thead");
  		var xhr = $.ajax({
  	        type        : 'POST', 
  	        url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
  	        data		: {productId : productId},
  	        dataType    : 'json',
  	        encode		: true,
  	     }).success(function(data){
      	 	 $("div.qtipLoader").remove();
  		     if(data.content.length === 0) {
  		    	 $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
  		     }
  		     else {
  		    	 // make sure tbody is clear
  				  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  					  $(v).remove();
  				  });
      		     $.each(data.content.reverse(), function(i ,v) {
      		    	 $(v).insertAfter("table#catProductTable"+productId + " thead")
      		     });
  		    	 
  		     }

  	     });
  		if(e.type === "mouseout") {
  			xhr.abort();
  		}
  	  });
});

window.enableDisableAddCategoryBtn = function(action){
	var addCategory = $('.addCategory');
	if(action == 'enable'){
		addCategory.attr('disabled', false);
		addCategory.attr('title', null);
	}else if (action == 'disable'){
		addCategory.attr('disabled', true);
		addCategory.attr('title', translations.tr_meliscommerce_categories_category_no_selected_catalog_category);
	}
}

window.initCategoryTreeView = function(){
	
	$("body").on("click", "#categoryTreeView", function(evt){
		$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	$('#categoryTreeView')
		.on('changed.jstree', function (e, data) {
			enableDisableAddCategoryBtn('enable');
		})
		.on('refresh.jstree', function (e, data) {
			enableDisableAddCategoryBtn('disable');
		})
		.on('loading.jstree', function (e, data) {
			melisCommerce.pendingZoneStart("meliscommerce_categories_list_search_input");
		})
		.on('loaded.jstree', function (e, data) {
			melisCommerce.pendingZoneDone("meliscommerce_categories_list_search_input");
		})
		.on('open_node.jstree', function (e, data) {
			
			if(categoryOpeningItemFlag == true){
				if($(".cat-div").length){
					// if Node open sub nodes and not visible to the parent container, this will scroll down to show the sub nodes
					if($(".cat-div #"+data.node.id).offset().top + $(".cat-div #"+data.node.id).height() > $(".cat-div").offset().top + $(".cat-div").height() ){
						// exucute scroll after the opening animation of the node
						$timeOut = setTimeout(function(){ 
							var catContainer = $('.cat-div').scrollTop();
							var catItemHeight = $(".cat-div #"+data.node.id).innerHeight()
							$('.cat-div').animate({
								scrollTop: catContainer + catItemHeight
							}, 'slow');
							
						}, 1000);
					}
				}
			}
		})
		.on("move_node.jstree", function (e, data) {
	        // Category Id
	        var categoryId = data.node.id;
	        // New category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var newParentId = (data.parent=='#') ? '-1' : data.parent;
	        // Old category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var oldParent = (data.old_parent=='#') ? '-1' : data.old_parent;
	        // New Category Position
	        // Position is the index on the data
	        // Adding One(1) to make to avaoid Zero(0) index of position
	        var categoryNewPosition = data.position + 1;
	        
	        var dataString = new Array();
			// get data from input
	        dataString.push({
				name: "cat_id",
				value: categoryId
			});
			// get date data from param
			dataString.push({
				name: "cat_father_cat_id",
				value: newParentId
			});
			// get date data from param
			dataString.push({
				name: "cat_order",
				value: categoryNewPosition
			});
			// get date data from param
			dataString.push({
				name: "old_parent",
				value: oldParent
			});
			
			dataString = $.param(dataString);
			
	        $.ajax({
		        type        : "POST", 
		        url         : "/melis/MelisCommerce/MelisComCategoryList/saveCategoryTreeView",
		        data		: dataString,
		        dataType    : "json",
		        encode		: true
			}).done(function(data) {
				
				if(data.success) {
					$currentCategoryId = $("#saveCategory").data("catid");
					
					if($currentCategoryId == categoryId){
						$("#saveCategory").data("catfatherid", newParentId);
					}
				}else{
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				
				alert( translations.tr_meliscore_error_message );
			});
	    })
	    .jstree({
		"contextmenu" : {
		    "items" : function (node) {
		        return {
		            "Add" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_add,
		                "icon"  : "fa fa-plus",
		                "action" : function (obj) {
		                	
		                	var parentId = parseInt(node.id);
		                	var position = node.children.length + 1;
		                	
		                	$("#categoryTreeViewPanel").collapse("hide");
		                	
		                	var zoneId = "id_meliscommerce_categories_category";
		                	var melisKey = "meliscommerce_categories_category";
		                	
		            		melisHelper.zoneReload(zoneId, melisKey,{catId:0, catFatherId:parentId, catOrder:position});
		                	
		                }
		            },
		            "Update" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_update,
		                "icon"  : "fa fa-edit",
		                "action" : function (obj) {
		                	
		            		var catId = node.id;
		                	
		            		var zoneId = 'id_meliscommerce_categories_category';
		            		var melisKey = 'meliscommerce_categories_category';
		            		
		            		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		            		
		            		$("#categoryTreeViewPanel").collapse("hide");
		                	
		                }
		            },
		            "Delete" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_delete,
		                "icon"  : "fa fa-trash-o",
		                "action" : function (obj) {
		                		
		                	var dataString = new Array();
		                	
		                	// New category Parent ID
		        	        // if value is '#', the Category is on the root of the list
		        	        var parentId = (node.parent=='#') ? '-1' : node.parent;
		        	        
		        	        dataString.push({
		        				name: "cat_father_cat_id",
		        				value: parentId
		        			});
		                	
		                	var cattId = parseInt(node.id);
		                	
		                	dataString.push({
		        				name: "cat_id",
		        				value: cattId
		        			});
		                	
		                	dataString = $.param(dataString);
		                	
		                	var deleteTitle = translations.tr_meliscommerce_categories_category_delete;
		                	var deleteMessage = translations.tr_meliscommerce_categories_category_delete_confirm_msg;
		                	if(parentId == '-1'){
		                		deleteTitle = translations.tr_meliscommerce_categories_catalog_delete;
			                	deleteMessage = translations.tr_meliscommerce_categories_catalog_delete_confirm_msg;
		                	}
		                	
		                	// deletion confirmation
		            		melisCoreTool.confirm(
		            		translations.tr_meliscommerce_categories_common_label_yes,
		            		translations.tr_meliscommerce_categories_common_label_no,
		            		deleteTitle, 
		            		deleteMessage, 
		            		function() {
		            			$.ajax({
			        		        type        : "POST", 
			        		        url         : "/melis/MelisCommerce/MelisComCategory/deleteCategory",
			        		        data		: dataString,
			        		        dataType    : "json",
			        		        encode		: true
			        			}).done(function(data) {
			        				if(data.success) {
			        					var catTree = $('#categoryTreeView').jstree(true);
			        	            	catTree.delete_node(cattId);
			        	            	
			        	            	if($("#saveCategory").data("catid")==cattId){
			        	            		var zoneId = "id_meliscommerce_categories_category";
			    		                	var melisKey = "meliscommerce_categories_category";
			    		                	
			    		            		melisHelper.zoneReload(zoneId, melisKey);
			        	            	}
			        	            	
			        	            	melisCore.flashMessenger();
			        					melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			        				}else{
			        					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			        				}
			        			}).fail(function(){
			        				alert( translations.tr_meliscore_error_message );
			        			});
		            		});
		                }
		            },
		        };
		    }
		},
	    "core" : {
	    	"multiple": false,
	        "check_callback": true,
	        "animation" : 500,
	        "themes": {
                "name": "proton",
                "responsive": false
            },
            "dblclick_toggle" : false,
	        "data" : {
	        	"cache" : false,
	            "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?langlocale="+$("#categoryTreeView").data('langlocale'),
	        },
	    },
	    "types" : {
            "#" : {
                "valid_children" : ["catalog"]
            },
            "catalog" : {
                "valid_children" : ["category"]
            },
            "category" : {
            	"valid_children" : ["category"]
            },
        },
	    "plugins": [
            "contextmenu", // plugin makes it possible to right click nodes and shows a list of configurable actions in a menu.
	        "changed", // Plugins for Change and Click Event
	        "dnd", // Plugins for Drag and Drop
	        "search", // Plugins for Search of the Node(s) of the Tree View
	        "types", // Plugins for Customizing the Nodes
        ]
    });
	
	$("body").on("click", ".categoryProductsExport", function() {
		if(!melisCoreTool.isTableEmpty("categoryProductListTbl")) {
			melisCoreTool.exportData('/melis/MelisCommerce/MelisComCategory/productsExportToCsv?catId='+$(this).data('catid'));
		}
	});
}

// Category Information Status Switch Initialization
window.initCategoryStatus = function(){
	$('#cat_status').bootstrapSwitch();
}

window.initCategoryProducts = function(data, tblSettings) {
	
	// get Category Id from table data
	var catId = $("#" + tblSettings.sTableId ).data("catid");
	
	// Add DataTable Data catId and assign value of CategoryId
	data.catId = catId;
	
	var catLangLocale = $("#categoryTreeView").data('langlocale');
	
	// Add DataTable Data catLangLocale and assign value of catLangLocale
	data.catLangLocale = catLangLocale;
	
	$('#categoryProductListTbl').on( 'row-reorder.dt', function ( e, diff, edit ) {
	    var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

	    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
	        var rowData = $categoryProductListTbl.row( diff[i].node ).data();
	        console.log('Bogo ', rowData);
	         result += rowData[1]+' updated to be in position '+ diff[i].newData+' (was '+diff[i].oldData+')<br>';
	    }
		
	    if(!$.isEmptyObject(diff)){
	        
	        var dataString = new Array;
	        var prdNodes = new Array;
	        
	        $.each(diff, function(){
	        	prdNodes.push(this.node.id+'-'+this.newPosition);
	        });
	        
	        dataString.push({
				name : "catPrdOrderData",
				value: prdNodes.join()
			});
			
	        dataString = $.param(dataString);
			
		    $.ajax({
			     type        : "POST", 
			     url         : "/melis/MelisCommerce/MelisComCategory/reOrderCategoryProducts",
			     data		: dataString,
			     dataType    : "json",
			     encode		: true
			}).done(function(data) {
				if(!data.success) {
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				alert( translations.tr_meliscore_error_message );
			});
		}
	});
}

window.initCategoryProductsImgs = function(){
	// Lightbox Plugin Initialization
    lightbox.option({
	    'resizeDuration': 200,
	    'wrapAround': true
    })
}


