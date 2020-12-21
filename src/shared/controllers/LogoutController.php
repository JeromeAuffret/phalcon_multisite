<?php

namespace Base\Controllers;

use Phalcon\Mvc\Controller;

/**
 * Class LogoutController
 *
 * @package Controllers
 */
final class LogoutController extends Controller
{
    
    /**
     *
     */
    public function indexAction()
    {
        if ($this->di->has('session')) {
            $this->session->destroy();
        }

        $this->response->redirect('');
        $this->response->send();
        exit();
    }

}