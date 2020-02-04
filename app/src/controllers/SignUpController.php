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

    public function verifyNonceAndChangeIsVerify($username, $nonce) {
        /*  temp_user 테이블의 temp_user_name, nonce_link 열을 이용해
        **  사용자의 sign up 인증을 진행한다
        */
        $sql = "update temp_user set is_verify = '1' where (temp_user_name = :username and nonce_link = :nonce)";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'username' => $username,
            'nonce' => $nonce
        ];
        if ($stmt->execute($params)) return 0;
        else return -1;
    }

    public function storeUserInfo($userInfo) {
        /*  user information을 user 테이블에 저장
        **
        */
        $sql = "";
    }

    public function signUp(Request $request, Response $response, $args) {
        /*  sign up 페이지를 띄우는 기본 함수
        **  사용자로부터 입력된 값을 POST 방식으로 전달
        */
        $this->view->render($response, 'sign_up.twig', ['user_name'=>$user_name, 'email'=>$email, 'pwd'=>$pwd, 'pwd_confirm'=>$pwd_confirm, 'agree'=>$agree]);
    }    

    public function signUpHandle(Request $request, Response $response, $args) {
        /*  메일로 nonce link를 보내기까지의 sign up 과정
        **  정상적으로 진행할 경우, sign in 페이지로 넘어가고 중복된 user_name이 입력될 경우 sign up 페이지로 넘어감
        */
        $isDup = $this->duplicateUser($_POST['user_name']);
        
        if ($isDup == -1) {
            echo "<script> alert('duplicateUser Query error')
            window.location = '/signup'</script>";
            exit;
        }
        if ($isDup != 0) {
            echo "<script> alert('This username is already exists.')
            window.location = '/signup'</script>";
            exit;
        }
   
        $nonce = makeRandomString(); // make nonce link
        if ($this->storeUsernameAndNonce($_POST['user_name'], $nonce) != 0){
            echo "storeUsernameAndNonce Query error";
            exit;
        }
        
        echo "
        <script>
            alert('We will send you a confirmation email, so please check it.') 
            window.location = '/signin'
        </script>";

        sendMail($_POST['email'], $_POST['user_name'], $nonce);
    }

    public function signUpVerify(Request $request, Response $response, $args) {
        /*  사용자가 nonce link를 누른 후부터 진행되는 sign up의 인증 과정
        **
        */
        if ($this->verifyNonceAndChangeIsVerify($_GET['user_name'], $_GET['nonce']) != 0){
            echo "<script> alert('verifyNonceAndChangeIsVerify Query error.')
            window.location = '/signup'</script>";
            exit;
        }

        
        // password_hash()
    }
}
