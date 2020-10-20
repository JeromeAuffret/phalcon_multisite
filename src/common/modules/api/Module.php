<?php

namespace Common\Modules\Api;

use Acl\AclComponent;
use Acl\AclUserRole;
use Phalcon\Di\DiInterface;
use Phalcon\Helper\Str;
use Provider\ModuleProvider;


class Module extends ModuleProvider
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Common\Modules\Api\Controllers'      => __DIR__ . '/controllers/',
                'Common\Modules\Api\Controllers\Data' => __DIR__ . '/controllers/data/',
                'Common\Modules\Api\Controllers\Form' => __DIR__ . '/controllers/form/',
            ])
            ->register();
    }

    /**
     * Register specific routes for API module
     *
     * @param DiInterface $container
     */
    public function registerRouter(DiInterface $container)
    {
        $router = $container->get('router');
        $router
            ->add('/api/{reference}/:controller/:action/:params', [
                'namespace' => $this->controllerNamespace,
                'module' => 'api',
                'controller' => 2,
                'action' => 3,
                'params' => 4
            ]);
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container)
    {
        $acl = $container->get('acl');

        // Roles
        $acl->addRole('admin');
        $acl->addRole('user');

        // Components
        $acl->addComponent('api_data', ['get', 'create', 'update', 'delete']);
        $acl->addComponent('api_form', ['index', 'get', 'create', 'update', 'delete']);

        // Rules
        $acl->allow('admin', 'api_data', '*');
        $acl->allow('admin', 'api_form', '*');

        $acl->allow('user', 'api_data', '*');
        $acl->allow('user', 'api_form', '*');

        // By default, prevent 'user' role to use DELETE method from api
        $acl->allow('user', 'api_data', '*', function (AclUserRole $AclUserRole, AclComponent $AclComponent) {
            return $AclComponent->getMethod() !== 'DELETE';
        });

        $acl->allow('user', 'api_form', '*', function (AclUserRole $AclUserRole, AclComponent $AclComponent) {
            return $AclComponent->getMethod() !== 'DELETE';
        });

    }

    /**
     * @Override
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        // Register events in dispatcher service
        $container->get('dispatcher')
            ->getEventsManager()
            ->attach('dispatch:beforeDispatch', function () use ($container) {
                $this->dispatchController($container);
            });
    }

    /**
     * @Override
     *
     * Specific controller dispatch for api module
     * Register correct controller in dispatcher
     *
     * @param DiInterface $container
     */
    private function dispatchController(DiInterface $container)
    {
        $dispatcher = $container->get('dispatcher');
        $router = $container->get('router');
        $application = $container->get('application');

        $moduleName = $router->getModuleName();
        $controllerName = $router->getControllerName();
        $referenceName = $router->getParams()['reference'];
        $referenceControllerFile = Str::camelize($referenceName).'Controller.php';

        $appControllerModulePath = $application->getApplicationModulePath($moduleName).'/controllers/'.$controllerName;
        $commonControllerModulePath = $application->getCommonModulePath($moduleName).'/controllers/'.$controllerName;

        if ($controllerName === 'error') {
            $dispatcher->setNamespaceName('Controllers');
        }
        else if ($application && file_exists($appControllerModulePath.'/'.$referenceControllerFile)) {
            $namespace = $application->getApplicationModulePath($moduleName).'\Controllers\\'.$controllerName;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $appControllerModulePath])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($referenceName);
        }
        else if (file_exists($commonControllerModulePath.'/'.$referenceControllerFile)) {
            $namespace = $application->getCommonModulePath($moduleName).'\Controllers\\'.$controllerName;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $commonControllerModulePath])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($referenceName);
        }
    }
}
