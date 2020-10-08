<?php

namespace Component;

use Phalcon\Di;

/**
 * Class Config
 *
 * @package Component
 */
final class Router extends \Phalcon\Mvc\Router
{

    /**
     * @return void
     */
    public function registerRouter(): void
    {
        $container = Di::getDefault();
        $config = $container->get('config');
        $modules = $config->get('modules');

        $defaultModule = $config->get('defaultModule');

        if ($config->get('applicationType') === 'modules')
        {
            $module = $modules->get($defaultModule);
            $namespace = preg_replace('/Module$/', 'Controllers', $module->get('className'));

            $defaultController = $module->get('defaultController') ?? $config->get('defaultController');
            $defaultAction = $module->get('defaultAction') ?? $config->get('defaultAction');

            $container->get('router')->setDefaultNamespace($namespace);
            $container->get('router')->setDefaultModule($defaultModule);

            $container->get('router')->setDefaultController($defaultController);
            $container->get('router')->setDefaultAction($defaultAction);
        }
    }

    /**
     * @return void
     */
    public function registerMainRoutesFile(): void
    {
        $container = Di::getDefault();
        $app_path = $container->get('application')->getApplicationPath();

        $app_config_path = $app_path.'/config/routes.php';
        $app_common_path = COMMON_PATH.'/config/routes.php';

        if (file_exists($app_config_path)) {
            include $app_config_path;
        }
        else if (file_exists($app_common_path)) {
            include $app_common_path;
        }
    }

    /**
     * @return void
     */
    public function registerModulesRoutesFile(): void
    {
        $container = Di::getDefault();
        $modules = $container->get('config')->get('modules');
        $app_path = $container->get('application')->getApplicationPath();

        foreach ($modules as $key => $module)
        {
            $moduleName = basename(dirname($module->get('path')));

            $app_config_path = $app_path.'/modules/'.$moduleName.'/config/routes.php';
            $app_common_path = COMMON_PATH.'/modules/'.$moduleName.'/config/routes.php';

            if (file_exists($app_config_path)) {
                include $app_config_path;
            }
            else if (file_exists($app_common_path)) {
                include $app_common_path;
            }
        }
    }


}