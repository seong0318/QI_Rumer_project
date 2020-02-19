function getAirDataList() {
	let airDataList;

	$.ajax({
		type: 'POST',
		url: './chartshandle/0',
		datatype: 'JSON',
		async: false,
	})
		.done(function(json) {
			let jsonData = JSON.parse(json);
			let execResult = jsonData['result'];

			switch (execResult) {
				case 0:
					airDataList = jsonData['data'];
					break;
				case -1:
					alert('ERROR: sql query error');
					break;
				case -5:
					alert('Please sign in first');
					break;
				default:
					alert('ERROR: Invalid access');
					break;
			}
		})
		.fail(function(request, status, error) {
			alert(
				'code:' +
					request.status +
					'\n' +
					'message:' +
					request.responseText +
					'\n' +
					'error:' +
					error
			);
		});

	return airDataList;
}

// function initMap() {
// 	let map = new google.maps.Map(document.getElementById('map'), {
// 		zoom: 5,
// 		center: { lat: 32.877108, lng: -117.235582 },
// 	});
// 	let airDataList, markerCluster;
// 	let locations = [];
// 	let markers = [];

// 	airDataList = getAirDataList();

// 	for (var airData of airDataList) {
// 		let pos = new google.maps.LatLng(
// 			airData['latitude'],
// 			airData['longitude']
// 		);
// 		let marker = new google.maps.Marker({
// 			position: pos,
// 			map: map,
// 			title: airData['sensor_name'],
// 			content: JSON.stringify(airData, null, 2),
// 		});
// 		let infoWindow = new google.maps.InfoWindow({
// 			content: JSON.stringify(airData, null, 2),
// 		});

// 		marker.addListener('click', function() {
// 			infoWindow.open(map, this);
// 		});

// 		markers.push(marker);

// 		var circ = new google.maps.Circle({
// 			center: pos,
// 			clickable: false,
// 			fillColor: '#fcc056',
// 			fillOpacity: 0.3,
// 			map: map,
// 			radius: 500,
// 			strokeColor: '#fcc056',
// 			strokeOpacity: 0.3,
// 		});
// 	}

// 	markerCluster = new MarkerClusterer(map, markers, {
// 		imagePath:
// 			'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
// 	});

// 	google.maps.event.addListener(map, 'zoom_changed', function() {
// 		var zoom = map.getZoom();
// 		// iterate over markers and call setVisible
// 		for (i = 0; i < locations.length; i++) {
// 			markers[i].setVisible(zoom <= 15);
// 		}
// 	});
// }

function setCircle(aqi_type) {
	switch (aqi_type) {
		case 0:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#7fc7d4',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#7fc7d4',
				strokeOpacity: 0.3,
			}); // end circle
			gcircle.push(circ);
			break;
		case 1:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#7fa0d4',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#7fa0d4',
				strokeOpacity: 0.3,
			}); // end circle
			gcircle.push(circ);
			break;
		case 2:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#7fa0d4',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#7fa0d4',
				strokeOpacity: 0.3,
			}); // end circle
			//gcircle.push(circ);
			break;
		case 3:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#d4d17f',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#d4d17f',
				strokeOpacity: 0.3,
			}); // end circle
		//gcircle.push(circ);
		case 4:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#d49d7f',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#d49d7f',
				strokeOpacity: 0.3,
			}); // end circle
			break;
		case 5:
			var circ = new google.maps.Circle({
				center: map_center,
				clickable: false,
				fillColor: '#d47f96',
				fillOpacity: 0.3,
				map: map,
				radius: 1000,
				strokeColor: '#d47f96',
				strokeOpacity: 0.3,
			}); // end circle
			break;
		default:
			alert('Invalid Input');
			break;
	}
}
function drawChart() {
	var button = document.getElementById('change-chart');
	var chartDiv = document.getElementById('chart_div');

	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Month');
	data.addColumn('number', 'Average Temperature');
	data.addColumn('number', 'Average Hours of Daylight');

	data.addRows([
		[new Date(2018, 0), -0.5, 5.7],
		[new Date(2018, 1), 0.4, 8.7],
		[new Date(2018, 2), 0.5, 12],
		[new Date(2019, 3), 2.9, 15.3],
		[new Date(2019, 4), 6.3, 18.6],
		[new Date(2019, 5), 9, 20.9],
		[new Date(2019, 6), 10.6, 19.8],
		[new Date(2019, 7), 10.3, 16.6],
		[new Date(2019, 8), 7.4, 13.3],
		[new Date(2019, 9), 4.4, 9.9],
		[new Date(2019, 10), 1.1, 6.6],
		[new Date(2019, 11), -0.2, 4.5],
	]);

	var materialOptions = {
		chart: {
			title: 'AQI Values in your sensors',
		},
		width: 900,
		height: 500,
		series: {
			// Gives each series an axis name that matches the Y-axis below.
			0: { axis: 'Temps' },

			1: { axis: 'Daylight' },
		},
		axes: {
			// Adds labels to each axis; they don't have to match the axis names.
			y: {
				Temps: { label: 'Temps (Celsius)' },
				Daylight: { label: 'Daylight' },
			},
		},
	};

	var classicOptions = {
		title:
			'Average Temperatures and Daylight in Iceland Throughout the Year',
		width: 900,
		height: 500,
		// Gives each series an axis that matches the vAxes number below.
		series: {
			0: { targetAxisIndex: 0 },
			0: { targetAxisIndex: 0 },
			1: { targetAxisIndex: 1 },
		},
		vAxes: {
			// Adds titles to each axis.
			0: { title: 'Temps (Celsius)' },
			1: { title: 'Daylight' },
		},
		hAxis: {
			ticks: [
				new Date(2014, 0),
				new Date(2014, 1),
				new Date(2014, 2),
				new Date(2014, 3),
				new Date(2014, 4),
				new Date(2014, 5),
				new Date(2014, 6),
				new Date(2014, 7),
				new Date(2014, 8),
				new Date(2014, 9),
				new Date(2014, 10),
				new Date(2014, 11),
			],
		},
		vAxis: {
			viewWindow: {
				max: 30,
			},
		},
	};

	function drawMaterialChart() {
		var materialChart = new google.visualization.LineChart(chartDiv);
		materialChart.draw(data, materialOptions);
		button.innerText = 'Change to Classic';
		button.onclick = drawClassicChart;
	}

	function drawClassicChart() {
		var classicChart = new google.visualization.LineChart(chartDiv);
		classicChart.draw(data, classicOptions);
		button.innerText = 'Change to Material';
		button.onclick = drawMaterialChart;
	}

	drawMaterialChart();
}

let airDataList = getAirDataList();

document.getElementById('sensor1').innerHTML = airDataList[0]['sensor_name'];
