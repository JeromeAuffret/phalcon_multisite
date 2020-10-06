<?php

use \Common\Acl\AclUserRole;
use \Common\Acl\AclResource;


$this->allow('admin', 'api_data', '*');
$this->allow('admin', 'api_form', '*');

$this->allow('user', 'api_data', '*');
$this->allow('user', 'api_form', '*');


/**
 *  By default, prevent 'user' role to use DELETE method from api
 */

$this->allow('user', 'api_data', '*', function (AclUserRole $AclUserRole, AclResource $AclResource) {
    return $AclResource->getMethod() !== 'DELETE';
});

$this->allow('user', 'api_form', '*', function (AclUserRole $AclUserRole, AclResource $AclResource) {
    return $AclResource->getMethod() !== 'DELETE';
});
