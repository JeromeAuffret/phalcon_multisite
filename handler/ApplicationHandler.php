<?php

namespace Handler;

use Component\Application;
use Provider\ServiceProvider;
use Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Middleware\Application as ApplicationMiddleware;

class ApplicationHandler
{
    /**
     * @var Application
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
        $this->registerBootEvent();
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
                'Acl'        => BASE_PATH . '/core/acl',
                'Component'  => BASE_PATH . '/core/components',
                'Error'      => BASE_PATH . '/core/errors',
                'Middleware' => BASE_PATH . '/core/middlewares',
                'Provider'   => BASE_PATH . '/core/providers',
                'Service'    => BASE_PATH . '/core/services',
                'Libraries'  => BASE_PATH . '/libraries',
            ])
            ->register();
    }

    /**
     * @return void
     */
    public function registerProviders()
    {
        $serviceProvider = new ServiceProvider();
        $serviceProvider->registerServices();
    }

    /**
     * @return void
     */
    public function registerMvcApplication()
    {
        $this->application = $this->container->get('application');
    }

    /**
     * @return void
     */
    public function registerBootEvent()
    {
        $manager = new Manager();
        $this->container->get('application')->setEventsManager($manager);
        $manager->attach('application:boot', new ApplicationMiddleware());
    }

}
