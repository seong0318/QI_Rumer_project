<!doctype html>
<html lang="en">

<script src="bootstrap/assets/vendor/jquery/jquery-3.3.1.min.js"></script>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RUMER - Sign up</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="bootstrap/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/assets/libs/css/style.css">
    <link rel="stylesheet" href="bootstrap/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-size: cover;
            background-image: url('https://www.francechimie.fr/media/e34580ffb57afb63ff48eeee4f85e8cb.jpg');
        }
    </style>
</head>
<!-- ============================================================== -->
<!-- signup form  -->
<!-- ============================================================== -->

<body>
    <!-- ============================================================== -->
    <!-- signup form  -->
    <!-- ============================================================== -->
    <form class="splash-container" id = 'input_form' method="post" action="signuphandle">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-1" style="color : whitesmoke">Registrations Form</h3>
                <p style="color : whitesmoke">Please enter your user information.</p>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" id = 'user_name' name="user_name" required="" placeholder="Username" autocomplete="off" style = "display : inleine; width :70%">
                    <button class = "btn" id ='username_btn' style="background-color : #2e2f39">Check</button>
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="email" id = 'email' name="email" required="" placeholder="E-mail" autocomplete="off" style = "display : inleine; width :100%">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" id="pwd1" type="password" name="pwd" required=""
                        placeholder="Password">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" id="pwd2" type="password" name="pwd_confirm" required=""
                        placeholder="Confirm">
                </div>
                <div class="form-group pt-2">
                    <button class="btn btn-block btn-primary" id ="register" type="submit" disabled=false>Register My Account</button>
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="agree" name="agree">
                        {# <span class="custom-control-label">By creating an account, you agree the <a href="#">terms and conditions</a></span> #}
                    </label>
                </div>
                {# <div class="form-group row pt-0">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                        <button class="btn btn-block btn-social btn-facebook " type="button">Facebook</button>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <button class="btn  btn-block btn-social btn-twitter" type="button">Twitter</button>
                    </div>
                </div> #}
            </div>
            <div class="card-footer bg-white">
                <p>Already member? <a href="signin" class="text-secondary">Sign in Here.</a></p>
            </div>
        </div>
    </form>
</body>

<script>

    function checkSpace(str) {
        if(str.search(/\s/) != -1) return true; 
        else return false; 
    }

    $("#pwd2").keyup(function () {
        var pwd1 = $("#pwd1").val();
        var pwd2 = $("#pwd2").val();

        if(checkSpace(pwd1)){
             alert("you can't use \"blank\" as Password ");
             $("#register").attr("disabled", "disabled");
        }
        else if (pwd1.length <8 ||pwd1.length > 20 ){
             alert("Please enter a password between 8 and 20 digits.");
        }
        else {
            if (pwd1 == pwd2)
                $("#register").removeAttr("disabled");
            else
                $("#register").attr("disabled", "disabled");
        }
    });

    $("#username_btn").click(function() {    
        let username = $("#user_name").val();

        $.ajax({
            type: 'GET',
            url: './usernamecheck',
            data: {user_name: username},
            datatype: 'JSON',
            async: false
        })
        .done(function(json) {
            let jsonData = JSON.parse(json);
            let num_user = jsonData['result'];

            if (num_user < 0) 
                alert("query error");
            else if (num_user == 0) {
                alert("usable username");
                $('#user_name').attr("readOnly", "readOnly");
                $('#username_btn').attr("disabled", "disabled");
            }
            else
                alert("Duplicate username");
                
            return;
        })
        .fail(function(jrequest,status,error) {
            alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        })
    });

    $("#register").click(function() {
        event.preventDefault();
        usernameBtn = document.getElementById('username_btn');

        if (!usernameBtn.disabled) {
            alert("Please check id duplication first.");
            return;
        }
        else{
            let formData = $('#input_form').serialize();
            $.ajax({
                type: 'POST',
                url: './signuphandle',
                data: formData,
                datatype: 'JSON',
                async: false
            })
            .done(function(json) {
                let jsonData = JSON.parse(json);
                let execResult = jsonData['result'];

                switch (execResult) {
                    case 0:
                        alert("Please complete the account verification in the email provided.");
                        window.location.href = 'signin';
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
                        alert("ERROR: Invalid access "+execResult);
                        break;                    
                }
                return;
            })
            .fail(function(request, status, error) {
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            })
        }
    });
</script> 
</html>