<?php

namespace Common\Modules\Auth\Controllers;

use Models\User;
use Error\AuthException;
use Exception;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\View;


class LoginController extends ControllerBase
{
    /**
     * Disabled layout level
     */
    public function initialize()
    {
        // The login page does not use layout level
        $this->view->disableLevel(
            View::LEVEL_LAYOUT
        );
    }

    /**
     *  Login page
     *  Destroy session if exist
     *
     * @return void|Response|ResponseInterface
     * @throws Exception
     */
    public function indexAction()
    {
        if ($this->request->isPost())
        {
            $login = $this->request->getPost('login');
            $password = $this->request->getPost('password');

            $user = User::checkConnexion($login, $password);
            if (!$user) {
                throw new AuthException('User Not Found', 1);
            }

            $this->session->setupUserSession($user);

            $this->response->redirect('auth/application');
        }
    }

}

