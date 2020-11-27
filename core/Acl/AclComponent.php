<?php

namespace Core\Acl;

use Phalcon\Acl\ComponentAware;
use Phalcon\Di;

/**
 * Class AclComponent
 *
 * @package Acl
 */
class AclComponent implements ComponentAware
{
    /**
     * @var string
     */
    protected $componentName;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var String
     */
    protected $actionName;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var array
     */
    protected $requestQuery;

    /**
     * @var array
     */
    protected $requestPost;

    /**
     * AclComponent constructor.
     *
     * In case of empty controllerName or actionName,
     * we get default in module definition or in global configuration
     *
     * @param string|null $moduleName
     * @param string|null $controllerName
     * @param string|null $actionName
     * @param array $params
     */
    public function __construct(?string $moduleName, ?string $controllerName, ?string $actionName, array $params)
    {
        $config = Di::getDefault()->get('config');

        if (!$controllerName) {
            $controllerName = $config->get('modules')[$moduleName]['defaultController'] ?? $config->defaultController;
        }

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
        $config = $container->get('config');
        $request = $container->get('request');

        if ($controllerName === 'error') {
            $this->componentName = '_error';
        }
        elseif ($controllerName === 'logout') {
            $this->componentName = '_logout';
        }
        elseif ($config->get('tenantType') === 'modules') {
            $this->componentName = $moduleName.'_'.$controllerName;
        }
        elseif ($config->get('tenantType') === 'simple') {
            $this->componentName = $controllerName;
        }

        $this->moduleName = $moduleName;

        $this->controllerName = $controllerName;

        $this->actionName = $actionName;

        $this->params = $params;

        $this->requestMethod = $request->getMethod();

        $this->requestQuery = $request->getQuery();

        $this->requestPost = $request->getPost();
    }

    /**
     * Verify if the given Components is defined as public
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