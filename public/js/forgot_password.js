$("#forgot_btn").click(function() {
  var username = $("#user_name").val();

  $.ajax({
    type: "GET",
    url: "./forgotpasswordhandle",
    data: { user_name: username },
    datatype: "JSON"
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];

      switch (execResult) {
        case 0:
          alert(
            "Please complete the account verification in the email provided."
          );
          window.location.href = "/signin";
          break;
        case -1:
          alert("ERROR: Query error");
          break;
        case -2:
          alert("The verification process is already in progress.");
          break;
        case -3:
          alert("Not exist username");
          break;
        case -4:
          alert("ERROR: sending email error");
          break;
        default:
          alert("ERROR: Invalid access: " + execResult);
          break;
      }
      return;
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
