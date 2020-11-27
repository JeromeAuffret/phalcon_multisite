<?php

namespace Core\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class TenantProvider
 *
 * @package Provider
 */
abstract class TenantProvider implements ModuleDefinitionInterface
{

    /**
     * This register specific namespaces and Services for an application.
     *
     * @param DiInterface $container
     */
    public function __construct(DiInterface $container)
    {
        // Register Autoloader
        $this->registerAutoloaders($container);

        // Register Services
        $this->registerServices($container);
    }

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    abstract public function registerAutoloaders(DiInterface $container = null);

    /**
     * Registers Services related to the application
     *
     * @param DiInterface $container
     */
    abstract public function registerServices(DiInterface $container);

}
