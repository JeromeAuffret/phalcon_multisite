<?php

namespace Handler;

use Component\ServiceProvider as ServiceProviderComponent;
use Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application as MvcApplication;


class ApplicationHandler
{
    /**
     * @var MvcApplication
     */
    protected $application;
    /**
     * @var FactoryDefault
     */
    protected $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->registerDiContainer();
        $this->registerCoreNamespaces();
        $this->registerProviders();
        $this->registerMvcApplication();
    }

    /**
     * Handle application's response
     *
     * @return string`
     * @throws Exception
     */
    public function handle(): string
    {
        return (string) $this->application->handle(
            $this->container->get('config')->get('requestUri')
        )
            ->getContent();
    }

    /**
     * @return void
     */
    public function registerDiContainer()
    {
        $this->container = new FactoryDefault();
    }

    /**
     * @return void
     */
    public function registerCoreNamespaces()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Component'  => BASE_PATH . '/core/components',
                'Error'      => BASE_PATH . '/core/errors',
                'Middleware' => BASE_PATH . '/core/middlewares',
                'Service'    => BASE_PATH . '/core/services',
                'Acl'        => BASE_PATH . '/core/acl',
                'Libraries'  => BASE_PATH . '/libraries',
            ])
            ->register();
    }

    /**
     * @return void
     */
    public function registerProviders()
    {
        $serviceProvider = new ServiceProviderComponent($this->container);
        $serviceProvider->registerServices();
    }

    /**
     * @return void
     */
    public function registerMvcApplication()
    {
        $this->application = new MvcApplication($this->container);

        if ($this->container->get('config')->get('applicationType') === 'modules')
        {
            $this->application->registerModules(
                $this->container->get('config')->get('modules')->toArray()
            );
        }
    }

}
