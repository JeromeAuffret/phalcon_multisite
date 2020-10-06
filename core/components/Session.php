<?php

namespace Component;

use Acl\AclUserRole;
use Models\Role;
use Models\User;
use Models\Application;
use Phalcon\Collection;
use Phalcon\Helper\Str;
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
     *                       SESSION
     *
     *******************************************************/

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
     * Destroy session related to application
     */
    public function destroyApplication()
    {
        $this->remove('application');
    }

    /**
     *  Setup ACL useRole
     *  Guest profile per default
     */
    public function setupUserRole()
    {
        $role = Role::getUserRole();

        $this->setAclRole(
            new AclUserRole (
                $role ? $role->getSlug() : 'guest',
                $this->getUser('id'),
                $this->getUser('login'),
                $this->getApplication('id')
            )
        );
    }


    /*******************************************************
     *
     *                         USERS
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
     * Return user values or given key
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getUser($key = null)
    {
        if (!$key) {
            return $this->get('user');
        }
        else if ($this->hasUser() && $this->get('user')->has($key)) {
            return $this->get('user')->get($key);
        }
        else {
            return null;
        }
    }

    /**
     * Set user value
     *
     * @param $key
     * @param $value
     */
    public function setUser($key, $value)
    {
        if ($this->hasUser()) {
            $application = $this->get('user')->set($key, $value);
            $this->set('user', $application);
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
     * Return application values or given key
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getApplication($key = null)
    {
        if (!$key) {
            return $this->get('application');
        }
        else if ($this->hasApplication() && $this->get('application')->has($key)) {
            return $this->get('application')->get($key);
        }
        else {
            return null;
        }
    }

    /**
     * Set application value
     *
     * @param $key
     * @param $value
     */
    public function setApplication($key, $value)
    {
        if ($this->hasApplication()) {
            $application = $this->get('application')->set($key, $value);
            $this->set('application', $application);
        }
    }

    /**
     * @param string|null $application_slug
     * @return null|string
     */
    public function getApplicationPath(string $application_slug = null): ?string
    {
        if ($application_slug) {
            return APPS_PATH.'/'.$application_slug;
        }
        elseif ($this->hasApplication()) {
            return APPS_PATH.'/'.$this->getApplication('slug');
        }
        else {
            return null;
        }
    }

    /**
     * Camelize application_slug
     *
     * @param null $application_slug
     * @param string $delimiter
     * @return string|null
     */
    public function getApplicationNamespace($application_slug = null, $delimiter = '_')
    {
        if ($application_slug) {
            return Str::camelize($application_slug, $delimiter);
        }
        else if ($this->hasApplication()) {
            return Str::camelize($this->getApplication('slug'), $delimiter);
        }
        else {
            return null;
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
    public function getAclRole(): AclUserRole
    {
        return $this->get('acl_role') ?: new AclUserRole('guest');
    }

    /**
     * @param AclUserRole $acl_role
     */
    public function setAclRole(AclUserRole $acl_role)
    {
        $this->set('acl_role', $acl_role);
    }

}