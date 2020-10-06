<?php

namespace Handler;

use Component\Provider as ProviderComponent;
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
     * @var ProviderComponent
     */
    protected $provider;

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
     * @return string
     * @throws Exception
     */
    public function handle(): string
    {

//        var_dump($_GET['_url'] ?? '/');
//        var_dump($_SERVER['REQUEST_URI']);
//
//        var_dump($this->container->get('config')->get('baseUri'));
//        var_dump($this->container->get('config')->get('requestUri'));
//        die();

        return (string) $this->container->get('application')->handle(
            $_GET['_url'] ?? '/'
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
        $this->provider = new ProviderComponent($this->container);
    }

    /**
     * @return void
     */
    public function registerMvcApplication()
    {
        $this->container->setShared('application', new MvcApplication($this->container));

        $this->container->get('application')->registerModules(
            $this->container->get('config')->get('modules')->toArray()
        );
    }


    /************************************************************
     *
     *                   MVC APPLICATION ACCESSOR
     *
     ************************************************************/

    /**
     * Returns the default module name
     *
     * @return string
     */
    public function getDefaultModule(): string
    {
        return $this->application->getDefaultModule();
    }

    /**
     * Return the modules registered in the application
     *
     * @return array
     */
    public function getModules(): array
    {
        return $this->application->getModules();
    }

    /**
     * Gets the module definition registered in the application via module name
     *
     * @param string $name
     * @return array|object
     */
    public function getModule(string $name): array
    {
        return $this->application->getModule($name);
    }

    /**
     * Check if a module is registered in the application
     *
     * @param string $name
     * @return bool
     */
    public function hasModule(string $name): bool
    {
        return array_key_exists($name, $this->application->getModules());
    }

}
