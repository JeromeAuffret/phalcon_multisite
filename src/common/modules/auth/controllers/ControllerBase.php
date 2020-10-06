<?php

namespace Common\Modules\Auth\Controllers;

use Controllers\BaseController;
use Phalcon\Mvc\View;


class ControllerBase extends BaseController
{
	/**
	 *
	 */
	public function initialize()
	{
		$this->view->disableLevel(
    		View::LEVEL_LAYOUT
		);
	}
}
