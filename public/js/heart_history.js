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

function addOptionSensor(heartDataList) {
  let sensorNameList = [];

  //  sensorNameList key: sensor_name, val: sensor_id
  for (heartData of heartDataList)
    sensorNameList[heartData.sensor_name] = heartData.sensor_id;

  for (sensorName in sensorNameList) {
    let option = new Option(sensorName, sensorNameList[sensorName]);
    $(option).html(sensorName);
    $("#options").append(option);
  }
}

function getHeartDataList(sensorId, startTime, endTime) {
  let heartDataList;

  $.ajax({
    type: "POST",
    url: "./hearthandle/0",
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
          heartDataList = jsonData.data;
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

  return heartDataList;
}

function getHeartData() {
  let sensorId = document.getElementById("options");
  let startTime = $("#startdatepicker")
    .datepicker()
    .val();
  let endTime = $("#lastdatepicker")
    .datepicker()
    .val();
  let heartDataList = getHeartDataList(
    parseInt(sensorId.value),
    startTime,
    endTime
  );
  let data = {
    cols: [
      { id: "time", label: "Time", type: "date" },
      { id: "heart_rate", label: "HEART RATE", type: "number" },
      { id: "rr_interval", label: "RR", type: "number" }
    ],
    rows: []
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
        { v: parseFloat(heartData.rr_interval) }
      ]
    };
    data.rows.push(elem);
  }
  return data;
}

function drawChart(columns) {
  let chartDiv = document.getElementById("chart_div");
  let chartsData = getHeartData();
  let data = new google.visualization.DataTable(chartsData);
  let view = new google.visualization.DataView(data);
  let checkedElem = document.getElementsByName("checked_elem");

  view.setColumns(columns);

  let lineChart = new google.visualization.LineChart(chartDiv);

  var options = {
    title: "Heart Values in your sensors",
    hAxis: {
      titleTextStyle: { color: "#333" },
      slantedText: true,
      slantedTextAngle: 10
    },
    vAxis: { minValue: 1 },
    explorer: {
      actions: ["dragToZoom", "rightClickToReset"],
      axis: "horizontal",
      keepInBounds: true,
      maxZoomIn: 1000.0
    }
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
  
	  drawChart(columns);
	});
  });
  