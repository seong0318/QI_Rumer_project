function getRecentlyHeartData(sensorId) {
	let RecentlyHeartData;

	$.ajax({
		type: 'POST',
    url: './heartrealtimehandle/0',
    data: { sensor_id = sensorId },
		datatype: 'JSON',
		async: false,
	})
		.done(function(json) {
			let jsonData = JSON.parse(json);
			let execResult = jsonData['result'];

			switch (execResult) {
				case 0:
					RecentlyHeartData = jsonData['data'];
					break;
				case -1:
					alert('ERROR: sql query error');
					break;
				case -5:
					console.log("1");
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

	return RecentlyHeartData;
}

$(document).ready(function() {

})

setInterval(() => {
  let sensorOne = Math.floor(Math.random() * 120) + 80;
  document.getElementById("heart_id").innerHTML = sensorOne;
  console.log("123");
}, 1000);
