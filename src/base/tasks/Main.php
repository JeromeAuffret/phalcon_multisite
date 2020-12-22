<?php


namespace Base\Tasks;

use Core\Components\Application;
use Core\Components\Console;
use Phalcon\Cli\Task;

/**
 * Class TestTask
 * @property Console $console
 * @property Application $application
 * @property Console $config
 * @package Core\tasks
 */
class Main extends Task
{
    /**
     *
     */
    public function mainAction()
    {
        echo 'This is the default task and the default action' . PHP_EOL;
    }
}