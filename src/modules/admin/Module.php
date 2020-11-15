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
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        $container->get('router')->registerRouterFromFile(__DIR__.'/config/routes.php');
        $container->get('acl')->registerAclFromFile(__DIR__.'/config/acl.php');
    }

    /**
     * Register events related to the module
     * This method is call only in the after start module event
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
