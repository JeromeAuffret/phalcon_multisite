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
        $results = Lot::find([
            'columns' => [
                'IdLot',
                'NomFlux',
                'Statut',
                'TypeFlux',
                'DateLot',
                'ClefNumLot',
                'NbPlisIdx',
                'NbPlisCons',
                'NbPlisDest'
            ],
            'conditions' => 'NomFlux = "ACTEL-OPT"'
        ]);

        $this->response->setHeader('Cache-Control', 'max-age=60');
        $this->response->setJsonContent($results->jsonSerialize());
        return $this->response;
    }

}
