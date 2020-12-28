<?php

namespace Base\Controllers;

use Core\Controllers\Controller;
use Phalcon\Mvc\View;

/**
 * Class ErrorController
 *
 * @package Controllers
 */
class ErrorController extends Controller
{
    /**
     *
     */
    public function NotFoundAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir($this->application->getBasePath() . '/pages/');
        $this->view->render('errors', '404');

        $this->response->setStatusCode(404, 'Not Found');
    }

    /**
     *
     */
    public function InternalErrorAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir($this->application->getBasePath() . '/pages/');
        $this->view->render('errors', '500');

        $this->response->setStatusCode(500, 'Internal Server Error');
    }

}