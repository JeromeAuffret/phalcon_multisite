<?php

namespace Base\Modules\Auth\Controllers;

use Base\Models\User;
use Core\Errors\AuthException;
use Exception;
use Phalcon\Collection;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\View;

/**
 * Class LoginController
 *
 * @package Base\Modules\Auth\Controllers
 */
class LoginController extends ControllerBase
{
    /**
     * Disabled layout level
     */
    public function initialize()
    {
        $this->view->disableLevel(
            View::LEVEL_LAYOUT
        );
    }

    /**
     *  Login page
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

            if ($this->di->has('session') && $this->session->exists()) {
                $this->session->set('user', new Collection($user->toArray()));
            }

            $this->response->redirect('auth/application');
        }
    }

}

