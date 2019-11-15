$(function() {
	var $body = $("body");
		// char counter in seo title
		$body.on("keyup keydown change", "input[name='eseo_meta_title']", { limit: 60 }, seoCharCounter);
		
		// char counter in seo description
		$body.on("keyup keydown change", "textarea[name='eseo_meta_description']", { limit: 160 }, seoCharCounter);
});

window.preSeoCharCounter = function() {
	$("form.seoForm").each(function() {
		var from 		= $(this),
			metaTitle 	= from.find("input[name='eseo_meta_title']"),
			metaDesc 	= from.find("textarea[name='eseo_meta_description']");

			metaTitle.trigger('keyup');
			metaDesc.trigger('keyup');
	});
}

// Meta Title and Description Character Counter
window.seoCharCounter = function (event) {
	var $this 		= $(this),
		charLength 	= $this.val().length,
		prevLabel 	= $this.prev('label'),
		limit 		= event.data.limit;
	
		if ( prevLabel.find('span').length ) {
			if ( charLength === 0 ) {
				prevLabel.removeClass('limit');
				prevLabel.find('span').remove();
			}
			else {
				prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
				if ( charLength > limit ) {
					prevLabel.addClass('limit');
					prevLabel.find('span').addClass('limit');
				}
				else {
					prevLabel.removeClass('limit');
					prevLabel.find('span').removeClass('limit');
				}	
			}
		}
		else {
			if ( charLength !== 0 ) {
				prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
				
				if ( charLength > limit ) {
					prevLabel.addClass('limit');
					prevLabel.find('span').addClass('limit');
				}
			}
		}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString) {
		
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