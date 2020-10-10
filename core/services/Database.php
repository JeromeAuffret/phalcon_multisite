<?php

namespace Service;

use Component\Database as DatabaseComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Database
 *
 * @package Service
 */
class Database implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('database', function () {
            return new DatabaseComponent();
        });

        // Register main database
        $container->get('database')->registerMainDatabase();

        // Register applications database
        if ($container->get('application')->hasApplication()) {
            $container->get('database')->registerApplicationDatabase();
        }
    }

}