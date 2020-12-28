<?php

namespace Base\Controllers;

use Core\Controllers\Controller;

/**
 * Class LogoutController
 *
 * @package Controllers
 */
class LogoutController extends Controller
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