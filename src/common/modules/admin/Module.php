<?php

namespace Common\Modules\Admin;

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
                'Common\Modules\Admin\Controllers' => __DIR__ . '/controllers/',
                'Common\Modules\Admin\Models'      => __DIR__ . '/models/',
                'Common\Modules\Admin\Forms'       => __DIR__ . '/forms/',
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
        $acl->addComponent('admin_index', ['index']);
        $acl->addComponent('admin_profile', ['index']);
        $acl->addComponent('admin_user', ['index']);

        // Rules
        $acl->allow('admin', 'admin_index', '*');
        $acl->allow('admin', 'admin_profile', '*');
        $acl->allow('admin', 'admin_user', '*');

        $acl->allow('user', 'admin_profile', '*');
    }

}
