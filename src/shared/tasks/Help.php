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
class Help extends Task
{
    /**
     * Prompt console usage and available options
     */
    public function mainAction()
    {
        echo 'Usage : php console [task] [action] [...params] [--options]' . PHP_EOL;

        echo PHP_EOL;

        echo 'Options : ' . PHP_EOL;
        echo ' --tenant : Register tenants separated by commas. Use wildcard * ro register every tenants' . PHP_EOL;
        echo ' --module : Register module where script should be dispatch' . PHP_EOL;
    }
}