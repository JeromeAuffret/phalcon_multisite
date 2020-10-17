<?php

namespace Common;

use Phalcon\Di\DiInterface;
use Provider\ApplicationProvider;

class Application extends ApplicationProvider
{
    /**
     * @Override
     *
     * Disabled specific application services in common
     *
     * @param DiInterface|null $container
     */
    public function registerServices(DiInterface $container = null) {}
}
