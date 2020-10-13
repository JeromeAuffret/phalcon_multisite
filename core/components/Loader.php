<?php

namespace Component;

use Phalcon\Di\Injectable;
use Phalcon\Helper\Str;
use Phalcon\Assets\Collection;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;

/**
 * Class Loader
 *
 * @property Acl acl
 * @property Application application
 * @property Session session
 * @property Config config
 * @property Database database
 * @property Loader loader
 * @package Component
 */
final class Loader extends Injectable
{

    /**********************************************************
     *
     *                          BASE
     *
     **********************************************************/

    /**
     * Register common namespace
     */
    public function registerMainNamespaces()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Controllers' => BASE_PATH . '/src/controllers',
                'Models'      => BASE_PATH . '/src/models',
                'Forms'       => BASE_PATH . '/src/forms',

                'Common'              => COMMON_PATH,
                'Common\\Models'      => COMMON_PATH . '/models/',
                'Common\\Traits'      => COMMON_PATH . '/traits/',
                'Common\\Controllers' => COMMON_PATH . '/controllers/',
                'Common\\Forms'       => COMMON_PATH . '/forms/'
            ])
            ->register();
    }

    /**
     * Loader Application loader and load namespaces in di
     *
     * @return void
     */
    public function registerApplicationNamespaces()
    {
        $application_path = $this->application->getApplicationPath();
        $application_namespace = $this->application->getApplicationNamespace();

        // Register application's namespaces
        (new \Phalcon\Loader())
            ->registerNamespaces([
                $application_namespace => $application_path,
                "$application_namespace\\Models"      => $application_path.'/models',
                "$application_namespace\\Controllers" => $application_path.'/controllers',
                "$application_namespace\\Traits"      => $application_path.'/traits',
                "$application_namespace\\Forms"       => $application_path.'/forms',
                "$application_namespace\\Console"     => $application_path.'/console',
            ])
            ->register();
    }


    /**********************************************************
     *
     *                     BEFORE DISPATCH
     *
     **********************************************************/

    /**
     * Dispatch controller namespace between common and application folders
     *
     * @param Dispatcher $dispatcher
     */
    public function dispatchController(Dispatcher $dispatcher)
    {
        $module_name = $dispatcher->getModuleName();
        $application_namespace = $this->application->getApplicationNamespace();

        $controller_class = explode('\\', $dispatcher->getControllerClass());
        $controller_file = end($controller_class).'.php';

        if (end($controller_class) === 'ErrorController') {
            $dispatcher->setNamespaceName('Controllers');
        }
        elseif ($this->config->get('applicationType') === 'simple') {
            $dispatcher->setNamespaceName($application_namespace.'\Controllers');
        }
        elseif ($this->config->get('applicationType') === 'modules') {
            $app_controller_module_path = $this->application->getApplicationModulePath($module_name).'/controllers';
            $module_namespace = $this->application->getApplicationModuleNamespace($module_name).'\\Controllers';

            if(file_exists($app_controller_module_path.'/'.$controller_file)) {
                (new \Phalcon\Loader())->registerNamespaces([$module_namespace => $app_controller_module_path])->register();
                $dispatcher->setNamespaceName($module_namespace);
            }
        }
    }

    /**
     * Register correct controller in dispatcher based on application defined in session
     *
     * @param Dispatcher $dispatcher
     * @param Router $router
     * @return void
     */
    public function dispatchApiController(Dispatcher $dispatcher, Router $router)
    {
        $module_name = $router->getModuleName();
        $controller_name = $router->getControllerName();
        $reference_name = $router->getParams()['reference'];
        $reference_controller_file = Str::camelize($reference_name).'Controller.php';

        $app_controller_module_path = $this->application->getApplicationModulePath($module_name).'/controllers/'.$controller_name;
        $common_controller_module_path = $this->application->getCommonModulePath($module_name).'/controllers/'.$controller_name;

        if ($controller_name === 'error') {
            $dispatcher->setNamespaceName('Controllers');
        }
        else if ($this->application && file_exists($app_controller_module_path.'/'.$reference_controller_file)) {
            $namespace = $this->application->getApplicationModulePath($module_name).'\Controllers\\'.$controller_name;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $app_controller_module_path])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($reference_name);
        }
        else if (file_exists($common_controller_module_path.'/'.$reference_controller_file)) {
            $namespace = $this->application->getCommonModulePath($module_name).'\Controllers\\'.$controller_name;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $common_controller_module_path])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($reference_name);
        }
    }


    /**********************************************************
     *
     *                        NAMESPACES
     *
     **********************************************************/

    /**
     * Dispatch a namespace between common and application folder
     *
     * @param string $class_name
     * @param string $base_namespace
     * @return string|null
     */
    public function dispatchNamespace(string $class_name, string $base_namespace)
    {
        // Get class path base on namespace. This use a lowercase version of PSR-4 standard for folder's name
        $base_path = [];
        foreach (explode('\\', $base_namespace) as $namespace_folder) {
            if (!empty($namespace_folder)) $base_path[] = Str::uncamelize($namespace_folder, '_');
        }
        $base_path = implode('/', $base_path);

        $app_path = $this->application->getApplicationPath().'/'.$base_path.'/';
        $common_path = $this->application->getCommonPath().'/'.$base_path.'/';

        $namespace = $path = null;
        if (file_exists($app_path.$class_name.'.php')) {
            $namespace = $this->application->getApplicationNamespace()."\\$base_namespace\\$class_name";
        }
        elseif (file_exists($common_path.$class_name.'.php')) {
            $namespace = $this->application->getCommonNamespace()."\\$base_namespace\\$class_name";
        }
        elseif ($this->config->get('applicationType') === 'modules')
        {
            foreach ($this->config->get('modules') as $module_name => $definition)
            {
                $app_module_path = $this->application->getApplicationModulePath($module_name).'/'.$base_path.'/';
                $common_module_path = $this->application->getCommonModulePath($module_name).'/'.$base_path.'/';

                if (file_exists($app_module_path.$class_name.'.php')) {
                    $namespace = $this->application->getApplicationModuleNamespace($module_name)."\\$base_namespace\\$class_name";
                    $path = $app_module_path.$class_name.'.php';
                    break;
                }
                elseif (file_exists($common_module_path.$class_name.'.php')) {
                    $namespace = $this->application->getCommonModuleNamespace($module_name)."\\$base_namespace\\$class_name";
                    $path = $common_module_path.$class_name.'.php';
                    break;
                }
            }
        }

        // Register namespace before return it
        (new \Phalcon\Loader())->registerClasses([$namespace => $path])->register();

        return $namespace;
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
        $app_path = $this->application->getApplicationPath().'/assets/';

        // Load each files in $app_path by type
        $asset_path = [];
        foreach (glob($app_path.'/*.'.$type) as $file_path) {
            array_push($asset_path, $file_path);
        }

        // Then register asset file if exist
        foreach ($asset_path as $path) {
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
        /* @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $module_name = $dispatcher->getModuleName();
        $asset_file_path = $dispatcher->getControllerName().'/'.$dispatcher->getActionName().'.'.$type;

        // Common and application assets roots paths
        if ($this->config->get('applicationType') === 'modules') {
            $app_module_path = $this->application->getApplicationModulePath($module_name).'/assets/';
            $common_module_path = $this->application->getCommonModulePath($module_name).'/assets/';
        } else {
            $app_module_path = $this->application->getApplicationPath().'/assets/';
            $common_module_path = $this->application->getCommonPath().'/assets/';
        }

        // Load assets from app module path if exist. If not, use the common path if exist
        $asset_path = [];
        if (file_exists($app_module_path.$asset_file_path)) {
            array_push($asset_path,$app_module_path.$asset_file_path);
        }
        else if (file_exists($common_module_path.$asset_file_path)) {
            array_push($asset_path,$common_module_path.$asset_file_path);
        }

        // Then register asset file if exist
        foreach ($asset_path as $path) {
            if ($type === 'css') {
                $collection->addCss($path);
            } elseif ($type === 'js') {
                $collection->addJs($path);
            }
        }

        return $collection;
    }

}