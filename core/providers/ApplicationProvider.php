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
        // Register Autoloader
        $this->registerAutoloaders($container);

        // Register services
        $this->registerServices($container);
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

}
