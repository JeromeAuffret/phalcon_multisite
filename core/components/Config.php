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
        $this->mergeConfigFile(COMMON_PATH.'/config/main.php');
        $this->mergeConfigFile(COMMON_PATH.'/config/config.php');
        $this->mergeConfigFile(COMMON_PATH.'/config/modules.php', 'modules');
    }

    /**
     * Merge and register configuration for a registered or given application
     *
     * @param string|null $application_slug
     * @return void
     */
    public function registerApplicationConfig(string $application_slug = null)
    {
        $application_path = Di::getDefault()->get('session')->getApplicationPath($application_slug);

        $this->mergeConfigFile($application_path.'/config/main.php');
        $this->mergeConfigFile($application_path.'/config/config.php');
        $this->mergeConfigFile($application_path.'/config/modules.php', 'modules');
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