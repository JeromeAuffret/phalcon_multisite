<?php

namespace Handler;

use Component\Application;
use Exception;
use Middleware\Application as ApplicationMiddleware;
use Phalcon\Di\FactoryDefault;

use Service\Acl as AclService;
use Service\Application as ApplicationService;
use Service\Config as ConfigService;
use Service\Database as DbService;
use Service\Dispatcher as DispatcherService;
use Service\Router as RouterService;
use Service\Session as SessionService;
use Service\Url as UrlService;
use Service\View as ViewService;

/**
 * Class ApplicationHandler
 *
 * @package Handler
 */
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
        $this->registerCoreNamespaces();

        // Register main services in DI container
        $this->registerCoreServices();

        // Get application service
        $this->application = $this->container->get('application');

        // Bind event to mvc application
        $this->registerApplicationEvents();
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
     * Register core namespaces
     */
    public function registerCoreNamespaces()
    {
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
    }

    /**
     * Register core services
     */
    public function registerCoreServices()
    {
        (new ApplicationService)
            ->register($this->container);

        (new DispatcherService)
            ->register($this->container);

        (new SessionService)
            ->register($this->container);

        (new ConfigService)
            ->register($this->container);

        (new DbService)
            ->register($this->container);

        (new AclService)
            ->register($this->container);

        (new ViewService)
            ->register($this->container);

        (new UrlService)
            ->register($this->container);

        (new RouterService)
            ->register($this->container);
    }

    /**
     * Register application events
     */
    public function registerApplicationEvents()
    {
        // Register eventsManager
        $this->application->setEventsManager(
            $this->container->get('eventsManager')
        );

        // Bind boot event to correctly dispatch applications
        $this->application
            ->getEventsManager()
            ->attach('application', new ApplicationMiddleware());
    }

}
