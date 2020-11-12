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
     * @var DiInterface $container
     */
    protected $container;

    /**
     * This register specific namespaces and services for an application.
     *
     * @param DiInterface $container
     */
    public function __construct(DiInterface $container)
    {
        $this->container = $container;

        $this->registerAutoloaders($this->container);
        $this->registerServices($this->container);
        $this->registerRouter($this->container);
        $this->registerAcl($this->container);
        $this->registerEvents($this->container);
    }

    /**
     * Initialize application providers.
     */
    public function initialize() {}

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null) {}

    /**
     * Registers services related to the application
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

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
     * Register events related to the application
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
