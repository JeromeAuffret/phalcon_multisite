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
        $basePath = $this->application->getBasePath();
        $tenantPath = $this->application->getTenantPath() ?: $basePath;

        if (file_exists($tenantPath.'/dist/js/'.$fileName)) {
            $this->response->setContent(file_get_contents($tenantPath.'/dist/js/'.$fileName));
        }
        elseif (file_exists($basePath.'/dist/js/'.$fileName)) {
            $this->response->setContent(file_get_contents($basePath.'/dist/js/'.$fileName));
        }
        else {
            $this->response->setStatusCode(404);
        }

        $this->response->setHeader('Cache-Control', 'max-age=60');
        $this->response->setContentType('application/javascript', 'UTF-8');
        return $this->response;
    }

    /**
     * @param string $fileName
     *
     * @return Response|ResponseInterface
     */
    public function cssAction(string $fileName)
    {
        $basePath = $this->application->getBasePath();
        $tenantPath = $this->application->getTenantPath() ?: $basePath;

        $this->response->setHeader('Cache-Control', 'max-age=60');
        $this->response->setContentType('text/css', 'UTF-8');

        if (file_exists($tenantPath.'/dist/css/'.$fileName)) {
            $this->response->setContent(file_get_contents($tenantPath.'/dist/css/'.$fileName));
        }
        elseif (file_exists($basePath.'/dist/css/'.$fileName)) {
            $this->response->setContent(file_get_contents($basePath.'/dist/css/'.$fileName));
        }
        else {
            $this->response->setStatusCode(404);
        }

        $this->response->setHeader('Cache-Control', 'max-age=60');
        $this->response->setContentType('text/css', 'UTF-8');
        return $this->response;
    }

}