<?php

namespace Handlers;

use Core\Middlewares\Mvc as MvcMiddleware;
use Core\Services\Acl as AclService;
use Core\Services\Application as ApplicationService;
use Core\Services\Config as ConfigService;
use Core\Services\Database as DbService;
use Core\Services\Dispatcher as DispatcherService;
use Core\Services\Mvc as MvcService;
use Core\Services\Router as RouterService;
use Core\Services\Session as SessionService;
use Core\Services\Url as UrlService;
use Core\Services\View as ViewService;

use Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;


/**
 * Class ApplicationHandler
 *
 * @package Handler
 */
final class ApplicationHandler
{

    /**
     * @var FactoryDefault
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * Setup MVC application
     *
     * @throws Exception
     */
    public function __construct()
    {
        // Start Di container
        $this->container = new FactoryDefault();

        // Start Di container
        $this->application = new Application();

        // Register core namespaces
        $this->registerCoreNamespaces();

        // Register main Services in DI container
        $this->registerCoreServices();

        // Bind event to console application
        $this->registerCoreEvents();
    }

    /**
     * Handle application's response
     *
     * @return string
     * @throws Exception
     */
    public function handle(): string
    {
        $requestUri = $this->container->get('config')->get('requestUri');

        return (string) $this->container
            ->get('mvc')
            ->handle($requestUri)
            ->getContent();
    }

    /**
     * Register core namespaces
     */
    private function registerCoreNamespaces(): ApplicationHandler
    {
        (new \Phalcon\Loader())
            ->registerNamespaces(['Core' => BASE_PATH.'/core'])
            ->register();

        return $this;
    }

    /**
     * Register core Services
     */
    private function registerCoreServices(): ApplicationHandler
    {
        (new MvcService())
            ->register($this->container);

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

        return $this;
    }

    /**
     * Register application events
     */
    private function registerCoreEvents(): ApplicationHandler
    {
        $eventsManager = $this->container->get('eventsManager');
        $eventsManager->attach('application', new MvcMiddleware);

        $this->container->get('mvc')->setEventsManager($eventsManager);

        return $this;
    }

}
