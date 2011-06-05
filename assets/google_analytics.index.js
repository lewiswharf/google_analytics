		google.load("visualization", "1", {packages:["corechart"]});

		function drawChart() {
			jQuery(document).ready(function() {
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
								data.setCell(index, index2, String(jQuery(this).text()).slice(4, 6) + '/' + String(jQuery(this).text()).slice(6, 8));
							}
						});
				});
		
				var chart = new google.visualization.AreaChart(document.getElementById('thirty-day-chart'));
				chart.draw(data, {
														width: jQuery(window).width(), 
														height: 240, 
														lineWidth: 3,
														pointSize: 5,
														colors: ['#2A4269', '#38588E'],
														hAxis: {slantedText: true, slantedTextAngle: 30, textStyle: {color: '#666666', fontSize: 12}, showTextEvery: 2},
														vAxis: {textStyle: {color: '#666666', fontSize: 12}}
												 });
			});
		}
			
		google.setOnLoadCallback(drawChart);
