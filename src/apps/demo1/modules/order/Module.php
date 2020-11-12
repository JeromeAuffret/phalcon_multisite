<?php

namespace Demo1\Modules\Order;

use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;

/**
 * Class Module
 *
 * @package Demo1\Modules\Order
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
                'Demo1\Modules\Order\Controllers' => __DIR__ . '/controllers/'
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
        $container->get('router')->registerModuleRoutes(
            $this->moduleName,
            $this->controllerNamespace,
            $this->defaultController,
            $this->defaultController,
            $this->defaultAction
        );
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
        $acl->addComponent('order_index', ['index']);

        // Rules
        $acl->allow('admin', 'order_index', '*');
        $acl->allow('user', 'order_index', '*');
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register events related to the module
     * Events are bind only in module dispatch loop
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
