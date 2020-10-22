<?php

namespace Component;

use Phalcon\Helper\Str;

/**
 * Class Dispatcher
 *
 * @package Component
 */
final class Dispatcher extends \Phalcon\Mvc\Dispatcher
{

    /**
     * Register correct controller namespace in dispatcher
     *
     * @throws \ReflectionException
     */
    public function dispatchControllers()
    {
        if (!class_exists($this->getControllerClass())) return;

        $application = $this->getDI()->get('application');

        $controllerClass = $this->getControllerClass();
        $controllerPath = (new \ReflectionClass($controllerClass))->getFileName();

        // Error controllers is not overridable
        if (substr($controllerPath, -strlen('ErrorController')) === 'ErrorController') {
            $this->setNamespaceName('Controllers');
        }
        // Prevent dispatching controller if no application is registered
        elseif (!$application->hasApplication()) {
            return;
        }
        // If controller is part of common namespace we check if override exist in application folder
        elseif (substr($controllerClass, 0, strlen($application->getCommonNamespace())) === $application->getCommonNamespace())
        {
            $overridePath = str_replace($application->getCommonPath(), $application->getApplicationPath(), $controllerPath);

            if (file_exists($overridePath))
            {
                $overrideNamespace = str_replace($application->getCommonNamespace(), $application->getApplicationNamespace(), $controllerClass);

                (new \Phalcon\Loader())
                    ->registerClasses([$overrideNamespace => $overridePath])
                    ->register();

                $this->setNamespaceName((new \ReflectionClass($overrideNamespace))->getNamespaceName());
            }
        }
    }

    /**
     * Helper method to dispatch a namespace between common and application folder
     *
     * @param string $className
     * @param string $baseNamespace Base namespace use to be concatenated between applicationNamespace and className
     * @return string|null
     */
    public function dispatchNamespace(string $className, string $baseNamespace = '')
    {
        $application = $this->getDI()->get('application');
        $config = $this->getDI()->get('config');

        $basePath = $this->buildNamespacePath($baseNamespace);
        $appPath = $application->getApplicationPath().'/'.$basePath;
        $commonPath = $application->getCommonPath().'/'.$basePath;

        $namespace = $path = null;
        if (file_exists($appPath.'/'.$className.'.php')) {
            return $application->getApplicationNamespace().'\\'.$baseNamespace.'\\'.$className;
        }
        elseif (file_exists($commonPath.'/'.$className.'.php')) {
            return $application->getCommonNamespace().'\\'.$baseNamespace.'\\'.$className;
        }
        elseif ($config->get('applicationType') === 'modules')
        {
            foreach ($config->get('modules') as $moduleName => $definition)
            {
                $appModulePath = $application->getApplicationModulePath($moduleName).'/'.$basePath;
                $commonModulePath = $application->getCommonModulePath($moduleName).'/'.$basePath;

                if (file_exists($appModulePath.'/'.$className.'.php')) {
                    $namespace = $application->getApplicationModuleNamespace($moduleName).'\\'.$baseNamespace.'\\'.$className;
                    $path = $appModulePath.'/'.$className.'.php';
                    break;
                }
                elseif (file_exists($commonModulePath.'/'.$className.'.php')) {
                    $namespace = $application->getCommonModuleNamespace($moduleName).'\\'.$baseNamespace.'\\'.$className;
                    $path = $commonModulePath.'/'.$className.'.php';
                    break;
                }
            }
        }

        // Register namespace before return it
        (new \Phalcon\Loader())
            ->registerClasses([$namespace => $path])
            ->register();

        return $namespace;
    }

    /**
     * Get class path based on namespace.
     * /!\ This use a lowercase version of PSR-4 standard for folder's name
     *
     * @param string $baseNamespace
     * @return string
     */
    private function buildNamespacePath(string $baseNamespace) {
        $namespacePath = [];
        foreach (explode('\\', $baseNamespace) as $namespace_folder) {
            if (!empty($namespace_folder)) $namespacePath[] = Str::uncamelize($namespace_folder, '_');
        }
        return implode('/', $namespacePath);
    }

}