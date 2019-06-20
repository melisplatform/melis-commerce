window.initProductSwitch = function() {
    setOnOff();
    allLoaded();
}

window.initProductCategoryList = function(productId, langLocale){
    // IE 11 below cross browser js
    if(typeof langLocale === "undefined") langLocale = melisLangId;

    if($("#"+productId+"_productCategoryList").length){

        var target = $("#"+productId+"_productCategoryList");

        if(langLocale !== melisLangId){
            target.data('langlocale', langLocale);
        }

        target.jstree('destroy');

        var dataString = new Array;

        dataString.push({
            name : 'productId',
            value : productId
        });

        dataString.push({
            name : 'idAndNameOnly',
            value : true
        });

        dataString.push({
            name : 'langlocale',
            value : target.data('langlocale')
        });

//        var categoriesChecked = new Array;
        $("div#"+productId+"_product_category_area > span.prod-cat-values").each(function(){
//            categoriesChecked.push($(this).data('pcat-cat-id'))
            
            dataString.push({
                name : 'categoriesChecked[]',
                value : $(this).data('pcat-cat-id')
            });
        });

//        if(categoriesChecked.length){
//            dataString.push({
//                name : 'categoriesChecked',
//                value : categoriesChecked
//            });
//        }

        dataString = $.param(dataString);

        target
            .on('loading.jstree', function (e, data) {
                melisCommerce.pendingZoneStart("productCategorySearchZone");
            })
            .on('loaded.jstree', function (e, data) {
                melisCommerce.pendingZoneDone("productCategorySearchZone");
            })
            .jstree({
                "types" : {
                    "default" : {
                        "icon" : "fa fa-circle text-success",
                    },
                    "selected": {
                        "select_node": false
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
                        "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?"+dataString
                    }
                },
                "checkbox": {
                    three_state: false,
                    whole_node : false,
                    tie_selection : false
                },
                "plugins": [
                    "search",
                    "changed", // Plugins for Change and Click Event
                    "types", // Plugins for Customizing the Nodes
                    "checkbox"
                ]
            });
    }
}

window.initAttribute = function(data) {
    if(melisCommerce.getCurrentProductId() != null) {
        if(!data) {
            populateAttribList(data);
        }
    }
}

window.populateAttribList = function(data) {
    if($("#"+activeTabId).length === 1) {
        melisCommerce.enableAllTabs();
    }
}


window.initProductTextTinyMce = function(productId) {
    var targetEditor = "#"+productId+"_id_meliscommerce_products_page textarea.product-text-mce[data-display='true']:not([id])";

    if($(targetEditor).length){
        $(targetEditor).each(function(index, value){
            var form = $(this);

            var random = Math.random().toString(36).substr(2, 9);
            var targetSelector = random+"_"+productId+"_"+value.name;

            form.attr("id", targetSelector);

            var option = {
                mode : "none",
                height : "400px",
                relative_urls : false,
                language : 'en',
                templates : 'miniTemplates',
                menubar : false,
                forced_root_block : '',
                paste_word_valid_elements : "p,b,strong,i,em,h1,h2,h3,h4",
                cleanup : false,
                verify_html : false,
                plugins : [
                    //[contextmenu, textcolor, colorpicker] this plugin is already built in the core editor as of TinyMCE v. 5
                   'autolink advlist lists link paste image charmap preview anchor emoticons help hr nonbreaking',
                   'searchreplace visualblocks code fullscreen',
                   'insertdatetime media table template'
                ],
                image_advtab: true,
                toolbar : 'insertfile undo redo paste | formatselect | forecolor | bold italic strikethrough underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media template | code',
                init_instance_callback  : productTextTinyMCECallback(form, productId)
            }

            /*
                For reference before fixed: http://mantis.melistechnology.fr/view.php?id=3675
                plugins : [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste template',
                    'textcolor',
                ],
                toolbar : 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | forecolor backcolor | code',
            */

            //Initialize TinyMCE editor
            melisTinyMCE.createTinyMCE("tool", "#"+targetSelector, option);
        });
    }else{
        $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeOut("slow");
    }
}

window.productTextTinyMCECallback = function(editor, productId){
    $("#"+editor.id).parents("div.form-group").attr("data-tinymce", "true");

    // Removing Alert message for Initializing TinyMCE
    $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeOut("slow");
}

