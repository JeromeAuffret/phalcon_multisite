<?php

namespace Demo1\Modules\Dashboard\Controllers;

use Base\Controllers\BaseController;
use Demo1\Models\Lot;

/**
 * Class IndexController
 *
 * @package Demo1\Modules\Dashboard\Controllers
 */
class IndexController extends BaseController
{

    /**
     *
     */
    public function indexAction() {}

    /**
     *
     */
    public function tableDataAction()
    {
        $data = file_get_contents('https://raw.githubusercontent.com/ag-grid/ag-grid/master/grid-packages/ag-grid-docs/src/olympicWinnersSmall.json');

        $result = Lot::find([
            'limit' => 100
        ]);

        $this->response->setJsonContent($result->toArray());
        return $this->response;
    }

}
