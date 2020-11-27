<?php

namespace Core\Components;

use Phalcon\Di;
use Phalcon\Config\ConfigFactory;
use Phalcon\Di\DiInterface;

/**
 * Class Config
 *
 * @package Core\Components
 */
final class Config extends \Phalcon\Config
{

    /**
     * Merge and register main configuration
     *
     * @param DiInterface $container
     */
    public function registerMainConfig(DiInterface $container)
    {
        $basePath = $container->get('application')->getBasePath();

        $this->mergeConfigFile($basePath.'/config/main.php');
        $this->mergeConfigFile($basePath.'/config/config.php');
    }

    /**
     * Merge and register configuration for a registered or given application
     *
     * @param DiInterface $container
     * @return void
     */
    public function registerTenantConfig(DiInterface $container)
    {
        $tenantPath = $container->get('application')->getTenantPath();

        $this->mergeConfigFile($tenantPath.'/config/main.php');
        $this->mergeConfigFile($tenantPath.'/config/config.php');
    }

    /**
     * @param string     $file_path
     * @param mixed|null $key
     * @return void
     */
    public function mergeConfigFile(string $file_path, $key = null)
    {
        if (!file_exists($file_path)) return;

        $config = $this;

        if ($key) {
            if (!$config->offsetExists($key)) $config->offsetSet($key, new Config());
            $config = $config->get($key);
        }

        $factory = new ConfigFactory();
        $config_file = $factory->load($file_path);

        $config->merge($config_file);
    }

}