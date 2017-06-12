<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'datetime' => date('Y-m-d H:i:s', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time()),
    'timestamp' => isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time(),
];
