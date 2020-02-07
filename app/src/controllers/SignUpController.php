<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

include '../app/src/util.php';

final class SignUpController extends BaseController {
    public function duplicateUser($username){
        /*  사용자의 이름으로 중복된 ID가 존재하는지 확인
        **  반환값은 찾은 ID 수로 반환
        */
        $sql = "select count(usn) from user where (user_name = :username)";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params['username'] = $username;
        if (!$stmt->execute($params)) return -1;
        $userResult = $stmt->fetch();

        if ($userResult['count(usn)'] == 0) {
            $sql = 'select count(*) from temp_user where (temp_user_name = :username)';
            $stmt = $this->em->getConnection()->prepare($sql);
            $params['username'] = $username;
            if (!$stmt->execute($params)) return -1;
            $tempResult = $stmt->fetch();
            return $tempResult['count(*)'];
        }

        return $result['count(usn)'];
    }

    public function storeTempUser($username, $nonce) {
        /*  temp_user 테이블에 값 저장
        **  
        */
        $sql = "insert into temp_user values (:username, :nonce, NOW())";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'username' => $username,
            'nonce' => $nonce
        ];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function verifyNonceAndChangeVerifyState($nonce) {
        /*  temp_user 테이블의 nonce_link 열을 이용해
        **  사용자의 sign up 인증을 진행한다
        */
        $sql = "update user 
                set verify_state = '1' 
                where user_name = (select temp_user_name
                                   from temp_user
                                   where nonce_link = :nonce)";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['nonce' => $nonce];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function storeUserInfo($userInfo) {
        /*  user 정보를 인증 전 상태로 저장
        ** 
        */
        $hashedPwd = password_hash($userInfo['pwd'], PASSWORD_DEFAULT);

        $sql = "insert into user(user_name, hashed_pwd, email, verify_state, register_date)
        values(:username, :hashedPwd, :email, '0', NOW())";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'username' => $userInfo['user_name'],
            'hashedPwd' => $hashedPwd,
            'email' => $userInfo['email']
        ];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function deleteUserInfo($username) {
        $sql = "delete from user where user_name = :username";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['username' => $username];
        if ($stmt->execute($params))
            return 0;
        else return -1;
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

    public function signUp(Request $request, Response $response, $args) {
        /*  sign up 페이지를 띄우는 기본 함수
        **  사용자로부터 입력된 값을 Ajax POST 방식으로 전달
        */
        $this->view->render($response, 'sign_up.twig');
    }    

    public function signUpHandle(Request $request, Response $response, $args) {
        /*  sign up 페이지의 register 버튼
        **  storeUserInfo에서 오류 발생시 -1 반환
        **  storeTempUser에서 오류 발생시 -2 반환
        **  sendMail에서 오류 발생시 -4 반환
        */
        $nonce = makeRandomString(); // make nonce link

        $mailSubject = "Website Activation Email";
        $mailBody = "<h1>THANK YOU</h1>Please click the link to activate your account.<br>
        <a href='http://192.168.33.99/signupverify?nonce=$nonce'>Register My Account</a><br>";
        $mailAltBody = "Thank you . Please click the link to activate your account.";

        if ($this->storeTempUser($_POST['user_name'], $nonce) != 0) 
            return json_encode(-2);

        if ($this->storeUserInfo($_POST) != 0) {
            $this->deleteTempUser($nonce);
            return json_encode(-1);
        }

        if (sendMail($_POST['email'], $mailSubject, $mailBody, $mailAltBody) != 0) {
            $this->deleteTempUser($nonce);
            $this->deleteUserInfo($_POST['user_name']);
            return json_encode(-4);
        }

        return json_encode(0);
    }

    public function usernameCheck(Request $request, Response $response, $args) {
        /*  username 중복 검사를 실행하는 버튼
        **  아이디 사용자 수를 json 형식으로 반환함
        */
        $isDup = $this->duplicateUser($_GET['user_name']);
        return json_encode($isDup);
    }

    public function signUpVerify(Request $request, Response $response, $args) {
        /*  사용자가 nonce link를 누른 후부터 진행되는 sign up의 인증 과정
        **
        */
        if ($this->verifyNonceAndChangeVerifyState($_GET['nonce']) != 0){
            echo "ERROR: Verify error";
            if ($this->deleteTempUser($_GET['nonce']) != 0){
                echo "ERROR: Clear temp_user error";
                return json_encode(-3);
            }
        }
          
        if ($this->deleteTempUser($_GET['nonce']) != 0){
            echo "ERROR: Clear temp_user error";
            return json_encode(-3); 
        }
        
        $this->flash->addMessage('Test', 'this is message');
        return $response->withRedirect('signin');  
    }
}
