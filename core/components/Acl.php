<?php

namespace Component;

use Acl\AclComponent;
use Acl\AclUserRole;

use Phalcon\Acl\ComponentInterface;
use Phalcon\Acl\RoleInterface;
use Phalcon\Acl\Adapter\AbstractAdapter;
use Phalcon\Acl\Adapter\AdapterInterface;
use Phalcon\Di\Injectable;

/**
 * Class Acl
 *
 * @property Application application
 * @property Session     session
 * @property Config      config
 * @package Component
 */
final class Acl extends Injectable implements AdapterInterface
{
    /* @var AbstractAdapter $adapter */
    private $adapter;

    /**
     * @param $adapter
     * @return Acl
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Register Main ACL files 
     */
    public function registerMainAcl()
    {
        $common_path = $this->application->getCommonPath();
        $modules = $this->config->get('modules');

        /**
         * Load common's components definition
         * Components can be defined in generic components files from configuration folder
         * or directly in modules
         */
        if (file_exists($common_path.'/acl/components.php')) {
            include $common_path.'/acl/components.php';
        }

        foreach ($modules as $module_name => $module) {
            $module_path = $this->application->getCommonModulePath($module_name);
            if (file_exists($module_path.'/acl/components.php')) {
                include $module_path.'/acl/components.php';
            }
        }

        /**
         * Load common's rules definition
         * Rules can be defined in generic acl files from configuration folder
         * or directly in modules
         */
        if (file_exists($common_path.'/acl/acl.php')) {
            include $common_path.'/acl/acl.php';
        }

        foreach ($modules as $module_name => $module) {
            $module_path = $this->application->getCommonModulePath($module_name);
            if (file_exists($module_path.'/acl/acl.php')) {
                include $module_path.'/acl/acl.php';
            }
        }
    }

    /**
     * Load modules acl components and rules
     */
    public function registerApplicationAcl()
    {
        $application_path = $this->application->getApplicationPath();
        $modules = $this->config->get('modules');

        /**
         * Load application's components overrides
         * Components can be defined in generic components files from configuration folder
         * or directly in modules
         */
        if (file_exists($application_path.'/acl/components.php')) {
            include $application_path.'/acl/components.php';
        }

        foreach ($modules as $module_name => $module) {
            $module_path = $this->application->getApplicationModulePath($module_name);
            if (file_exists($module_path.'/acl/components.php')) {
                include $module_path.'/acl/components.php';
            }
        }

        /**
         * Load application's rules overrides
         * Rules can be defined in generic acl files from configuration folder
         * or directly in modules
         */
        if (file_exists($application_path.'/acl/acl.php')) {
            include $application_path.'/acl/acl.php';
        }

        foreach ($modules as $module_name => $module) {
            $module_path = $this->application->getApplicationModulePath($module_name);
            if (file_exists($module_path.'/acl/acl.php')) {
                include $module_path.'/acl/acl.php';
            }
        }
    }

    /**
     * @param string|null $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     * @return AclComponent
     */
    public function getAclComponent(string $moduleName = null, $controllerName = 'index', $actionName = 'index', $params = [])
    {
        // If no parameters is pass, we use the current dispatcher to define AclComponent
        if (!$moduleName) {
            $dispatcher = $this->dispatcher;

            $moduleName = $dispatcher->getModuleName();
            $controllerName = $dispatcher->getControllerName();
            $actionName = $dispatcher->getActionName();
            $params = $dispatcher->getParams();
        }

        return new AclComponent($moduleName, $controllerName, $actionName, $params);
    }

    /**
     * Verify is the given components is defined as public
     *
     * @param string|null $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     * @return bool
     */
    public function isPublicComponent(string $moduleName = null, $controllerName = 'index', $actionName = 'index', $params = [])
    {
        $AclComponent = $this->getAclComponent($moduleName, $controllerName, $actionName, $params);
        return in_array($AclComponent->getComponentName(), $this->config->get('publicComponents')->getValues());
    }

    /**
     * Verify if the current profile is allowed to access a resource from a given module
     *
     * @param string|null $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     * @return bool
     */
    public function userAllowed(string $moduleName = null, $controllerName = 'index', $actionName = 'index', $params = [])
    {
        // Defined resource
        $AclComponent = $this->getAclComponent($moduleName, $controllerName, $actionName, $params);

        // Prevent verification for public components
        if ($this->isPublicComponent($moduleName, $controllerName, $actionName, $params)) {
            return true;
        }
        // UserAdmin can access to registered components
        elseif ($this->isSuperAdmin()) {
            return $this->isComponent($AclComponent->getComponentName());
        }
        else {
            return
                $this->roleIsRegistered()
                && $this->moduleIsRegistered($AclComponent->getModuleName())
                && $this->isComponent($AclComponent->getComponentName())
                && $this->isAllowed(
                    $this->getUserRole(),
                    $AclComponent,
                    $AclComponent->getActionName()
//                    $AclComponent->getParams() TODO why ?
                );
        }
    }

    /**
     * @return boolean
     */
    public function roleIsRegistered(): bool
    {
        return $this->isRole($this->getRoleName());
    }

    /**
     * @param $module
     *
     * @return boolean
     */
    public function moduleIsRegistered($module): bool
    {
        return $this->config->get('modules')->has($module);
    }

    /**
     * @return AclUserRole
     */
    public function getUserRole(): AclUserRole
    {
        return $this->session->getAclRole();
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->getUserRole()->getRoleName();
    }

