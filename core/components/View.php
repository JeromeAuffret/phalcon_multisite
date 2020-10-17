<?php

namespace Component;

use Phalcon\Assets\Collection;
use Phalcon\Mvc\Dispatcher;

/**
 * Class View
 *
 * @property Application application
 * @package Component
 */
final class View extends \Phalcon\Mvc\View
{

    /**
     * @param string $view
     * @param array  $vars
     *
     * @return string|void
     */
    public function getPartial(string $view, $vars = []): string
    {
        $di = $this->getDi();
        $module = $di['router']->getModuleName();
        $app_path = $di->get('application')->getApplicationPath();

        $view_app_path = $app_path.'/views';
        $app_module_path = $app_path.'/modules/'.$module.'/views';
        $view_common_path = COMMON_PATH.'/views';
        $common_module_path = COMMON_PATH.'/modules/'.$module.'/views';

        $view_array = explode('/', $view);

        $partial = end($view_array);
        $partial_key = array_keys($view_array, $partial)[0];

        unset($view_array[$partial_key]);

        $partial_dir = implode('/', $view_array);
        $partial_pattern = '/'.$partial_dir.'/'.$partial.'.phtml';

        if (file_exists($app_module_path.$partial_pattern)) {
            $this->setPartialsDir($app_module_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($view_app_path.$partial_pattern)) {
            $this->setPartialsDir($view_app_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($common_module_path.$partial_pattern)) {
            $this->setPartialsDir($common_module_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($view_common_path.$partial_pattern)) {
            $this->setPartialsDir($view_common_path.'/'.$partial_dir.'/');
        }

        return $this->getPartialContent($partial, $vars);
    }

    /**
     * @param       $partial
     * @param array $vars
     *
     * @return false|string
     */
    public function getPartialContent($partial, $vars = [])
    {
        ob_start();
        $this->partial($partial, $vars);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }


    /**********************************************************
     *
     *                         ASSETS
     *
     **********************************************************/

    /**
     * Register Application general assets
     *
     * @param Collection $collection
     * @param            $type
     *
     * @return Collection
     */
    public function registerApplicationAssetsCollection(Collection $collection, $type)
    {
        $application = $this->getDI()->get('application');
        $appPath = $application->getApplicationPath().'/assets/';

        // Load each files in $appPath by type
        $assetPath = [];
        foreach (glob($appPath.'/*.'.$type) as $file_path) {
            array_push($assetPath, $file_path);
        }

        // Then register asset file if exist
        foreach ($assetPath as $path) {
            if ($type === 'css') {
                $collection->addCss($path);
            } elseif ($type === 'js') {
                $collection->addJs($path);
            }
        }

        return $collection;
    }

    /**
     * Register Application assets
     * Load assets based on module / controller / action path
     *
     * @param Collection $collection
     * @param string     $type
     * @return Collection
     */
    public function registerViewAssetsCollection(Collection $collection, string $type)
    {
        $container = $this->getDI();

        /* @var Application $application */
        $application = $container->get('application');

        /* @var Dispatcher $dispatcher */
        $dispatcher = $container->get('dispatcher');

        /* @var Config $config */
        $config = $container->get('config');

        $moduleName = $dispatcher->getModuleName();
        $assetFilePath = $dispatcher->getControllerName().'/'.$dispatcher->getActionName().'.'.$type;

        // Common and application assets roots paths
        if ($config->get('applicationType') === 'modules') {
            $appModulePath = $application->getApplicationModulePath($moduleName).'/assets/';
            $commonModulePath = $application->getCommonModulePath($moduleName).'/assets/';
        } else {
            $appModulePath = $application->getApplicationPath().'/assets/';
            $commonModulePath = $application->getCommonPath().'/assets/';
        }

        // Load assets from app module path if exist. If not, use the common path if exist
        $assetPath = [];
        if (file_exists($appModulePath.$assetFilePath)) {
            array_push($assetPath,$appModulePath.$assetFilePath);
        }
        else if (file_exists($commonModulePath.$assetFilePath)) {
            array_push($assetPath,$commonModulePath.$assetFilePath);
        }

        // Then register asset file if exist
        foreach ($assetPath as $path) {
            if ($type === 'css') {
                $collection->addCss($path);
            } elseif ($type === 'js') {
                $collection->addJs($path);
            }
        }

        return $collection;
    }

}