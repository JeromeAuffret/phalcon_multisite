<?php

namespace Controllers;

use Component\Acl;
use Component\Config;
use Component\Loader;
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
 * @property Loader loader
 * @property Acl acl
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
        // Display maintenance page if defined in config
        $this->displayMaintenancePage();

        // Defined controller as Acl Resource
        $this->setComponentName();

        // Dispatch views directories
        $this->dispatchViews();

        // Initialise Assets collections
        if (!$this->view->isDisabled()) {
            $this->setupAssetsCollection();
        }
    }


    /************************************************************
     *
     *                      COMPONENT AWARE
     *
     ************************************************************/

    /**
     *  Set component name with pattern {module}_{controller}_{action}
     */
    public function setComponentName()
    {
        $this->component_name = $this->dispatcher->getModuleName().'_'.$this->dispatcher->getControllerName().'_'.$this->dispatcher->getActionName();
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
     *  Dispatch views between common/application folders
     */
    public function dispatchViews()
    {
        $application_path = $this->session->getApplicationPath();
        $module = $this->dispatcher->getModuleName();

        $app_path = $application_path.'/views';
        $app_module_path = $application_path.'/modules/'.$module.'/views';

        $common_path = COMMON_PATH.'/views/';
        $common_module_path = COMMON_PATH.'/modules/'.$module.'/views';

        $this->dispatchMainView($common_path, $common_module_path, $app_path, $app_module_path);
        $this->dispatchLayoutDir($common_path, $common_module_path, $app_path, $app_module_path);
        $this->dispatchViewsDir($common_path, $common_module_path, $app_path, $app_module_path);
    }

    /**
     *  Set default view main
     *
     * @param $common_path
     * @param $common_module_path
     * @param $app_path
     * @param $app_module_path
     */
    public function dispatchMainView($common_path, $common_module_path, $app_path, $app_module_path)
    {
        if (file_exists($app_module_path.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($app_module_path.'/'.$this->view->getMainView());
        } 
        elseif (file_exists($app_path.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($app_path.'/'.$this->view->getMainView());
        }
        elseif (file_exists($common_module_path.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($common_module_path.'/'.$this->view->getMainView());
        }
        elseif (file_exists($common_path.'/'.$this->view->getMainView().'.phtml')) {
            $this->view->setMainView($common_path.'/'.$this->view->getMainView());
        }
    }

    /**
     *  Set default layouts directory
     *
     * @param $common_path
     * @param $common_module_path
     * @param $app_path
     * @param $app_module_path
     */
    public function dispatchLayoutDir($common_path, $common_module_path, $app_path, $app_module_path)
    {
        if (!$this->view->getLayout()) {
            $this->view->setLayout('main');
        }

        if (file_exists($app_module_path.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($app_module_path.'/layouts/');
        } 
        elseif (file_exists($app_path.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($app_path.'/layouts/');
        } 
        elseif (file_exists($common_module_path.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($common_module_path.'/layouts/');
        } 
        elseif (file_exists($common_path.'/layouts/'.$this->view->getLayout().'.phtml')) {
            $this->view->setLayoutsDir($common_path.'/layouts/');
        }
    }

    /**
     *  Set default views directory
     *
     * @param $common_path
     * @param $common_module_path
     * @param $app_path
     * @param $app_module_path
     */
    public function dispatchViewsDir($common_path, $common_module_path, $app_path, $app_module_path)
    {
        $controller_name = $this->dispatcher->getControllerName();
        $action_name = $this->dispatcher->getActionName();

        if (file_exists($app_module_path.'/'.$controller_name.'/'.$action_name.'.phtml')) {
            $this->view->setViewsDir($app_module_path);
        } 
        elseif (file_exists($app_path.'/'.$controller_name.'/'.$action_name.'.phtml')) {
            $this->view->setViewsDir($app_path);
        } 
        elseif (file_exists($common_module_path.'/'.$controller_name.'/'.$action_name.'.phtml')) {
            $this->view->setViewsDir($common_module_path);
        } 
        elseif (file_exists($common_path.'/'.$controller_name.'/'.$action_name.'.phtml')) {
            $this->view->setViewsDir($common_path);
        }
    }


    /************************************************************
     *
     *                      ASSETS COLLECTION
     *
     ************************************************************/

    /**
     *  Setup Assets collection
     *  Add mains libraries / application assets / specific view collection
     */
    public function setupAssetsCollection()
    {
        $this->setMainCollection();
        $this->setFrameworkCollection();
        $this->setApplicationCollection();
        $this->setViewCollection();
    }

    /**
     *  Initialize assets collection and upload main scripts
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
     *  Initialize application libraries
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
     *  Initialize application general assets defined in application asset's folder
     */
    public function setApplicationCollection()
    {
        $asset_path = $this->getApplicationAssetPath();

        $app_style = $this->assets->collection('app_style');
        $app_script = $this->assets->collection('app_script');

        $app_style
            ->setTargetPath($asset_path.'/app.css')
            ->setTargetUri($asset_path.'/app.css')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Cssmin());

        $app_script
            ->setTargetPath($asset_path.'/app.js')
            ->setTargetUri($asset_path.'/app.js')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Jsmin());

        /**
         *     Load specific view assets base on routing process
         */
        $this->loader->registerApplicationAssetsCollection($app_style, 'css');
        $this->loader->registerApplicationAssetsCollection($app_script, 'js');
    }

    /**
     *  Initialize dynamic assets loading
     *  Merge given files to assets loading and minify
     */
    public function setViewCollection()
    {
        $asset_path = $this->getApplicationAssetPath();

        $view_style = $this->assets->collection('view_style');
        $view_script = $this->assets->collection('view_script');

        $view_style
            ->setTargetPath($asset_path.'/'.$this->getViewCollectionName().'.css')
            ->setTargetUri($asset_path.'/'.$this->getViewCollectionName().'.css')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Cssmin());

        $view_script
            ->setTargetPath($asset_path.'/'.$this->getViewCollectionName().'.js')
            ->setTargetUri($asset_path.'/'.$this->getViewCollectionName().'.js')
            ->setLocal(false)
            ->join(true)
            ->addFilter(new Jsmin());

        /**
         *     Load specific view assets base on routing process
         */
        $this->loader->registerViewAssetsCollection($view_style, 'css');
        $this->loader->registerViewAssetsCollection($view_script, 'js');
    }


    /************************************************************
     *
     *                          HELPERS
     *
     ************************************************************/

    /**
     * Display maintenance page if defined in config
     */
    public function displayMaintenancePage()
    {
        if ($this->config->get('maintenance')) {
            $this->view->setMainView('maintenance');
        }
    }

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
        $asset_path = 'temp/'.($this->session->getApplication('slug') ?: 'shared');

        if (!is_dir($asset_path)) {
            mkdir($asset_path, 2777);
        }

        return $asset_path;
    }

}
