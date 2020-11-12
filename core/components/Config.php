<?php

namespace Component;

use Phalcon\Di;
use Phalcon\Config\ConfigFactory;

/**
 * Class Config
 *
 * @package Component
 */
final class Config extends \Phalcon\Config
{

    /**
     *  Merge and register main configuration
     */
    public function registerMainConfig()
    {
        $basePath = Di::getDefault()->get('application')->getBasePath();

        $this->mergeConfigFile($basePath.'/config/main.php');
        $this->mergeConfigFile($basePath.'/config/config.php');
    }

    /**
     * Merge and register configuration for a registered or given application
     *
     * @return void
     */
    public function registerApplicationConfig()
    {
        $applicationPath = Di::getDefault()->get('application')->getApplicationPath();

        $this->mergeConfigFile($applicationPath.'/config/main.php');
        $this->mergeConfigFile($applicationPath.'/config/config.php');
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