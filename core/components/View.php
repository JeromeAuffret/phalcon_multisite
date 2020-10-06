<?php

namespace Component;

/**
 * Class View
 *
 * @package Component
 */
final class View extends \Phalcon\Mvc\View
{

    /**
     * @param string $view
     * @param array  $vars
     *
     * @return string|void
     */
    public function getPartial(string $view, $vars = []): string
    {
        $di = $this->getDi();
        $module = $di['router']->getModuleName();
        $app_path = $di->get('session')->getApplicationPath();

        $view_app_path = $app_path.'/views';
        $app_module_path = $app_path.'/modules/'.$module.'/views';
        $view_common_path = COMMON_PATH.'/views';
        $common_module_path = COMMON_PATH.'/modules/'.$module.'/views';

        $view_array = explode('/', $view);

        $partial = end($view_array);
        $partial_key = array_keys($view_array, $partial)[0];

        unset($view_array[$partial_key]);

        $partial_dir = implode('/', $view_array);
        $partial_pattern = '/'.$partial_dir.'/'.$partial.'.phtml';

        if (file_exists($app_module_path.$partial_pattern)) {
            $this->setPartialsDir($app_module_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($view_app_path.$partial_pattern)) {
            $this->setPartialsDir($view_app_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($common_module_path.$partial_pattern)) {
            $this->setPartialsDir($common_module_path.'/'.$partial_dir.'/');
        }
        elseif (file_exists($view_common_path.$partial_pattern)) {
            $this->setPartialsDir($view_common_path.'/'.$partial_dir.'/');
        }

        return $this->getPartialContent($partial, $vars);
    }

    /**
     * @param       $partial
     * @param array $vars
     *
     * @return false|string
     */
    public function getPartialContent($partial, $vars = [])
    {
        ob_start();
        $this->partial($partial, $vars);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }


    /************************************************************
     *
     *                     DYNAMIC SIDEBAR
     *
     ************************************************************/

    /**
     * @param $module
     *
     * @return bool
     */
    public function getModuleMenu($module)
    {
        $app_path = $this->getDi()->get('session')->getApplicationPath();

        if (file_exists($app_path.'/modules/'.$module.'/views/menu.phtml')) {
            $this->setPartialsDir($app_path.'/modules/'.$module.'/views/');
        }
        elseif (file_exists(COMMON_PATH.'/modules/'.$module.'/views/menu.phtml')) {
            $this->setPartialsDir(COMMON_PATH.'/modules/'.$module.'/views/');
        }
        else {
            return false;
        }

        return $this->getPartialContent('menu');
    }

    /**
     * @return bool
     */
    public function getAdminReferenceMenu()
    {
        $app_path = $this->getDi()->get('session')->getApplicationPath();

        if (file_exists($app_path.'/modules/admin/partials/reference.phtml')) {
            $this->setPartialsDir($app_path.'/modules/admin/partials/');
            return $this->getPartialContent('reference');
        }
    }

}