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
     *  Login page
     *  Destroy session if exist
     *
     * @return void|Response|ResponseInterface
     * @throws Exception
     */
    public function indexAction()
    {
        if ($this->request->isGet() && $this->session->hasUser())
        {
            // We destroy session on login page.
            $this->session->destroy();

            // The login page does not use layout level
            $this->view->disableLevel(
                View::LEVEL_LAYOUT
            );
        }

        // Register User
        else if ($this->request->isPost()) //  && $this->security->checkToken()
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

