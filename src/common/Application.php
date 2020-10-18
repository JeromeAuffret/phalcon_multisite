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
                'Common\\Models'      => $container->get('application')->getCommonPath() . '/models/',
                'Common\\Traits'      => $container->get('application')->getCommonPath() . '/traits/',
                'Common\\Controllers' => $container->get('application')->getCommonPath() . '/controllers/',
                'Common\\Forms'       => $container->get('application')->getCommonPath() . '/forms/'
            ])
            ->register();
    }
}
