<?php

namespace Demo2;

use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

/**
 * Class Application
 *
 * @package Demo2
 */
class Application extends ApplicationProvider
{

    /**
     *  Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Controllers'  => __DIR__ . '/controllers',
                'Demo2\Controllers' => __DIR__ . '/controllers'
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
    }

}
