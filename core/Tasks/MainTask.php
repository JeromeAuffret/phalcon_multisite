<?php


namespace Core\Tasks;

use Core\Components\Application;
use Core\Components\Console;
use Phalcon\Cli\Task;
use Phalcon\Cli\Dispatcher;

/**
 * Class MainTask
 * @property Console $console
 * @property Application $application
 * @property Console $config
 * @package Core\Tasks
 */
class MainTask extends Task
{
    /**
     *
     */
    public function mainAction()
    {
        echo 'This is the default task and the default action' . PHP_EOL;
    }
}