<?php

namespace Demo2;

use Common\Application as CommonProvider;
use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

/**
 * Class Application
 *
 * @package Demo2
 */
class Application extends CommonProvider
{

    /**
     *  Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        parent::registerAutoloaders($container);

        (new \Phalcon\Loader())
            ->registerNamespaces([
                "Demo2\\Controllers" => __DIR__.'/controllers'
            ])
            ->register();
    }

}
