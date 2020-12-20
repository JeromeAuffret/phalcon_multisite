<?php

namespace Core\Helpers;

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
     * Helper method to find Tenant\Namespace from Base\Namespace
     * Return parameters if class is not correctly registered
     *
     * @param string $classNamespace
     * @return string
     */
    public static function toTenantNamespace(string $classNamespace): string
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
            } catch (ReflectionException $e) {
                return $classNamespace;
            }
        }

        return $classNamespace;
    }

    /**
     * Helper method to find Base\Namespace from Tenant\Namespace
     * Return parameters if class is not correctly registered
     *
     * @param string $classNamespace
     * @return string
     */
    public static function toBaseNamespace(string $classNamespace): string
    {
        $di = Di::getDefault();
        $application = $di->get('application');
        $tenantNamespace = $application->getTenantNamespace();

        // If namespace is part of tenant namespace, we check if override exist in base namespace
        if (substr($classNamespace, 0, strlen($tenantNamespace)) === $tenantNamespace)
        {
            try {
                $classPath = (new \ReflectionClass($classNamespace))->getFileName();
                $overridePath = str_replace( $application->getTenantPath(), $application->getBasePath(), $classPath);

                if (file_exists($overridePath))
                {
                    $classNamespace = str_replace($tenantNamespace, $application->getBaseNamespace(), $classNamespace);

                    (new \Phalcon\Loader())
                        ->registerClasses([$classNamespace => $overridePath])
                        ->register();
                }
            } catch (ReflectionException $e) {
                return $classNamespace;
            }
        }

        return $classNamespace;
    }

    /**
     * Helper method to find tenant/path from shared/path
     * Return parameters if path does not exist
     *
     * @param string $classPath
     * @return string
     */
    public static function findTenantPath(string $classPath): string
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
     * Helper method to dispatch a class name between base and application folder
     * If some modules are registered, it try to resolve modules path
     *
     * This method is based on the PSR-4 standard but resolve folder_name using snake_case format
     *
     * @param string $className
     * @param string $prefixNamespace Namespace is use to be concatenated between applicationNamespace and className
     * @return string|null Return a namespace if class exit in path
     */
    public static function dispatchClass(string $className, string $prefixNamespace = ''): ?string
    {
        $di = Di::getDefault();
        $application = $di->get('application');

        $prefixPath = self::buildNamespacePath($prefixNamespace);
        $tenantPath = $application->getTenantPath().'/'.$prefixPath;
        $basePath = $application->getBasePath().'/'.$prefixPath;

        $namespace = $path = null;
        if (file_exists($tenantPath.'/'.$className.'.php')) {
            $namespace = $application->getTenantNamespace().'\\'.$prefixNamespace.'\\'.$className;
        }
        elseif (file_exists($basePath.'/'.$className.'.php')) {
            $namespace = $application->getBaseNamespace().'\\'.$prefixNamespace.'\\'.$className;
        }

        // Register namespace before return it
        if ($namespace) {
            (new \Phalcon\Loader())
                ->registerClasses([$namespace => $path])
                ->register();
        }

        return $namespace;
    }

    /**
     * Helper method to dispatch a class name between base and application folder
     * If some modules are registered, it try to resolve modules path
     *
     * This method is based on the PSR-4 standard but resolve folder_name using snake_case format
     *
     * @param string $className
     * @param string $moduleName
     * @param string $prefixNamespace Namespace is use to be concatenated between applicationNamespace and className
     * @return string|null Return a namespace if class exit in path
     */
    public static function dispatchModuleClass(string $className, string $moduleName = '', string $prefixNamespace = ''): ?string
    {
        $di = Di::getDefault();
        $application = $di->get('application');
        $namespace = $path = null;

        $prefixPath = self::buildNamespacePath($prefixNamespace);
        $tenantModulePath = $application->getTenantModulePath($moduleName).'/'.$prefixPath;
        $baseModulePath = $application->getBaseModulePath($moduleName).'/'.$prefixPath;

        if (file_exists($tenantModulePath.'/'.$className.'.php')) {
            $namespace = $application->getTenantModuleNamespace($moduleName).'\\'.$prefixNamespace.'\\'.$className;
            $path = $tenantModulePath.'/'.$className.'.php';
        }
        elseif (file_exists($baseModulePath.'/'.$className.'.php')) {
            $namespace = $application->getBaseModuleNamespace($moduleName).'\\'.$prefixNamespace.'\\'.$className;
            $path = $baseModulePath.'/'.$className.'.php';
        }

        // Register namespace before return it
        if ($namespace) {
            (new \Phalcon\Loader())
                ->registerClasses([$namespace => $path])
                ->register();
        }

        return $namespace;
    }

    /**
     * Get class path based on namespace
     *
     * This method is based on the PSR-4 standard but resolve folder_name using snake_case format
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
