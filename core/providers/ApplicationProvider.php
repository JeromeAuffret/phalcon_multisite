<?php

namespace Provider;

use Middleware\Acl as AclMiddleware;
use Middleware\Auth as AuthMiddleware;
use Middleware\Error as ErrorMiddleware;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class ApplicationProvider  implements ModuleDefinitionInterface
{

    /**
     * @param DiInterface $container
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

        // Register application specific modules
        if ($container->get('config')->get('applicationType') === 'modules') {
            $container->get('application')->registerModulesProvider();
        }
        // Register default namespace if simple application
        else if ($container->get('config')->get('applicationType') === 'simple') {
            $container->get('dispatcher')->setDefaultNamespace(
                $container->get('application')->getApplicationNamespace().'\Controllers'
            );
        }
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
    public function registerAcl(DiInterface $container)
    {
        $acl = $container->get('acl');

        $acl->addRole('admin');
        $acl->addRole('user');

        // Allow access to error's pages
        $acl->addComponent('_error', ['NotFound', 'InternalError']);
        $acl->allow('*', '_error', '*');
    }

    /**
     * Register events related to the module
     * Events are only bind when module is dispatch
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        // Register eventsManager in dispatcher service
        $container->get('dispatcher')->setEventsManager(
            $container->get('eventsManager')
        );

        $container->get('dispatcher')
            ->getEventsManager()
            ->attach('dispatch:beforeExecuteRoute', new AuthMiddleware());

        $container->get('dispatcher')
            ->getEventsManager()
            ->attach("dispatch:beforeExecuteRoute", new AclMiddleware());

        $container->get('dispatcher')
            ->getEventsManager()
            ->attach("dispatch:beforeException", new ErrorMiddleware());

    }

}
