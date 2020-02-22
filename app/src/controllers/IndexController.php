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

    public function profile(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'profile.twig');
    }

    public function charts(Request $request, Response $response, $args)
    {

        $this->view->render($response, 'charts.twig');
        return $response;
    }
    public function getAqiData($usn)
    {
        $sql = "select hashed_pwd from user where usn = :inputUsn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['inputUsn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult['hashed_pwd'];
    }
}
