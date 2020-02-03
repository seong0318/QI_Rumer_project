<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignInController extends BaseController
{
    public function signIn(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'sign_in.twig', ['user_name' => $user_name, 'pwd' => $pwd, 'remember' => $remember]);
    }

    public function signInHandle(Request $request, Response $response, $args)
    {
        print_r($_POST);

        // $this->view->render($response, 'index.twig');
        // exit;
    }
}
