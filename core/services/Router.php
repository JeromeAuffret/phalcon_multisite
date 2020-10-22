<?php

namespace Service;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Router
 *
 * @package Service
 */
class Router implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('router', function () use ($container) {
            $router =  new \Phalcon\Mvc\Router();

            $config = $container->get('config');

            if ($config->get('applicationType') === 'modules') {
                $moduleName = $config->get('defaultModule');
                $router->setDefaultModule($moduleName);

                $controllerName = $config->get('modules')[$moduleName]['defaultController'] ?? $config->defaultController;
                $actionName = $config->get('modules')[$moduleName]['defaultAction'] ?? $config->defaultAction;
            }
            else {
                $controllerName = $config->defaultController;
                $actionName = $config->defaultAction;
            }

            $router->setDefaultController($controllerName);
            $router->setDefaultAction($actionName);

            return $router;
        });
    }

}