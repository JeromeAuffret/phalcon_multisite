<?php

namespace Common\Acl;

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

    protected $superAdmin;

    /**
     * AclUserRole constructor.
     *
     * @param string      $role_name
     * @param int|null    $userId
     * @param string|null $userLogin
     * @param int|null    $applicationId
     */
    public function __construct($role_name = 'guest', $userId = null, $userLogin = null, $applicationId = null)
    {
        $this->roleName      = $role_name;
        $this->userId        = $userId;
        $this->userLogin     = $userLogin;
        $this->applicationId = $applicationId;

        if ($this->loginIsSuperAdmin()) {
            $this->superAdmin = true;
        }
    }

    /**
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return !!$this->superAdmin;
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

}