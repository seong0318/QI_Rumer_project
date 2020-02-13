<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class IdCancellationController extends BaseController {
    public function idCancellation(Request $request, Response $response, $args) {
        if (empty($_SESSION['usn'])) return json_encode(-1);
        $this->view->render($response, 'id_cancellation.twig');
    }

    public function getHashedPwd($usn) {
        $sql = "select hashed_pwd from user where usn = :inputUsn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['inputUsn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult['hashed_pwd'];
    }

    public function removeUserInfo($usn) {
        $sql = "delete from user where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        return 0;
    }

    public function idCancelHandle(Request $request, Response $response, $args) {
        /*  회원탈퇴할 경우, user에 관한 정보만 삭제하고 air_data와 heart_data는 남김 
        **  air_data와 heart_data의 경우 usn 값이 null로 설정됨
        **  정상일 경우 0, 쿼리 에러일 경우 -1, 비밀번호가 틀릴 경우 -2, 세션이 없는 경우 -3 반환
        */        
        if (empty($_SESSION['usn']))
            return json_encode(-3);

        $hashedPwd = $this->getHashedPwd($_SESSION['usn']);
        if ($hashedPwd == -1) return json_encode(-1);
        
        if (password_verify($_POST['pwd'], $hashedPwd) != 1) return json_encode(-2);  

        if ($this->removeUserInfo($_SESSION['usn']) != 0) return json_encode(-1);
        
        $_SESSION = [];
        setcookie(session_name(), '', time() - 42000);
        session_destroy(); 

        return json_encode(0); 
    }
}
