$("#pwd3").keyup(function() {
  var pwd2 = $("#pwd2").val();
  var pwd3 = $("#pwd3").val();

  if (pwd2 == pwd3) $("#change_pwd_btn").removeAttr("disabled");
  else $("#change_pwd_btn").attr("disabled", "disabled");
});

$("#change_pwd_btn").click(function() {
  event.preventDefault();
  var formData = $("#input_form").serialize();
  $.ajax({
    type: "POST",
    url: "./changepwdbtn/0",
    data: formData,
    datatype: "JSON"
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];

      switch (execResult) {
        case 0:
          alert("Successful password change");
          window.location.href = "index";
          break;
        case -1:
          alert("ERROR: query error");
          break;
        case -2:
          alert("No update occurs");
          break;
        case -3:
          alert("This account is already signed out.");
          window.location.href = "signin";
          break;
        case -4:
          alert("Invalid password");
          break;
        case -5:
          alert("ERROR: Invalid isDevice");
          break;
        default:
          alert("Invalid Access: " + execResult);
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
