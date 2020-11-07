<?php

namespace Common\Modules\Admin\Controllers;

use Controllers\BaseController;
use Models\Application;

/**
 * Class ControllerBase
 *
 * @package Common\Modules\Admin\Controllers
 */
class ControllerBase extends BaseController
{
    protected $id_application;

    /* @var Application $application */
    protected $application;


    public function initialize()
    {
        $this->application = Application::findFirst(
            $this->sessionManager->getApplication('id')
        );
    }

}
