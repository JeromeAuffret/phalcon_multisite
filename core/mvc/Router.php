<?php

namespace Mvc;

/**
 * Class Router
 *
 * @package Component
 */
class Router extends \Phalcon\Mvc\Router
{

    /**
     *  Initialize defaults for router service
     */
    public function initDefaults()
    {
        $config = $this->getDI()->get('config');
        $application = $this->getDI()->get('application');

        $defaultNamespace = $application->hasApplication() ? $application->getApplicationNamespace() : $application->getBaseNamespace();

        $this->setDefaultNamespace($defaultNamespace.'\\Controllers');
        $this->setDefaultController($config->defaultController);
        $this->setDefaultAction($config->defaultAction);
    }

    /**
     *  Initialize defaults for router service and for a specific module
     *
     * @param string $moduleName
     * @param string $controllerNamespace
     * @param string $defaultController
     * @param string $defaultAction
     */
    public function initModuleDefaults(string $moduleName, string $controllerNamespace, string $defaultController, string $defaultAction)
    {
        if ($this->getDI()->get('config')->get('defaultModule') === $moduleName)
        {
            $this->setDefaultModule($moduleName);
            $this->setDefaultNamespace($controllerNamespace);
            $this->setDefaultController($defaultController);
            $this->setDefaultAction($defaultAction);
        }
    }


    /*************************************************************
     *
     *                          REGISTER
     *
     *************************************************************/

    /**
     * @param string $filePath
     */
    public function registerRouterFromFile(string $filePath)
    {
        if (file_exists($filePath)) include_once $filePath;
    }

    /**
     * @param string $moduleName
     * @param string $controllerNamespace
     * @param string $controllerName
     * @param string $defaultController
     * @param string $defaultAction
     */
    public function registerModuleRoutes(string $moduleName, string $controllerNamespace, string $controllerName, string $defaultController, string $defaultAction)
    {
        // Register a generic routing for modules
        $this->add('/'.$moduleName.'/:params', [
            'namespace' => $controllerNamespace,
            'module' => $moduleName,
            'controller' => $controllerName,
            'action' => $defaultAction,
            'params' => 1
        ]);

        $this->add('/'.$moduleName.'/:controller/:params', [
            'namespace' => $controllerNamespace,
            'module' => $moduleName,
            'controller' => 1,
            'action' => $defaultAction,
            'params' => 2
        ]);

        $this->add('/'.$moduleName.'/:controller/:action/:params', [
            'namespace' => $controllerNamespace,
            'module' => $moduleName,
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
    }

}