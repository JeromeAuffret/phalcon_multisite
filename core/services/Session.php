<?php

namespace Service;

use Component\Session as SessionComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Stream as SessionAdapter;

/**
 * Class Session
 *
 * @package Service
 */
class Session implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('session', function ()
        {
            $manager = new SessionComponent();
            $manager
                ->setAdapter(new SessionAdapter())
                ->setName('auth-session')
                ->start();

            return $manager;
        });

        // Register application service if it is store in session
        if ($container->get('session')->hasApplication())
        {
            $container->get('application')->setupApplication(
                $container->get('session')->getApplication('slug')
            );
        }
    }

}