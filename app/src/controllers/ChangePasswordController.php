<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ChangePasswordController extends BaseController {
    public function getHashedPwd($usn) {
        $sql = "select hashed_pwd from user where usn = :inputUsn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['inputUsn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult['hashed_pwd'];
    }

    public function changePassword(Request $request, Response $response, $args) {
        $this->view->render($response, 'change_password.twig');
    }

    public function updatePassword($usn, $hashedPwd) {
        /** usn으로 새 비밀번호를 갱신함
         ** 정상일 경우 0, 쿼리 에러시 -1, 갱신이 안되었을 경우 -2, 그 외 -3을 반환
         */
        $sql = "update user set hashed_pwd = :hashedPwd where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'usn' => $usn,
            'hashedPwd' => $hashedPwd    
        ];
        if (!$stmt->execute($params)) return -1;

        $updatedRowNum = $stmt->rowCount();
        
        if ($updatedRowNum == 1) return 0;
        else if($updatedRowNum == 0) return -2;
        else return -3;
    }
    
    public function changePwdBtn(Request $request, Response $response, $args) {
        /*  change password 프로시저
        **  updatePassword와 에러 공유 및 세션이 없을 경우 -3, 비밀번호가 틀렸을 경우 -4 반환
        */
        if (empty($_SESSION['usn']))
            return json_encode(-3);

        $hashedPwd = $this->getHashedPwd($_SESSION['usn']);
        if ($hashedPwd == -1) return json_encode(-1);

        if (password_verify($_POST['pwd1'], $hashedPwd) != 1) return json_encode(-4);

        $newHashedPwd = password_hash($_POST['pwd2'], PASSWORD_DEFAULT);
        $exec_update = $this->updatePassword($_SESSION['usn'], $newHashedPwd);
        
        return json_encode($exec_update);
    }
}
