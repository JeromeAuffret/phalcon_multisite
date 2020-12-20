<?php


namespace Demo1\Tasks;

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
class TestTask extends Task
{
    /**
     * @param int $value_1
     * @param int $value_2
     */
    public function mainAction(int $value_1, int $value_2)
    {
        echo $value_1 + $value_2;
        echo PHP_EOL;
    }
}