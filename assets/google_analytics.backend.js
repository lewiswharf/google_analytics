google.load("visualization", "1", {packages:["corechart", "table"]});

function defineCharts() {
	jQuery(document).ready(function() {
	
		var panelWidth = jQuery('#field-analytics').width();
	
		jQuery('#field-analytics').drawChart('AreaChart', {
			width: panelWidth, 
			chartArea:{width: panelWidth-120},
			height: 240, 
			lineWidth: 3,
			pointSize: 5,
			legend: 'top',
			colors: ['#2A4269', '#38588E'],
			hAxis: {slantedText: true, slantedTextAngle: 30, textStyle: {color: '#666666', fontSize: 12}, showTextEvery: 2},
			vAxis: {textStyle: {color: '#666666', fontSize: 12}}
		});
	});
};

(function (jQuery){
	jQuery.fn.drawChart = function(type, settings){ 

  if(jQuery(this).length == 0) return false;
	
	var id = jQuery(this).attr('id');

	var data = new google.visualization.DataTable();

	var headers = jQuery(this).find('thead').find('th');
	var rows = jQuery(this).find('tbody').find('tr');

	headers.each(function(index){
		if(index){
				data.addColumn('number',jQuery(this).text());
		} else {
				data.addColumn('string',String(jQuery(this).text()));
		}
	});

	data.addRows(rows.length);

	rows.each(function(index){
		jQuery(this).find('td').each(function(index2){
			if(index2){
				data.setCell(index, index2, parseFloat(jQuery(this).text()));
			} else {
				if(type == 'AreaChart') {
					data.setCell(index, index2, String(jQuery(this).text()).slice(4, 6) + '/' + String(jQuery(this).text()).slice(6, 8));
				} else {
					data.setCell(index, index2, String(jQuery(this).text()));
				}
			}
		});
	});

	eval("var chart = new google.visualization."+type+"(document.getElementById('"+id+"'))");
	
	chart.draw(data, settings);
  }
})(jQuery);

google.setOnLoadCallback(defineCharts);

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
