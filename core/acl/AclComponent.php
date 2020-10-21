<?php

namespace Acl;

use Phalcon\Acl\ComponentAware;
use Phalcon\Di;

/**
 * Class AclComponent
 *
 * @package Acl
 */
class AclComponent implements ComponentAware
{
    protected $componentName;

    protected $moduleName;

    protected $controllerName;

    protected $actionName;

    protected $params;

    protected $requestMethod;

    protected $requestQuery;

    protected $requestPost;

    /**
     * AclComponent constructor.
     *
     * @param string|null $moduleName
     * @param string|null $controllerName
     * @param string|null $actionName
     * @param array $params
     */
    public function __construct(?string $moduleName, ?string $controllerName, ?string $actionName, array $params)
    {
        $config = Di::getDefault()->get('config');

        // In case of empty controllerName, we check for specific defaultController in module definition
        if (!$controllerName) {
            $controllerName = $config->get('modules')[$moduleName]['defaultController'] ?? $config->defaultController;
        }

        // In case of empty actionName, we check for specific defaultAction in module definition
        if (!$actionName) {
            $actionName = $config->get('modules')[$moduleName]['defaultAction'] ?? $config->defaultAction;
        }

        $this->initialize($moduleName, $controllerName, $actionName, $params);
    }

    /**
     * Initialize Acl Component
     *
     * @param string|null $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     */
    private function initialize(?string $moduleName, string $controllerName, string $actionName, array $params)
    {
        $container = Di::getDefault();

        if ($controllerName === 'error') {
            $this->componentName = '_error';
        }
        elseif ($container->get('config')->get('applicationType') === 'modules') {
            $this->componentName = $moduleName.'_'.$controllerName;
        }
        elseif ($container->get('config')->get('applicationType') === 'simple') {
            $this->componentName = $controllerName;
        }

        $this->moduleName = $moduleName;

        $this->controllerName = $controllerName;

        $this->actionName = $actionName;

        $this->params = $params;

        $this->requestMethod = $container->get('request')->getMethod();

        $this->requestQuery = $container->get('request')->getQuery();

        $this->requestPost = $container->get('request')->getPost();
    }

    /**
     * Verify is the given components is defined as public
     *
     * @return bool
     */
    public function isPublicComponent()
    {
        return in_array($this->getComponentName(), Di::getDefault()->get('config')->get('publicComponents')->getValues());
    }


    /*************************************************************
     *
     *                          GETTERS
     *
     *************************************************************/

    /**
     * @return string
     */
    public function getComponentName(): string
    {
        return $this->componentName;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->requestMethod;
    }

}