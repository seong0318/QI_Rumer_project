<?php
// Routes

//homePage===================================================================================
$app->get('/', 'App\Controller\HomeController:homePage')
    ->setName('homepage');



//sign up =================================================================================
$app->get('/signup', 'App\Controller\SignUpController:signUp')
    ->setName('sign_up');

$app->post('/signuphandle', 'App\Controller\SignUpController:signUpHandle')
    ->setName('sign_up_handle');

$app->get('/signupverify', 'App\Controller\SignUpController:signUpVerify')
    ->setName('sign_up_verify');

$app->get('/usernamecheck', 'App\Controller\SignUpController:usernameCheck')
    ->setName('username_check');

//sign in =================================================================================
$app->get('/signin', 'App\Controller\SignInController:signIn')
    ->setName('sign_in');

$app->post('/signinhandle', 'App\Controller\SignInController:signInHandle')
    ->setName('sign_in_handle');

//sign out =================================================================================
$app->get('/signout', 'App\Controller\SignOutController:signOut')
    ->setName('sign_out');

//forgotPassword =================================================================================
$app->get('/forgotpassword', 'App\Controller\ForgotPasswordController:forgotPassword')
    ->setName('forgot_password');

$app->get('/forgotpasswordhandle', 'App\Controller\ForgotPasswordController:forgotPasswordHandle')
    ->setName('forgot_password_handle');

$app->get('/verifynonce', 'App\Controller\ForgotPasswordController:verifyNonce')
    ->setName('verify_nonce');

//index =================================================================================
$app->get('/index', 'App\Controller\IndexController:index')
    ->setName('index');

//index =================================================================================
$app->get('/changepassword', 'App\Controller\ChangePasswordController:changePassword')
    ->setName('change_password');

$app->post('/changepwdbtn', 'App\Controller\ChangePasswordController:changePwdBtn')
    ->setName('change_pwd_btn');

//verified page
$app->get('/verifiedpage', 'App\Controller\VerifiedPage:verifiedPage')
    ->setName('verified_page');