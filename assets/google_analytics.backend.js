(function($) {
	jQuery(document).ready(function() {
		
		var google_analytics_menu_item = jQuery('#nav a[href$="/extension/google_analytics/"]');
		
		google_analytics_menu_item.parents('li:last')
			.addClass('google-analytics')
			.bind('click', function() {
				window.location.href = google_analytics_menu_item.attr('href');
			});
				
	});
})(jQuery.noConflict());
