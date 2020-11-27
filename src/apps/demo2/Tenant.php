<?php

namespace Demo2;

use Phalcon\Di\DiInterface;
use Core\Providers\TenantProvider;

/**
 * Class Tenant
 *
 * @package Demo2
 */
class Tenant extends TenantProvider
{

    /**
     *  Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Controllers'  => __DIR__ . '/controllers',
                'Demo2\Controllers' => __DIR__ . '/controllers'
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
    }

}
