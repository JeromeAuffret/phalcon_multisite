<?php

namespace Demo1;

use Core\Middlewares\Acl as AclMiddleware;
use Core\Middlewares\Auth as AuthMiddleware;
use Phalcon\Di\DiInterface;
use Core\Providers\TenantProvider;

/**
 * Class Tenant
 *
 * @package Demo1
 */
class Tenant extends TenantProvider
{

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Controllers' => __DIR__ . '/controllers',
                'Base\Models'      => __DIR__ . '/models',
                'Base\Forms'       => __DIR__ . '/forms',
            ])
            ->register();
    }

    /**
     * Registers Services related to the application
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        // Register Tenant Config
        $container->get('config')->registerTenantConfig($container);

        // Register Tenant Database
        $container->get('database')->registerTenantDb($container);

        // Register Tenant Modules
        $container->get('application')->registerModulesProvider();

        // Events
        $container->get('eventsManager')->attach("dispatch", new AclMiddleware);
        $container->get('eventsManager')->attach("dispatch", new AuthMiddleware);
    }

}