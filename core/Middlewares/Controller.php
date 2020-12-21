<?php

namespace Core\Middlewares;

use Core\Components\Application;
use Core\Components\Config;
use Core\Helpers\NamespaceHelper;
use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use Phalcon\Di\Injectable;
use ReflectionException;

/**
 * Class Controller
 *
 * @property Application application
 * @property Config $config
 * @property Dispatcher dispatcher
 * @package Middleware
 */
class Controller extends Injectable
{

    /**
     * Dispatch controller between Base and Tenant namespaces
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws DispatchException
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controllerClass = NamespaceHelper::toTenantNamespace(
            $this->dispatcher->getControllerClass()
        );

        try {
            $this->dispatcher->setNamespaceName((new \ReflectionClass($controllerClass))->getNamespaceName());
        }
        catch (ReflectionException $e) {
            throw new DispatchException('Controller '.$controllerClass.' not found', DispatchException::EXCEPTION_HANDLER_NOT_FOUND, $e);
        }
    }


    /************************************************************
     *
     *                   CONTROLLER MIDDLEWARES
     *
     ************************************************************/

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // Disable view for call ajax or external call
        $this->isViewDisabled();

        // Display maintenance page if defined in config
        $this->displayMaintenancePage();

        // Controller views directories
        $this->dispatchViews();

        // Initialise Assets collections
        $this->setupAssetsCollection();
    }

    /**
     * If request is ajax we disable views
     */
    private function isViewDisabled()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
        }
    }

    /**
     * Display maintenance page if defined in config
     */
    private function displayMaintenancePage()
    {
        if ($this->config->get('maintenance')) {
            $this->view->setMainView('maintenance');
        }
    }


    /************************************************************
     *
     *                          VIEW
     *
     ************************************************************/

    /**
     * Controller views between base/application folders
     */
    public function dispatchViews()
    {
        $application = $this->getDI()->get('application');
        $moduleName = $this->router->getModuleName();

        $baseViewPath = $application->getBasePath().'/Views/';
        $baseModuleViewPath = $application->getBaseModulePath($moduleName).'/views';

        $appViewPath = $application->getTenantPath().'/views';
        $appModuleViewPath = $application->getTenantModulePath($moduleName).'/views';

        $this->dispatchMainView($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath);
        $this->dispatchLayoutDir($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath);
        $this->dispatchViewsDir($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath);
    }

    /**
     * Set default view main
     *
     * @param $baseViewPath
     * @param $baseModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchMainView($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath)
    {
        if (file_exists($appModuleViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($appModuleViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($appViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($appViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($baseModuleViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($baseModuleViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($baseViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($baseViewPath.'/'.$this->view->getMainView());
        }
    }

    /**
     * Set default layouts directory
     *
     * @param $baseViewPath
     * @param $baseModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchLayoutDir($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath)
    {
        if (!$this->view->getLayout()) {
            $this->view->setLayout('main');
        }

        if (file_exists($appModuleViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($appModuleViewPath.'/layouts/');
        }
        elseif (file_exists($appViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($appViewPath.'/layouts/');
        }
        elseif (file_exists($baseModuleViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($baseModuleViewPath.'/layouts/');
        }
        elseif (file_exists($baseViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($baseViewPath.'/layouts/');
        }
    }

    /**
     * Set default views directory
     *
     * @param $baseViewPath
     * @param $baseModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchViewsDir($baseViewPath, $baseModuleViewPath, $appViewPath, $appModuleViewPath)
    {
        $controllerName = $this->dispatcher->getControllerName();
        $actionName = $this->dispatcher->getActionName();

        if (file_exists($appModuleViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($appModuleViewPath);
        }
        elseif (file_exists($appViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($appViewPath);
        }
        elseif (file_exists($baseModuleViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($baseModuleViewPath);
        }
        elseif (file_exists($baseViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($baseViewPath);
        }
    }


    /************************************************************
     *
     *                     ASSETS COLLECTION
     *
     ************************************************************/

    /**
     * Setup Assets collection
     * Add mains libraries / application assets / specific view collection
     */
    public function setupAssetsCollection()
    {
        $this->setMainCollection();
        $this->setFrameworkCollection();
        $this->setTenantCollection();
        $this->setViewCollection();
    }

    /**
     * Initialize assets collection and upload main scripts
     */
    public function setMainCollection()
    {
        $this->assets->collection('main_style')
            ->addCss('img/favicon.ico')
            ->join(true);

        $this->assets->collection('main_script')
            ->join(true);
    }

    /**
     * Initialize application libraries
     */
    public function setFrameworkCollection()
    {
        $frk_style = $this->assets->collection('frk_style');
        $frk_script = $this->assets->collection('frk_script');

        $frk_style
            ->setTargetPath('temp/application.css')
            ->setTargetUri('temp/application.css')
            ->setLocal(true)
            ->join(true)
            ->addFilter(new Cssmin());

        $frk_script
            ->setTargetPath('temp/application.js')
            ->setTargetUri('temp/application.js')
            ->setLocal(true)
            ->join(true)
            ->addFilter(new Jsmin());
    }

    /**
     * Initialize application general assets defined in application asset's folder
     */
    public function setTenantCollection()
    {
        $assetPath = $this->getTenantAssetPath();

        $app_style = $this->assets->collection('app_style');
        $app_script = $this->assets->collection('app_script');

        $app_style
            ->setTargetPath($assetPath.'/app.css')
            ->setTargetUri($assetPath.'/app.css')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Cssmin());

        $app_script
            ->setTargetPath($assetPath.'/index.js')
            ->setTargetUri($assetPath.'/index.js')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Jsmin());

        /**
         * Load specific view assets base on routing process
         */
        $this->view->registerTenantAssetsCollection($app_style, 'css');
        $this->view->registerTenantAssetsCollection($app_script, 'js');
    }

    /**
     * Initialize dynamic assets loading
     * Merge given files to assets loading and minify
     */
    public function setViewCollection()
    {
        $assetPath = $this->getTenantAssetPath();

        $view_style = $this->assets->collection('view_style');
        $view_script = $this->assets->collection('view_script');

        $view_style
            ->setTargetPath($assetPath.'/'.$this->getViewCollectionName().'.css')
            ->setTargetUri($assetPath.'/'.$this->getViewCollectionName().'.css')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Cssmin());

        $view_script
            ->setTargetPath($assetPath.'/'.$this->getViewCollectionName().'.js')
            ->setTargetUri($assetPath.'/'.$this->getViewCollectionName().'.js')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Jsmin());

        $view_script
            ->addJs($this->application->getBasePath().'/dist/js/chunk-vendors.js')
            ->addJs($this->application->getBasePath().'/dist/js/app.js');

        /**
         * Load specific view assets base on routing process
         */
        $this->view->registerViewAssetsCollection($view_style, 'css');
        $this->view->registerViewAssetsCollection($view_script, 'js');
    }


    /************************************************************
     *
     *                         HELPERS
     *
     ************************************************************/

    /**
     *
     */
    public function getViewCollectionName(): string
    {
        $module = $this->dispatcher->getModuleName();
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName();

        return $module.'_'.$controller.'_'.$action;
    }

    /**
     *
     */
    public function getTenantAssetPath(): string
    {
        $assetPath = 'temp/'.($this->application->getTenantSlug() ?: 'shared');

        if (!is_dir($assetPath)) {
            mkdir($assetPath, 2777);
        }

        return $assetPath;
    }

}