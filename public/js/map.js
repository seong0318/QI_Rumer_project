function getAirDataList() {
	let airDataList;

	$.ajax({
		type: 'POST',
		url: './maphandle/0',
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

function initMap() {
	let map = new google.maps.Map(document.getElementById('map'), {
		zoom: 5,
		center: { lat: 32.877108, lng: -117.235582 },
	});
	let airDataList, markerCluster;
	let locations = [];
	let markers = [];

	airDataList = getAirDataList();
	for (var airData of airDataList) {
		let pos = new google.maps.LatLng(
			airData['latitude'],
			airData['longitude']
		);
		let marker = new google.maps.Marker({
			position: pos,
			map: map,
			title: airData['sensor_name'],
		});

		marker.addListener('click', function() {
			alert(JSON.stringify(airData, null, 2));
		});

		markers.push(marker);

		var circ = new google.maps.Circle({
			center: pos,
			clickable: false,
			fillColor: '#fcc056',
			fillOpacity: 0.3,
			map: map,
			radius: 500,
			strokeColor: '#fcc056',
			strokeOpacity: 0.3,
		});
	}

	markerCluster = new MarkerClusterer(map, markers, {
		imagePath:
			'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
	});

	google.maps.event.addListener(map, 'zoom_changed', function() {
		var zoom = map.getZoom();
		// iterate over markers and call setVisible
		for (i = 0; i < locations.length; i++) {
			markers[i].setVisible(zoom <= 15);
		}
	});
}

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
