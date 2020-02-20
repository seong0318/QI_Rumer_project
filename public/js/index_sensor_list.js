function showSensorList(inputType) {
	$.ajax({
		type: 'GET',
		url: './sensorlisthandle/0',
		data: { type: inputType },
		datatype: 'JSON',
	})
		.done(function(json) {
			let jsonData = JSON.parse(json);
			let execResult = jsonData['result'];

			switch (execResult) {
				case 0:
					$('#sensor_table > tbody').empty();
					$.each(jsonData['data'], function(index, item) {
						let typeStr;
						if (item.type == 0) typeStr = 'Udoo Sensor';
						else if (item.type == 1) typeStr = 'Polar Sensor';
						else typeStr = 'Unkown';
						let eachRow =
							'<tr><th>' +
							index +
							'</th><td>' +
							item.sensor_name +
							'</td><td>' +
							typeStr +
							'</td><td>' +
							item.mac_address +
							'</button></td></tr>';
						$('#sensor_table').append(eachRow);
					});
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
}

function deregistSensor(sensorId, index) {
	$.ajax({
		type: 'POST',
		url: './sensorderegist/0',
		data: { sensor_id: sensorId },
		datatype: 'JSON',
	})
		.done(function(json) {
			let jsonData = JSON.parse(json);
			let execResult = jsonData['result'];
			let table = document.getElementById('sensor_table');

			switch (execResult) {
				case 0:
					alert('Success deregist sensor');
					table.deleteRow(index + 1);
					break;
				case -1:
					alert('ERROR: Query error');
					break;
				case -2:
					alert('ERROR: Fail deregist sensor');
					break;
				case -3:
					alert('ERROR: Invalid delete execution');
					break;
				default:
					alert('ERROR: Invalid access' + execResult);
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
}
