<?php

namespace Common;

use Middleware\Acl as AclMiddleware;
use Middlewares\Auth as AuthMiddleware;
use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

/**
 * Class Application
 *
 * @package Common
 */
class Application extends ApplicationProvider
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
                'Common\Controllers' => __DIR__ . '/controllers',
                'Common\Models'      => __DIR__ . '/models',
                'Common\Forms'       => __DIR__ . '/forms',
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

}