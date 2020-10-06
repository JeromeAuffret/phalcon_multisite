<?php

namespace Acl;

use Phalcon\Acl\ComponentAware;
use Phalcon\Di;


class AclComponent implements ComponentAware
{
    protected $componentName;

    protected $moduleName;

    protected $controllerName;

    protected $actionName;

    protected $params;

    protected $request_method;

    protected $request_query;

    protected $request_post;

    /**
     * AclComponent constructor.
     *
     * @param $moduleName
     * @param $controllerName
     * @param $actionName
     * @param $params
     */
    public function __construct($moduleName, $controllerName, $actionName, $params)
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
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     */
    private function initialize(string $moduleName, string $controllerName, string $actionName, array $params)
    {
        $this->componentName = $controllerName === 'error' ? '_error' : $moduleName.'_'.$controllerName;

        $this->moduleName = $moduleName;

        $this->controllerName = $controllerName;

        $this->actionName = $actionName;

        $this->params = $params;

        $this->request_method = Di::getDefault()->get('request')->getMethod();

        $this->request_query = Di::getDefault()->get('request')->getQuery();

        $this->request_post = Di::getDefault()->get('request')->getPost();
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
        return $this->request_method;
    }

}