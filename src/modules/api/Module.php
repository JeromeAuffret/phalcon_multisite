<?php

namespace Base\Modules\Api;

use Base\Module as BaseModule;
use Phalcon\Di\DiInterface;
use Phalcon\Helper\Str;

/**
 * Class Module
 *
 * @package Modules\Api
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
                'Base\Modules\Api\Controllers'      => __DIR__ . '/controllers/',
                'Base\Modules\Api\Controllers\Data' => __DIR__ . '/controllers/data/',
                'Base\Modules\Api\Controllers\Form' => __DIR__ . '/controllers/form/',
            ])
            ->register();
    }

    /**
     * Register specific routes for API module
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
     * Register correct controller in dispatcher
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
