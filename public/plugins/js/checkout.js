$(function() {
	var $body = $("body");

		if ( $("#checkout-addresses-default #m_add_delivery_id").val() == "new_address") {
			action("delivery", "block")
		}
		else {
			action("delivery", "none")
		}
		
		if ( $("#checkout-addresses-default #m_add_billing_id").val() == "new_address" ) {
			action("billing", "block")
		}
		else {
			action("billing", "none")
		}
		
		$("#checkout-addresses-default #m_add_delivery_id").on("change", function() {
			var $this = $(this);

				if( $this.val() === "new_address" ) {
					action("delivery", "block")
				}
				else {
					action("delivery", "none")
				}
		});
		
		$("#checkout-addresses-default #m_add_billing_id").on("change", function() {
			var $this = $(this);
			
				if ( $this.val() === "new_address" ) {
					action("billing", "block")
				}
				else {
					action("billing", "none")
				}
		});
		
		function action(type, display) {
			$("#checkout-"+type+"-address-default fieldset input:not([type='hidden']), #checkout-"+type+"-address-default fieldset select:not([name='m_add_"+type+"_id'])").val("")
			$("#checkout-"+type+"-address-default fieldset input:not([type='hidden']), #checkout-"+type+"-address-default fieldset select:not([name='m_add_"+type+"_id'])").css("display", display)
			$("#checkout-"+type+"-address-default fieldset input:not([type='hidden']), #checkout-"+type+"-address-default fieldset select:not([name='m_add_"+type+"_id'])").prev("label").css("display", display)
			$("#checkout-"+type+"-address-default fieldset input:not([type='hidden']), #checkout-"+type+"-address-default fieldset select:not([name='m_add_"+type+"_id'])").next("ul").css("display", display)
		}
		
		$body.on('click', "#checkout-billing-address-same-default", function() {
			var $this = $(this);

				if ( $this.is(":checked") ) {
					$(".checkout-billing-address-default-zone").css("display", "none");
					$("#m_add_use_same_address").val(1);
				}
				else {
					$(".checkout-billing-address-default-zone").css("display", "block");
					$("#m_add_use_same_address").val(0);
				}
		});

		$body.on('click', "#checkout-billing-address-same", function() {
			var $this = $(this);

				if ( $this.is(":checked") ) {
					$(".checkout-billing-address-zone").addClass("hidden");
					$("#m_add_use_same_address").val(1);
				}
				else {
					$(".checkout-billing-address-zone").removeClass("hidden");
					$("#m_add_use_same_address").val(0);
				}
		});
});