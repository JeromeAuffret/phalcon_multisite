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
            $database = new DatabaseComponent();
            $database->registerMainDatabase();

            return $database;
        });

        // Register applications database
        if ($container->get('application')->hasApplication())
        {
            $container->get('database')->registerApplicationDatabase(
                $container->get('application')->getApplicationSlug()
            );
        }
    }

}