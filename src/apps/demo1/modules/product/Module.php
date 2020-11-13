<?php

namespace Demo1\Modules\Product;

use Base\Module as BaseModule;
use Phalcon\Di\DiInterface;

/**
 * Class Module
 *
 * @package Demo1\Modules\Product
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
                'Demo1\Modules\Product\Controllers' => __DIR__ . '/controllers/'
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
        $acl = $container->get('acl');

        // Roles
        $acl->addRole('admin');
        $acl->addRole('user');

        // Components
        $acl->addComponent('product_index', ['index']);

        // Rules
        $acl->allow('admin', 'product_index', '*');
        $acl->allow('user', 'product_index', '*');
    }

}
