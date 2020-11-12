<?php

namespace Demo1\Modules\Admin\Controllers;

use Base\Modules\Admin\Controllers\IndexController as BaseIndexController;


class IndexController extends BaseIndexController
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
