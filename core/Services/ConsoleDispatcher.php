<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Url
 *
 * @package Core\Services
 */
class ConsoleDispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () {
            $dispatcher = new \Phalcon\Cli\Dispatcher();

            $dispatcher->setDefaultNamespace('Core\Tasks');
            $dispatcher->setDefaultTask('main');
            $dispatcher->setDefaultAction('main');

            return $dispatcher;
        });
    }

}