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
    /**
     * @var integer $id_application
     */
    protected $id_application;

    /**
     * @var Application $applicationObject
     */
    protected $applicationObject;

    /**
     *
     */
    public function initialize()
    {
        $this->applicationObject = Application::findFirst(
            $this->application->getApplication('id')
        );
    }

}
