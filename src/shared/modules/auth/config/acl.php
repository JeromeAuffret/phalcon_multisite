<?php

use Core\Acl\AclComponent;
use Core\Acl\AclUserRole;

$this->addRole('guest');

$this->addComponent('auth_login', ['index']);
$this->addComponent('auth_application', ['index', 'switchApplication']);

// Allow every roles for user_login page
$this->allow('*', 'auth_login', 'index');

// Only accept connected user
$this->allow('*', 'auth_application', ['index', 'switchApplication'],
    function (AclUserRole $AclUserRole, AclComponent $AclResource) {
        return !!$AclUserRole->getIdUser();
    }
);
