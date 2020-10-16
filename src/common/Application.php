<?php

namespace Common;

use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;


class Application extends ApplicationProvider
{

    /**
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

}
