function widgetCollapsibleInit() {
	(function($)
	{
		$('.widget[data-toggle="collapse-widget"] .widget-body')
			.on('show.bs.collapse', function(){
				$(this).parents('.widget:first').attr('data-collapse-closed', "false");
			})
			.on('shown.bs.collapse', function(){
				setTimeout(function(){ $(window).resize(); }, 500);
			})
			.on('hidden.bs.collapse', function(){
				$(this).parents('.widget:first').attr('data-collapse-closed', "true");
			});
		
		$('.widget[data-toggle="collapse-widget"]').each(function()
		{
			// append toggle button
			if (!$(this).find('.widget-head > .collapse-toggle').length)
				$('<span class="collapse-toggle"></span>').prependTo($(this).find('.widget-head'));
			
			// make the widget body collapsible
			$(this).find('.widget-body').not('.collapse').addClass('collapse');
			
			// verify if the widget should be opened
			if ($(this).attr('data-collapse-closed') !== "true")
				$(this).find('.widget-body').addClass('in');
			
			// bind the toggle button
			$(this).find('.accordionTitle').on('click', function(){
				//close all accordion before toggling the selected one
				var accordionCont = $(".prices-accordion.active");
				$.each(accordionCont, function(){
					var widget = $(this).find(".widget[data-collapse-closed=false]");
					widget.find('.widget-body').collapse('toggle');
				});
				//toogle the selected accordion
				$(this).parents('.widget:first').find('.widget-body').collapse('toggle');
			});
		});
	})(jQuery);
}