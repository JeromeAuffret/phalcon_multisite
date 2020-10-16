<?php

namespace Handler;

use Component\Application as ApplicationComponent;
use Component\ServiceProvider as ServiceProviderComponent;
use Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Middleware\Dispatch as DispatchMiddleware;


class ApplicationHandler
{
    /**
     * @var ApplicationComponent
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
        $this->registerBeforeHandle();
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
        $serviceProvider = new ServiceProviderComponent();
        $serviceProvider->registerServices();
    }

    /**
     * @return void
     */
    public function registerMvcApplication()
    {
        $this->application = $this->container->get('application');

        if ($this->container->get('config')->get('applicationType') === 'modules')
        {
            $this->application->registerModules(
                $this->container->get('config')->get('modules')->toArray()
            );
        }
    }

    /**
     * @return void
     */
    public function registerBeforeHandle()
    {
        $manager = new Manager();

        $this->container->get('application')->setEventsManager($manager);

        $manager->attach(
            'application:boot', new DispatchMiddleware()
        );
    }

}
