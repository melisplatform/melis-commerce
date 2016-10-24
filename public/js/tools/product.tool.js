window.initProductSwitch = function() {
	setOnOff();
	allLoaded();
}

window.initTreeCat = function(){
	
	$("body").on("click", "#productCategory", function(evt){
		$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
		evt.stopPropagation();
		evt.preventDefault();
	});

	
	$("#"+melisCommerce.getCurrentProductId()+"_productCategory")
	    .jstree({
		"types" : {
			"default" : {
				"icon" : "fa fa-circle text-success",
			}
		},
	    "core" : {
	    	//"multiple": true,
	        "check_callback": true,
	        "animation" : 500,
	        "themes": {
                "name": "proton",
                "responsive": false
            },
            "dblclick_toggle" : false,
	        "data" : {
	        	"cache" : true,
	            "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?langlocale="+$("#" + melisCommerce.getCurrentProductId() + "_productCategory").data('langlocale'),
	        },
	    },
        "checkbox": {
            three_state: false,
            whole_node : false,
            tie_selection : false,
        },
	    "plugins": [
            "search",
	        "changed", // Plugins for Change and Click Event
	        "types", // Plugins for Customizing the Nodes
	        "checkbox",
        ]
    });
}

window.initAttribute = function(data) {
	$("#" + activeTabId + " div.pdc-content .form-group div.awesomplete").find("ul").remove();
	if(melisCommerce.getCurrentProductId() != null) {
		if(!data) {
			$.ajax({
				type : 'GET',
				url : '/melis/MelisCommerce/MelisComProduct/getAttributesExceptAttributesOnProductId',
				data : {productId : melisCommerce.getCurrentProductId()},
		        dataType    : 'json',
		        encode		: true,
			}).success(function(data) {
				populateAttribList(data.lists);
			});
		}
		else {
			populateAttribList(data);
		}
	}
}

window.populateAttribList = function(data) {
	var comboplete = new Awesomplete($('div#'+activeTabId+' input.dropdown-input'+melisCommerce.getCurrentProductId())[0], {
		minChars: 0,
		list: data
	});
	comboplete.close();
	Awesomplete.$('#attrButton'+melisCommerce.getCurrentProductId()).addEventListener("click", function() {
		if (comboplete.ul.childNodes.length === 0) {
			comboplete.minChars = 0;
			comboplete.evaluate();
		}
		else if (comboplete.ul.hasAttribute('hidden')) {
			comboplete.open();
		}
		else {
			comboplete.close();
		}
	});
	
	if($.browser.mozilla) {
		$("#attrButton"+melisCommerce.getCurrentProductId()).css("margin-top", "-27px");
	}
	
	$("button.btn").not(".dropdown-btn").click(function() {
		comboplete.close();
    });
	$('.attr-input-dropdown').on('focusout',function(){
		comboplete.close();
	});
}

