$("#signin_btn").click(function() {
  event.preventDefault();
  var formData = $("#input_form").serialize();
  $.ajax({
    type: "POST",
    url: "./signinhandle/0",
    data: formData,
    datatype: "JSON"
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];

      switch (execResult) {
        case 0:
          alert("Success Sign in");
          window.location.href = "index";
          break;
        case -1:
          alert("ERROR: Query error");
          break;
        case -2:
          alert("Invalid username or password");
          break;
        case -3:
          alert("Please complete email verification first");
          break;
        case -4:
          alert("ERROR: isDevice error");
          break;
        default:
          alert("ERROR: Invalid access: " + execResult);
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
});
