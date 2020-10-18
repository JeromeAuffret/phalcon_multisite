<?php

namespace Common\Modules\Api;

use Acl\AclComponent;
use Acl\AclUserRole;
use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;


class Module  extends ModuleProvider
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
     * @param $moduleName
     * @param $module
     */
    public function registerRouter(DiInterface $container, $moduleName, $module)
    {
        $namespace = preg_replace('/Module$/', 'Controllers', $module->get("className"));

        $router = $container->get('router');
        $router
            ->add('/api/{reference}/:controller/:action/:params', [
                'namespace' => $namespace,
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
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        $container->get('dispatcher')
            ->getEventsManager()
            ->attach('dispatch:beforeDispatch', function () use($container) {
                $container->get('application')
                    ->dispatchApiController($container->get('dispatcher'), $container->get('router'));
            });
    }

}
