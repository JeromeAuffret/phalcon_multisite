<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Url
 *
 * @package Core\Services
 */
class DispatcherCli implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('dispatcher', function () use ($container) {
            $dispatcher = new \Phalcon\Cli\Dispatcher();

            $dispatcher->setDefaultNamespace('Core\Tasks');

            return $dispatcher;
        });
    }

}