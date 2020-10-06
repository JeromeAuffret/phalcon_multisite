<?php

namespace Common\Modules\Api\Controllers;

use Controllers\BaseController;
use Component\Loader;
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

    /* @var string $model_name */
    protected $model_name;

    /* @var ModelInterface $model_namespace */
    protected $model_namespace;

    /* @var string $primary_key */
    protected $primary_key = 'id';

    /* @var mixed $primary_value */
    protected $primary_value;

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
            $this->primary_value = intval($params[0]) ?: null;
            unset($params[0]);
        }
        else {
            $this->primary_value = null;
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
        $this->model_name = Str::camelize($this->reference);
        $this->model_namespace = $this->loader->dispatchNamespace($this->model_name, 'Models');

        if ($this->model_namespace)
        {
            if ($this->primary_value) {
                $this->model = $this->model_namespace::findFirst($this->primary_key.' = '.$this->primary_value);
            } else {
                $this->model = new $this->model_namespace();
            }
        }

        if (!$this->model_namespace || !$this->model) {
            throw new Exception('Not Found', 1);
        }
    }


}
