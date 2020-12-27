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
     * @param string|null $subFolder
     * @return Response|ResponseInterface
     */
    public function jsAction(string $filePath, ?string $subFolder = null)
    {
        $file = BASE_PATH.'/src/base/dist/'.$filePath;

        // Setting up the content type
//        $this->response->setHeader('Cache-Control', 'max-age=1000');
        $this->response->setContentType('application/javascript', 'UTF-8');

        if (file_exists($file)) {
            $this->response->setContent(
                file_get_contents($file)
            );
        } else {
            $this->response->setStatusCode(404);
        }

        return $this->response;
    }

    /**
     * @param string $filePath
     *
     * @return Response|ResponseInterface
     */
    public function cssAction(string $filePath)
    {
        $file = BASE_PATH.'/dist/css/'.$filePath;

        // Setting up the content type;
        $this->response->setContentType('text/css', 'UTF-8');

        if (file_exists($file)) {
            $this->response->setContent(
                file_get_contents($file)
            );
        } else {
            $this->response->setStatusCode(404);
        }

        return $this->response;
    }

}