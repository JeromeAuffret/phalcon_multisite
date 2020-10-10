<?php

$this->addRole('admin');
$this->addRole('user');

// Allow access to error's pages
$this->addComponent('_error', ['NotFound', 'InternalError']);
$this->allow('*', '_error', '*');
