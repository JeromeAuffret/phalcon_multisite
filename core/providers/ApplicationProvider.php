<?php

namespace Provider;

use Middleware\Acl as AclMiddleware;
use Middleware\Auth as AuthMiddleware;
use Middleware\Dispatch as DispatchMiddleware;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class ApplicationProvider
 *
 * @package Provider
 */
class ApplicationProvider implements ModuleDefinitionInterface
{

    /**
     * Initialize application providers.
     * This register specific namespaces and services for an application.
     *
     * @param DiInterface|null $container
     */
    public function initialize(DiInterface $container)
    {
        $this->registerAutoloaders($container);
        $this->registerServices($container);
        $this->registerRouter($container);
        $this->registerAcl($container);
        $this->registerEvents($container);
    }

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Controllers' => BASE_PATH . '/src/controllers',
                'Models'      => BASE_PATH . '/src/models',
                'Forms'       => BASE_PATH . '/src/forms'
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

        // Register default namespace
        $container->get('dispatcher')->registerDefaultNamespace();
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

    /**
     * Register events related to the module
     * Events are only bind when module is dispatch
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        $eventManager = $container->get('dispatcher')->getEventsManager();

        $eventManager->attach('dispatch', new AuthMiddleware);
        $eventManager->attach("dispatch", new AclMiddleware);
    }

}
