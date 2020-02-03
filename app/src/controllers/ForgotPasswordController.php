<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ForgotPasswordController extends BaseController
{
    public function forgotPassword(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'forgot_password.twig');

        return $response;
    }


    // $this->view->render($response, 'index.twig');
    // exit;
}
