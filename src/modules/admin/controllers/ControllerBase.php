<?php

namespace Base\Modules\Admin\Controllers;

use Base\Controllers\BaseController;
use Base\DispatcherMvc;

/**
 * Class ControllerBase
 *
 * @package Base\Modules\Admin\Controllers
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
