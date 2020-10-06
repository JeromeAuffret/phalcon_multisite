<?php

namespace Service;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Escaper;
use Phalcon\Flash\Direct as FlashAdapter;


/**
 * Class Flash
 *
 * @package Service
 */
class Flash implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->set('flash', function ()
        {
            $escaper = new Escaper();
            $flash = new FlashAdapter($escaper);

            $flash->setImplicitFlush(false);
            $flash->setCssClasses([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            return $flash;
        });
    }

}