var commerceCategoryTree = (function(){

	function initCategoryTree(targetId, loaded){
		loaded = (loaded == undefined) ? null : loaded;

		var categoryTree = $("#"+targetId);

	    categoryTree.jstree({
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
	            	"data": categoryTreeData(targetId),
	                "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView",
	            },
	        },
	        "types" : {
				"default" : {
					"icon" : "fa fa-circle text-success",
				},
				"selected": {
	                select_node : false
	            }
			},
	        "checkbox": {
		        three_state: false,
		        whole_node : false,
		        tie_selection : false,
		    },
	        "plugins": [
	            "search", // Plugins for Search of the Node(s) of the Tree View
	            "types", // Plugins for Customizing the Nodes
				"checkbox",
	        ]
		    
	    }).on("check_node.jstree uncheck_node.jstree", function(e, data) {
            var inputField = $("#" + targetId + "_form").data("input-field-selected");
			if (data.node.state.checked) {
				$("#" + targetId + "_form").append('<input type="hidden" name="' + inputField + '[]" value="' + data.node.original.cat_id + '">');
			} else {
				$("#" + targetId + "_form input[name='"+inputField+"[]'][value='" + data.node.original.cat_id + "']").remove();
			}
	    }).on("select_node.jstree deselect_node.jstree ", function(e, data){
            var inputField = $("#" + targetId + "_form").data("input-field-root");
            if (data.node.state.selected) {
                $("#" + targetId + "_form input[name='"+inputField+"']").val(data.node.original.cat_id);
            }
        }).on('after_open.jstree', function (e, data) {
			
	    	setCheckCategoryTreeNodes(targetId);
	    	setCustomNodeText(targetId);
			
	    }).on('loaded.jstree', function (e, data) {
	    	
			categoryTreeEvents(targetId);

            /**
			 * Check if loaded callback is existing function
			 *
             */
            if(loaded !== null)
				loaded(e, data, categoryTree);

		}).on('refresh.jstree', function (e, data) {
			
			setCheckCategoryTreeNodes(targetId);
			setCustomNodeText(targetId);
			
		});
	}

    function setCheckCategoryTreeNodes(targetId){
		
		$("form#"+targetId+"_form input[name='m_category_ids[]']").each(function(){
    		$("#"+$(this).val()+"_categoryId_anchor").addClass("jstree-checked");
    	});
	}
	
	function setCustomNodeText(targetId){
		
		$("#"+targetId+".jstree li.jstree-node .jstree-anchor").each(function(){
			
    		var nodeTargetAnchor = $(this);
			var textlang = nodeTargetAnchor.data('textlang');
			var spanHtml = '<span title="' + translations.tr_meliscommerce_categories_list_tree_view_product_num + '"></span>';
			
			if(textlang){
				spanHtml = ' ' + textlang + spanHtml;
			}
			
			if(!nodeTargetAnchor.hasClass('updatedText')){
				nodeTargetAnchor.append(spanHtml);
				nodeTargetAnchor.addClass('updatedText');
			}
		});
	}
	
	function categoryTreeData(targetId){
    	
		var categoryTree = $("#"+targetId);
    	
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'langlocale',
    		value : categoryTree.data('langlocale')
    	});
    	
    	dataString.push({
    		name : 'idAndNameOnly',
    		value : true
    	});
    	
    	$("form#"+targetId+"_form input[name='m_category_ids[]']").each(function(){
    		dataString.push({
        		name : 'categoriesChecked[]',
        		value : $(this).val()
        	});
    	});
    	
    	return dataString;
    }
	
	function categoryTreeEvents(targetId){
		var categoryTree = $("#"+targetId);
		
		$("#catPrdSliderPluginSearch").on("keyup", function(e){ 
			
			var searchString = $(this).val().trim();
			var searchResult = categoryTree.jstree('search', searchString);
			
			setTimeout(function(){ 
				if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
					$("#"+targetId).prev("div.alert").removeClass('hidden');
					$("#"+targetId).prev("div.alert").find("strong").text(searchString);
				}else{
					$("#"+targetId).prev("div.alert").addClass('hidden');
				}
			}, 1500);
		});
		
		
		// Clear Input Search
		$("#catPrdSliderPluginClearBtn").on("click", function(e){ 
			$("#catPrdSliderPluginSearch").val("");
			categoryTree.jstree('search', '');
			$("#"+targetId+"SearchNoResult").addClass('hidden');
		});
		
		// Toggle Buttons for Category Tree View
		$("#catPrdSliderPluginExpandBtn").on("click", function(e){ 
			categoryTree.jstree("open_all");
		});
		
		$("#catPrdSliderPluginCollapseBtn").on("click", function(e){ 
			categoryTree.jstree("close_all");
		});
		
		// Refrech Category Tree View
		$("#catPrdSliderPluginRefreshBtn").on("click", function(e){ 
			//categoryTree.jstree(true).refresh("forget_state", true);
			categoryTree.jstree('search', '');
			$("#catPrdSliderPluginSearch").val("");
			categoryTree.jstree(true).refresh();
		});
		
		$(".category-tree-view-lang-plugin li a").on("click", function(){
			var langText = $(this).text();
			var langLocale = $(this).data('locale');
			$('.categoryLangTreePluginDropdown span.filter-key').text(langText);
			categoryTree.data('langlocale', langLocale);
			
			categoryTree.jstree(true).settings.core.data.data = categoryTreeData(targetId);
			categoryTree.jstree(true).refresh();
		});
	}
	
	return {
		initCategoryTree : initCategoryTree
	};
})();