<?php

namespace Service;

use Component\View as ViewComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class View
 *
 * @package Service
 */
class View implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('view', function ()
        {
            $view = new ViewComponent();
            $view
                ->setViewsDir($this->get('application')->getCommonPath() . '/views')
                ->registerEngines([
                    '.phtml' => \Phalcon\Mvc\View\Engine\Php::class
                ]);

            return $view;
        });
    }

}