$(function() {
	var $body = $("body");

	$body.on("click", ".return-product-show-form", function(){
		var remProd = $(this).data("rm-pr");
		var variantId = $(this).data("variantid");
		var sku = $(this).data("sku");
		var form = $("#returnProductForm");
		var selectName = "m_rp_data["+variantId+"]";
		var qtySelect = $("<select/>").attr("name", selectName);

		//hide all alert
		$(".alert").addClass("hidden");
		$(".alert").addClass("hidden");

		//check if variant select quantity is already added
		if($("select[name='"+selectName+"']").length <= 0) {
			//make option depending on remaining product to return
			for (var i = 1; i <= remProd; i++) {
				var opt = "<option value=" + i + ">" + i + "</option>";
				qtySelect.append(opt);
			}
			//constuct form group for quantity
			var label = '<label class="col-form-label col-md-4">Quantity (' + sku + ')</label>';
			var formGroup = $("<div/>").attr("class", "form-group row ").css("margin-bottom", "7px");
			var qtyCont = $("<div/>").attr("class", "col-md-6")
			var removeDiv = $("<div/>").attr("class", "col-md-2");
			removeDiv.append("<span class='removeQty float-left'>Remove</span>");

			qtyCont.append(qtySelect);
			formGroup.append(label);
			formGroup.append(qtyCont);
			formGroup.append(removeDiv);
			form.prepend(formGroup);
		}
		//show return message form
		$(".rp-msg-form-cont").removeClass("hidden");
	});

	$body.on("click", ".removeQty", function(){
		$(this).closest("div.form-group").remove();

		var form = $("#returnProductForm");
		var allSelect = form.find("select[name*='m_rp_data']");
		//if all select quantity is removed, hide the form
		if(allSelect.length <= 0){
			//hidden the form
			$(".rp-msg-form-cont").addClass("hidden");
		}
	});
});