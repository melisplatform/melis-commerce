$(function() {
	var $body = $("body");

		// Modal Save Button
		$body.on("submit", "form.frmDocAddFile", function(e) {
			var relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
				target 			= "form#"+relationId+"_"+relationType+"_frmDocUpload",
				docType 		= $(target).data("upload-type"),
				formData 		= new FormData($(target)[0]),
				saveType 		= $(target).data("savetype");

				formData.append("docRelationType", relationType);  // doc type if category, product, or variant
				formData.append("relationId", relationId);
				formData.append("docType", docType); // if image or file

				melisCoreTool.pending(".btn");

				$.ajax({
					type 		: 'POST',
					url  		: '/melis/MelisCommerce/MelisComDocument/saveDocument',
					data 		: formData,
					processData : false,
					cache       : false,
					contentType : false,
					dataType    : 'json',
					xhr: function() {
						var fileXhr = $.ajaxSettings.xhr();
							if ( fileXhr.upload ) {
								fileXhr.upload.addEventListener('progress',progress, false);
							}
							return fileXhr;
					}
				}).done(function(data) {
					if ( data && data.success ) {
						var docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
							docRelationId   = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");

							// $("div.modal").modal("hide");
							var modalId = $("div.modal").attr("id");
								if (modalId != "" && modalId != "undefined") {
									melisCoreTool.hideModal(modalId);
								}
							
							if ( data.type == "file" ) {
								melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {
									docRelationType : docRelationType, docRelationId : docRelationId
								});
							}
							else if ( data.type == "image") {
								melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {
									docRelationType : docRelationType, docRelationId : docRelationId
								});
							}
							
							melisCommerce.setUniqueId(melisCommerce.getUniqueId());
							melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
						melisCoreTool.highlightErrors(data.success, data.errors, relationId+"_"+relationType+"_"+"frmDocUpload");
						$("div.progressContent").addClass("hidden");
					}
					melisCoreTool.done(".btn");
					melisCore.flashMessenger();
				}).fail(function() {
					//$("div.modal").modal("hide");
					melisCoreTool.done(".btn");

					melisHelper.melisKoNotification(translations.tr_meliscommerce_documents_Documents, translations.tr_meliscommerce_documents_save_fail, [], 'closeByButtonOnly');

					if ( $(target).find(".filestyle").val() !== '' ) {
						if ( docType == "file" ) {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
						}
						else if ( docType == "image" ) {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
						}
					}
					melisCore.flashMessenger();
				});

				e.preventDefault();
		});

		function progress(e) {
			resetProgressBar();

			if ( e.lengthComputable ) {
				var max 		= e.total,
					current 	= e.loaded,
					percentage 	= (current * 100)/max;
				
					$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", percentage);
					$("div.progressContent > div.progress > div.progress-bar").css("width", percentage+"%");

					if ( percentage > 100 ) {
						$("div.progressContent").addClass("hidden");
					}
					else {
						$("div.progressContent > div.progress > span.status").html(Math.round(percentage)+"%");
					}
			}
		}

		function resetProgressBar() {
			$("div.progressContent").removeClass("hidden");
			$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", 0);
			$("div.progressContent > div.progress > div.progress-bar").css("width", '0%');
			$("div.progressContent > div.progress > span.status").html("");
		}

		// Deleting File/Image Document Confirmation Dialog
		$body.on("click", ".deleteFileImageDocument", function(e){
			// Data Attribute on the Selected Element/button
			// Type can be "image" or "file"
			var $this       	= $(this),
				docId 			= $this.data("doc-id"),
				docType 		= $this.data("type"),
				docRelationId 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");

				if ( docType == 'image' ) {
					var title 		= translations.tr_meliscommerce_documents_delete_image_title,
						confirmMsg 	= translations.tr_meliscommerce_documents_delete_image_msg_confirm;
				} 
				else if ( docType == 'file' ) {
					var title 		= translations.tr_meliscommerce_documents_delete_file_title,
						confirmMsg 	= translations.tr_meliscommerce_documents_delete_file_msg_confirm;
				}

				melisCoreTool.confirm(
					translations.tr_meliscommerce_documents_common_label_yes,
					translations.tr_meliscommerce_documents_common_label_no,
					title,
					confirmMsg,
					function() {
						$.ajax({
							type        : 'POST',
							url         : '/melis/MelisCommerce/MelisComDocument/delete',
							data		: {id : docId, docType : docType, formType : docRelationType, uniqueId : docRelationId},
							dataType    : 'json',
							encode		: true
						}).done(function(data){
							melisCoreTool.pending(".btn");

							if ( data && data.success ) {
								if ( docType == "file") {
									melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
								}
								else if ( docType == "image" ) {
									melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
								}
								melisCore.flashMessenger();
								melisHelper.melisOkNotification(data.textTitle, data.textMessage);
							}
							else {
								melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
							}
							melisCoreTool.done(".btn");
						}).fail(function(){
							// $("div.modal").modal("hide");
							var modalId = $("div.modal").attr("id");
								if (modalId != "" && modalId != "undefined") {
									melisCoreTool.hideModal(modalId);
								}

							melisCoreTool.done(".btn");

							if ( docType == "file" ) {
								melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
							}
							else if ( docType == "image" ) {
								melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
							}
							melisCore.flashMessenger();
						});
					});
		});

		$body.on("click", ".collapseAddImageType", function() {
			var formDiv = $("div.addImageType"),
				form 	= $("form.frmDocAddFile");

				formDiv.slideToggle();

				$(".collapseAddImageType").find("i[data-class='iconAddImageType']").toggleClass("fa-angle-down");
		});

		$body.on("click", ".btnDocAddImageType", function() {
			var $this       	= $(this),
				dataString 		= $("form.frmDocAddImageType").serialize(),
				relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
				saveType 		= $("form.frmDocAddImageType").data("upload-type"),
				formData 		= $this.parent().prev().parent().find("form.frmDocAddFile").serialize(),
				image 			= $this.parent().prev().parent().find("img.imgDocThumbnail").attr("src");

				melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addImageType?typeUpload='+saveType, dataString, function(data) {
					if ( data.success ) {
						melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "image", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
						// put back all the info
						setTimeout(function() {
							$(".btnDocAddImageType").parent().prev().parent().find("img.imgDocThumbnail").attr("src", image);

							$.each(formData.split('&'), function (index, elem) {
								var vals = elem.split('=');
								$("[name='" + vals[0] + "']").val(vals[1]);
							});
						}, 2000);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
						melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
					}
					melisCore.flashMessenger();
				});
		});

		$body.on("click", ".btnDocAddFileType", function() {
			var $this    		= $(this),
				dataString 		= $("form.frmDocAddImageType").serialize(),
				relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
				saveType 		= $("form.frmDocAddImageType").data("upload-type"),
				formData 		= $this.parent().prev().parent().find("form.frmDocAddFile").serialize();

				melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addFileType?typeUpload='+saveType, dataString, function(data) {
					if ( data.success ) {
						melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "file", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);

						// put back all the info
						setTimeout(function() {
							$.each(formData.split('&'), function (index, elem) {
								var vals = elem.split('=');
								$("[name='" + vals[0] + "']").val(vals[1]);
							});
						}, 2000);

					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
						melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
					}
					melisCore.flashMessenger();
				});
		});

		$body.on("click", ".updateFileDocument", function(){
			var $this       	= $(this),
				docId 			= $this.data("doc-id"),
				docType 		= $this.data("type"),
				relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
				zoneId 			= 'id_meliscommerce_documents_modal_form',
				melisKey 		= 'meliscommerce_documents_modal_form',
				modalUrl 	= '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';

				melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'file', docId : docId, saveType : 'updatefile', docRelationId : relationId, docRelationType :relationType},  modalUrl);
		});

		$body.on("click", ".editImageDocumentModal", function() {
			var $this       	= $(this),
				typeUpload 		= "image",
				zoneId 			= 'id_meliscommerce_documents_modal_form',
				melisKey 		= 'meliscommerce_documents_modal_form',
				modalUrl 		= '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer',
				docId 			= $this.data("doc-id"),
				relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");

				melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'image', docId : docId, saveType : 'editimage', docRelationId : relationId, docRelationType :relationType}, modalUrl);
		});

		// Add File/Image Button, Request Modal Content
		$body.on("click", ".addFileImageDocument", function(e){
			var $this       	= $(this),
				typeUpload 		= $this.data('type'),
				relationId 		= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id"),
				relationType 	= $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type"),
				zoneId 			= 'id_meliscommerce_documents_modal_form',
				melisKey 		= 'meliscommerce_documents_modal_form',
				modalUrl 		= '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';

				melisCoreTool.pending(this);

				// requesitng to create modal and display after
				melisHelper.createModal(zoneId, melisKey, false, {typeUpload:typeUpload, docRelationId: relationId, docRelationType :relationType}, modalUrl, function() {
					melisCoreTool.done(".addFileImageDocument");
				});
		});
		
		$body.on("click", "#btnDocFileAdd", function(e) {
			var button = $(this),
				formId = '#'+button.attr("form");

				$(formId).trigger("submit");
				e.preventDefault();
		});
});

