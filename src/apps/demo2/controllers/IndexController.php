<?php

namespace Demo2\Controllers;

use Base\Controllers\BaseController;

/**
 * Class IndexController
 *
 * @package Demo2\Controllers
 */
class IndexController extends BaseController
{

    /**
     *
     */
    public function indexAction() {}

    /**
     *
     */
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('auth/login');

        return $this->response;
    }

}