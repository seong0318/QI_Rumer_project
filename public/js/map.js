function getRecentlyAirDataList() {
  let airDataList;

  $.ajax({
    type: "POST",
    url: "./maphandle/0",
    datatype: "JSON",
    async: false
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];


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



  airDataList = getRecentlyAirDataList();

  for (var airData of airDataList) {
    let pos = new google.maps.LatLng(airData["latitude"], airData["longitude"]);
    let marker = new google.maps.Marker({
      position: pos,
      map: map,
      title: airData["sensor_name"],
      content: JSON.stringify(airData, null, 2)
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
              <th scope='col'>PM2.5</th>\
              <th scope='col'>PM10</th>\
            </tr>\
          </thead>\
          <tbody>\
            <tr>\
              <td>" +
      airData["result_sensor_name"] +
      "</td>\
              <td>" +
      airData["measured_time"] +
      "</td>\
              <td>" +
      airData["latitude"] +
      "</td>\
              <td>" +
      airData["longitude"] +
      "</td>\
              <td>" +
      airData["co"] +
      "</td>\
              <td>" +
      airData["so2"] +
      "</td>\
              <td>" +
      airData["o3"] +
      "</td>\
              <td>" +
      airData["no2"] +
      "</td>\
              <td>" +
      airData["pm2.5"] +
      "</td>\
              <td>" +
      airData["pm10"] +
      "</td>\
            </tr>\
          </tbody>\
        </table>\
        <button onclick=\"window.open('/charts')\">Show Charts</button>\
        ";
    let infoWindow = new google.maps.InfoWindow({
      content: constentStr
    });

    marker.addListener("click", function() {
      infoWindow.open(map, this);
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
