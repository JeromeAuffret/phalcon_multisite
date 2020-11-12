<?php

namespace Base\Modules\Admin\Controllers;


class IndexController extends ControllerBase
{

    /**
     *
     */
    public function indexAction()
    {
        $this->view->count_users = 0;
        $this->view->count_active_users = 0;
    }

}