window.initImageDocuments = function(e) {
	// Fix for Mobile
	var custom_event = $.support.touch ? "touchstart" : "click",
		// Initialize Image Container ISOTOPE
		$container = $(e).find("div.imageDocumentContainer");

		$container.imagesLoaded( function() {
			$container.isotope({
				itemSelector : '.image'
			});
		});

		filters = {};

		// ISOTOPE filter dropdown
		/* Filter text change for Country */
		$body.on(custom_event, ".filter-div-country .filter-key-values li a", function() {
			var $this = $(this),
				value = $this.data('text');
			
				$('.filter-div-country .filter-dropdown').attr('data-value', value);
				$('.filter-div-country span.filter-key').text(value);
		});

		/* Filter text change for File Type */
		$body.on(custom_event, ".filter-div-file-type .filter-key-values li a", function() {
			var $this = $(this),
				value = $this.data('text');

				$('.filter-div-file-type .filter-dropdown').attr('data-value', value);
				$('.filter-div-file-type span.filter-key').text(value);
		});

		// Isotope sorting/filter
		$('.documentImageFilter a').on(custom_event,function() {
			var $this 		= $(this),
				$optionSet 	= $this.parents('.documentImageFilter'),
				group 		= $optionSet.attr('data-filter-group'),
				// convert object into array
				isoFilters 	= [];

				$('.documentImageFilter .current').removeClass('current');
				$this.addClass('current');
				
				// change selected class
				$optionSet.find('.selected').removeClass('selected');
				$this.addClass('selected');
				
				filters[ group ] = $this.attr('data-filter-value');
				
				for ( var prop in filters ) {
					isoFilters.push( filters[ prop ] );
				}

				var selector = isoFilters.join('');

					$grid = $container.isotope({
						filter: selector,
						animationOptions: {
							duration: 750,
							easing: 'linear',
							queue: false
						}
					});

					// ISOTOPE Event after filter
					$grid.on( 'arrangeComplete', function( event, filteredItems ) {
						// Deselect Images, to remove from the group of available/selected image uaing filter
						$('.viewImageDocument').attr('data-lightbox','deselected-images');

						$.each(filteredItems, function() {
							// updating data-lightbox attribute to make images available after filtering
							// making images as group and sliding images limited only to the group
							$selectedImgElem = $(this.element).find('.viewImageDocument');
							$selectedImgElem.attr('data-lightbox','selected-images');
						});
					});

					// Lightbox Plugin Initialization after ISOTOPE Filter action
					lightbox.option({
						'resizeDuration': 200,
						'wrapAround': true
					});
		});

		// Isotope sorting/filter Responsive
		$('.documentImageFilter a').on(custom_event,function() {
			var $this 		= $(this),
				$optionSet 	= $this.parents('.documentImageFilter'),
				group 		= $optionSet.attr('data-filter-group'),
				// convert object into array
				isoFilters 	= [];

			$('.documentImageFilter .current').removeClass('current');
			$this.addClass('current');

			// change selected class
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');

			filters[ group ] = $this.attr('data-filter-value');
			for ( var prop in filters ) {
				isoFilters.push( filters[ prop ] );
			}

			var selector = isoFilters.join('');

				$grid = $container.isotope({
					filter: selector,
					animationOptions: {
						duration: 750,
						easing: 'linear',
						queue: false
					}
				});

				// ISOTOPE Event after filter
				$grid.on( 'arrangeComplete', function( event, filteredItems ) {
					// Deselect Images, to remove from the group of available/selected image uaing filter
					$('.viewImageDocument').attr('data-lightbox','deselected-images');

					$.each(filteredItems, function(){
						// updating data-lightbox attribute to make images available after filtering
						// making images as group and sliding images limited only to the group
						$selectedImgElem = $(this.element).find('.viewImageDocument');
						$selectedImgElem.attr('data-lightbox','selected-images');
					});
				});

				// Lightbox Plugin Initialization after ISOTOPE Filter action
				lightbox.option({
					'resizeDuration': 200,
					'wrapAround': true
				});
		});

		// Lightbox Plugin Initialization
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		});
}

window.updateFormId = function() {
	var form = $("form.frmDocAddFile");

		if ( melisCommerce.getUniqueId() ) {
			form.attr("id", melisCommerce.getUniqueId()+"_frmDocUpload");
		}
}

window.imagePreview = function(id, fileInput) {
	if ( fileInput.files && fileInput.files[0] ) {
		var reader = new FileReader();
		
			reader.onload = function(e) {
				$(id).attr('src', e.target.result);
			}
			reader.readAsDataURL(fileInput.files[0]);
	}
}
