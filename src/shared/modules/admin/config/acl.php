<?php

// Roles
$this->addRole('admin');
$this->addRole('user');

// Components
$this->addComponent('admin_index', ['index']);
$this->addComponent('admin_profile', ['index']);
$this->addComponent('admin_user', ['index']);

// Rules
$this->allow('admin', 'admin_index', '*');
$this->allow('admin', 'admin_profile', '*');
$this->allow('admin', 'admin_user', '*');

$this->allow('user', 'admin_profile', '*');
