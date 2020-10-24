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
     * Override common class if exist in application folder
     *
     * @throws \ReflectionException
     */
    public function dispatchControllers()
    {
        $controllerClass = $this->getControllerClass();

        // Error controllers is not overridable
        if (substr($controllerClass, -strlen('ErrorController')) === 'ErrorController') {
            $this->setNamespaceName('Controllers');
            return;
        }

        $controllerClass = $this->dispatchNamespace($controllerClass);
        $this->setNamespaceName((new \ReflectionClass($controllerClass))->getNamespaceName());
    }

    /**
     * Helper method to dispatch a namespace between common and application folder
     *
     * @param string $classNamespace
     * @return string
     * @throws \ReflectionException
     */
    public function dispatchNamespace(string $classNamespace)
    {
        $application = $this->getDI()->get('application');
        $commonNamespace = $application->getCommonNamespace();

        // Prevent dispatching controller if no application is registered
        if (!$application->hasApplication()) {
            return $classNamespace;
        }

        // If namespace is part of common, we check if override exist in application folder
        if (substr($classNamespace, 0, strlen($commonNamespace)) === $commonNamespace)
        {
            $classPath = (new \ReflectionClass($classNamespace))->getFileName();
            $overridePath = str_replace($application->getCommonPath(), $application->getApplicationPath(), $classPath);

            if (file_exists($overridePath))
            {
                $classNamespace = str_replace($commonNamespace, $application->getApplicationNamespace(), $classNamespace);

                (new \Phalcon\Loader())
                    ->registerClasses([$classNamespace => $overridePath])
                    ->register();
            }
        }

        return $classNamespace;
    }

    /**
     * Helper method to dispatch a namespace between common and application folder
     *
     * @param string $classPath
     * @return string
     */
    public function dispatchPath(string $classPath)
    {
        $application = $this->getDI()->get('application');
        $commonPath = $application->getCommonPath();

        // Prevent dispatching controller if no application is registered
        if (!$application->hasApplication()) {
            return $classPath;
        }

        // If path is part of common, we check if override exist in application folder
        if (substr($classPath, 0, strlen($commonPath)) === $application->$commonPath())
        {
            $overridePath = str_replace($commonPath, $application->getApplicationPath(), $classPath);

            if (file_exists($overridePath))
            {
                $classPath = $overridePath;
                $classNamespace = $this->parseNamespaceFromFilePath($classPath);

                (new \Phalcon\Loader())
                    ->registerClasses([$classNamespace => $overridePath])
                    ->register();
            }
        }

        return $classPath;
    }

    /**
     * Helper method to dispatch a class between common and application folder
     *
     * @param string $className
     * @param string $baseNamespace Base namespace use to be concatenated between applicationNamespace and className
     * @return string|null
     */
    public function dispatchClass(string $className, string $baseNamespace = '')
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

    /**
     * Parse the complete file namespace by reading class header
     *
     * https://stackoverflow.com/questions/7153000/get-class-name-from-file
     * @param $filePath
     * @return string
     */
    private function parseNamespaceFromFilePath($filePath)
    {
        $fp = fopen($filePath, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;

        while (!$class)
        {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j=$i+1;$j<count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }

        return $namespace.'\\'.$class;
    }

}