$('#pwd_btn').click(function() {
	event.preventDefault();

	var formData = $('#input_form').serialize();

	$.ajax({
		type: 'POST',
		url: './idcancelhandle',
		data: formData,
		datatype: 'JSON',
	})
		.done(function(json) {
			var execResult = JSON.parse(json);

			switch (execResult) {
				case 0:
					alert('Successful ID Cancellation');
					window.location.href = '/';
					break;
				case -1:
					alert('ERROR: query error');
					break;
				case -2:
					alert('Invalid password');
					break;
				case -3:
					alert('Please login first');
					window.location.href = '/signin';
					break;
				default:
					alert('Invalid Access: ' + execResult);
					break;
			}
			return;
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
});
