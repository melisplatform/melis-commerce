$(function(){
	// char counter in seo description
	$("body").on("keyup keydown", "input[name='eseo_meta_description']", { limit: 255 }, categorySeoCharCounter);
    
    // char counter in seo title
	$("body").on("keyup keydown", "input[name='eseo_meta_title']", { limit: 255 }, categorySeoCharCounter);
});


// Category SEO Form 
// Meta Title and Description Character Counter
window.categorySeoCharCounter = function (event){
	
	var charLength = $(this).val().length;
	var prevLabel = $(this).prev('label');
	var limit = event.data.limit;
	
	if( prevLabel.find('span').length ){
		
		if(charLength === 0){
			prevLabel.find('span').remove();
		}else{
			prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
			
			if( charLength > limit ){
				prevLabel.find('span').addClass('limit');
			}else{
				prevLabel.find('span').removeClass('limit');
			}	
		}
	}else{
		if(charLength !== 0){
			prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
		}
	}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString){
		
		// Category Transalations
		var seoDataString = new Array;
		$("form."+fromType+"_"+typeId+"_seo_form").each(function(){
			langId = $(this).data("langid");
			
			seoDataString = $(this).serializeArray();
			
			$.each(seoDataString, function(){
				dataString.push({
					name : fromType+'_seo['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		return dataString;
	}
	
	return {
		serializeSeo: serializeSeo,
	}
})(window);