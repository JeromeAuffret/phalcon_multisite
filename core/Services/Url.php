<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Url as UrlResolver;

/**
 * Class Url
 *
 * @package Core\Services
 */
class Url implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('url', function () use ($container)
        {
            $url = new UrlResolver();
            $url->setBaseUri(
                $container->get('config')->get('baseUri')
            );

            return $url;
        });
    }

}