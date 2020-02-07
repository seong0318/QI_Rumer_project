<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMtP;
use PHPMailer\PHPMailer\Exception;

final class VerifiedPassword extends BaseController
{
    public function VerifiedPassword(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'verified_password.twig');
        return $response;
    }
}
