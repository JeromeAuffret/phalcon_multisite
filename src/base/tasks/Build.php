<?php

namespace Base\Tasks;

use Core\Tasks\Task;

/**
 * Class TestTask
 * @package Core\tasks
 */
class Build extends Task
{
    protected $vue_config = 'vue.config.js';

    /**
     *
     */
    public function mainAction()
    {
        $dest = $this->application->getTenantPath();
        $path = $dest . '/' . $this->vue_config;

        $build_cmd = 'npm run build';

        echo shell_exec("VUE_CLI_SERVICE_CONFIG_PATH=$path TARGET=wc DEST=$dest ENTRY=$dest/App.vue npm run build");
    }
}