<?php

namespace Component;

/**
 * Class Router
 *
 * @package Component
 */
class Router extends \Phalcon\Mvc\Router
{

    /*************************************************************
     *
     *                          REGISTER
     *
     *************************************************************/

    /**
     * @param string $filePath
     */
    public function registerRouterFromFile(string $filePath)
    {
        if (file_exists($filePath)) include_once $filePath;
    }

}