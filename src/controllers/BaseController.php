<?php

namespace Controllers;

use Component\Acl;
use Component\Application;
use Component\Config;
use Component\Session;
use Component\View;
use Phalcon\Acl\ComponentAware;
use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

/**
 * Class BaseController
 *
 * @property Acl acl
 * @property Application application
 * @property Session session
 * @property Config config
 * @property View view
 * @property AdapterInterface main_db
 * @package Controllers
 */
class BaseController extends Controller implements ComponentAware
{
    // ResourceAware implementation
    protected $component_name;

    /************************************************************
     *
     *                   CONTROLLER MIDDLEWARES
     *
     ************************************************************/

    /**
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // Disable view for call ajax or external call
        $this->isViewDisabled();

        // Display maintenance page if defined in config
        $this->displayMaintenancePage();

        // Defined controller as Acl Resource
        $this->setComponentName();

        // Dispatch views directories
        $this->dispatchViews();

        // Initialise Assets collections
        $this->setupAssetsCollection();
    }

    /**
     * Easy access to destroy session in beforeDispatch Middleware
     */
    public function _logoutAction() {}

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
    public function displayMaintenancePage()
    {
        if ($this->config->get('maintenance')) {
            $this->view->setMainView('maintenance');
        }
    }


    /************************************************************
     *
     *                      COMPONENT AWARE
     *
     ************************************************************/

    /**
     * Set component name with pattern {module}_{controller}_{action}
     */
    public function setComponentName()
    {
        if ($this->config->get('applicationType') === 'modules') {
            $this->component_name = $this->dispatcher->getModuleName().'_'.$this->dispatcher->getControllerName().'_'.$this->dispatcher->getActionName();
        } elseif ($this->config->get('applicationType') === 'simple') {
            $this->component_name = $this->dispatcher->getControllerName().'_'.$this->dispatcher->getActionName();
        }
    }

    /**
     * @return string
     */
    public function getComponentName(): string
    {
        return $this->component_name;
    }


    /************************************************************
     *
     *                          VIEW
     *
     ************************************************************/

    /**
     * Dispatch views between common/application folders
     */
    public function dispatchViews()
    {
        $application = $this->getDI()->get('application');
        $moduleName = $this->router->getModuleName();

        $commonViewPath = $application->getCommonPath().'/views/';
        $commonModuleViewPath = $application->getCommonModulePath($moduleName).'/views';

        $appViewPath = $application->getApplicationPath().'/views';
        $appModuleViewPath = $application->getApplicationModulePath($moduleName).'/views';

        $this->dispatchMainView($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath);
        $this->dispatchLayoutDir($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath);
        $this->dispatchViewsDir($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath);
    }

    /**
     * Set default view main
     *
     * @param $commonViewPath
     * @param $commonModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchMainView($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath)
    {
        if (file_exists($appModuleViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($appModuleViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($appViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($appViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($commonModuleViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($commonModuleViewPath.'/'.$this->view->getMainView());
        }
        elseif (file_exists($commonViewPath.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($commonViewPath.'/'.$this->view->getMainView());
        }
    }

    /**
     * Set default layouts directory
     *
     * @param $commonViewPath
     * @param $commonModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchLayoutDir($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath)
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
        elseif (file_exists($commonModuleViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($commonModuleViewPath.'/layouts/');
        }
        elseif (file_exists($commonViewPath.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($commonViewPath.'/layouts/');
        }
    }

    /**
     * Set default views directory
     *
     * @param $commonViewPath
     * @param $commonModuleViewPath
     * @param $appViewPath
     * @param $appModuleViewPath
     */
    public function dispatchViewsDir($commonViewPath, $commonModuleViewPath, $appViewPath, $appModuleViewPath)
    {
        $controllerName = $this->dispatcher->getControllerName();
        $actionName = $this->dispatcher->getActionName();

        if (file_exists($appModuleViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($appModuleViewPath);
        }
        elseif (file_exists($appViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($appViewPath);
        }
        elseif (file_exists($commonModuleViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($commonModuleViewPath);
        }
        elseif (file_exists($commonViewPath.'/'.$controllerName.'/'.$actionName.'.phtml')) {
            $this->view->setViewsDir($commonViewPath);
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
        $this->setApplicationCollection();
        $this->setViewCollection();
    }

    /**
     * Initialize assets collection and upload main scripts
     */
    public function setMainCollection()
    {
        $this->assets->collection('main_style')
            ->addCss('img/favicon.png')
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
    public function setApplicationCollection()
    {
        $assetPath = $this->getApplicationAssetPath();

        $app_style = $this->assets->collection('app_style');
        $app_script = $this->assets->collection('app_script');

        $app_style
            ->setTargetPath($assetPath.'/app.css')
            ->setTargetUri($assetPath.'/app.css')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Cssmin());

        $app_script
            ->setTargetPath($assetPath.'/app.js')
            ->setTargetUri($assetPath.'/app.js')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Jsmin());

        /**
         * Load specific view assets base on routing process
         */
        $this->view->registerApplicationAssetsCollection($app_style, 'css');
        $this->view->registerApplicationAssetsCollection($app_script, 'js');
    }

    /**
     * Initialize dynamic assets loading
     * Merge given files to assets loading and minify
     */
    public function setViewCollection()
    {
        $assetPath = $this->getApplicationAssetPath();

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
    public function getViewCollectionName()
    {
        $module = $this->dispatcher->getModuleName();
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName();

        return $module.'_'.$controller.'_'.$action;
    }

    /**
     *
     */
    public function getApplicationAssetPath()
    {
        $assetPath = 'temp/'.($this->getDI()->get('application')->getApplicationSlug() ?: 'shared');

        if (!is_dir($assetPath)) {
            mkdir($assetPath, 2777);
        }

        return $assetPath;
    }

}
