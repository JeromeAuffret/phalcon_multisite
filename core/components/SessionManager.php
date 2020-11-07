<?php

namespace Component;

use Common\Acl\AclUserRole;
use Models\Role;
use Models\User;
use Models\Application;
use Phalcon\Collection;
use Phalcon\Di\Injectable;


/**
 * Class sessionManager
 *
 * @package Component
 */
final class SessionManager extends Injectable
{

    /*******************************************************
     *
     *                          USER
     *
     *******************************************************/

    /**
     * @return boolean
     */
    public function hasUser(): bool
    {
        return $this->session->has('user');
    }

    /**
     * Setup session related to application
     *
     * @param User $user
     */
    public function setupUserSession(User $user)
    {
        $this->session->set('user', new Collection($user->toArray()));
        $this->setupUserRole();
    }

    /**
     *  Setup ACL useRole
     *  Default role is 'guest'
     */
    public function setupUserRole()
    {
        $role = Role::getUserRole($this->getUser('id'), $this->getApplication('id'));
        $this->setAclRole($role ? $role->getSlug() : 'guest');
    }

    /**
     * Return user collection or given key value
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getUser($key = null)
    {
        if (!$key)
            return $this->session->get('user');
        elseif ($this->hasUser() && $this->session->get('user')->has($key))
            return $this->session->get('user')->get($key);
        else
            return null;
    }

    /**
     * Set user key value
     *
     * @param $key
     * @param $value
     */
    public function setUser($key, $value)
    {
        if ($this->hasUser()) {
            $user = $this->session->get('user');
            $user->set($key, $value);

            $this->session->set('user', $user);
        }
    }


    /*******************************************************
     *
     *                      APPLICATION
     *
     *******************************************************/

    /**
     * Return session application_slug
     *
     * @return boolean
     */
    public function hasApplication(): bool
    {
        return $this->session->has('application');
    }

    /**
     * Destroy session related to application
     */
    public function destroyApplicationSession()
    {
        $this->session->remove('application');
    }

    /**
     * Setup session related to application
     *
     * @param Application $application
     */
    public function setupApplicationSession(Application $application)
    {
        $this->session->set('application', new Collection($application->toArray()));
        $this->setupUserRole();
    }

    /**
     * Return application collection o given key value
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getApplication($key = null)
    {
        if (!$key)
            return $this->session->get('application');
        elseif ($this->hasApplication() && $this->session->get('application')->has($key))
            return $this->session->get('application')->get($key);
        else
            return null;
    }

    /**
     * Set application key value
     *
     * @param $key
     * @param $value
     */
    public function setApplication($key, $value)
    {
        if ($this->hasApplication()) {
            $application = $this->session->get('application');
            $application->set($key, $value);

            $this->session->set('application', $application);
        }
    }


    /*******************************************************
     *
     *                         ACL
     *
     *******************************************************/

    /**
     * @return boolean
     */
    public function hasAclRole(): bool
    {
        return $this->session->has('acl_role');
    }

    /**
     * @return AclUserRole
     */
    public function getAclRole()
    {
        $aclRoleClass = $this->getDI()->get('dispatcher')->dispatchNamespace(AclUserRole::class);
        $aclRole = $this->hasAclRole() ? $this->session->get('acl_role') : 'guest';

        return new $aclRoleClass(
            $aclRole,
            $this->getUser('id'),
            $this->getUser('login'),
            $this->getApplication('id')
        );
    }

    /**
     * @param string $aclRole
     */
    public function setAclRole(string $aclRole)
    {
        $this->session->set('acl_role', $aclRole);
    }

}