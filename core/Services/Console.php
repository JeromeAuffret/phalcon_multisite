<?php

namespace Core\Services;

use Core\Components\Console as ConsoleComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Config
 *
 * @package Core\Services
 */
class Console implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('console', function () use ($di) {
            $console = new ConsoleComponent();
            $console->setDi($di);

            return $console;
        });
    }

}