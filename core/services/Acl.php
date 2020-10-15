<?php

namespace Service;

use Component\Acl as AclComponent;
use Phalcon\Acl\Adapter\Memory as AclAdapter;
use Phalcon\Acl\Enum;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Acl
 *
 * @package Service
 */
class Acl implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('acl', function ()
        {
            $acl = new AclComponent();
            $acl->setAdapter(new AclAdapter());
            $acl->setDefaultAction(Enum::DENY);

            return $acl;
        });

        // Register common acl
        $container->get('acl')->registerMainAcl();
    }

}