<?php

namespace Provider;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class ApplicationProvider
 *
 * @package Provider
 */
abstract class ApplicationProvider implements ModuleDefinitionInterface
{

    /**
     * This register specific namespaces and services for an application.
     *
     * @param DiInterface $container
     */
    public function __construct(DiInterface $container)
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
    abstract public function registerAutoloaders(DiInterface $container = null);

    /**
     * Registers services related to the application
     *
     * @param DiInterface $container
     */
    abstract public function registerServices(DiInterface $container);

    /**
     * Register router related to the application
     *
     * @param DiInterface $container
     */
    abstract public function registerRouter(DiInterface $container);

    /**
     * Register acl rules related to the application
     *
     * @param DiInterface $container
     */
    abstract public function registerAcl(DiInterface $container);

    /**
     * Register events related to the application
     *
     * @param DiInterface $container
     */
    abstract public function registerEvents(DiInterface $container);

}
