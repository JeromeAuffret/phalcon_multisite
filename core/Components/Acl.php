<?php

namespace Core\Components;

use Core\Acl\AclComponent;
use Core\Acl\AclUserRole;
use Core\Helpers\NamespaceHelper;
use Phalcon\Acl\ComponentInterface;
use Phalcon\Acl\RoleInterface;
use Phalcon\Acl\Adapter\AbstractAdapter;
use Phalcon\Acl\Adapter\AdapterInterface;
use Phalcon\Di\Injectable;

/**
 * Class Acl
 *
 * @property Application application
 * @property Config      config
 * @package Component
 */
final class Acl extends Injectable implements AdapterInterface
{

    /**
     * @var AbstractAdapter $adapter
     */
    private $adapter;

    /**
     * @param $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return AbstractAdapter
     */
    public function getAdapter(): AbstractAdapter
    {
        return $this->adapter;
    }

    /**
     * Verify if the current profile is allowed to access a resource from a given module
     *
     * @param string|null $moduleName
     * @param string|null $controllerName
     * @param string|null $actionName
     * @param array $params
     * @return bool
     */
    public function userAllowed(string $moduleName = null, string $controllerName = null, string $actionName = null, array $params = []): bool
    {
        // Defined resource
        $aclComponentClass = NamespaceHelper::dispatchNamespace(AclComponent::class);
        $AclComponent = new $aclComponentClass($moduleName, $controllerName, $actionName, $params);

        // Prevent verification for public Components
        if ($AclComponent->isPublicComponent()) {
            return true;
        }
        // superAdmin can access to every registered Components
        else if ($this->isSuperAdmin()) {
            return $this->isComponent($AclComponent->getComponentName());
        }
        // Check if acl is valid and resolve permission
        else {
            return
                $this->roleIsRegistered()
                && $this->moduleIsRegistered($AclComponent->getModuleName())
                && $this->isComponent($AclComponent->getComponentName())
                && $this->isAllowed(
                    $this->getAclRole(),
                    $AclComponent,
                    $AclComponent->getActionName(),
                    $AclComponent->getParams() ?? null
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
    public function getAclRole(): AclUserRole
    {
        $aclRoleClass = NamespaceHelper::dispatchNamespace(AclUserRole::class);
        $userRole = $this->application->hasUserRole() ? $this->application->getUserRole() : 'guest';

        return new $aclRoleClass(
            $userRole,
            $this->application->getUser('id'),
            $this->application->getUser('login'),
            $this->application->getTenant('id')
        );
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->getAclRole()->getRoleName();
    }

    /**
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return $this->getAclRole()->isSuperAdmin();
    }

    /**
     * @return boolean
     */
    public function loginIsSuperAdmin(): bool
    {
        return $this->getAclRole()->loginIsSuperAdmin();
    }


    /*************************************************************
     *
     *                          REGISTER
     *
     *************************************************************/

    /**
     * @param string $filePath
     */
    public function registerAclFromFile(string $filePath)
    {
        if (file_exists($filePath)) include_once $filePath;
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
     * Adds access to Components
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
     * Check whether component exist in the Components list
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