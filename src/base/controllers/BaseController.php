<?php

namespace Base\Controllers;

use Core\Components\Application;
use Core\Components\Acl;
use Core\Components\Config;
use Core\Components\View;
use Core\Controllers\Controller;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Mvc\Dispatcher as DispatcherMvc;

/**
 * Class BaseController
 *
 * @property Acl acl
 * @property Application application
 * @property Config config
 * @property DispatcherMvc dispatcher
 * @property View view
 * @property AdapterInterface main_db
 *
 * @package Base\Controllers
 */
class BaseController extends Controller
{

}
