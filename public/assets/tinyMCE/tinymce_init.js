var locale = melisLangId;

if(typeof(tinyMCE) != 'undefined' && tinymce != null) {
	try {
		tinymce.remove(".product-text-mce");
	} catch (e) {
	}
}
//var locale = "en";

// Remove first some specific attributes so we can initialize tinyMCE
// successfully, since tinyMCE doesn't accept any extra attributes while initializing
$("#" + activeTabId + " textarea[data-display='true']").removeAttr("name");
$("#" + activeTabId + " textarea[data-display='true']").removeAttr("style");
$("#" + activeTabId + " textarea[data-display='true']").removeAttr("id");

// do the initialization
tinymce.init({
	selector : "form div.form-group textarea.product-text-mce",
    width: "100%",
    height: '400px',
	language: locale,
	plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table contextmenu paste template',
		'textcolor',
	],
	relative_urls : false,
	moxiemanager_title: 'Media Library',
	force_br_newlines : false,
	force_p_newlines : false,
	forced_root_block : '',
	cleanup : false,
	verify_html : false,
	external_plugins: {
		"moxiemanager": "/melis/MelisSmallBusiness/js/tinyMCE/moxiemanager/plugin.min.js"
	},
	menubar: false,
	toolbar: 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | forecolor backcolor | code',
	setup : function(ed) {
		// callback event here
		ed.on('change', function() {
			// then ensures that the tinymce content and  textarea has the same value
			ed.save();
		});
		
		ed.on("init", function() {
			setTimeout(function() {
				$(".notifTinyMcePreloaInfo").fadeOut("slow");
			},1500);
			
		});
	}

});

// hide again the extra textarea that are not supposed to be exposed
$("#" + activeTabId + " textarea[data-display='false']").css("display", "none");
// instead of doing a callback on tinyMCE, we put it here so it would not cause any error on initialization
$("#" + activeTabId + " form div.form-group textarea.product-text-mce").attr("name", "ptxt_field_long");
$("#" + activeTabId + " form div.form-group textarea.product-text-mce").parents("div.form-group").attr("data-tinymce", "true");

setTimeout(function() {
	$(".notifTinyMcePreloaInfo").fadeOut("slow");
},1500);