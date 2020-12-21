<?php


namespace Core\Components;

use Phalcon\Cli\Dispatcher;
use Phalcon\Collection;

/**
 * Class Cli
 * @property Dispatcher $dispatcher
 * @package Core\Components
 */
final class Console extends \Phalcon\Cli\Console
{
    /**
     * @var Collection
     */
    protected $_arguments;

    /**
     * @var String $_task
     */
    protected $_task;

    /**
     * @var String $_action
     */
    protected $_action;

    /**
     * @var Collection
     */
    protected $_params;

    /**
     * @var Collection
     */
    protected $_options;

    /**
     * @var Collection
     */
    protected $_tenants = [];


    /**
     * Register argument in console service
     *
     * @param array $arguments
     */
    public function registerArguments(array $arguments)
    {
        // Register arguments in console component
        $this->setArguments(new Collection($arguments));

        // Register task in console component
        $this->setTask($arguments['task']);

        // Register action in console component
        $this->setAction($arguments['action']);

        // Register arguments in console component
        $this->setArguments(new Collection($arguments));

        // Register params in console component
        $this->setParams(new Collection($arguments['params']));

        // Register options in console component
        $this->setOptions(new Collection($arguments['options']));
    }

    /**
     * @return String|null
     */
    public function getTask(): ?string
    {
        return $this->_task;
    }

    /**
     * @param string|null $task
     */
    public function setTask(?string $task): void
    {
        $this->_task = $task;
    }

    /**
     * @return String|null
     */
    public function getAction(): ?string
    {
        return $this->_action;
    }

    /**
     * @param string|null $action
     */
    public function setAction(?string $action): void
    {
        $this->_action = $action;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getArguments(?string $key = null)
    {
        if (!$key)
            return $this->_arguments;
        elseif ($this->_arguments && $this->_arguments->has($key))
            return $this->_arguments->get($key);
        else
            return null;
    }

    /**
     * @param Collection $arguments
     */
    public function setArguments(Collection $arguments): void
    {
        $this->_arguments = $arguments;
    }

    /**
     * @param mixed|null $key
     * @return mixed
     */
    public function getParams($key = null)
    {
        if (!$key)
            return $this->_params;
        elseif ($this->_params && $this->_params->has($key))
            return $this->$this->_params->get($key);
        else
            return null;
    }

    /**
     * @param Collection $params
     */
    public function setParams(Collection $params): void
    {
        $this->_params = $params;
    }

    /**
     * @param mixed|null $key
     * @return mixed
     */
    public function hasOptions($key = null): bool
    {
        if (!$key)
            return !empty($this->_options);
        else
            return $this->_options->has($key);
    }

    /**
     * @param mixed|null $key
     * @return mixed
     */
    public function getOptions($key = null)
    {
        if (!$key)
            return $this->_options;
        elseif ($this->_options && $this->_options->has($key))
            return $this->_options->get($key);
        else
            return null;
    }

    /**
     * @param Collection $options
     */
    public function setOptions(Collection $options): void
    {
        $this->_options = $options;
    }

    /**
     * @param mixed|null $key
     * @return bool
     */
    public function hasTenants($key = null): bool
    {
        if (!$key)
            return !empty($this->_tenants);
        else
            return $this->_tenants->has($key);
    }

    /**
     * @return Collection
     */
    public function getTenants(): Collection
    {
        return $this->_tenants;
    }

    /**
     * @param Collection $_tenants
     */
    public function setTenants(Collection $_tenants): void
    {
        $this->_tenants = $_tenants;
    }

}