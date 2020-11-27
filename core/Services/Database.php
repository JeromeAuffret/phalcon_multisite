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
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('database', function () use ($container) {
            $database = new DatabaseComponent();
            $database->registerMainDb($container);

            return $database;
        });
    }

}