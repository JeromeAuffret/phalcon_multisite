<?php

namespace Libraries;

use Phalcon\Di;
use Phalcon\Helper\Str;
use ReflectionException;

/**
 * Class DateHelper
 *
 * @package Libraries
 */
final class NamespaceHelper
{
    /**
     * Helper method to dispatch a namespace between base and application folder
     *
     * @param string $classNamespace
     * @return string
     */
    public static function dispatchNamespace(string $classNamespace): string
    {
        $di = Di::getDefault();
        $application = $di->get('application');
        $baseNamespace = $application->getBaseNamespace();

        // Prevent dispatching controller if no application is registered
        if (!$application->hasTenant()) {
            return $classNamespace;
        }

        // If namespace is part of base namespace, we check if override exist in application namespace
        if (substr($classNamespace, 0, strlen($baseNamespace)) === $baseNamespace)
        {
            try {
                $classPath = (new \ReflectionClass($classNamespace))->getFileName();
                $overridePath = str_replace($application->getBasePath(), $application->getTenantPath(), $classPath);

                if (file_exists($overridePath))
                {
                    $classNamespace = str_replace($baseNamespace, $application->getTenantNamespace(), $classNamespace);

                    (new \Phalcon\Loader())
                        ->registerClasses([$classNamespace => $overridePath])
                        ->register();
                }
                // We return default namespace if
            } catch (ReflectionException $e) {
                return $classNamespace;
            }
        }

        return $classNamespace;
    }

    /**
     * Helper method to dispatch a namespace between base and application folder
     *
     * @param string $classPath
     * @return string
     */
    public static function dispatchPath(string $classPath): string
    {
        $di = Di::getDefault();
        $application = $di->get('application');
        $basePath = $application->getBasePath();

        // Prevent dispatching controller if no application is registered
        if (!$application->hasTenant()) {
            return $classPath;
        }

        // If path is part of base, we check if override exist in application folder
        if (substr($classPath, 0, strlen($basePath)) === $application->$basePath())
        {
            $overridePath = str_replace($basePath, $application->getTenantPath(), $classPath);

            if (file_exists($overridePath))
            {
                $classPath = $overridePath;
                $classNamespace = self::parseNamespaceFromFilePath($classPath);

                (new \Phalcon\Loader())
                    ->registerClasses([$classNamespace => $overridePath])
                    ->register();
            }
        }

        return $classPath;
    }

    /**
     * Helper method to dispatch a class between base and application folder
     *
     * @param string $className
     * @param string $prefixNamespace Namespace use to be concatenated between applicationNamespace and className
     * @return string|null
     */
    public static function dispatchClass(string $className, string $prefixNamespace = ''): ?string
    {
        $di = Di::getDefault();
        $application = $di->get('application');
        $config = $di->get('config');

        $basePath = self::buildNamespacePath($prefixNamespace);
        $tenantPath = $application->getTenantPath().'/'.$basePath;
        $basePath = $application->getBasePath().'/'.$basePath;

        $namespace = $path = null;
        if (file_exists($tenantPath.'/'.$className.'.php')) {
            return $application->getTenantNamespace().'\\'.$prefixNamespace.'\\'.$className;
        }
        elseif (file_exists($basePath.'/'.$className.'.php')) {
            return $application->getBaseNamespace().'\\'.$prefixNamespace.'\\'.$className;
        }
        elseif ($config->get('tenantType') === 'modules')
        {
            foreach ($config->get('modules') as $moduleName => $definition)
            {
                $tenantModulePath = $application->getTenantModulePath($moduleName).'/'.$basePath;
                $baseModulePath = $application->getBaseModulePath($moduleName).'/'.$basePath;

                if (file_exists($tenantModulePath.'/'.$className.'.php')) {
                    $namespace = $application->getTenantModuleNamespace($moduleName).'\\'.$prefixNamespace.'\\'.$className;
                    $path = $tenantModulePath.'/'.$className.'.php';
                    break;
                }
                elseif (file_exists($baseModulePath.'/'.$className.'.php')) {
                    $namespace = $application->getBaseModuleNamespace($moduleName).'\\'.$prefixNamespace.'\\'.$className;
                    $path = $baseModulePath.'/'.$className.'.php';
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
     * /!\ This use a snake_case version of PSR-4 standard for folder's name
     *
     * @param string $baseNamespace
     * @return string
     */
    public static function buildNamespacePath(string $baseNamespace): string
    {
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
     *
     * @param $filePath
     * @return string
     */
    public static function parseNamespaceFromFilePath($filePath): string
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
