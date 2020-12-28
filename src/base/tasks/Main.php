<?php

namespace Base\Tasks;

use Core\Tasks\Task;

/**
 * Class TestTask
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