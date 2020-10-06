<?php

$this->allow('admin', 'admin_index', '*');
$this->allow('admin', 'admin_profile', '*');
$this->allow('admin', 'admin_user', '*');

$this->allow('user', 'admin_profile', '*');
