<?php

namespace Provider;

use Phalcon\Collection;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class ModuleProvider implements ModuleDefinitionInterface
{
    /**
     * @var DiInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $moduleName;
    /**
     * @var string
     */
    protected $controllerNamespace;

    /**
     * @var Collection
     */
    protected $moduleDefinition;

    /**
     * @var string
     */
    protected $defaultController;

    /**
     * @var string
     */
    protected $defaultAction;

    /**
     * @param DiInterface|null $container
     * @param string|null $moduleName
     */
    public function initialize(DiInterface $container, string $moduleName)
    {
        $this->moduleName = $moduleName;

        $config = $container->get('config');

        $this->moduleDefinition = $config->get('modules')->get($moduleName);
        $this->controllerNamespace = preg_replace('/Module$/', 'Controllers', $this->moduleDefinition->get('className'));
        $this->defaultController = $this->moduleDefinition->get('defaultController') ?? $config->get('defaultController');
        $this->defaultAction = $this->moduleDefinition->get('defaultAction') ?? $config->get('defaultController');

        $this->registerRouter($container);
        $this->registerAcl($container);
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null) {}

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register router related to the module
     *
     * @param DiInterface $container
     */
    public function registerRouter(DiInterface $container)
    {
        $router = $container->get('router');
        $config = $container->get('config');

        // Register default module in router
        $router->setDefaultModule(
            $config->get('defaultModule')
        );

        // Register a generic routing for modules
        $router->add('/'.$this->moduleName.'/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => $this->defaultController,
            'action' => $this->defaultAction,
            'params' => 1
        ]);

        $router->add('/'.$this->moduleName.'/:controller/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => $this->defaultAction,
            'params' => 2
        ]);

        $router->add('/'.$this->moduleName.'/:controller/:action/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container) {}

    /**
     * Register events related to the module
     * Events are bind only in module dispatch loop
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
