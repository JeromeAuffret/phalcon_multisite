<?php

namespace Provider;

use Phalcon\Di\DiInterface;


class ApplicationProvider
{
    /**
     * @param DiInterface $container
     */
    public function initialize(DiInterface $container)
    {
        $this->registerAutoloader($container);

        $this->registerServices($container);

        $this->registerRouter($container);
    }

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    protected function registerAutoloader(DiInterface $container)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Controllers' => BASE_PATH . '/src/controllers',
                'Models'      => BASE_PATH . '/src/models',
                'Forms'       => BASE_PATH . '/src/forms',

                'Common'              => COMMON_PATH,
                'Common\\Models'      => COMMON_PATH . '/models/',
                'Common\\Traits'      => COMMON_PATH . '/traits/',
                'Common\\Controllers' => COMMON_PATH . '/controllers/',
                'Common\\Forms'       => COMMON_PATH . '/forms/'
            ])
            ->register();
    }

    /**
     * Registers services related to the application
     *
     * @param DiInterface $container
     */
    protected function registerServices(DiInterface $container)
    {
        // Register Application Config
        $container->get('config')->registerApplicationConfig();

        // Register Application Database
        $container->get('database')->registerApplicationDatabase();

        // Register Application Database
        $container->get('acl')->registerApplicationAcl();

        // Register Application routes
        $container->get('router')->registerRouter();

        // Register application specific modules
        $container->get('application')->registerModulesProvider();
    }

    /**
     * Register specific application router
     *
     * @param DiInterface $container
     */
    protected function registerRouter(DiInterface $container) {}

}
