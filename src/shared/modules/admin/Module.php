<?php

namespace Base\Modules\Admin;

use Phalcon\Di\DiInterface;
use Core\Providers\ModuleProvider;

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
     * Registers Services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        $router = $container->get('router');
        $router->add('/admin/reference/{reference}/:params', [
            'namespace' => $this->getModuleNamespace(),
            'module' => 'admin',
            'controller' => 'reference',
            'action' => 'index',
            'params' => 2
        ]);

        $router->add('/admin/reference/{reference}/:action/:params', [
            'namespace' => $this->getModuleNamespace(),
            'module' => 'admin',
            'controller' => 'reference',
            'action' => 2,
            'params' => 3
        ]);

        $container->get('acl')->registerAclFromFile(__DIR__.'/config/acl.php');
    }

    /**
     * Register events related to the module
     * This method is call only in the module's afterStart event
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
