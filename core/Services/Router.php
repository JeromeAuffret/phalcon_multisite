<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router as MvcRouter;

/**
 * Class Router
 *
 * @package Core\Services
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
            $router =  new MvcRouter();

            $router->setDI($container);

            $config = $container->get('config');
            $application = $container->get('application');

            // We set defaultNamespace as tenant namespace if exist
            $defaultNamespace = $application->hasTenant() ? $application->getTenantNamespace() : $application->getBaseNamespace();

            $router->setDefaultNamespace($defaultNamespace.'\\Controllers');
            $router->setDefaultController($config->defaultController);
            $router->setDefaultAction($config->defaultAction);

            return $router;
        });
    }

}