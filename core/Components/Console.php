<?php


namespace Core\Components;

/**
 * Class Console
 * @package Core\Components
 */
class Console extends \Phalcon\Cli\Console
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

}