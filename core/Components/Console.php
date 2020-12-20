<?php


namespace Core\Components;

use Phalcon\Collection;

/**
 * Class Cli
 * @package Core\Components
 */
class Console extends \Phalcon\Cli\Console
{
    /**
     * @var Collection
     */
    protected $_arguments;

    /**
     * @var Collection
     */
    protected $_params;

    /**
     * @var Collection
     */
    protected $_options;

    /**
     * @var array
     */
    protected $tenancy = [];

    /**
     * @param null $key
     * @return mixed
     */
    public function getArguments($key = null)
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
     * @param null $key
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
     * @param null $key
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
     * @return array
     */
    public function getTenancy(): array
    {
        return $this->tenancy;
    }

    /**
     * @param array $tenancy
     */
    public function setTenancy(array $tenancy): void
    {
        $this->tenancy = $tenancy;
    }

}