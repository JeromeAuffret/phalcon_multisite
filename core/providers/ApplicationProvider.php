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
        $commonPath = $container->get('application')->getCommonPath();

        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Controllers' => BASE_PATH . '/src/controllers',
                'Models'      => BASE_PATH . '/src/models',
                'Forms'       => BASE_PATH . '/src/forms',

                'Common\\Acl' => $commonPath . '/acl'
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
