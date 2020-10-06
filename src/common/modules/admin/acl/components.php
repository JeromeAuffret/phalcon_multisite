<?php

$this->addComponent(
    'admin_index',
    [
        'index'
    ]
);

$this->addComponent(
    'admin_profile',
    [
        'index'
    ]
);

$this->addComponent(
    'admin_application',
    [
        'index'
    ]
);

$this->addComponent(
    'admin_user',
    [
        'index', 'userList',
        'detail', 'resetPassword', 'saveUser',
        'deleteUser'
    ]
);

$this->addComponent(
    'admin_reference',
    [
        'index'
    ]
);