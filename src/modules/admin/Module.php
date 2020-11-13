<?php

namespace Base\Modules\Admin;

use Base\Module as BaseModule;
use Phalcon\Di\DiInterface;


/**
 * Class Module
 *
 * @package Base\Modules\Admin
 */
class Module extends BaseModule
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



}
