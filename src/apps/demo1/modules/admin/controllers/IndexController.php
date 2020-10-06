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
        $this->view->setVar('count_user', 15);
        $this->view->setVar('count_user_actif ', 20);
    }

}
