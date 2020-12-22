<?php

namespace Core\Controllers;

use Core\Components\Application;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller;

/**
 * Class LogoutController
 *
 * @property Application application
 * @package Controllers
 */
class AssetsController extends Controller
{

    /**
     *
     */
    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * @param string $filePath
     */
    public function jsAction(string $filePath)
    {
        // Setting up the content type
//        $this->response->setHeader('Cache-Control', 'max-age=1000');
        $this->response->setContentType('application/javascript', 'UTF-8');
        $this->response->setContent(
            file_get_contents(BASE_PATH.'/src/dist/js/'.$filePath)
        );
        return $this->response;
    }

    /**
     * @param string $filePath
     *
     * @return Response|ResponseInterface
     */
    public function cssAction(string $filePath)
    {
        // Setting up the content type;
        $this->response->setContentType('text/css', 'UTF-8');
        $this->response->setContent(
            file_get_contents(BASE_PATH.'/src/dist/css/'.$filePath)
        );

        return $this->response;
    }

}