window.allLoaded = function() {
    melisCommerce.enableAllTabs();
}
window.reInitProductTextTypeSelect = function(productId) {
    // Remove items that are already existing in the product text
    var productTexts = [];
    var formTextForms = $("#" + productId + "_id_meliscommerce_products_page .product-text-forms > .custom-field-type");
    $.each(formTextForms, function(i, v){
        $.each($(v).find("input#ptt_id"), function(x, y) {
            productTexts.push($(y).val());
        });
    });
    var select = $("form[data-modalform='"+melisCommerce.getCurrentProductId()+"_productTextForm'] select#ptxt_type");
    $.each(productTexts, function(i, v) {
        select.find("option[value='"+v+"']").remove();
    });
}
$(document).ready(function() {

    var body = $("body");

    body.on("click", ".productCategoryList", function(){
        var btn = $(this);
        var productId = btn.data("productid");

        btn.attr("disabled", true);

        zoneId = 'id_meliscommerce_products_main_tab_categories_modal';
        melisKey = 'meliscommerce_products_main_tab_categories_modal';
        modalUrl = '/melis/MelisCommerce/MelisComProduct/renderProductModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {productId: productId}, modalUrl, function(){
            btn.attr("disabled", false);
        });
    });

    body.on("click", ".addProductCategory", function() {
        var btn = $(this);
        var productId = btn.data("productid");

        var checkedCategories = new Array;
        $.each($("#"+productId+"_productCategoryList").jstree().get_checked(true), function(){
            checkedCategories.push(parseInt(this.id));
        });

        var uncheckedCategories = new Array;
        var addedCategories = new Array;
        $("#"+productId+"_product_category_area span[data-pcat-cat-id]").each(function(){
            var prdCatId = parseInt($(this).data("pcat-cat-id"));
            if(checkedCategories.indexOf(prdCatId) !== -1){
                addedCategories.push(prdCatId);
            }else{
                uncheckedCategories.push(prdCatId);
                $("span.prod-cat-values[data-pcat-cat-id='"+prdCatId+"']").remove();
            }
        });

        $.each($("#"+productId+"_productCategoryList").jstree().get_checked(true), function(){
            var catId = parseInt(this.id);
            if(uncheckedCategories.indexOf(catId) === -1){
                if(addedCategories.indexOf(catId) === -1){
                    var catText = this.text.split(" - ")[1];
                    $.get( "/melis/MelisCommerce/MelisComProduct/getProductCategoryLastOrderNum", {catId : catId, prodId : productId}, function( data ) {
                        $("#"+productId+"_product_category_area").append(
                            '<span class="prod-cat-values" data-pcat-id="'+data.id+'" data-pcat-cat-id="'+catId+'" data-pcat-order="'+data.order+'">' +
                            '<span class="ab-attr">' + catText +'<i class="prdDelCat fa fa-times"></i></span>' +
                            '</span>');
                    });
                }
            }
        });

        if(checkedCategories.length){
            $("p#"+productId+"_no_categories").hide();
        }else{
            $("p#"+productId+"_no_categories").show();
        }

        $("#id_meliscommerce_products_main_tab_categories_modal_container").modal("hide");
    });

    body.on("click", ".product-category-tree-view-lang li a", function() {
        var langText = $(this).text();
        var langLocale = $(this).data('locale');
        var productId = $('.productCategoryLangDropdown').data('productid');

        $('.productCategoryLangDropdown span.filter-key').text(langText);
        initProductCategoryList(productId, langLocale);
    });

    body.on("click",".productTextForm .deleteTextInput", function(){
        var text = $(this).parent().attr("data-text-identifier");

        var form  = $(this).parents("form");
        var select = $("form[data-modalform='"+melisCommerce.getCurrentProductId()+"_productTextForm'] select#ptxt_type");

        melisCoreTool.pending(".deleteTextInput");
        melisCoreTool.confirm(
            translations.tr_meliscore_common_yes,
            translations.tr_meliscore_common_no,
            translations.tr_meliscommerce_product_confirm_remove_title,
            translations.tr_meliscommerce_product_confirm_remove_test,
            function() {
                $.each(form, function(i, v) {
                    var text = $(v).find("a[data-text-identifier]").attr('data-text-identifier');
                    var id = $(v).find("input#ptt_id").val();
                    var exists = select.find("option[value='"+id+"']").length;
                    if(!exists) {
                        select.prepend($("<option>", {
                            value: id,
                            text : text
                        }));
                    }
                });
                $("#" + activeTabId + " .productTextForm .deleteTextInput").closest("a[data-text-identifier='"+text+"']").closest("form").remove();
            }
        );
        melisCoreTool.done(".deleteTextInput");

    });

    // coupons / product list
    body.on("click", ".btnProductListEdit", function() {
        var productId   = $(this).parents("tr").attr('id'),
            productName = $(this).parents("tr").find("td span").data("productname"),
            navTabsGroup = "id_meliscommerce_product_list_container";

        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_product_list_container']");

        // check whether to open the products tab
        if ( alreadyOpen.length > 0 ) {
            melisCommerce.disableAllTabs();
            melisCommerce.openProductPage(productId, productName, navTabsGroup);
            melisCommerce.setUniqueId(productId);
        } else {
            melisHelper.tabOpen("Products", "icon-shipment", "id_meliscommerce_product_list_container", "meliscommerce_product_list_container", "", navTabsGroup, function() {
                melisCommerce.disableAllTabs();
                melisCommerce.openProductPage(productId, productName, navTabsGroup);
                melisCommerce.setUniqueId(productId);
            });
        }
    });

    body.on("click", "#btnAddProduct", function() {
        var navTabsGroup = "id_meliscommerce_product_list_container";

        melisCommerce.openProductPage(0, translations.tr_meliscommerce_products_page_new_product, navTabsGroup);
        melisCommerce.setUniqueId(0);
    });

    body.on("click", "#addProdTextType", function() {
        var productId = $(this).data("productid");
        var dataString = $("form#productTextTypeForm").serialize();
        var $panel = $(this).closest('.panel').find('.panel-heading .panel-title a');
        var $panelBody = $(this).closest('.panel').find('.panel-collapse');
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
                //collapse the pannel
                $panel.addClass('collapsed');
                $panel.attr('aria-expanded', false);
                $panelBody.removeClass('in');
                $panelBody.attr('aria-expanded', false);

                melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_product_text_modal_form", "meliscommerce_products_page_content_tab_product_text_modal_form",  {productId : melisCommerce.getCurrentProductId()});
            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
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
        var productId = $(this).data("productid");
        var textSelect = $("#" + productId + "_id_meliscommerce_products_page_content_tab_product_text_modal_form").find("select#ptxt_type")
        typeId = textSelect.val();
        var typeText = $("#" + productId + "_id_meliscommerce_products_page_content_tab_product_text_modal_form").find("select#ptxt_type option:selected").text();
        melisCoreTool.pending(this);
        $.ajax({
            type: "GET",
            url : "/melis/MelisCommerce/MelisComProduct/getEmptyProductTextForm?textTypeId=" + typeId + "&text="+typeText,
            dataType : "json",
            encode		: true
        }).success(function(data) {
            var formTextForms = $("#" + productId + "_id_meliscommerce_products_page .product-text-forms > .custom-field-type");
            if(formTextForms.length){
                $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeIn("slow");
            }

            $.each(formTextForms, function(i, v){
                var langId =  $(v).data("lang-id");
                var totalLangLength = $("#" + productId + "_id_meliscommerce_products_page .product-text-tab li#languageList").length;
                if($("#" + productId + "_id_meliscommerce_products_page .productTextForm a[data-text='"+typeText+"']").length < totalLangLength) {
                    $(v).find(".custom-field-type-area").append(data.content).data("text-lang-id", langId);
                    $(v).find("form[name='productTextForm']").attr("data-text-lang-id", langId);
                    $(v).find("form[name='productTextForm'] input#ptxt_lang_id").val(langId);
                    initProductTextTinyMce(productId);
                    $("div[data-modalname='genericProductTextModal']").modal('hide');
                    $("div[data-class='addTextFieldNotif']").html("").attr("class", "");
                }else{
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
        var pageContainer = $(this).closest('.container-level-a');
        var stockAlertForm = $(pageContainer).find('#'+ productId + '_id_meliscommerce_settings_tabs_content_main_details_left form');
        var emails = $(pageContainer).find('.alert-email-values');

        Array.prototype.push.apply(dataString,$(stockAlertForm).serializeArray());

        var sea_id = '';
        var sea_email = '';
        var sea_user_id = '';

        emails.each(function(){

            sea_id = $(this).data('seaid');
            sea_email = $(this).data('alertemail');
            sea_user_id = $(this).data('userid');

            dataString.push({ name : 'recipients['+ctr+'][sea_id]', value : sea_id });
            dataString.push({ name : 'recipients['+ctr+'][sea_email]', value : sea_email });
            dataString.push({ name : 'recipients['+ctr+'][sea_user_id]', value : sea_user_id });

            if (typeof productId !== "undefined") {
                dataString .push({  name : 'recipients['+ctr+'][sea_prd_id]', value : productId});
            }
            ctr++;
        });

        ctr = 0;
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
            var attr = $(this).find('input').attr('name');
            if (typeof attr !== typeof undefined && attr !== false) {
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
            }
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
                var navTabsGroup = "id_meliscommerce_product_list_container";
                var listParent = $('.tab-element[data-id="'+ data.chunk.productId +'_id_meliscommerce_products_page"]').parent();

                melisCommerce.closeCurrentProductPage();
                melisCommerce.openProductPage(data.chunk.productId, data.chunk.prodName, navTabsGroup);
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload("id_meliscommerce_product_list_content", "meliscommerce_product_list_content");
                melisCommerce.setUniqueId(data.chunk.productId);

                if(listParent.hasClass("has-sub")) {
                    var subNav = listParent.find(".nav-group-dropdown").prop('outerHTML');
                    $('.tab-element[data-id="'+ data.chunk.productId +'_id_meliscommerce_products_page"]').parent().addClass("has-sub").append(subNav);
                }
            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
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


    body.on('click','.add-attribute',function() {
        var selAttrVal = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput");
        var selAttrValCont = $("#" + activeTabId + " #select2-"+melisCommerce.getCurrentProductId()+"_prodAttribInput-container");
        var select2 = $("#" + activeTabId + " span.select2");
        var attributeLists = $('div#'+activeTabId+' select.dropdown-input'+melisCommerce.getCurrentProductId());
        if(selAttrVal.val()) {
            var selId = selAttrVal.val();
            var selText = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput option[value='"+selId+"']").html();
            var selTextEl = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput option[value='"+selId+"']");
            if($("#"+melisCommerce.getCurrentProductId()+"_attribute_area").find("span[data-patt-attribute-id='"+selId+"']").length === 0) {
                $("#"+melisCommerce.getCurrentProductId()+"_attribute_area").append('<span class="attr-values" data-patt-attribute-id="'+selId+'"><span class="ab-attr"><span class="attrib-value">'+selText+'</span>'+
                    '<i class="prdDelAttr fa fa-times"></i></span>');
                $("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").hide();

                // manipulate select option items
                var ctr = 0;
                var tmpOptionItems = new Object();
                $.each(selAttrVal.find("option"), function(i,v) {
                    if(selId !== $(v).val()) {
                        tmpOptionItems[ctr] = {id : $(v).val(), value : $(v).html()};
                        ctr++;
                    }
                });
                selTextEl.remove();
                selAttrValCont.html("").attr("title", "");
            }
            selAttrValCont.css("color", "#444");
            select2.css("border", "none");
        }
        else {
            selAttrValCont.css("color", "#e80e05");
            select2.css("border", "1px solid #e80e05");
        }
    });

    body.on("click", ".prdDelAttr", function() {
        var _this = $(this);
        var selAttrVal = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput");

        var attr = _this.parent().parent();
        var id = _this.parents("span.attr-values").attr("data-patt-attribute-id");
        var value = _this.parent().find("span.attrib-value").html();

        $.ajax({
            type        : 'GET',
            url         : '/melis/MelisCommerce/MelisComProduct/checkAttributeOnVariant',
            data		: {productId : melisCommerce.getCurrentProductId(), patt_attr_id:id},
            dataType    : 'json',
            encode		: true,
            beforeSend  : function(){
                _this.removeClass('fa-times');
                _this.addClass('fa-spinner fa-pulse');
            }
        }).success(function(data){
            if(data.attribute_is_used){
                _this.removeClass('fa-spinner fa-pulse');
                _this.addClass('fa-times');
                melisHelper.melisKoNotification(translations.tr_meliscommerce_attribute_delete_product_attr_title, translations.tr_meliscommerce_attribute_delete_product_attr_title_msg);
            }else{
                var newOption = $("<option selected></option>");
                newOption.val(id);
                newOption.text(value);
                newOption.val(id); // set the id
                newOption.text(value);
                selAttrVal.append(newOption);
                selAttrVal.val("");
                selAttrVal.select2({
                    placeholder: translations.tr_meliscommerce_products_main_tab_attributes_content_label,
                    val: ''
                });
                selAttrVal.val("");
                attr.fadeOut("fast").remove();
                var selAttrValCont = $("#" + activeTabId + " #select2-"+melisCommerce.getCurrentProductId()+"_prodAttribInput-container");
                setTimeout(function() {
                    //selAttrValCont.html("").attr("title", "");
                },1);
                setTimeout(function() {
                    melisCoreTool.pending(".prdDelAttr");
                }, 1000);

                selAttrVal.css("border", "1px solid #e5e5e5");
                if($(".prdDelAttr").length === 0) {
                    $("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").show();
                }
            }
        });
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
        var prdId = $(this).data("productid");
        var searchString = $(this).val();
        $("#"+prdId+"_productCategoryList").jstree('search', searchString);
    });

    // Clear Input Search
    body.on("click", "#clearPrdCatSearchInputBtn", function(e){
        var prdId = $(this).data("productid");
        categoryOpeningItemFlag = false;
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        $("#productCategorySearch").val("");
        prdCatTree.jstree('search', '');
    });

    // Toggle Buttons for Category Tree View
    body.on("click", "#expandPrdCatTreeViewBtn", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree("open_all");
    });

    body.on("click", "#collapsePrdCatTreeViewBtn", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree("close_all");
    });

    // Refrech Category Tree View
    body.on("click", "#refreshPrdCatTreeView", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree(true).refresh("forget_state", true);
        prdCatTree.jstree('search', '');
        $("#productCategorySearch").val("");
    });

    body.on("click", ".btnProductListDelete", function() {

        var productId = $(this).closest("tr").attr("id");
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
                    encode		: true
                }).success(function(data){
                    if(data.success) {
                        $("#" + activeTabId + " .melis-refreshTable").trigger("click");
                        melisHelper.tabClose(productId+ "_id_meliscommerce_products_page");
                        melisCore.flashMessenger();
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                    }
                });
            });
        melisCoreTool.done(".btnProductListDelete");
    });

    body.on("click", ".toggleformCreateTextTypeContainer", function() {
        $("div.formCreateTextTypeContainer").slideToggle();
    });

    body.on("mouseenter mouseout", ".toolTipHoverEvent", function(e) {
        $(".thClassColId").attr("style", "");
        var productId = $(this).data("productid");
        var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
        $.each($("table#productTable"+productId + " thead").nextAll(), function(i,v) {
            $(v).remove();
        });
        $(loaderText).insertAfter("table#productTable"+productId + " thead");
        var xhr = $.ajax({
            type        : 'POST',
            url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
            data		: {productId : productId},
            dataType    : 'json',
            encode		: true
        }).success(function(data){
            $("div.qtipLoader").remove();
            if(data.content.length === 0) {
                $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
            }
            else {
                // make sure tbody is clear
                $.each($("table#productTable"+productId + " thead").nextAll(), function(i,v) {
                    $(v).remove();
                });
                $.each(data.content.reverse(), function(i ,v) {
                    $(v).insertAfter("table#productTable"+productId + " thead")
                });

            }

        });
        if(e.type === "mouseout") {
            xhr.abort();
        }
    });

    body.on("click",".add-product-text", function(){
        melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text", "meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text", {productId : melisCommerce.getCurrentProductId()});
        reInitProductTextTypeSelect(melisCommerce.getCurrentProductId());
    });

    /*$("body").on("click",".add-product-text", function() {
        melisHelper.createModal('id_meliscommerce_products_page_content_tab_product_text_modal_form',
            'meliscommerce_products_page_content_tab_product_text_modal_form',
            true,
            {},
            'melis/MelisCommerce/MelisComProduct/render-products-page-content-tab-text-modal-form',
            function() {
    
            }
        );
    });*/

    body.on("click", ".openVariant", function(){
        var $this        = $(this),
            productId    = $this.data("product-id"),
            productName  = $this.data("product-name"),
            prodTabId    = productId+"_id_meliscommerce_products_page",
            navTabsGroup = "id_meliscommerce_product_list_container";
           
            // openProductPage to open product page then callback function tabOpen to open variant page
            melisCommerce.openProductPage(productId, productName, navTabsGroup, function() {
                var variantId   = $this.parent().siblings(":first").text(),
                    variantName = $this.parent().parent().find("td:nth-child(4)").text();

                    melisCommerce.disableAllTabs();
                    melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId}, prodTabId);
                    melisCommerce.setUniqueId(variantId);
                    melisCommerce.enableAllTabs();
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

