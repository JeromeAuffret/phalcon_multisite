<?php

namespace Common\Modules\Admin\Controllers;


class IndexController extends ControllerBase
{

    /**
     *
     */
    public function indexAction()
    {
        $this->view->setVar('count_user', 0);
        $this->view->setVar('count_user_actif ', 0);
    }

}
