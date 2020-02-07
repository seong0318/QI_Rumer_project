<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMtP;
use PHPMailer\PHPMailer\Exception;

final class VerifiedPage extends BaseController
{
    public function VerifiedPage(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'verifiedPage.twig');
        return $response;
    }
}
