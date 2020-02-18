function callChangePassword() {
  $.ajax({
    type: "GET",
    url: "./changepassword",
    datatype: "JSON"
  })
    .done(function(json) {
      var execResult = JSON.parse(json);

      if (execResult == -1) {
        alert("Please login first");
        window.location.href = "/signin";
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
  window.location.href = "/changepassword";
}

function callSignOut() {
  $.ajax({
    type: "GET",
    url: "./signout/0",
    datatype: "JSON"
  })
    .done(function(json) {
      let jsonData = JSON.parse(json);
      let execResult = jsonData["result"];

      switch (execResult) {
        case 0:
          alert("Success Sign out");
          window.location.href = "/";
          break;
        case -1:
          alert("ERROR: Query error");
          break;
        case -2:
          alert("This account is already signed out.");
          break;
        case -3:
          alert("ERROR: Update query error");
          break;
        case -4:
          alert("Please sign in first");
          window.location.href = "/signin";
          break;
        case -5:
          alert("ERROR: Flag error");
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
}

function callIdCancellation() {
  $.ajax({
    type: "GET",
    url: "./idcancellation",
    datatype: "JSON"
  })
    .done(function(json) {
      let execResult = JSON.parse(json);

      if (execResult == -1) {
        alert("Please login first");
        window.location.href = "/signin";
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

  /* 이부분을 한번 다시 볼 것
  var option = "width = 500, height = 500, top = 100, left = 200, location = no";
  window.open("idcancellation", "idcancellation", option);*/
  window.location.href = "/idcancellation";
}
