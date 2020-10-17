<?php

namespace Demo1\Modules\Dashboard;

use Phalcon\Di\DiInterface;
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
                'Demo1\Modules\Dashboard\Controllers' => __DIR__ . '/controllers/'
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
        $acl->addRole('admin');
        $acl->addRole('user');

        // Components
        $acl->addComponent('dashboard_index', ['index']);

        // Rules
        $acl->allow('admin', 'dashboard_index', '*');
        $acl->allow('user', 'dashboard_index', '*');
    }

}
