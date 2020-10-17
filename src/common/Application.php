<?php

namespace Common;

use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

class Application extends ApplicationProvider {


    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        parent::registerAutoloaders();

        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Common\\Models'      => COMMON_PATH . '/models/',
                'Common\\Traits'      => COMMON_PATH . '/traits/',
                'Common\\Controllers' => COMMON_PATH . '/controllers/',
                'Common\\Forms'       => COMMON_PATH . '/forms/'
            ])
            ->register();
    }
}
