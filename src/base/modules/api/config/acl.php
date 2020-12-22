<?php

use Core\Acl\AclComponent;
use Core\Acl\AclUserRole;

$this->addRole('admin');
$this->addRole('user');

$this->addComponent('api_data', ['get', 'create', 'update', 'delete']);
$this->addComponent('api_form', ['index', 'get', 'create', 'update', 'delete']);

// Rules
$this->allow('admin', 'api_data', '*');
$this->allow('admin', 'api_form', '*');

$this->allow('user', 'api_data', '*');
$this->allow('user', 'api_form', '*');

// By default, prevent 'user' role to use DELETE method from api data
$this->allow('user', 'api_data', '*', function (AclUserRole $AclUserRole, AclComponent $AclComponent) {
    return $AclComponent->getMethod() !== 'DELETE';
});

// By default, prevent 'user' role to use DELETE method from api form
$this->allow('user', 'api_form', '*', function (AclUserRole $AclUserRole, AclComponent $AclComponent) {
    return $AclComponent->getMethod() !== 'DELETE';
});