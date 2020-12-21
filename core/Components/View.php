<?php

namespace Core\Components;

use Phalcon\Assets\Collection;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;

/**
 * Class View
 *
 * @property Application application
 * @package Core\Components
 */
final class View extends \Phalcon\Mvc\View
{

    /**
     * @param string $partialPath
     * @param array  $params
     *
     * @return string|void
     */
    public function getPartial(string $partialPath, $params = []): string
    {
        $di = $this->getDi();

        /** @var Application $application */
        $application = $di->get('application');
        /** @var Router $router */
        $router = $di->get('router');

        $moduleName = $router->getModuleName();

        $baseViewPath = $application->getBasePath().'/views';
        $baseModuleViewPath = $application->getBaseModulePath($moduleName).'/views';

        $appViewPath = $application->getTenantPath().'/views';
        $appModuleViewPath = $application->getTenantModulePath($moduleName).'/views';
        
        $viewArray = explode('/', $partialPath);
        $partial = end($viewArray);
        $partialKey = array_keys($viewArray, $partial)[0];

        unset($viewArray[$partialKey]);

        $partialDir = implode('/', $viewArray);
        $partialPattern = '/'.$partialDir.'/'.$partial.'.phtml';

        if (file_exists($appModuleViewPath.$partialPattern)) {
            $this->setPartialsDir($appModuleViewPath.'/'.$partialDir.'/');
        }
        elseif (file_exists($appViewPath.$partialPattern)) {
            $this->setPartialsDir($appViewPath.'/'.$partialDir.'/');
        }
        elseif (file_exists($baseModuleViewPath.$partialPattern)) {
            $this->setPartialsDir($baseModuleViewPath.'/'.$partialDir.'/');
        }
        elseif (file_exists($baseViewPath.$partialPattern)) {
            $this->setPartialsDir($baseViewPath.'/'.$partialDir.'/');
        }

        return $this->getPartialContent($partial, $params);
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
     * Register Tenant general assets
     *
     * @param Collection $collection
     * @param            $type
     *
     * @return Collection
     */
    public function registerTenantAssetsCollection(Collection $collection, $type): Collection
    {
        /** @var Application $application */
        $application = $this->getDI()->get('application');
        $appPath = $application->getTenantPath().'/assets/';

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
     * Register Tenant assets
     * Load assets based on module / controller / action path
     *
     * @param Collection $collection
     * @param string     $type
     * @return Collection
     */
    public function registerViewAssetsCollection(Collection $collection, string $type): Collection
    {
        $container = $this->getDI();

        /** @var Application $application */
        $application = $container->get('application');
        /** @var Dispatcher $application */
        $dispatcher = $container->get('dispatcher');
        /** @var Config $config */
        $config = $container->get('config');

        $moduleName = $dispatcher->getModuleName();
        $assetFilePath = $dispatcher->getControllerName().'/'.$dispatcher->getActionName().'.'.$type;

        // Base and application assets roots paths
        if ($config->get('tenantType') === 'modules') {
            $appModuleAssetPath = $application->getTenantModulePath($moduleName).'/assets/';
            $baseModuleAssetPath = $application->getBaseModulePath($moduleName).'/assets/';
        } else {
            $appModuleAssetPath = $application->getTenantPath().'/assets/';
            $baseModuleAssetPath = $application->getBasePath().'/assets/';
        }

        // Load assets from app module path if exist. If not, use the base path if exist
        $assetPath = [];
        if (file_exists($appModuleAssetPath.$assetFilePath)) {
            array_push($assetPath,$appModuleAssetPath.$assetFilePath);
        }
        else if (file_exists($baseModuleAssetPath.$assetFilePath)) {
            array_push($assetPath,$baseModuleAssetPath.$assetFilePath);
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