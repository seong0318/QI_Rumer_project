<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

include '../app/src/util.php';

final class ForgotPasswordController extends BaseController {
    public function getUsnAndEmail($username) {
        /** username으로 user의 usn과 email 찾음
         ** {'usn': value, 'email': value} 반환
         */
        $sql = "select usn, email from user where user_name = :username";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['username' => $username];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult;
    }

    public function storeTempUser($username, $nonce) {
        /*  temp_user 테이블에 값 저장
        **  정상 0, 쿼리 에러 -1, primary key 중복 발생시 -2 반환
        */
        $sql = "insert into temp_user values (:username, :nonce, NOW())";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'username' => $username,
            'nonce' => $nonce
        ];
        try {
            if ($stmt->execute($params)) return 0;
            else return -1;
        }
        catch (UniqueConstraintViolationException $e){
            return -2;
        }
    }

    public function forgotPassword(Request $request, Response $response, $args) {
        $this->view->render($response, 'forgot_password.twig');
    }

    public function verifyAndGetUsn($nonce) {
        /*  nonce 및 verify_state로 사용자 인증하고 usn을 찾음
        **  usn값으로 반환
        */
        $sql = "select usn 
                from user 
                where (verify_state <> 0 
                        and user_name = (select temp_user_name
                                        from temp_user
                                        where nonce_link = :nonce))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['nonce' => $nonce];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult['usn'];
    }

    public function updateTempPwd($usn, $hashedPwd) {
        /** usn으로 새 비밀번호를 갱신함
         ** 정상일 경우 0, 쿼리 에러시 -1, 갱신이 안되었을 경우 -2, 그 외 -4을 반환
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

    public function deleteTempUser($nonce) {
        /*  nonce을 이용해 temp_user_table 내용을 삭제
        ** 
        */
        $sql = "delete from temp_user where nonce_link = :nonce";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['nonce' => $nonce];
        
        if ($stmt->execute($params))
            return 0;
        else return -1;
    }

    public function forgotPasswordHandle(Request $request, Response $response, $args) {
        /*  비밀번호 찾기 과정 중 메일 보내기 전까지
        **  정상적으로 진행할 경우 0, sql 에러는 -1, 이미 인증메일이 보내졌다면 -2, 
        **  username이 없다면 -3, 이메일 전송 실패시 반환
        */

        $nonce = makeRandomString();
        
        $execResult = $this->getUsnAndEmail($_GET['user_name']);
        $mailSubject = "Password Reset Email";
        $mailBody = "<h1>THANK YOU</h1>Please click the link to reset your password.<br>
        <a href='http://192.168.33.99/verifynonce?nonce=$nonce'>Reset My Password</a><br>";
        $mailAltBody = "Thank you . Please click the link to reset your password.";

        if ($execResult == -1) {
            echo json_encode(array('result' => -1));
            return;
        }

        if (empty($execResult['usn'])) {
            echo json_encode(array('result' => -3));
            return;
        }

        $execStoreTemp = $this->storeTempUser($_GET['user_name'], $nonce);
        if ($execStoreTemp != 0) {
            echo json_encode(array('result' => $execStoreTemp));
            return;
        }

        if (sendMail($execResult['email'], $mailSubject, $mailBody, $mailAltBody) != 0) {
            $this->deleteTempUser($nonce);
            
            echo json_encode(array('result' => -4));
            return;
        }

        echo json_encode(array('result' => 0));
        return;
    }

    public function verifyNonce(Request $request, Response $response, $args) {
        $usn = $this->verifyAndGetUsn($_GET['nonce']);
        if ($usn < 1) {
            echo "Invalid Access";
            return -1;
        }

        $tempPwd = makeRandomString(6);
        $hashedPwd = password_hash($tempPwd, PASSWORD_DEFAULT);

        $execResult = $this->updateTempPwd($usn, $hashedPwd);

        if($this->deleteTempUser($_GET['nonce']) != 0) return -1;   // 원래는 마지막에 초기화하나 좀 더 빨리 삭제함
        
        if ($execResult == 0) 
            echo $tempPwd;
        else if ($execResult == -1)
            echo "ERROR: Query error";
        else if ($execResult == -2)
            echo "Invalid Email Link";
        else
            echo "ERROR: " . $execResult; 
            
        return $execResult;
    }
}
