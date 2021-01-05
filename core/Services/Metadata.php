<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Model\MetaData\Memory;
use Phalcon\Mvc\Model\MetaData\Strategy\Annotations;

/**
 * Class Database
 *
 * @package Core\Services
 */
class Metadata implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('modelsMetadata', function () {
            $metadata = new Memory();
            $metadata->setStrategy(new Annotations());

            return $metadata;
        });
    }

}