google.load("visualization", "1", {packages:["corechart", "table"]});

function defineCharts() {
	jQuery(document).ready(function() {
	
		var windowWidth = jQuery(window).width();
	
		jQuery('#thirty-day-chart').drawChart('AreaChart', {
			width: windowWidth, 
			chartArea:{width: windowWidth-120},
			height: 240, 
			lineWidth: 3,
			pointSize: 5,
			legend: 'top',
			colors: ['#2A4269', '#38588E'],
			hAxis: {slantedText: true, slantedTextAngle: 30, textStyle: {color: '#666666', fontSize: 12}, showTextEvery: 2},
			vAxis: {textStyle: {color: '#666666', fontSize: 12}}
		});
		
		jQuery('#top-pages').drawChart('Table', {
			width: Math.round(windowWidth/2)-20,
			allowHtml: true,
			showRowNumber: true,
			page: 'enable', 
		});
	
		jQuery('#top-keywords').drawChart('Table', {
			width: Math.round(windowWidth/2)-20,
			allowHtml: true,
			showRowNumber: true,
			page: 'enable', 
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

