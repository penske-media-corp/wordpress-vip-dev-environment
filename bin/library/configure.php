<?php
// Autoloader for Zend Framework 1.x and PMC classes
spl_autoload_register(function($class) {
    if (0 === strpos($class, 'Pmc\\')) {
        $class = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($class)) {
            include_once $class;
        }
    } elseif (0 === strpos($class, 'Zend')) {
        $class = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        include_once $class;
    }
});

