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

$app->post('/signinhandle/{isDevice}', 'App\Controller\SignInController:signInHandle')
    ->setName('sign_in_handle');

//sign out =================================================================================
$app->get('/signout/{isDevice}', 'App\Controller\SignOutController:signOut')
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

$app->get('/profile', 'App\Controller\IndexController:profile')
    ->setName('profile');

$app->post('/heartrealtimehandle/{isDevice}', 'App\Controller\HeartController:heartRealTimeHandle')
    ->setName('heartrealtime_handle');

//index heart ========================================================================

$app->get('/hearthistory', 'App\Controller\HeartController:hearthistory')
    ->setName('hearthistory');

$app->get('/heartrealtime', 'App\Controller\HeartController:heartrealtime')
    ->setName('heartrealtime');

$app->post('/hearthandle/{isDevice}', 'App\Controller\HeartController:heartHandle')
    ->setName('heart_handle');



//index/map ==========================================================================================
$app->get('/map', 'App\Controller\MapController:map')
    ->setName('map');

$app->get('/maphistory', 'App\Controller\MapController:maphistory')
    ->setName('maphistory');

$app->post('/maphandle/{isDevice}', 'App\Controller\MapController:mapHandle')
    ->setName('map_handle');
//index/charts============================================================================
$app->get('/charts', 'App\Controller\ChartsController:charts')
    ->setName('charts');

$app->get('/chartshistory', 'App\Controller\ChartsController:chartshistory')
    ->setName('chartshistory');

$app->post('/chartshandle/{isDevice}', 'App\Controller\ChartsController:chartsHandle')
    ->setName('chart_handle');

//index/sensorlist==========================================================================
$app->get('/sensorlist', 'App\Controller\SensorListController:sensorList')
    ->setName('sensorlist');

$app->get('/sensorlisthandle/{isDevice}', 'App\Controller\SensorListController:sensorListHandle')
    ->setName('sensorlist_handle');

//index/historytable==========================================================================
$app->get('/historytable', 'App\Controller\HistoryTableController:historytable')
    ->setName('historytable');


//sensor deregist===================================================================================
$app->post('/sensorderegist/{isDevice}', 'App\Controller\SensorDeregistController:sensorDeregist')
    ->setName('sensor_deregist');

//changepassword =================================================================================
$app->get('/changepassword', 'App\Controller\ChangePasswordController:changePassword')
    ->setName('change_password');

$app->post('/changepwdbtn/{isDevice}', 'App\Controller\ChangePasswordController:changePwdBtn')
    ->setName('change_pwd_btn');

//verified page =========================================================================
$app->get('/verifiedpage', 'App\Controller\VerifiedPageController:verifiedPage')
    ->setName('verified_page');

$app->post('/verifiedpassword', 'App\Controller\VerifiedPasswordController:verifiedPassword')
    ->setName('verified_password');

//ID Cancellation =========================================================================
$app->get('/idcancellation', 'App\Controller\IdCancellationController:idCancellation')
    ->setName('id_cancellation');

$app->post('/idcancelhandle/{isDevice}', 'App\Controller\IdCancellationController:idCancelHandle')
    ->setName('id_cancel_handle');

//Scheduler test =========================================================================
$app->get('/schedule', 'App\Controller\ScheduleController:Schedule')
    ->setName('schedule');

//Insert Sensor Data
$app->post('/sensor/insert/polar', 'App\Controller\InsertSensorData:insertPolarData')
    ->setName('sensor_insert_polar');

$app->post('/sensor/insert/udoo', 'App\Controller\InsertSensorData:insertUdooData')
    ->setName('sensor_insert_udoo');