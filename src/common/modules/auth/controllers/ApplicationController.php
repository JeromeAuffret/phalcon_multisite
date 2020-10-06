<?php

namespace Common\Modules\Auth\Controllers;

use Models\Application;
use Error\AuthException;
use Exception;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;


class ApplicationController extends ControllerBase
{
    /**
     * @return void|Response|ResponseInterface
     */
    public function indexAction()
    {
        // Destroy application session on application page
        if ($this->session->hasApplication()) {
            $this->session->destroyApplication();
            $this->response->redirect('auth/application');
        }
        else {
            $this->view->setVar('application_list',
                $this->acl->isSuperAdmin() ? Application::find() : Application::getUserApplicationList()
            );
        }
    }

    /**
     * @param int $id_application
     * @return void|Response|ResponseInterface
     * @throws Exception
     */
    public function switchApplicationAction(int $id_application)
    {
        if (!$application = Application::findFirst($id_application)) {
            throw new AuthException('Application Not Found');
        }

        $this->session->setupApplicationSession($application);

        $this->response->redirect('');
    }

}

