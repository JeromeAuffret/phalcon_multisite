<?php

namespace Demo2;

use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;


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
                "Demo2\\Controllers" => __DIR__.'/controllers'
            ])
            ->register();
    }

}
