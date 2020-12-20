<?php


namespace Base\Tasks;

use Core\Components\Application;
use Core\Components\Console;
use Phalcon\Cli\Task;

/**
 * Class MainTask
 * @property Console $console
 * @property Application $application
 * @property Console $config
 * @package Core\tasks
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