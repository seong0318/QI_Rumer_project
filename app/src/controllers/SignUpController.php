<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignUpController extends BaseController {
    public function signUp(Request $request, Response $response, $args) {
        $this->view->render($response, 'sign_up.twig', ['user_name'=>$user_name, 'email'=>$email, 'pwd'=>$pwd, 'pwd_confirm'=>$pwd_confirm, 'agree'=>$agree]);
    }

    public function signUpHandle(Request $request, Response $response, $args) {
        // print_r($_POST);
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $pwd_confirm = $_POST['pwd_confirm'];

        // if ($pwd != $pwd_confirm) {
        //     echo "입력한 비밀번호와 비밀번호 확인이 다릅니다.";
        //     echo "<a href=signup>back page</a>";
        //     exit();
        // }

        exit;
    }
}
