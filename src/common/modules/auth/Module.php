<?php

namespace Common\Modules\Auth;

use Common\Acl\AclComponent;
use Common\Acl\AclUserRole;
use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;


class Module extends ModuleProvider
{

    /**
     *  Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        $modulePath = $container->get('application')->getCommonModulePath('auth');

        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Common\Modules\Auth\Controllers' => $modulePath . '/controllers/'
            ])
            ->register();
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
        $acl->addRole('guest');

        // Components
        $acl->addComponent('auth_login', ['index']);
        $acl->addComponent('auth_application', ['index', 'switchApplication']);

        // Rules
        // Allow every roles for user_login page
        $acl->allow('*', 'auth_login', 'index');

        // Only accept connected user
        $acl->allow('*', 'auth_application', ['index', 'switchApplication'],
            function (AclUserRole $AclUserRole, AclComponent $AclResource) {
                return $AclUserRole->getIdUser();
            }
        );
    }

}
