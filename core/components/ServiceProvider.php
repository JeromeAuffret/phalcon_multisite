<?php

namespace Component;

use Phalcon\Di\FactoryDefault;
use Phalcon\Di\Injectable;

use Service\Acl as AclService;
use Service\Application as ApplicationService;
use Service\Config as ConfigService;
use Service\Database as DbService;
use Service\Dispatcher as DispatcherService;
use Service\Flash as FlashService;
use Service\Loader as LoaderService;
use Service\Router as RouterService;
use Service\Session as SessionService;
use Service\Url as UrlService;
use Service\View as ViewService;

/**
 * Class ServiceProvider
 *
 * @package Component
 */
final class ServiceProvider extends Injectable
{

    /**
     *
     */
    public function registerServices()
    {
        $container = $this->getDI();

        (new ApplicationService)->register($container);

        (new SessionService)->register($container);

        (new ConfigService)->register($container);

        (new DbService)->register($container);

        (new LoaderService)->register($container);

        (new AclService)->register($container);

        (new DispatcherService)->register($container);

        (new ViewService)->register($container);

        (new FlashService)->register($container);

        (new UrlService)->register($container);

        (new RouterService)->register($container);
    }

}