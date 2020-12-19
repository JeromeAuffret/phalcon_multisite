<?php

namespace Core\Services;

use Core\Components\Database as DatabaseComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Database
 *
 * @package Core\Services
 */
class Database implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('database', function () use ($di) {
            $database = new DatabaseComponent();
            $database->registerMainDb($di);

            return $database;
        });
    }

}