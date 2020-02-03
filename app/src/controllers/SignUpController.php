<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

include '../app/src/util.php';

final class SignUpController extends BaseController
{
    public function duplicateUser($username)
    {
        /*  사용자의 이름으로 중복된 ID가 존재하는지 확인.
        **  반환값은 cont(usn)을 index로 가지는 배열로 반환
        */
        try {
            $sql = "select count(usn) from user where (user_name = :username)";
            $stmt = $this->em->getConnection()->prepare($sql);
            $params['username'] = $username;
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            echo '{"error": {"text": ' . $e->getMessage() . '}';
        }
    }

    public function storeUsernameAndNonce($username, $nonce)
    {
        try {
            $sql = "insert into temp_user values (:username, :nonce, '0')";
            $stmt = $this->em->getConnection()->prepare($sql);
            $params = [
                'username' => $username,
                'nonce' => $nonce
            ];
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo '{"error": {"text": ' . $e->getMessage() . '}';
        }
    }

    public function signUp(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'sign_up.twig', ['user_name' => $user_name, 'email' => $email, 'pwd' => $pwd, 'pwd_confirm' => $pwd_confirm, 'agree' => $agree]);
    }

    public function signUpHandle(Request $request, Response $response, $args)
    {
        $isDup = $this->duplicateUser($_POST['user_name']);

        if ($isDup['count(usn)'] != 0) {
            echo "<script> alert('This username is already exists.')
            window.location = '/signup'</script>";
        }

        $nonce = makeRandomString(); // make nonce link
        // $this->storeUsernameAndNonce($_POST['user_name'], $nonce);
        echo "<script> alert('We will send you a confirmation email, so please check it.') 
        window.location = '/signin'</script>";
        sendMail($_POST['email'], $nonce);
    }

    public function signUpVerify(Request $request, Response $response, $args)
    {
        echo "ffffffffffffffffffff";
    }
}
