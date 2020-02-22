var isAddOption = false;

function addOptionSensor(heartDataList) {
	let sensorNameList = [];

	//  sensorNameList key: sensor_name, val: sensor_id
	for (heartData of heartDataList)
		sensorNameList[heartData.sensor_name] = heartData.sensor_id;

	for (sensorName in sensorNameList) {
		let option = new Option(sensorName, sensorNameList[sensorName]);
		$(option).html(sensorName);
		$('#options').append(option);
	}
}

function getHeartDataList(sensorId) {
	let heartDataList;

	$.ajax({
		type: 'POST',
		url: './hearthandle/0',
		data: { sensor_id: sensorId },
		datatype: 'JSON',
		async: false,
	})
		.done(function(json) {
			let jsonData = JSON.parse(json);
			let execResult = jsonData.result;

			switch (execResult) {
				case 0:
					heartDataList = jsonData.data;
					break;
				case -1:
					alert('ERROR: sql query error');
					break;
				case -2:
					alert('ERROR: Invalid sensor id');
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

	return heartDataList;
}

function getHeartData() {
	let sensorId = document.getElementById('options');

	let heartDataList = getHeartDataList(parseInt(sensorId.value));
	let data = {
		cols: [
			{ id: 'time', label: 'Time', type: 'date' },
			{ id: 'heart_rate', label: 'HEART RATE', type: 'number' },
			{ id: 'rr_interval', label: 'RR', type: 'number' },
		],
		rows: [],
	};

	if (!isAddOption) {
		addOptionSensor(heartDataList);
		isAddOption = true;
	}

	for (heartData of heartDataList) {
		let date = new Date(heartData.measured_time);
		let elem = {
			c: [
				{ v: date },
				{ v: parseFloat(heartData.heart_rate) },
				{ v: parseFloat(heartData.rr_interval) },
			],
		};
		data.rows.push(elem);
	}
	return data;
}

function drawChart(columns) {
	let chartDiv = document.getElementById('chart_div');
	let chartsData = getHeartsData();
	let data = new google.visualization.DataTable(chartsData);
	let view = new google.visualization.DataView(data);
	let checkedElem = document.getElementsByName('checked_elem');

	view.setColumns(columns);

	let lineChart = new google.visualization.LineChart(chartDiv);

	var materialOptions = {
		chart: {
			title: 'HEART Values in your sensors',
		},
		width: 900,
		height: 500,
	};

	lineChart.draw(view, materialOptions);
}

setInterval(() => {
	let columns = [0];
	$('#checkboxes input:checked').map(function() {
		columns.push(parseInt(this.value));
	});
	drawChart(columns);
}, 1000);

var updateCharts = $('#checkboxes input').click(function() {
	let columns = [0];
	$('#checkboxes input:checked').map(function() {
		columns.push(parseInt(this.value));
	});
	drawChart(columns);
});
