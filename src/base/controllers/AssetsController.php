<?php

namespace Base\Controllers;

use Core\Controllers\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

/**
 * Class LogoutController
 *
 * @package Controllers
 */
class AssetsController extends Controller
{

    /**
     *  Disabled view for assets delivery controller
     */
    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * @param string $fileName
     * @param string|null $subFolder
     * @return Response|ResponseInterface
     */
    public function jsAction(string $fileName, ?string $subFolder = null)
    {
        foreach (glob(BASE_PATH.'/src/base/dist/js/'.$fileName.'.*.js') as $file)
        {
            // Setting up the content type
            $this->response->setContentType('application/javascript', 'UTF-8');
            // $this->response->setHeader('Cache-Control', 'max-age=1000');

            if (file_exists($file)) {
                $this->response->setContent(file_get_contents($file));
            } else {
                $this->response->setStatusCode(404);
            }

            break;
        }

        return $this->response;
    }

    /**
     * @param string $fileName
     *
     * @return Response|ResponseInterface
     */
    public function cssAction(string $fileName)
    {
        foreach (glob(BASE_PATH.'/src/base/dist/css/'.$fileName.'.*.css') as $file)
        {
            // Setting up the content type;
            $this->response->setContentType('text/css', 'UTF-8');
            // $this->response->setHeader('Cache-Control', 'max-age=1000');

            if (file_exists($file)) {
                $this->response->setContent(file_get_contents($file));
            } else {
                $this->response->setStatusCode(404);
            }

            break;
        }

        return $this->response;
    }
}