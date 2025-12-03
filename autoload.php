<?php

spl_autoload_register('my_custom_autoloader');
function my_custom_autoloader($class_name)
{
    $file = __DIR__ . '\\' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
    return null;
}
