var isAddOption = false;

$(function() {
  $("#startdatepicker")
    .datepicker({ dateFormat: "yy-mm-dd" })
    .val();
});

$(function() {
  $("#lastdatepicker")
    .datepicker({ dateFormat: "yy-mm-dd" })
    .val();
});

$("#dateInput").click(function() {
  event.preventDefault();
  var startDate = document.getElementById("startdatepicker");
  var endDate = document.getElementById("lastdatepicker");
  alert(startDate);
});

function addOptionSensor(airDataList) {
  let sensorNameList = [];

  //  sensorNameList key: sensor_name, val: sensor_id
  for (airData of airDataList)
    sensorNameList[airData.sensor_name] = airData.sensor_id;

  for (sensorName in sensorNameList) {
    let option = new Option(sensorName, sensorNameList[sensorName]);
    $(option).html(sensorName);
    $("#options").append(option);
  }
}

function getAirDataList(sensorId, startTime, endTime) {
  let airDataList;

  $.ajax({
    type: "POST",
    url: "./chartshandle/0",
    data: {
      sensor_id: sensorId,
      start_time: startTime,
      end_time: endTime
    },
    datatype: "JSON",
    async: false
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData.result;

      switch (execResult) {
        case 0:
          airDataList = jsonData.data;
          break;
        case -1:
          alert("ERROR: sql query error");
          break;
        case -2:
          alert("ERROR: Invalid sensor id");
          break;
        case -5:
          alert("Please sign in first");
          break;
        default:
          alert("ERROR: Invalid access");
          break;
      }
    })
    .fail(function(request, status, error) {
      alert(
        "code:" +
          request.status +
          "\n" +
          "message:" +
          request.responseText +
          "\n" +
          "error:" +
          error
      );
    });

  return airDataList;
}

function getChartsData() {
  let sensorId = document.getElementById("options");
  let startTime = $("#startdatepicker").val();
  let endTime = $("#lastdatepicker")
    .datepicker()
    .val();

  let airDataList = getAirDataList(
    parseInt(sensorId.value),
    startTime,
    endTime
  );
  let data = {
    cols: [
      { id: "time", label: "Time", type: "date" },
      { id: "co", label: "CO", type: "number" },
      { id: "so2", label: "SO2", type: "number" },
      { id: "o3", label: "O3", type: "number" },
      { id: "no2", label: "NO2", type: "number" },
      { id: "pm25", label: "PM25", type: "number" },
      { id: "temperature", label: "temperature", type: "number" }
    ],
    rows: []
  };

  if (!isAddOption) {
    addOptionSensor(airDataList);
    isAddOption = true;
  }

  for (airData of airDataList) {
    let date = new Date(airData.measured_time);
    let elem = {
      c: [
        { v: date },
        { v: parseFloat(airData.co) },
        { v: parseFloat(airData.so2) },
        { v: parseFloat(airData.o3) },
        { v: parseFloat(airData.no2) },
        { v: parseFloat(airData.pm25) },
        { v: parseFloat(airData.temperature) }
      ]
    };
    data.rows.push(elem);
  }
  return data;
}

function drawChart(columns) {
	let chartDiv = document.getElementById('chart_div');
	let chartsData = getChartsData();
	let data = new google.visualization.DataTable(chartsData);
	let view = new google.visualization.DataView(data);
	let checkedElem = document.getElementsByName('checked_elem');

	view.setColumns(columns);

	let lineChart = new google.visualization.LineChart(chartDiv);

	var materialOptions = {
		chart: {
			title: 'AQI Values in your sensors',
		},
		width: 900,
		height: 500,
	};
	var options = {
		title: 'AQI Values in your sensors',
		hAxis: {
			titleTextStyle: { color: '#333' },
			slantedText: true,
			slantedTextAngle: 10,
		},
		vAxis: { minValue: 1 },
		explorer: {
			actions: ['dragToZoom', 'rightClickToReset'],
			axis: 'horizontal',
			keepInBounds: true,
			maxZoomIn: 100.0,
		},
	};
	lineChart.draw(view, options);
}
var updateCharts = $("#checkboxes input").click(function() {
  let columns = [0];
  $("#checkboxes input:checked").map(function() {
    columns.push(parseInt(this.value));
  });
  drawChart(columns);
});

$(document).ready(function() {
  $("#get_data_btn").click(function() {
    let columns = [0];
    $("#checkboxes input:checked").map(function() {
      columns.push(parseInt(this.value));
    });
    console.log(columns);

    drawChart(columns);
  });