window.initProductTextTinyMce = function() {
	$(".notifTinyMcePreloaInfo").fadeIn("slow");
	$.getScript('/MelisCommerce/assets/tinyMCE/tinymce_init.js', function() {
	});
}
window.allLoaded = function() {
	melisCommerce.enableAllTabs();
}
$(document).ready(function() {

	var body = $("body");
	
	$('body').on("click",".productTextForm .deleteTextInput", function(){
		var text = $(this).parent().data("text");
        $("#" + activeTabId + " .productTextForm .deleteTextInput").closest("a[data-text='"+text+"']").closest("form").remove();
	});
	
	body.on("click", ".btnProductListEdit", function() {
		var productId   = $(this).parent().parent().attr('id');
		var productName = $(this).parent().parent().find("td span").data("productname");
		melisCommerce.disableAllTabs();
		melisCommerce.openProductPage(productId, productName);
		melisCommerce.setUniqueId(productId);
	});
	
	body.on("click", "#btnAddProduct", function() {
		melisCommerce.openProductPage(0, translations.tr_meliscommerce_products_page_new_product);
		melisCommerce.setUniqueId(0);
	});
	
	body.on("click", "#addProdTextType", function() {
		var dataString = $("form#productTextTypeForm").serialize();
		melisCoreTool.pending("#addProdTextType");
		$.ajax({
			type: 'POST',
			data: dataString,
			url: '/melis/MelisCommerce/MelisComProduct/addProductTextType',
	        dataType    : "json",
	        encode		: true
		}).success(function(data) {
			if(data.success) {
				melisCoreTool.clearForm("productTextTypeForm");
				melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_product_text_modal_form", "meliscommerce_products_page_content_tab_product_text_modal_form",  {productId : melisCommerce.getCurrentProductId()});
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, "productTextTypeForm");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#addProdTextType");
		});
	});
	body.on('click', '.add-product-text', function() {
		$("div[data-class='addTextFieldNotif']").html("").attr("class", "addTextFieldNotif");
	});
	
	body.on('click', '.btnAddText', function(){
		var textSelect = $("#" + activeTabId + " .btnAddText").parent().find("select#ptxt_type")
		typeId = textSelect.val();
		var typeText = textSelect = $("#" + activeTabId + " .btnAddText").parent().find("select#ptxt_type option:selected").text();
		melisCoreTool.pending(this);
		$.ajax({
			type: "GET",
			url : "melis/MelisCommerce/MelisComProduct/getEmptyProductTextForm?textTypeId=" + typeId + "&text="+typeText,
			dataType : "json",
			encode		: true
		}).success(function(data) {
			var formTextForms = $("#" + activeTabId + " .product-text-forms > .custom-field-type");
			$.each(formTextForms, function(i, v){
				var langId =  $(v).data("lang-id");
				if($("#" + activeTabId + " .productTextForm a[data-text='"+typeText+"']").length < 2) {
					$(v).find(".custom-field-type-area").append(data.content).data("text-lang-id", langId);
					$(v).find("form[name='productTextForm']").attr("data-text-lang-id", langId);
					$(v).find("form[name='productTextForm'] input#ptxt_lang_id").val(langId);
					initProductTextTinyMce();
					$("div[data-modalname='genericProductTextModal']").modal('hide');
					$("div[data-class='addTextFieldNotif']").html("").attr("class", "");
				}
				else {
					melisCoreTool.alertDanger("div[data-class='addTextFieldNotif']", "", translations.tr_meliscommerce_product_text_add_exists);
				}
			}); 
			
			melisCoreTool.done(".btnAddText");
			
		});
	});
	
	body.on("click", ".save_product", function() {
		var productId = $(this).data("productid");
		var forms = $("#" + melisCommerce.getCurrentProductId() +"_id_meliscommerce_products_page form");
		var dataString = [];
		var len = 0;
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
		

		
		$('#'+productId +'_id_meliscommerce_products_page').find('.make-switch div').each(function(){
			var field = 'product[0]['+$(this).find('input').attr('name')+']';
			var status = $(this).hasClass('switch-on');
			var saveStatus = 0;
			if(status) {
				saveStatus = 1;
			}
			dataString.push({
				name : field,
				value: saveStatus
			})
		});
		
		dataString.push({ 
			name: "productId",
			value: productId 
		});
		
		dataString = melisCommerceSeo.serializeSeo('product', productId, dataString);
		
		dataString.push({ name: "product[0][prd_id]", value: productId });
		ctr = 0;
		$("div#"+melisCommerce.getCurrentProductId()+"_attribute_area > span.attr-values").each(function(){
			dataString .push({ name : 'attributes['+ctr+'][patt_id]', value : $(this).data('patt-id')});
			dataString .push({ name : 'attributes['+ctr+'][patt_attribute_id]', value : $(this).data('patt-attribute-id')});
			ctr++;
		});
		ctr = 0;
		
		$("div#"+melisCommerce.getCurrentProductId()+"_product_category_area > span.prod-cat-values").each(function(){
			dataString .push({ name : 'categories['+ctr+'][pcat_id]', value : $(this).data('pcat-id')});
			dataString .push({ name : 'categories['+ctr+'][pcat_cat_id]', value : $(this).data('pcat-cat-id')});
			dataString .push({ name : 'categories['+ctr+'][pcat_order]', value : $(this).data('pcat-order')});
			ctr++;
		})
		ctr = 0;
		
		$("div#"+melisCommerce.getCurrentProductId()+"_deleted_product_category_area > span").each(function(){
			dataString.push({ name : 'delcategories['+ctr+'][pcat_id]', value : $(this).data('pcat-id')});
			ctr++;
		});
		ctr = 0;
		melisCoreTool.pending(".save_product");
		melisCommerce.disableAllTabs();
		$.ajax({
			type: 'POST',
			data: dataString,
			url: '/melis/MelisCommerce/MelisComProduct/saveProduct',
	        dataType    : "json",
	        encode		: true
		}).success(function(data) {
			if(data.success) {
				melisCommerce.closeCurrentProductPage();
				melisCommerce.openProductPage(data.chunk.productId, data.chunk.prodName);
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_product_list_content", "meliscommerce_product_list_content");
				melisCommerce.setUniqueId(data.chunk.productId);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
				melisCoreTool.highlightErrors(data.success, data.errors, activeTabId + " form");
			}
			
			melisCoreTool.done(".save_product");
			melisCore.flashMessenger();
			melisCommerce.enableAllTabs();
		});
	});
		
	body.on("click", "a[data-meliskey='meliscommerce_products_page_header_save_product_cancel']", function() {
		melisCommerce.closeCurrentProductPage();
	});
	
	getCurrentAttribLists = function(){
		$('#attrButton'+melisCommerce.getCurrentProductId()).trigger("click");
		var attrib = []; 
		$.each($("#" + activeTabId + " div.pdc-content .form-group div.awesomplete").find("ul li"), function(i, v) {
			if($(v).length) {
				attrib[i] = $(v).html();
			}
		});
		return attrib;
	}
	
	body.on('click','.add-attribute',function() {
		var selAttrVal = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput");
		var regExp = /\(([^)]+)\)/;
		if(selAttrVal.val() != "") {
			var matches = regExp.exec(selAttrVal.val());
			if(matches) {
				var attribute = selAttrVal.val().split("(");
				var attributes = getCurrentAttribLists();
	        	if($("#"+melisCommerce.getCurrentProductId()+"_attribute_area").find("span[data-patt-attribute-id='"+matches[1]+"']").length === 0) {
					$("#"+melisCommerce.getCurrentProductId()+"_attribute_area").append('<span class="attr-values" data-patt-attribute-id="'+matches[1]+'"><span class="ab-attr"><span class="attrib-value">'+attribute[0]+'</span>'+
					'<i class="prdDelAttr fa fa-times"></i></span>');
					
					if($.inArray(selAttrVal.val(), attributes) !== -1) {
						var idx = attributes.indexOf(selAttrVal.val());
						attributes.splice(idx, 1);
					}
					$("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").hide();
					initAttribute(attributes);
	        	}
	        	selAttrVal.val("");
	        	selAttrVal.css("border", "1px solid #e5e5e5");
			}
			else {
				melisCoreTool.alertWarning("#frmProdAttribNotif", '', translations.tr_meliscommerce_products_attributes_add_failed);
				setTimeout(function() { melisCoreTool.hideAlert("#frmProdAttribNotif"); }, 5000);
			}
		}	
		else {
			selAttrVal.css("border", "1px solid #e80e05");
		}
	});
	

	body.on("click", ".refresh-attribute-lists", function() {
		melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_main_tab_attributes_add", "meliscommerce_products_main_tab_attributes_add", {productId : melisCommerce.getCurrentProductId()});
	});
	
	body.on("click", ".prdDelAttr", function() {
		var attr = $(this).parent().parent();
		var value = $(this).parent().find("span.attrib-value").html() + " (" + attr.data("patt-attribute-id") + ")";
		var attributes = getCurrentAttribLists();
		attributes.push(value);
		initAttribute(attributes);
		attr.fadeOut("fast").remove();
		setTimeout(function() {
			melisCoreTool.pending(".prdDelAttr");
		}, 1000);
		
		if($(".prdDelAttr").length === 0) {
			$("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").show();
		}
		
	});
	
	body.on("click", ".prdDelCat", function() {
		var cat = $(this).parent().parent();
		// add in the deleted_product_category
		var delProdCatCont = $("#" + melisCommerce.getCurrentProductId()  + "_deleted_product_category_area");
		delProdCatCont.append('<span data-pcat-id="' + cat.data("pcat-id") + '" data-pcat-cat-id="'+cat.data("pcat-cat-id")+'"></span>');
		cat.fadeOut("slow").remove();
		if($(".prdDelCat").length === 0) {
			$("p#" + melisCommerce.getCurrentProductId()+"_no_categories").show();
		}
	});

	body.on("keyup", "#productCategorySearch", function(e){ 
		var searchString = $(this).val();
		$("#"+melisCommerce.getCurrentProductId()+"_productCategory").jstree('search', searchString);
	});
	
	body.on("click", ".btnProdSaveCategory", function() {
        var checkedIds = []; 
        var textProd = "";
        checkedIds = $("#"+melisCommerce.getCurrentProductId()+"_productCategory").jstree().get_checked(true); 
        var prodCat = [];
        $.each(checkedIds, function(i, v){
        	textProd = v.text.split(" - ");
        	if($("#"+melisCommerce.getCurrentProductId()+"_product_category_area").find("span[data-pcat-cat-id='"+v.id+"']").length === 0) {
            	$("#"+melisCommerce.getCurrentProductId()+"_product_category_area").append(
        			'<span class="prod-cat-values" data-pcat-cat-id="'+v.id+'">' +
                        '<span class="ab-attr">' + textProd[1] +'<i class="prdDelCat fa fa-times"></i></span>' +
                    '</span>'
                );
            	$.get( "melis/MelisCommerce/MelisComProduct/getProductCategoryLastOrderNum", {catId : v.id, prodId : melisCommerce.getCurrentProductId()}, function( data ) {
            		$("#"+melisCommerce.getCurrentProductId()+"_product_category_area").find("span[data-pcat-cat-id='"+v.id+"']").attr("data-pcat-order", data.order); 
    			});
        		$("#" + melisCommerce.getCurrentProductId()  + "_deleted_product_category_area span[data-pcat-cat-id='"+v.id+"']").remove(); 
        		$("p#" + melisCommerce.getCurrentProductId()+"_no_categories").hide();
        	}
        });
	}); 
	
	body.on("click", ".btnProductListDelete", function() {
		
		var productId = $(this).parent().parent().attr("id");
		melisCoreTool.pending(".btnProductListDelete");
		melisCoreTool.confirm(
		translations.tr_meliscommerce_documents_common_label_yes,
		translations.tr_meliscommerce_documents_common_label_no,
		translations.tr_meliscommerce_product_remove_title, 
		translations.tr_meliscommerce_product_remove_confirm, 
		function() {
    		$.ajax({
    	        type        : 'POST', 
    	        url         : '/melis/MelisCommerce/MelisComProduct/delete',
    	        data		: {productId : productId},
    	        dataType    : 'json',
    	        encode		: true,
    	     }).success(function(data){
				if(data.success) {
	 				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
	 				melisHelper.tabClose(productId+ "_id_meliscommerce_products_page");
	 				melisCore.flashMessenger();
	 				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
	 			}
    	     });
		});
		melisCoreTool.done(".btnProductListDelete");
	});
	
	body.on("click", ".toggleformCreateTextTypeContainer", function() {
		$("div.formCreateTextTypeContainer").slideToggle();
	});
	
	body.on("click", ".product-category-tree-view-lang li a", function() {
		var langText = $(this).text();
		var langLocale = $(this).data('locale');
		$(this).parents('.product-category-tree-view-lang').prev().find('span.filter-key').text(langText);
		$("#" + melisCommerce.getCurrentProductId() + "_productCategory").data('langlocale',langLocale);
		$("#" + melisCommerce.getCurrentProductId() + "_productCategory").jstree('destroy');
		initTreeCat();
	});
	
	
    $("body").on("mouseenter", ".toolTipHoverEvent", function(e) {
	  var productId = $(this).data("productid");
	  var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
	  $.each($("table.productTable"+productId + " thead").nextAll(), function(i,v) {
		  $(v).remove();
	  });
	  $(loaderText).insertAfter("table.productTable"+productId + " thead");
		$.ajax({
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
				  $.each($("table.productTable"+productId + " thead").nextAll(), function(i,v) {
					  $(v).remove();
				  });
    		     $.each(data.content.reverse(), function(i ,v) {
    		    	 $(v).insertAfter("table.productTable"+productId + " thead")
    		     });
		    	 
		     }

	     });
	  });

	function getSelAttributes() {
		var strInt = [];
		var attribs = $("div#"+melisCommerce.getCurrentProductId()+"_attribute_area > span.attr-values");
		$.each(attribs, function(i, v) {
			strInt.push($(v).data("attr-id"));
		});
		
		return strInt;
		
	}
	
	function getSelCategories() {
		var ctr = 0;
		var strInt = [];
		var attribs = $("div#"+melisCommerce.getCurrentProductId()+"_product_category_area > span.prod-cat-values");
		$.each(attribs, function(i, v) {
			strInt.push({name: 'categories['+ctr+'][pcat_cat_id]', value: $(v).data("pcat-id")});
		});
		
		return strInt;
	}
});  

