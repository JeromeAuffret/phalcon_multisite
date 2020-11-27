<?php

namespace Core\Acl;

use Phalcon\Acl\RoleAware;

/**
 * Class AclUserRole
 *
 * @package Acl
 */
class AclUserRole implements RoleAware
{
    /**
     * @var string[]
     */
    private $super_admin = ['admin'];

    /**
     * @var int|null
     */
    protected $userId;

    /**
     * @var string
     */
    protected $roleName;

    /**
     * @var int|null
     */
    protected $applicationId;

    /**
     * @var string|null
     */
    protected $userLogin;

    /**
     * @var bool
     */
    protected $superAdmin = false;

    /**
     * AclUserRole constructor.
     *
     * @param string      $roleName
     * @param int|null    $userId
     * @param string|null $userLogin
     * @param int|null    $applicationId
     */
    public function __construct($roleName = 'guest', $userId = null, $userLogin = null, $applicationId = null)
    {
        $this->roleName      = $roleName;
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