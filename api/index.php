<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

if (getenv('VERCEL') || getenv('VERCEL_ENV')) {
    $storagePath = '/tmp/storage';
    $app->useStoragePath($storagePath);

    foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs', 'app/public'] as $directory) {
        $path = $storagePath.'/'.$directory;
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}

$app->handleRequest(Request::capture());
