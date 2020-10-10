<?php

use Acl\AclUserRole;
use Acl\AclComponent;

// Allow every roles for user_login page
$this->allow('*', 'auth_login', 'index');

// Only accept connected user
$this->allow('*', 'auth_application', ['index', 'switchApplication'],
    function (AclUserRole $AclUserRole, AclComponent $AclResource) {
    return true;
//        return $AclUserRole->getIdUser();
    }
);
