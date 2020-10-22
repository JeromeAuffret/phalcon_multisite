<?php

namespace Component;

use Acl\AclUserRole;
use Models\Role;
use Models\User;
use Models\Application;
use Phalcon\Collection;
use Phalcon\Session\Manager as SessionManager;

/**
 * Class Session
 *
 * @package Component
 */
final class Session extends SessionManager
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
        return $this->has('user');
    }

    /**
     * Setup session related to application
     *
     * @param User $user
     */
    public function setupUserSession(User $user)
    {
        $this->set('user', new Collection($user->toArray()));
        $this->setupUserRole();
    }

    /**
     *  Setup ACL useRole
     *  Default role is 'guest'
     */
    public function setupUserRole()
    {
        $role = Role::getUserRole();
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
            return $this->get('user');
        elseif ($this->hasUser() && $this->get('user')->has($key))
            return $this->get('user')->get($key);
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
            $user = $this->get('user');
            $user->set($key, $value);

            $this->set('user', $user);
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
        return $this->has('application');
    }

    /**
     * Destroy session related to application
     */
    public function destroyApplicationSession()
    {
        $this->remove('application');
    }

    /**
     * Setup session related to application
     *
     * @param Application $application
     */
    public function setupApplicationSession(Application $application)
    {
        $this->set('application', new Collection($application->toArray()));
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
            return $this->get('application');
        elseif ($this->hasApplication() && $this->get('application')->has($key))
            return $this->get('application')->get($key);
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
            $application = $this->get('application');
            $application->set($key, $value);

            $this->set('application', $application);
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
        return $this->has('acl_role');
    }

    /**
     * @return AclUserRole
     */
    public function getAclRole(): ?AclUserRole
    {
        $aclRole = $this->hasAclRole() ? $this->get('acl_role') : 'guest';
        return new AclUserRole(
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
        $this->set('acl_role', $aclRole);
    }

}