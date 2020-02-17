<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class IndexController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'index.twig');
    }

    public function map(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'map.twig');
        return $response;
    }

    public function sensor_list(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'sensor_list.twig');
        return $response;
    }

    public function charts(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'charts.twig');
        return $response;
    }
}
