function showSensorList(inputType) {
  $.ajax({
    type: "GET",
    url: "./sensorlisthandle/0",
    data: { type: inputType },
    datatype: "JSON"
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];

      switch (execResult) {
        case 0:
          $("#sensor_table > tbody").empty();
          $.each(jsonData["data"], function(index, item) {
            let typeStr;
            if (item.type == 0) typeStr = "Udoo Sensor";
            else if (item.type == 1) typeStr = "Polar Sensor";
            else typeStr = "Unkown";
            let eachRow =
              "<tr><th>" +
              index +
              "</th><td>" +
              item.sensor_name +
              "</td><td>" +
              typeStr +
              "</td><td>" +
              item.mac_address +
              "</td></tr>";
            $("#sensor_table").append(eachRow);
          });
          break;
        case -1:
          alert("ERROR: sql query error");
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
}
