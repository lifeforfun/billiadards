<?php

require __DIR__ . '/../config.php';

use JMessage\IM\Admin;

$admin = new Admin($jm);

$response = $admin->listAll();
print_r($response);
