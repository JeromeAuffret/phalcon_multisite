<?php

namespace Common\Modules\Admin\Controllers;

use Controllers\BaseController;
use Models\Application;


class ControllerBase extends BaseController
{
    protected $id_application;

    /* @var Application $application */
    protected $application;


    public function initialize()
    {
        $this->application = Application::findFirst(
            $this->session->getApplication('id')
        );
    }

}
