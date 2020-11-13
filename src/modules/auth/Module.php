<?php

namespace Base\Modules\Auth;

use Base\Module as ModuleAlias;
use Phalcon\Di\DiInterface;

/**
 * Class Module
 *
 * @package Modules\Auth
 */
class Module extends ModuleAlias
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
                'Base\Modules\Auth\Controllers' => __DIR__ . '/controllers/'
            ])
            ->register();
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
