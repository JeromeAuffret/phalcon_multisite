<?php

namespace Demo1\Modules\Product;

use Phalcon\Di\DiInterface;
use Core\Providers\ModuleProvider;

/**
 * Class Module
 *
 * @package Demo1\Modules\Product
 */
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
                'Demo1\Modules\Product\Controllers' => __DIR__ . '/controllers/',
                'Demo1\Modules\Product\Tasks'       => __DIR__ . '/tasks/'
            ])
            ->register();
    }

    /**
     * Registers Services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        // Register Router
        $this->registerRouter($container);

        // Register Acl
        $this->registerAcl($container);
    }

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
    private function registerAcl(DiInterface $container)
    {
        $acl = $container->get('acl');

        // Roles
        $acl->addRole('admin');
        $acl->addRole('user');

        // Components
        $acl->addComponent('product_index', ['index']);

        // Rules
        $acl->allow('admin', 'product_index', '*');
        $acl->allow('user', 'product_index', '*');
    }

    /**
     * Register router related to the application
     *
     * @param DiInterface $container
     */
    private function registerRouter(DiInterface $container)
    {
        $this->registerDefaultRoutes($container);
    }

}
