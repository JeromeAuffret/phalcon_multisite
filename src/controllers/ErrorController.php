<?php

namespace Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;


class ErrorController extends Controller
{
    /**
     *
     */
    public function NotFoundAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir(COMMON_PATH . '/views/');
        $this->view->render('errors', '404');

        $this->response->setStatusCode(404, 'Not Found');
    }

    /**
     *
     */
    public function InternalErrorAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir(COMMON_PATH . '/views/');
        $this->view->render('errors', '500');

        $this->response->setStatusCode(500, 'Internal Server Error');
    }

}