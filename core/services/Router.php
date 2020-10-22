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
            $application = $container->get('application');

            // TODO this should be refactored in a RouterComponent
            $defaultNamespace = $application->hasApplication() ? $application->getApplicationNamespace() : $application->getCommonNamespace();

            $router->setDefaultNamespace($defaultNamespace.'\\Controllers');
            $router->setDefaultController($config->defaultController);
            $router->setDefaultAction($config->defaultAction);

            return $router;
        });
    }

}