<?php

namespace Base\Modules\Api;

use Phalcon\Di\DiInterface;
use Phalcon\Helper\Str;
use Provider\ModuleProvider;

/**
 * Class Module
 *
 * @package Modules\Api
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
                'Base\Modules\Api\Controllers'      => __DIR__ . '/controllers/',
                'Base\Modules\Api\Controllers\Data' => __DIR__ . '/controllers/data/',
                'Base\Modules\Api\Controllers\Form' => __DIR__ . '/controllers/form/',
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
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container)
    {
        // Register events in dispatcher service
        $container->get('dispatcher')
            ->getEventsManager()
            ->attach('dispatch:beforeDispatch', function () use ($container) {
                $this->dispatchController($container);
            });
    }

    /**
     * Specific controller dispatch for api module
     * This method is call only in the after start module event
     *
     * @param DiInterface $container
     */
    private function dispatchController(DiInterface $container)
    {
        $dispatcher = $container->get('dispatcher');
        $router = $container->get('router');
        $application = $container->get('application');

        $moduleName = $router->getModuleName();
        $controllerName = $router->getControllerName();
        $referenceName = $router->getParams()['reference'];
        $referenceControllerFile = Str::camelize($referenceName).'Controller.php';

        $appControllerModulePath = $application->getApplicationModulePath($moduleName).'/controllers/'.$controllerName;
        $baseControllerModulePath = $application->getBaseModulePath($moduleName).'/controllers/'.$controllerName;

        if ($controllerName === 'error') {
            $dispatcher->setNamespaceName('Controllers');
        }
        else if ($application && file_exists($appControllerModulePath.'/'.$referenceControllerFile)) {
            $namespace = $application->getApplicationModulePath($moduleName).'\Controllers\\'.$controllerName;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $appControllerModulePath])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($referenceName);
        }
        else if (file_exists($baseControllerModulePath.'/'.$referenceControllerFile)) {
            $namespace = $application->getBaseModulePath($moduleName).'\Controllers\\'.$controllerName;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $baseControllerModulePath])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($referenceName);
        }
    }

}
