<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignInController extends BaseController {
    public function getUsnAndHashedPwd($username) {
        /*  사용자 이름으로 usn과 hashed_pwd를 받아옴
        **  ['usn': value, 'hashed_pwd': value, 'verify_state': value]
        */
        $sql = "select usn, hashed_pwd, verify_state from user where user_name = :username";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['username' => $username];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult;
    }

    public function updateVerifyState($usn) {
        /* 사용자가 sigin in한 것으로 처리함
        ** usn으로 user의 verify_state를 2로 바꿈
        */
        $sql = "update user set verify_state = 2 where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        return 0;
    }

    public function signIn(Request $request, Response $response, $args) {
        $this->view->render($response, 'sign_in.twig');
    }

    public function signInHandle(Request $request, Response $response, $args) {
        /** Sign in 버튼 클릭시 username, pwd 확인함
         ** 정상적으로 완료될 경우 0, sql 에러일 시 -1, 비밀번호가 틀렸을 경우 -2, 인증이 안된 사용자일 경우 -3를 반환
         */
        $execResult = $this->getUsnAndHashedPwd($_POST['user_name']);
        if ($execResult == -1) return json_encode(-1);

        if (password_verify($_POST['pwd'], $execResult['hashed_pwd']) != 1) return json_encode(-2);
        
        if ($execResult['verify_state'] == 0) return json_encode(-3);
        
        if ($this->updateVerifyState($execResult['usn']) != 0) return json_encode(-1);

        return json_encode(0);
    }
}
