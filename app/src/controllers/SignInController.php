<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignInController extends BaseController {
    public function getUsnAndHashedPwd($username) {
        /*  사용자 이름으로 usn과 hashed_pwd를 받아옴
        **  ['usn': value, 'hashed_pwd': value]
        */
        $sql = "select usn, hashed_pwd from user where user_name = :username";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['username' => $username];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult;
    }

    public function signIn(Request $request, Response $response, $args) {
        $this->view->render($response, 'sign_in.twig');
    }

    public function signInHandle(Request $request, Response $response, $args) {
        $execResult = $this->getUsnAndHashedPwd($_POST['user_name']);
        echo "success " . $execResult['usn'] . ' ' . $execResult['hashed_pwd'];
    }
}
