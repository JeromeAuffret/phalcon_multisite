<?php

namespace Component;

use Phalcon\Di\Injectable;

/**
 * Class Database
 *
 * @package Component
 */
final class Database extends Injectable
{

    /**
     *  Register main database
     */
    public function registerMainDatabase()
    {
        $this->getDI()->setShared('main_db', function ()
        {
            $database = $this->get('config')->get('main_database');

            $class = Database::parseDatatableAdapter($database);
            $params = Database::parseDatatableParameters($database);

            return new $class($params);
        });
    }

    /**
     * Specific database connection for the registered application
     *
     * @return void
     */
    public function registerApplicationDatabase()
    {
        $this->getDI()->remove('db');

        $this->getDI()->setShared('db', function()
        {
            $database = $this->get('config')->get('database');

            $class = Database::parseDatatableAdapter($database);
            $params = Database::parseDatatableParameters($database);

            return new $class($params);
        });
    }

    /**
     * @param \Phalcon\Config $database
     * @return string
     */
    public static function parseDatatableAdapter(\Phalcon\Config $database): string
    {
        return 'Phalcon\Db\Adapter\Pdo\\' . $database->get('adapter');;
    }

    /**
     * @param \Phalcon\Config $database
     * @return array
     */
    public static function parseDatatableParameters(\Phalcon\Config $database)
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