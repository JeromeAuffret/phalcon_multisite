<?php

namespace Handler;

use Component\Application;
use Exception;
use Middleware\Application as ApplicationMiddleware;
use Provider\ServiceProvider;
use Phalcon\Di\FactoryDefault;

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
     * Setup MVC application
     *
     * @throws Exception
     */
    public function __construct()
    {
        // Start Di container
        $this->container = new FactoryDefault();

        // Register core namespaces
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

        // Register main services in DI container
        (new ServiceProvider())->registerServices();

        // Get application service
        $this->application = $this->container->get('application');

        // Register boot event to correctly dispatch applications
        $this->container->get('eventsManager')->attach('application:boot', new ApplicationMiddleware());
        $this->application->setEventsManager($this->container->get('eventsManager'));
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

}
