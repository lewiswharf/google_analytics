		google.load("visualization", "1", {packages:["corechart", "table"]});

		function drawChart() {
			jQuery(document).ready(function() {
				
				var width = jQuery(window).width();
				//Big chart
				var data = new google.visualization.DataTable();
						
				var headers = jQuery('#thirty-day-chart').find('thead').find('th');
				var rows = jQuery('#thirty-day-chart').find('tbody').find('tr');
			
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
								data.setCell(index, index2, String(jQuery(this).text()).slice(4, 6) + '/' + String(jQuery(this).text()).slice(6, 8));
							}
						});
				});
		
				var chart = new google.visualization.AreaChart(document.getElementById('thirty-day-chart'));
				chart.draw(data, {
														width: width, 
														chartArea:{width: width-120},
														height: 240, 
														lineWidth: 3,
														pointSize: 5,
														legend: 'top',
														colors: ['#2A4269', '#38588E'],
														hAxis: {slantedText: true, slantedTextAngle: 30, textStyle: {color: '#666666', fontSize: 12}, showTextEvery: 2},
														vAxis: {textStyle: {color: '#666666', fontSize: 12}}
												 });
			
				//Pages table
				var data2 = new google.visualization.DataTable();
						
				var headers2 = jQuery('#top-pages').find('thead').find('th');
				var rows2 = jQuery('#top-pages').find('tbody').find('tr');
			
				headers2.each(function(index){
					if(index){
							data2.addColumn('number',jQuery(this).text());
					} else {
							data2.addColumn('string',String(jQuery(this).text()));
					}
				});
			
				data2.addRows(rows2.length);
			
				rows2.each(function(index){
						jQuery(this).find('td').each(function(index2){
							if(index2){
								data2.setCell(index, index2, parseFloat(jQuery(this).text()));
							} else {
								data2.setCell(index, index2, String(jQuery(this).text()));
							}
						});
				});

				var mytable = new google.visualization.Table(document.getElementById('top-pages'));
				mytable.draw(data2, {
														width: Math.round(width/2)-20,
														showRowNumber: true,
														page: 'enable', 
												 });
			
				//Keywords table
				var data3 = new google.visualization.DataTable();
						
				var headers3 = jQuery('#top-keywords').find('thead').find('th');
				var rows3 = jQuery('#top-keywords').find('tbody').find('tr');
			
				headers3.each(function(index){
					if(index){
							data3.addColumn('number',jQuery(this).text());
					} else {
							data3.addColumn('string',String(jQuery(this).text()));
					}
				});
			
				data3.addRows(rows3.length);
			
				rows3.each(function(index){
						jQuery(this).find('td').each(function(index2){
							if(index2){
								data3.setCell(index, index2, parseFloat(jQuery(this).text()));
							} else {
								data3.setCell(index, index2, String(jQuery(this).text()));
							}
						});
				});

				var mytable2 = new google.visualization.Table(document.getElementById('top-keywords'));
				mytable2.draw(data3, {
														width: Math.round(width/2)-20,
														allowHtml: true,
														showRowNumber: true,
														page: 'enable', 
												 });
			});
		}
			
		google.setOnLoadCallback(drawChart);

