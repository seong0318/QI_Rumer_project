var markers = [];
var circles = [];

function getRecentlyAirDataList() {
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

	airDataList = getRecentlyAirDataList();

	for (var airData of airDataList) {
		let pos = new google.maps.LatLng(
			airData['latitude'],
			airData['longitude']
		);
		let marker = new google.maps.Marker({
			position: pos,
			map: map,
			title: airData['sensor_name'],
			content: airData,
		});
		let constentStr =
			"<table class='table'>\
          <thead>\
            <tr>\
              <th scope='col'>Sensor Name</th>\
              <th scope='col'>Measure Time</th>\
              <th scope='col'>Lat</th>\
              <th scope='col'>Lng</th>\
              <th scope='col'>CO</th>\
              <th scope='col'>SO2</th>\
              <th scope='col'>O3</th>\
              <th scope='col'>NO2</th>\
              <th scope='col'>PM25</th>\
              <th scope='col'>PM10</th>\
              //<th scope='col'>TEMPERATEURE</th>\
            </tr>\
          </thead>\
          <tbody>\
            <tr>\
              <td>" +
			airData['result_sensor_name'] +
			'</td>\
              <td>' +
			airData['measured_time'] +
			'</td>\
              <td>' +
			airData['latitude'] +
			'</td>\
              <td>' +
			airData['longitude'] +
			'</td>\
              <td>' +
			airData['co'] +
			'</td>\
              <td>' +
			airData['so2'] +
			'</td>\
              <td>' +
			airData['o3'] +
			'</td>\
              <td>' +
			airData['no2'] +
			'</td>\
              <td>' +
			airData['pm25'] +
			'</td>\
              <td>' +
			airData['pm10'] +
			'</td>\
      /*<td>' +
			airData['temperature'] +
			'</td>*/\
            </tr>\
          </tbody>\
        </table>\
        <button onclick="window.open(\'/charts\')">Show Charts</button>\
        ';
		let infoWindow = new google.maps.InfoWindow({
			content: constentStr,
		});

		marker.addListener('click', function() {
			infoWindow.open(map, this);
		});
		markers.push(marker);

		var circ = new google.maps.Circle({
			center: pos,
			clickable: false,
			fillColor: '#fcc056',
			fillOpacity: 1,
			map: map,
			radius: 500,
			strokeColor: '#fcc056',
			strokeOpacity: 0.3,
		});
		// circles.push(circ);
		circles[pos] = circ;
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

function setColorInCircle(aqi, circ) {
	if (0 <= aqi && aqi < 50) {
		circ.setOptions({
			fillColor: '#37FF00',
		});
	} else if (50 <= aqi && aqi < 100) {
		circ.setOptions({
			fillColor: '#ECFF00',
		});
	} else if (100 <= aqi && aqi < 150) {
		circ.setOptions({
			fillColor: '#FF7F00',
		});
	} else if (150 <= aqi && aqi < 200) {
		circ.setOptions({
			fillColor: '#FF0000',
		});
	} else if (200 <= aqi && aqi < 300) {
		circ.setOptions({
			fillColor: '#DD00FF',
		});
	} else if (301 <= aqi) {
		circ.setOptions({
			fillColor: '#730000',
		});
	} else {
		circ.setOptions({
			fillColor: '#877C7C',
		});
	}
}

function iterMarker(type) {
	let aqi, circ;
	for (var marker of markers) {
		aqi = marker.content[type];
		circ = circles[marker.position];
		setColorInCircle(marker.content[type], circ);
	}
}

function setCircle(aqi_type) {
	switch (aqi_type) {
		case 0:
			iterMarker('o3');
			break;
		case 1:
			iterMarker('pm25');
			break;
		case 2:
			iterMarker('pm10');
			break;
		/*
        온도 로 교체 될 시 아에 지워야하는것인가?
      */

		case 3:
			iterMarker('co');
			break;
		case 4:
			iterMarker('so2');
			break;
		case 5:
			iterMarker('no2');
			break;
		default:
			alert('ERROR: Invalid input type');
			break;
	}
}
