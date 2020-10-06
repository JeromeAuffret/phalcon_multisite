<?php

$this->addRole('guest');

$this->addComponent(
    'auth_login',
    [
        'index'
    ]
);

$this->addComponent(
    'auth_application',
    [
        'index',
        'switchApplication'
    ]
);
