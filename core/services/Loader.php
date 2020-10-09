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
            return new LoaderComponent();
        });

        // Register Main namespaces
        $container->get('loader')->registerMainNamespaces();

        // Register application namespaces
        if ($container->get('application')->hasApplication())
        {
            $container->get('loader')->registerApplicationNamespaces(
                $container->get('application')->getApplicationSlug()
            );
        }
    }

}