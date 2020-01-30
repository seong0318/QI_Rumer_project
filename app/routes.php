<?php
// Routes

$app->get('/', 'App\Controller\HomeController:dispatch')
    ->setName('homepage');

$app->get('/signup', 'App\Controller\HomeController:signUp')
    ->setName('sign_up');

$app->post('/signuphandle', 'App\Controller\HomeController:signUpHandle')
    ->setName('sign_up_handle');

$app->get('/signin', 'App\Controller\HomeController:signIn')
    ->setName('sign_in');

$app->post('/signinhandle', 'App\Controller\HomeController:signInHandle')
    ->setName('sign_in_handle');

$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');
