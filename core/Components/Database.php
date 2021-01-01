<?php

namespace Core\Components;

use Phalcon\Di\DiInterface;
use Phalcon\Di\Injectable;

/**
 * Class Database
 *
 * @package Core\Components
 */
final class Database extends Injectable
{

    /**
     * Register main database
     *
     * @param DiInterface $container
     */
    public function registerMainDb(DiInterface $container)
    {
        $container->setShared('main_db', function () use ($container)
        {
            $database = $container->get('config')->get('main_database');

            $class = Database::parseDbAdapter($database);
            $params = Database::parseDbParameters($database);

            return new $class($params);
        });
    }

    /**
     * Specific database connection for the registered application
     *
     * @param DiInterface $container
     * @return void
     */
    public function registerTenantDb(DiInterface $container)
    {
        $container->remove('db');

        $container->setShared('db', function() use ($container)
        {
            $database = $container->get('config')->get('database');

            $class = Database::parseDbAdapter($database);
            $params = Database::parseDbParameters($database);

            return new $class($params);
        });
    }

    /**
     * @param \Phalcon\Config $database
     * @return string
     */
    public static function parseDbAdapter(\Phalcon\Config $database): string
    {
        return 'Phalcon\Db\Adapter\Pdo\\' . $database->get('adapter');
    }

    /**
     * @param \Phalcon\Config $database
     * @return array
     */
    public static function parseDbParameters(\Phalcon\Config $database)
    {
        $params = [
            'host'     => $database->get('host'),
            'username' => $database->get('username'),
            'password' => $database->get('password'),
            'dbname'   => $database->get('dbname')
        ];

        if ($database->has('port')) {
            $params['port'] = $database->get('port');
        }

        if ($database->has('charset') && $database->get('adapter') !== 'Postgresql') {
            $params['charset'] = $database->get('charset');
        }

        return $params;
    }

}