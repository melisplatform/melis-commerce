$(function(){
	$("body").on("click", ".melisComDuplicateVariant", function(){
		var btn = $(this);
		var variantId   = btn.parents("tr").attr('id');
		btn.attr("disabled", true);
		
		zoneId = 'id_meliscommerce_variant_duplication';
		melisKey = 'meliscommerce_variant_duplication';
		modalUrl = '/melis/MelisCommerce/MelisComPrdVarDuplication/renderDuplicateModal';
		
    	melisHelper.createModal(zoneId, melisKey, false, {variantId: variantId}, modalUrl, function(){
    		btn.attr("disabled", false);
    	});
	});
	
	$("body").on("click", ".melisComDuplicateProduct", function(){
		var btn = $(this);
		var productId   = btn.parents("tr").attr('id');
		btn.attr("disabled", true);
		
		zoneId = 'id_meliscommerce_product_duplication';
		melisKey = 'meliscommerce_product_duplication';
		modalUrl = '/melis/MelisCommerce/MelisComPrdVarDuplication/renderDuplicateModal';
		
		melisHelper.createModal(zoneId, melisKey, false, {productId: productId}, modalUrl, function(){
			btn.attr("disabled", false);
		});
	});
	
	$("body").on("click", "#melisComStartDuplicateVariant", function(){
		var btn = $(this);
		var dataString = new Array;
		
		$("#id_meliscommerce_variant_duplication form").each(function(){
			var var_id = $(this).find("input[name='var_id']").val();
			var tempData = $(this).serializeArray();
			$.each(tempData, function(){
				if(this.name !== 'var_id'){
					dataString.push({
						name : 'variantSku['+var_id+']['+this.name+']',
						value: this.value
					});
				}
			});
		});
		
		var duplicateImages = 0;
		if($('#duplicate_images').is(':checked')){
			duplicateImages = 1;
		}
		
		dataString.push({
			name : "duplicate_images",
			value: duplicateImages
		});
		
		var duplicateDocs = 0;
		if($('#duplicate_documents').is(':checked')){
			duplicateDocs = 1;
		}
		
		dataString.push({
			name : "duplicate_documents",
			value: duplicateDocs
		});
		
		var putOnline = 0;
		if($('#put_online').is(':checked')){
			putOnline = 1;
		}
		
		dataString.push({
			name : "var_status",
			value: putOnline
		});
		
		dataString.push({
			name : "duplication_type",
			value: "variant"
		});
		
		btn.attr('disabled', true);
		
		$.ajax({
	        type        : "POST",
	        url         : "/melis/MelisCommerce/MelisComPrdVarDuplication/duplicateVariant",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data){
			
			btn.attr('disabled', false);
			
			if(data.success){
				$("#id_meliscommerce_variant_duplication_container").modal("hide");
				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});
				
			}else{
				melisSKUKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
			}
			
			highlightSKUErrors(data.success, data.errors);
			melisCore.flashMessenger();	
		}).fail(function(){
			btn.attr('disabled', false);
			alert(translations.tr_meliscore_error_message);
		});
	});
	
	$("body").on("click", ".main-variant-radio i", function(){
		console.log($(this).data("variantid"));
		
		$(this).addClass("fa-dot-circle-o");
		$(this).addClass("text-success");
		
		$(".main-variant-radio i").not(this).addClass("fa-circle-o");
		$(".main-variant-radio i").not(this).removeClass("fa-dot-circle-o");
		$(".main-variant-radio i").not(this).removeClass("text-success");
	})
	
	$("body").on("click", "#melisComStartDuplicateProduct", function(){
		
		var btn = $(this);
		var dataString = new Array;
		
		$("#id_meliscommerce_product_duplication form").each(function(){
			var var_id = $(this).find("input[name='var_id']").val();
			var tempData = $(this).serializeArray();
			$.each(tempData, function(){
				if(this.name !== 'var_id'){
					dataString.push({
						name : 'variantSku['+var_id+']['+this.name+']',
						value: this.value
					});
				}
			});
		});
		
		dataString.push({
			name : "product_id",
			value: btn.data("productid")
		});
		
		var duplicateImages = 0;
		if($('#duplicate_images').is(':checked')){
			duplicateImages = 1;
		}
		
		dataString.push({
			name : "duplicate_images",
			value: duplicateImages
		});
		
		var duplicateDocs = 0;
		if($('#duplicate_documents').is(':checked')){
			duplicateDocs = 1;
		}
		
		dataString.push({
			name : "duplicate_documents",
			value: duplicateDocs
		});
		
		var putOnline = 0;
		if($('#put_online').is(':checked')){
			putOnline = 1;
		}
		
		dataString.push({
			name : "prd_status",
			value: putOnline
		});
		
		dataString.push({
			name : "main_variant_id",
			value: $(".main-variant-radio i.fa-dot-circle-o").data("variantid")
		});
		
		dataString.push({
			name : "duplication_type",
			value: "product"
		});
		
		btn.attr('disabled', true);
		
		$.ajax({
	        type        : "POST",
	        url         : "/melis/MelisCommerce/MelisComPrdVarDuplication/duplicateProduct",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data){
			
			btn.attr('disabled', false);
			
			if(data.success){
				$("#id_meliscommerce_product_duplication_container").modal("hide");
				
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload("id_meliscommerce_product_list_container", "meliscommerce_product_list_container");
			}else{
				melisSKUKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
			}
			
			highlightSKUErrors(data.success, data.errors);
			melisCore.flashMessenger();	
		}).fail(function(){
			btn.attr('disabled', false);
			alert(translations.tr_meliscore_error_message);
		});
	});
});

function melisSKUKoNotification(title, message, errors, closeByButtonOnly){
	
	( closeByButtonOnly !== 'closeByButtonOnly' ) ? closeByButtonOnly = 'overlay-hideonclick' : closeByButtonOnly = '';

	var errorTexts = '<h3>'+ title +'</h3>';
	errorTexts +='<h4>'+ message +'</h4>';
	
	$.each( errors, function( key, error ) {
		if(key !== 'label'){
			errorTexts += '<p class="modal-error-cont"><b>'+ (( errors[key]['label'] == undefined ) ? ((errors['label']== undefined) ? key : errors['label'] ) : errors[key]['label'] )+ ': </b>  ';
			// catch error level of object
			try {
				$.each( error, function( key, value ) {
					if(key !== 'label' && key !== 'form'){
						
						$errMsg = '';
						if(value instanceof Object){
							$errMsg = value[0];
						}else{
							$errMsg = value;
						}
						errorTexts += '<span><i class="fa fa-circle"></i>'+ $errMsg + '</span>';
					}
				});
			} catch(Tryerror) {
				if(key !== 'label' && key !== 'form'){
					 errorTexts +=  '<span><i class="fa fa-circle"></i>'+ error + '</span>';
				} 
			}	
			errorTexts += '</p>';
		}
	});
		
	var div = "<div class='melis-modaloverlay "+ closeByButtonOnly +"'></div>";
	div += "<div class='melis-modal-cont KOnotif'>  <div class='modal-content'>"+ errorTexts +" <span class='btn btn-block btn-primary'>"+ translations.tr_meliscore_notification_modal_Close +"</span></div> </div>";
	$body.append(div);
}

function highlightSKUErrors(success, errors, divContainer){
	
	$(".duplicate-table-label").css("color", "inherit");
	
	// if all form fields are error color them red
	if(success === 0){
		
		$.each( errors, function( key, error ) { 
			if("form" in error){
				$.each(this.form, function( fkey, fvalue ){
					$("#" + fvalue + "_label").css("color","red");
				});
			}
		});
	}
}