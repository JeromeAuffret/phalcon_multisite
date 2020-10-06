<?php

namespace Acl;

use Phalcon\Acl\RoleAware;
use Phalcon\Collection;


class AclUserRole implements RoleAware
{
    private $super_admin = ['admin'];

    protected $id_user;

    protected $roleName;

    protected $id_application;

    protected $user_login;

    protected $parameters;

    /**
     * AclUserRole constructor.
     *
     * @param string      $role_name
     * @param int|null    $id_user
     * @param string|null $user_login
     * @param int|null    $id_application
     * @param bool        $noAdmin
     * @param array       $parameters
     */
    public function __construct($role_name = 'guest', $id_user = null, $user_login = null, $id_application = null, $noAdmin = false, array $parameters = [])
    {
        $this->roleName       = $role_name;
        $this->id_user        = $id_user;
        $this->user_login     = $user_login;
        $this->id_application = $id_application;
        $this->parameters     = new Collection($parameters);

        if (!$noAdmin && $this->loginIsSuperAdmin()) {
            $this->setParameters('superAdmin', true);
        }
    }

    /**
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return $this->getParameters('superAdmin', false);
    }

    /**
     * @return boolean
     */
    public function loginIsSuperAdmin(): bool
    {
        return in_array($this->getUserLogin(), $this->super_admin);
    }

    /**
     * @return integer
     */
    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    /**
     * @return string
     */
    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * @return integer
     */
    public function getIdApplication(): ?int
    {
        return $this->id_application;
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return Collection|mixed
     */
    public function getParameters(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->parameters->get($key, $default);
        } else {
            return $this->parameters;
        }
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setParameters(string $key, $value)
    {
        $this->parameters->set($key, $value);
    }

}