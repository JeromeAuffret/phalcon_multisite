<?php

namespace Demo2;

use Base\Application as BaseProvider;
use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

/**
 * Class Application
 *
 * @package Demo2
 */
class Application extends BaseProvider
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
