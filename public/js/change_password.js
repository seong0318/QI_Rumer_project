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
    url: "./changepwdbtn",
    data: formData,
    datatype: "JSON"
  })
    .done(function(json) {
      var execResult = JSON.parse(json);
      if (execResult == 0) {
        alert("Successful password change");
        window.location.href = "index";
      } else if (execResult == -1) alert("ERROR: query error");
      else if (execResult == -2) alert("No update occurs");
      else if (execResult == -3) {
        alert("This account is already signed out.");
        window.location.href = "signin";
      } else if (execResult == -4) alert("Invalid password");
      else alert("Invalid Access: " + execResult);
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
