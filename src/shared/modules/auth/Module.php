<?php

namespace Base\Modules\Auth;

use Phalcon\Di\DiInterface;
use Core\Providers\ModuleProvider;

/**
 * Class Module
 *
 * @package Modules\Auth
 */
class Module extends ModuleProvider
{

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Modules\Auth\Controllers' => __DIR__ . '/controllers/'
            ])
            ->register();
    }

    /**
     * Registers Services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register events related to the module
     * This method is call only in the module's afterStart event
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

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
        $acl->addComponent('auth_application', ['index']);

        // Rules
        $acl->allow('guest', 'auth_application', '*');
    }

}
