<?php

namespace Core\Controllers;

use Core\Components\Acl;
use Core\Components\Application;
use Core\Components\Config;
use Core\Components\View;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Mvc\Dispatcher as DispatcherMvc;

/**
 * Class Controller
 *
 * @property Acl acl
 * @property Application application
 * @property Config config
 * @property DispatcherMvc dispatcher
 * @property View view
 * @property AdapterInterface main_db
 *
 * @package Core\Controllers
 */
class Controller extends \Phalcon\Mvc\Controller
{

}