<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMtP;
use PHPMailer\PHPMailer\Exception;

final class HomeController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args) {
        $this->logger->info("Home page action dispatched");

        $this->flash->addMessage('info', 'Sample flash message');

        $this->view->render($response, 'home.twig');
        return $response;
    }

    public function signUp(Request $request, Response $response, $args) {
        $this->view->render($response, 'sign_up.twig', ['username'=>$username, 'email'=>$email, 'pwd'=>$pwd, 'pwd_confirm'=>$pwd_confirm]);
    }

    public function signUpHandle(Request $request, Response $response, $args) {
        print_r($_POST);

        exit;
    }

    public function signIn(Request $request, Response $response, $args) {
        $this->view->render($response, 'sign_in.twig', ['username'=>$username, 'pwd'=>$pwd, 'remember'=>$remember]);
    }

    public function signInHandle(Request $request, Response $response, $args) {
        print_r($_POST);

        exit;
    }

    public function viewPost(Request $request, Response $response, $args) {
        $this->logger->info("View post using Doctrine with Slim 3");

        $messages = $this->flash->getMessage('info');

        try {
            $post = $this->em->find('App\Model\Post', intval($args['id']));
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

        $this->view->render($response, 'post.twig', ['post' => $post, 'flash' => $messages]);
        return $response;
    }
}
