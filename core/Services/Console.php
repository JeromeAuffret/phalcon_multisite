<?php

namespace Core\Services;

use Core\Components\Config as ConfigComponent;
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
            $console = new \Phalcon\Cli\Console();
            $console->setDi($di);

            return $console;
        });
    }

}