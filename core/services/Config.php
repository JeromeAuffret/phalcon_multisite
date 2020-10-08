<?php

namespace Service;

use Component\Config as ConfigComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Config
 *
 * @package Service
 */
class Config implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('config', function () {
            $config = new ConfigComponent();
            $config->registerMainConfig();

            return $config;
        });

        // Register applications config
        if ($container->get('application')->hasApplication())
        {
            $container->get('config')->registerApplicationConfig(
                $container->get('application')->getApplicationSlug()
            );
        }
    }

}