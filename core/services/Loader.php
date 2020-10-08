<?php

namespace Service;

use Component\Loader as LoaderComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Loader
 *
 * @package Service
 */
class Loader implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('loader', function () {
            $loader = new LoaderComponent();
            $loader->registerMainNamespaces();

            return $loader;
        });

        // Register application namespaces
        if ($container->get('application')->hasApplication())
        {
            $container->get('loader')->registerApplicationNamespaces(
                $container->get('application')->getApplicationSlug()
            );
        }
    }

}