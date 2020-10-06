<?php

namespace Demo1\Modules\Admin\Controllers;

use Common\Modules\Admin\Controllers\IndexController as CommonIndexController;


class IndexController extends CommonIndexController
{

    /**
     *
     */
    public function indexAction()
    {
        $this->view->count_users = 15;
        $this->view->count_active_users = 20;
    }

}
