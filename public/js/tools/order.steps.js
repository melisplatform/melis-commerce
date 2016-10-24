// melis Order Steps JS
/* #### FIX DataTable issue in Tab #### */

$(document).ready(function() {

	/* Address clear */
	$('body').on("click",".create-new-billing-address",function(){
		$(".form-new-billing-address input").val("");
		$(".form-new-billing-address .form-group:last-child").html('<a href="#" class="btn btn-success"><i class="fa fa-save"></i> Save the Billing Address</a>');
	});
	$("body").on("click",".create-new-delivery-address",function(){
		$(".form-new-delivery-address input").val("");
		$(".form-new-delivery-address .form-group:last-child").html('<a href="#" class="btn btn-success"><i class="fa fa-save"></i> Save the Delivery Address</a>');
	});
	
	/* Change address */
	$("body").on("change",".billing-address-option",function(){
		$(".form-new-billing-address input").val("sample text");
		$(".form-new-billing-address .form-group:last-child a").remove();
	});
	$("body").on("change",".delivery-address-option",function(){
		$(".form-new-delivery-address input").val("sample text");
		$(".form-new-delivery-address .form-group:last-child a").remove();
	});
	
	/* Child rows for Product list table */
	function format(value) {
		return '<div>Hidden Value: ' + value + '</div>';
	}
		
	/* Tab Move */
	// Next
	var orderNewOrder = '#id_meliscommerce_order_checkout';
	$('body').on('click',orderNewOrder + ' a.btn.next-step',function(){
		var tabSelected = $(orderNewOrder + ' div.tab-pane.active').next().attr('id');
		$(orderNewOrder + ' .tabsbar li').removeClass('active');
		$(orderNewOrder + ' .tabsbar li a[data-tab="'+tabSelected+'"]').parent().addClass('active');
	});
	// Previous
	$('body').on('click',orderNewOrder + ' a.btn.prev-step',function(){
		var tabSelected = $(orderNewOrder + ' div.tab-pane.active').prev().attr('id');
		$(orderNewOrder + ' .tabsbar li').removeClass('active');
		$(orderNewOrder + ' .tabsbar li a[data-tab="'+tabSelected+'"]').parent().addClass('active');
	});
	
	/* Add to Basket */
	$('body').on('click','.order-add-basket', function(){
		var id = $(this).data('id');
		var sku = $(this).data('sku');
		var name = $(this).data('name');
		var price = $(this).data('price');
		/* console.log(id+'-'+sku+'-'+name+'-'+price); */
		$('.basket-panel-list').prepend('<div class="order-basket-list">'+
			'<div class="form-group">'+
				'<h4>'+name+'</h4>'+
				'<span>'+sku+'</span>'+
			'</div>'+
			'<div class="form-group">'+
				'<a href="#"><i class="fa fa-minus qty-minus"></i></a> '+
				'<input type="text" class="form-control qty-val" style="width:40px" name="" id="" placeholder="" value="1">'+
				' <a href="#"><i class="fa fa-plus qty-plus"></i></a>'+
				'<strong class="order-price float-right">'+price+'</strong>'+
			'</div>'+
		'</div>');
	});
	
	
});