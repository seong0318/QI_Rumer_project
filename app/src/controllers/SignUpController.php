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
        $result = $stmt->fetch();
        return $result['count(usn)'];
    }

    public function storeUsernameAndNonce($username, $nonce) {
        /*  temp_user 테이블에 값 저장
        **  
        */
        $sql = "insert into temp_user values (:username, :nonce, '0')";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'username' => $username,
            'nonce' => $nonce
        ];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function verifyNonceAndChangeIsVerify($nonce) {
        /*  temp_user 테이블의 nonce_link 열을 이용해
        **  사용자의 sign up 인증을 진행한다
        */
        $sql = "update temp_user set is_verify = '1' where nonce_link = :nonce";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['nonce' => $nonce];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function verifyAndstoreUserInfo($userInfo) {
        /*  비밀번호를 해쉬로 암호화 한 후, 이메일 인증을 받았는지 확인
        **  마지막으로 user information을 user 테이블에 저장
        **  정상적으로 끝날 경우 0, mysql 쿼리 오류 시 -1, 이메일 인증을 안했을 경우 -2, 
        **  하나 이상이 갱신될 경우 -4, 인증 중 다른 사용자가 username을 선점할 경우 -5 반환
        */
        try{
            $hashedPwd = password_hash($userInfo['pwd'], PASSWORD_DEFAULT);

            $sql = "insert into user(user_name, hashed_pwd, email, is_signed)
            select temp_user_name, :hashedPwd, :email, '0'
            from temp_user
            where temp_user_name = :username and is_verify = 1";
            $stmt = $this->em->getConnection()->prepare($sql);
            $params = [
                'username' => $userInfo['user_name'],
                'hashedPwd' => $hashedPwd,
                'email' => $userInfo['email']
            ];
            $exec_result = $stmt->execute($params);

            $numUpdateRows = $stmt->rowCount(); //  1일 경우 성공
        }
        catch (UniqueConstraintViolationException $e) {
            return -5;  //  인증 중 다른 사용자가 username을 선점할 경우
        }

        if ($exec_result) {
            if ($numUpdateRows < 1) 
                return -2;  //  이메일 인증을 안했을 경우
            else if ($numUpdateRows == 1)
                return 0;   //  정상
            else
                return -4;  //  하나 이상이 바뀌었으므로 오류
        }
        else return -1; //  쿼리 실행 오류
    }

    public function clearTempUserTable($username) {
        /*  user_name을 이용해 temp_user_table 내용을 삭제
        ** 
        */
        $sql = "delete from temp_user where temp_user_name = :username";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['username' => $username];
        
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
        **  verifyAndStoreUserInfo와 오류 코드 공유
        **  clearTempUserTable에서 오류 발생시 -3 반환
        */
        $exec_result = $this->verifyAndstoreUserInfo($_POST);

        if($exec_result == 0) {
            //  정상적으로 진행할 경우
            if ($this->clearTempUserTable($_POST['user_name']) != 0)
                return json_encode(-3);
        }
        return json_encode($exec_result);
    }

    public function usernameCheck(Request $request, Response $response, $args) {
        /*  username 중복 검사를 실행하는 버튼
        **  아이디 사용자 수를 json 형식으로 반환함
        */
        $isDup = $this->duplicateUser($_GET['user_name']);
        return json_encode($isDup);
    }

    public function emailVerify(Request $request, Response $response, $args) {
        /*  email을 이용하여 인증을 시작하는 버튼
        **  성공시 0을 반환
        */
        $nonce = makeRandomString(); // make nonce link
        if ($this->storeUsernameAndNonce($_GET['user_name'], $nonce) != 0)
            return json_encode(-1);

        if (sendMail($_GET['email'], $nonce) != 0){
            if ($this->clearTempUserTable($_GET['user_name']) != 0)
                return json_encode(-3);
            return json_encode(-1);
        }

        return json_encode(0);
    }

    public function signUpVerify(Request $request, Response $response, $args) {
        /*  사용자가 nonce link를 누른 후부터 진행되는 sign up의 인증 과정
        **
        */
        if ($this->verifyNonceAndChangeIsVerify($_GET['nonce']) != 0){
            echo "<script> alert('verifyNonceAndChangeIsVerify Query error.')
            window.location = '/signup'</script>";
            exit;
        }

        echo "<script>window.location = '/signup'</script>";        
    }
}
