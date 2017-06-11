<?php
function emchatAutoload($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/request/' . $path . '.php';
    if (file_exists($file)) {
        return require $file;
    }
}

spl_autoload_register('emchatAutoload');
require __DIR__.'/EmchatClient.php';
require __DIR__.'/ApiRequest.php';