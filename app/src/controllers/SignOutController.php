<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignOutController extends BaseController {
    public function updateVerifyState($usn) {
        /* 사용자가 sigin in한 것으로 처리함
        ** usn 및 verify_state =2를 확인하고 user의 verify_state를 1로 바꿈
        ** 정상일 경우 0, sql 에러일 경우 -1, 이미 sign out 되어있는 경우 -2, 갱신이 잘못될 경우 -3 반환
        */
        $sql = "update user set verify_state = 1 where usn = :usn and verify_state = 2";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;

        $numUpdatedRow = $stmt->rowCount();

        if ($numUpdatedRow == 1) return 0;
        else if ($numUpdatedRow == 0) return -2;
        else return -3;
    }

    public function signOut(Request $request, Response $response, $args) {
        /*  sign out 프로시저
        ** updateVerifyState와 에러 공유 및 세선 삭제 후 시도할 경우 -4, isDevice 오류시 -5 반환
        */
        $isDevice = $args['isDevice'];
        
        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_GET['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        $execResult = $this->updateVerifyState($usn);

        if (empty($usn)) {
            echo json_encode(array('result' => -4));
            return;
        }

        if ($isDevice == 0 && $execResult == 0) {
            $_SESSION = [];
            setcookie(session_name(), '', time() - 42000);
            session_destroy(); 
        }

        echo json_encode(array('result' => $execResult));
        return;
    }
}
