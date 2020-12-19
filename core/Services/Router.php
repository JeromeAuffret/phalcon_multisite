<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Router
 *
 * @package Core\Services
 */
class Router implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('router', function () use ($di) {
            $router =  new \Phalcon\Mvc\Router();
            $router->setDI($di);

            $config = $di->get('config');
            $application = $di->get('application');

            // We set tenant namespace as defaultNamespace if exist
            $defaultNamespace = $application->hasTenant() ? $application->getTenantNamespace() : $application->getBaseNamespace();

            $router->setDefaultNamespace($defaultNamespace.'\\Controllers');
            $router->setDefaultController($config->defaultController);
            $router->setDefaultAction($config->defaultAction);

            return $router;
        });
    }

}