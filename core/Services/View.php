<?php

namespace Core\Services;

use Core\Components\View as ViewComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class View
 *
 * @package Core\Services
 */
class View implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('view', function () use ($di) {
            $view = new ViewComponent();
            $view
                ->setViewsDir($di->get('application')->getBasePath() . '//views')
                ->registerEngines([
                    '.phtml' => \Phalcon\Mvc\View\Engine\Php::class
                ]);

            return $view;
        });
    }

}