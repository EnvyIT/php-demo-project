<?php

// force strict type declarations
declare(strict_types=1);

// set error reporting

use Core\Session\SessionContext;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// autoload Classes
spl_autoload_register(function ($class) {
    $filename = __DIR__ . '/../server/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($filename)) {
        include($filename);
    }
});

//start session
SessionContext::create();

//load .env - file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    $envs = explode("\n", $content);
    foreach ($envs as $env) {
        $keyValuePair = explode("=", $env);
        if (count($keyValuePair) == 2) {
            $_ENV[$keyValuePair[0]] = str_replace("\r", "", $keyValuePair[1]);
        }
    }
}



