<?php

namespace Base;

use Middleware\Acl as AclMiddleware;
use Middlewares\Auth as AuthMiddleware;
use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

/**
 * Class Application
 *
 * @package Base
 */
abstract class Application extends ApplicationProvider
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
                'Base\Controllers' => __DIR__ . '/controllers',
                'Base\Models'      => __DIR__ . '/models',
                'Base\Forms'       => __DIR__ . '/forms',
            ])
            ->register();
    }

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

    /**
     * Register router related to the application
     *
     * @param DiInterface $container
     */
    public function registerRouter(DiInterface $container) {}

    /**
     * Register acl rules related to the application
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container) {}

}