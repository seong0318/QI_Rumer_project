function checkSpace(str) {
  if (str.search(/\s/) != -1) return true;
  else return false;
}
$("#pwd2").keyup(function() {
  var pwd1 = $("#pwd1").val();
  var pwd2 = $("#pwd2").val();
  if (checkSpace(pwd1)) {
    alert('you can\'t use "blank" as Password ');
    $("#register").attr("disabled", "disabled");
  } else if (pwd1.length < 8 || pwd1.length > 20) {
    alert("Please enter a password between 8 and 20 digits.");
  } else {
    if (pwd1 == pwd2) $("#register").removeAttr("disabled");
    else $("#register").attr("disabled", "disabled");
  }
});
$("#username_btn").click(function() {
  let username = $("#user_name").val();
  $.ajax({
    type: "GET",
    url: "./usernamecheck",
    data: { user_name: username },
    datatype: "JSON",
    async: false
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let num_user = jsonData["result"];
      if (num_user < 0) alert("query error");
      else if (num_user == 0) {
        alert("usable username");
        $("#user_name").attr("readOnly", "readOnly");
        $("#username_btn").attr("disabled", "disabled");
      } else alert("Duplicate username");

      return;
    })
    .fail(function(jrequest, status, error) {
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
$("#register").click(function() {
  event.preventDefault();
  usernameBtn = document.getElementById("username_btn");
  if (!usernameBtn.disabled) {
    alert("Please check id duplication first.");
    return;
  } else {
    let formData = $("#input_form").serialize();
    $.ajax({
      type: "POST",
      url: "./signuphandle",
      data: formData,
      datatype: "JSON",
      async: false
    })
      .done(function(json) {
        let jsonData = JSON.parse(json);
        let execResult = jsonData["result"];
        switch (execResult) {
          case 0:
            alert(
              "Please complete the account verification in the email provided."
            );
            window.location.href = "signin";
            break;
          case -1:
            alert("ERROR: Store user Query error");
            break;
          case -2:
            alert("ERROR: Store temp_user Query error");
            break;
          case -4:
            alert("ERROR: Send mail error");
            break;
          default:
            alert("ERROR: Invalid access " + execResult);
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
  }
});
