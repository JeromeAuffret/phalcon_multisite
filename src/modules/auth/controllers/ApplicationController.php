<?php

namespace Common\Modules\Auth\Controllers;

use Models\Application;
use Error\AuthException;
use Exception;
use Models\Role;
use Phalcon\Collection;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

/**
 * Class ApplicationController
 *
 * @package Modules\Auth\Controllers
 */
class ApplicationController extends ControllerBase
{

    /**
     * @return void|Response|ResponseInterface
     */
    public function indexAction()
    {
        // Remove application already registered in session
        if ($this->request->isGet() && $this->di->has('session') && $this->session->exists()) {
            $this->session->remove('application');
        }

        $id_user = $this->application->getUser('id');

        $this->view->setVar('application_list',
            $this->acl->isSuperAdmin() ? Application::find() : Application::getUserApplicationList($id_user)
        );
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

        if ($this->di->has('session') && $this->session->exists()) {
            $this->session->set('application', new Collection($application->toArray()));

            /** @var Role $roleModel */
            $roleModel = $this->dispatcher->dispatchNamespace(Role::class);
            $role = $roleModel::getUserRole($this->session->get('user')->get('id'), $this->session->get('application')->get('id'));
            $this->session->set('user_role', $role ? $role->getSlug() : 'guest');
        }

        $this->response->redirect('');
    }

}

