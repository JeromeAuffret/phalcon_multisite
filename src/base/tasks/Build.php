<?php

namespace Base\Tasks;

use Core\Tasks\Task;

/**
 * Class TestTask
 * @package Core\tasks
 */
class Build extends Task
{
    protected $vueConfig = 'vue.config.js';

    protected $buildDir = 'dist';

    /**
     *
     */
    public function mainAction()
    {
        $tenant_path = $this->application->getTenantPath() ?: $this->application->getBasePath();

        $config_path = $tenant_path.'/'.$this->vueConfig;
        $build_path = $tenant_path.'/'.$this->buildDir;

        $build_cmd = 'npm run build';
        if ($this->console->hasOptions('watch'))
            $build_cmd = 'npm run build-dev';

        echo shell_exec("VUE_CLI_SERVICE_CONFIG_PATH=$config_path DEST=$build_path $build_cmd");
    }
}