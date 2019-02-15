<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!array_key_exists('APP_ENV', $_SERVER)) {
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] ?? null;
}

$servers = [
    'locahost:8000' => 'dev',
    'dev-symfony-4-blog.test' => 'dev',
    'symfony-4-blog.test' => 'pro'
];

$isCli = PHP_SAPI === 'cli';

if ($isCli) {
    $env = '/.env.dev';
} else {
    if (isset($servers[$_SERVER['HTTP_HOST']])) {
        $env = '/.env.' . $servers[$_SERVER['HTTP_HOST']];
    } else {
        throw new RuntimeException('The "APP" are not installed');
    }
}

unset($servers);
$env = dirname(__DIR__) . $env;

if (!class_exists(Dotenv::class)) {
    throw new RuntimeException('The "APP_ENV" environment variable is not set to "prod". Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
}

if (!file_exists($env)) {
    throw new RuntimeException('The file config not exists');
}

(new Dotenv())->loadEnv($env);
