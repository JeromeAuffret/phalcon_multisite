<?php

namespace Demo1;

use Middleware\Acl as AclMiddleware;
use Middlewares\Auth as AuthMiddleware;
use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

class Application extends ApplicationProvider
{

    /**
     * Registers services related to the application
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        // Register Application Config
        $container->get('config')->registerApplicationConfig();

        // Register Application Database
        $container->get('database')->registerApplicationDatabase();

        // Register Application Modules
        $container->get('application')->registerModulesProvider();
    }

    /**
     * Register events related to the application
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        $eventsManager = $container->get('eventsManager');
        $eventsManager->attach("dispatch", new AclMiddleware);
        $eventsManager->attach("dispatch", new AuthMiddleware);
    }

}
