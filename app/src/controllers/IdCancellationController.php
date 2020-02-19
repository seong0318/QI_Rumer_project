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
        **  정상일 경우 0, 쿼리 에러일 경우 -1, 비밀번호가 틀릴 경우 -2, 세션이 없는 경우 -3, isDevice 에러는 -5 반환
        */        
        $isDevice = $args['isDevice'];
        
        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_POST['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        if (empty($usn)) {
            echo json_encode(array('result' => -3));
            return;
        }

        $hashedPwd = $this->getHashedPwd($usn);
        if ($hashedPwd == -1) {
            echo json_encode(array('result' => -1));
            return;
        }
        
        if (password_verify($_POST['pwd'], $hashedPwd) != 1) {
            echo json_encode(array('result' => -2));
            return;  
        }
        
        $execResult = $this->removeUserInfo($usn);
        if ($execResult != 0) {
            echo json_encode(array('result' => -1));
            return;
        }
        
        if ($isDevice == 0 && $execResult == 0) {
            $_SESSION = [];
            setcookie(session_name(), '', time() - 42000);
            session_destroy(); 
        }

        echo json_encode(array('result' => 0));
        return; 
    }
}
