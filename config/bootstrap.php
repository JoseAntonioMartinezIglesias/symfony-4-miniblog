<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!array_key_exists('APP_ENV', $_SERVER)) {
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] ?? null;
}

$servers = array(
    'dev-symfony-4-blog.test' => 'dev',
    'symfony-4-blog.test' => 'pro'
);
$isCli = PHP_SAPI === 'cli';

if ($isCli) {
    $_SERVER['APP_ENV'] = 'dev';
    $_SERVER['APP_DEBUG'] = false;
    $env = dirname(__DIR__) . '/.env';
} else {
    if (isset($servers[$_SERVER['HTTP_HOST']])) {
        $_server = $servers[$_SERVER['HTTP_HOST']];
        if ($_server == 'pro') {
            $_SERVER['APP_ENV'] = 'pro';
            $_SERVER['APP_DEBUG'] = false;
            $env = dirname(__DIR__) . '/.env';
        } else {
           $_SERVER['APP_ENV'] = 'dev';
            $_SERVER['APP_DEBUG'] = true;
            $env = dirname(__DIR__) . '/.env';
        }
    } else {
        die('La aplicacion no esta instalada correctamente. El servidor no es valido ' . $_SERVER['HTTP_HOST']);
    }
}

unset($servers);

if (!class_exists(Dotenv::class)) {
    throw new RuntimeException('The "APP_ENV" environment variable is not set to "prod". Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
}
(new Dotenv())->loadEnv($env);


