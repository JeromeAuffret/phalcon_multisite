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
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('url', function () use ($di) {
            $url = new UrlResolver();
            $url->setBaseUri(
                $di->get('config')->get('baseUri')
            );

            return $url;
        });
    }

}