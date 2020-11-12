<?php

namespace Base\Modules\Admin;

use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;

/**
 * Class Module
 *
 * @package Base\Modules\Admin
 */
class Module extends ModuleProvider
{

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Modules\Admin\Controllers' => __DIR__ . '/controllers/',
                'Base\Modules\Admin\Models'      => __DIR__ . '/models/',
                'Base\Modules\Admin\Forms'       => __DIR__ . '/forms/',
            ])
            ->register();
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerRouter(DiInterface $container)
    {
        $container->get('router')->registerRouterFromFile(__DIR__.'/config/routes.php');
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container)
    {
        $container->get('acl')->registerAclFromFile(__DIR__.'/config/acl.php');
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register events related to the module
     * Events are bind only in module dispatch loop
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
