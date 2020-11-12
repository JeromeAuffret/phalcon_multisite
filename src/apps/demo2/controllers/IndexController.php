<?php

namespace Demo2\Controllers;

use Base\Controllers\BaseController;


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