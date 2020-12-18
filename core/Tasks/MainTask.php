<?php


namespace Core\Tasks;

use Phalcon\Cli\Task;

/**
 * Class MainTask
 * @package Core\Tasks
 */
class MainTask extends Task
{
    public function mainAction()
    {
        echo 'This is the default task and the default action' . PHP_EOL;
    }
}