    /**
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return $this->getUserRole()->isSuperAdmin();
    }

    /**
     * @return boolean
     */
    public function loginIsSuperAdmin(): bool
    {
        return $this->getUserRole()->loginIsSuperAdmin();
    }


    /*************************************************************
     *
     *                     ADAPTER INTERFACE
     *
     *************************************************************/

    /**
     * Do a role inherit from another existing role
     *
     * @param string $roleName
     * @param mixed $roleToInherit
     * @return bool
     */
    public function addInherit(string $roleName, $roleToInherit): bool
    {
        return $this->adapter->addInherit($roleName, $roleToInherit);
    }

    /**
     * Adds a role to the ACL list. Second parameter lets to inherit access data
     * from other existing role
     *
     * @param mixed $role
     * @param mixed $accessInherits
     * @return bool
     */
    public function addRole($role, $accessInherits = null): bool
    {
        return $this->adapter->addRole($role, $accessInherits);
    }

    /**
     * Adds a component to the ACL list
     *
     * Access names can be a particular action, by example
     * search, update, delete, etc or a list of them
     *
     * @param mixed $componentObject
     * @param mixed $accessList
     * @return bool
     */
    public function addComponent($componentObject, $accessList): bool
    {
        return $this->adapter->addComponent($componentObject, $accessList);
    }

    /**
     * Adds access to components
     *
     * @param string $componentName
     * @param mixed $accessList
     * @return bool
     */
    public function addComponentAccess(string $componentName, $accessList): bool
    {
        return $this->adapter->addComponentAccess($componentName, $accessList);
    }

    /**
     * Allow access to a role on a component
     *
     * @param string $roleName
     * @param string $componentName
     * @param mixed $access
     * @param mixed $func
     * @return void
     */
    public function allow(string $roleName, string $componentName, $access, $func = null): void
    {
        $this->adapter->allow($roleName, $componentName, $access, $func);
    }

    /**
     * Deny access to a role on a component
     *
     * @param string $roleName
     * @param string $componentName
     * @param mixed $access
     * @param mixed $func
     * @return void
     */
    public function deny(string $roleName, string $componentName, $access, $func = null): void
    {
        $this->adapter->deny($roleName, $componentName, $access, $func);
    }

    /**
     * Removes an access from a component
     *
     * @param string $componentName
     * @param mixed $accessList
     * @return void
     */
    public function dropComponentAccess(string $componentName, $accessList): void
    {
        $this->adapter->dropComponentAccess($componentName, $accessList);
    }

    /**
     * Returns the access which the list is checking if some role can access it
     *
     * @return string
     */
    public function getActiveAccess(): string
    {
        return $this->adapter->getActiveAccess();
    }

    /**
     * Returns the role which the list is checking if it's allowed to certain
     * component/access
     *
     * @return string
     */
    public function getActiveRole(): string
    {
        return $this->adapter->getActiveRole();
    }

    /**
     * Returns the component which the list is checking if some role can access
     * it
     *
     * @return string
     */
    public function getActiveComponent(): string
    {
        return $this->adapter->getActiveComponent();
    }

    /**
     * Returns the default ACL access level
     *
     * @return int
     */
    public function getDefaultAction(): int
    {
        return $this->adapter->getDefaultAction();
    }

    /**
     * Returns the default ACL access level for no arguments provided in
     * isAllowed action if there exists func for accessKey
     *
     * @return int
     */
    public function getNoArgumentsDefaultAction(): int
    {
        return $this->adapter->getNoArgumentsDefaultAction();
    }

    /**
     * Return an array with every role registered in the list
     *
     * @return array|RoleInterface[]
     */
    public function getRoles(): array
    {
        return $this->adapter->getRoles();
    }

    /**
     * Return an array with every component registered in the list
     *
     * @return array|ComponentInterface[]
     */
    public function getComponents(): array
    {
        return $this->adapter->getComponents();
    }

    /**
     * Check whether a role is allowed to access an action from a component
     *
     * @param mixed $roleName
     * @param mixed $componentName
     * @param string $access
     * @param array|null $parameters
     * @return bool
     */
    public function isAllowed($roleName, $componentName, string $access, array $parameters = null): bool
    {
        return $this->adapter->isAllowed($roleName, $componentName, $access, $parameters);
    }

    /**
     * Check whether component exist in the components list
     *
     * @param string $componentName
     * @return bool
     */
    public function isComponent(string $componentName): bool
    {
        return $this->adapter->isComponent($componentName);
    }

    /**
     * Check whether role exist in the roles list
     *
     * @param string $roleName
     * @return bool
     */
    public function isRole(string $roleName): bool
    {
        return $this->adapter->isRole($roleName);
    }

    /**
     * Sets the default access level (Phalcon\Ac\Enum::ALLOW or Phalcon\Acl\Enum::DENY)
     *
     * @param int $defaultAccess
     * @return void
     */
    public function setDefaultAction(int $defaultAccess): void
    {
        $this->adapter->setDefaultAction($defaultAccess);
    }

    /**
     * Sets the default access level (Phalcon\Acl\Enum::ALLOW or Phalcon\Acl\Enum::DENY)
     * for no arguments provided in isAllowed action if there exists func for
     * accessKey
     *
     * @param int $defaultAccess
     * @return void
     */
    public function setNoArgumentsDefaultAction(int $defaultAccess): void
    {
        $this->adapter->setNoArgumentsDefaultAction($defaultAccess);
    }

}