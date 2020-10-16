<?php

namespace Common\Modules\Admin;

use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;


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
                'Common\Modules\Admin\Controllers' => __DIR__ . '/controllers/',
                'Common\Modules\Admin\Models'      => __DIR__ . '/models/',
                'Common\Modules\Admin\Forms'       => __DIR__ . '/forms/',
            ])
            ->register();
    }

}
