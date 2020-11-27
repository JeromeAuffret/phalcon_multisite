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
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('view', function () use ($container)
        {
            $application = $container->get('application');

            $view = new ViewComponent();
            $view
                ->setViewsDir($application->getBasePath() . '/views')
                ->registerEngines([
                    '.phtml' => \Phalcon\Mvc\View\Engine\Php::class
                ]);

            return $view;
        });
    }

}