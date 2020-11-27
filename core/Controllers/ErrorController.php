<?php

namespace Core\Controllers;

use Core\Components\Application;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class ErrorController
 *
 * @property Application application
 * @package Controllers
 */
final class ErrorController extends Controller
{

    /**
     *
     */
    public function NotFoundAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir($this->application->getBasePath() . '/views/');
        $this->view->render('errors', '404');

        $this->response->setStatusCode(404, 'Not Found');
    }

    /**
     *
     */
    public function InternalErrorAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setViewsDir($this->application->getBasePath() . '/views/');
        $this->view->render('errors', '500');

        $this->response->setStatusCode(500, 'Internal Server Error');
    }

}