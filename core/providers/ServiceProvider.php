<?php

namespace Provider;

use Phalcon\Di\Injectable;
use Service\Acl as AclService;
use Service\Application as ApplicationService;
use Service\Config as ConfigService;
use Service\Database as DbService;
use Service\Dispatcher as DispatcherService;
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
     *  Default services
     */
    public function registerServices()
    {
        $container = $this->getDI();

        (new ApplicationService)->register($container);

        (new SessionService)->register($container);

        (new ConfigService)->register($container);

        (new DbService)->register($container);

        (new AclService)->register($container);

        (new DispatcherService)->register($container);

        (new ViewService)->register($container);

        (new UrlService)->register($container);

        (new RouterService)->register($container);
    }

}