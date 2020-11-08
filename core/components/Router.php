<?php

namespace Component;

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

        $defaultNamespace = $application->hasApplication() ? $application->getApplicationNamespace() : $application->getCommonNamespace();

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

}