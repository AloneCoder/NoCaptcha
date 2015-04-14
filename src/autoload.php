<?php
spl_autoload_register(function ($class) {
    if (substr($class, 0, 10) !== 'NoCaptcha\\') {
        return;
    }
    $class = str_replace('\\', '/', $class);
    $path = __DIR__ . '/' . $class . '.php';
    if (is_readable($path)) {
        require_once $path;
    }
    $path = __DIR__ . '/../tests/' . $class . '.php';
    if (is_readable($path)) {
        require_once $path;
    }
});