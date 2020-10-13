<?php

namespace Service;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Url as UrlResolver;

/**
 * Class Url
 *
 * @package Service
 */
class Url implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('url', function ()
        {
            $url = new UrlResolver();
            $url->setBaseUri(
                $this->get('config')->get('baseUri')
            );

            return $url;
        });
    }

}