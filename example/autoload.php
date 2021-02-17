<?php

spl_autoload_register(function ($class) {

    $prefix = 'LDL\\Example\\Build\\';

    $base_dir = __DIR__ . '/Build/';

    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return false;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }

    return true;
});
