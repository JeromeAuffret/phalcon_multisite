<?php

namespace Common\Modules\Api\Controllers;

use Controllers\BaseController;
use Exception;
use Models\BaseModel;
use Phalcon\Helper\Str;
use Phalcon\Mvc\ModelInterface;


class ControllerBase extends BaseController
{
    /* @var string $reference */
    protected $reference;

    /* @var BaseModel $model */
    protected $model;

    /* @var string $modelName */
    protected $modelName;

    /* @var ModelInterface $modelNamespace */
    protected $modelNamespace;

    /* @var string $primaryKey */
    protected $primaryKey = 'id';

    /* @var mixed $primaryValue */
    protected $primaryValue;

    /* @var array $parameters */
    protected $parameters = [];


    /**
     *  Initialize model object, merge params and check for user permission
     *
     * @throws Exception
     */
    public function initialize()
    {
        $this->view->disable();

        $this->sanitizeParams();
        $this->instantiateModel();
    }

    /**
     *
     */
    protected function sanitizeParams()
    {
        $params = $this->router->getParams();

        $this->reference = $params['reference'];
        unset($params['reference']);

        if (isset($params[0])) {
            $this->primaryValue = intval($params[0]) ?: null;
            unset($params[0]);
        }
        else {
            $this->primaryValue = null;
        }

        foreach ($params as $param) {
            array_push($this->parameters, $param);
        }
    }

    /**
     *
     * @throws Exception
     */
    protected function instantiateModel()
    {
        $this->modelName = Str::camelize($this->reference);
        $this->modelNamespace = $this->dispatcher->dispatchClass($this->modelName, 'Models');

        if ($this->modelNamespace)
        {
            if ($this->primaryValue) {
                $this->model = $this->modelNamespace::findFirst($this->primaryKey.' = '.$this->primaryValue);
            } else {
                $this->model = new $this->modelNamespace();
            }
        }

        if (!$this->modelNamespace || !$this->model) {
            throw new Exception('Not Found', 1);
        }
    }


}
