<?php

namespace Base;

use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;

/**
 * Class Application
 *
 * @package Base
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
                $this->controllerNamespace => $this->modulePath.'/controllers'
            ])
            ->register();
    }

    /**
     * Registers services related to the application
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register events related to the application
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

    /**
     * Register router related to the application
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
     * Register acl rules related to the application
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container)
    {
        $container->get('acl')->registerAclFromFile(__DIR__.'/config/acl.php');
    }

}