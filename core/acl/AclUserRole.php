<?php

namespace Acl;

use Phalcon\Acl\RoleAware;
use Phalcon\Collection;

/**
 * Class AclUserRole
 *
 * @package Acl
 */
class AclUserRole implements RoleAware
{
    private $super_admin = ['admin'];

    protected $userId;

    protected $roleName;

    protected $applicationId;

    protected $userLogin;

    protected $parameters;

    /**
     * AclUserRole constructor.
     *
     * @param string      $role_name
     * @param int|null    $userId
     * @param string|null $userLogin
     * @param int|null    $applicationId
     * @param bool        $noAdmin
     * @param array       $parameters
     */
    public function __construct($role_name = 'guest', $userId = null, $userLogin = null, $applicationId = null, $noAdmin = false, array $parameters = [])
    {
        $this->roleName      = $role_name;
        $this->userId        = $userId;
        $this->userLogin     = $userLogin;
        $this->applicationId = $applicationId;
        $this->parameters    = new Collection($parameters);

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
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserLogin(): ?string
    {
        return $this->userLogin;
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
        return $this->applicationId;
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