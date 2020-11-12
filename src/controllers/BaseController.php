<?php

namespace Base\Controllers;

use Component\Acl;
use Component\Config;
use Mvc\Dispatcher as DispatcherMvc;
use Component\View;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Mvc\Controller;
use Mvc\Application;

/**
 * Class BaseController
 *
 * @property Acl acl
 * @property Application application
 * @property Config config
 * @property DispatcherMvc dispatcher
 * @property View view
 * @property AdapterInterface main_db
 * @package Controllers
 */
class BaseController extends Controller
{

}
