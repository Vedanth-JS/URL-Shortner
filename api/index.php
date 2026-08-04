<?php

/**
 * Vercel Serverless Entrypoint for Laravel
 * Routes all incoming Vercel requests to the Laravel public index.
 */

// Suppress deprecation warnings caused by newer PHP versions on Vercel
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Suppress deprecation warnings caused by newer PHP versions on Vercel
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Ensure storage directories exist in the Vercel /tmp filesystem
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app',
    '/tmp/storage/bootstrap/cache'
];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Override Laravel cache paths to write to /tmp
putenv('APP_SERVICES_CACHE=/tmp/storage/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/storage/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/storage/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/storage/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/storage/bootstrap/cache/events.php');

// Bootstrap Laravel and intercept the storage path
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

// OVERRIDE LARAVEL'S STRICT ERROR HANDLER
spl_autoload_register(function ($class) {
    if ($class === 'Illuminate\\Foundation\\Bootstrap\\HandleExceptions') {
        $file = __DIR__.'/../vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php';
        if (file_exists($file)) {
            $code = file_get_contents($file);
            // Patch the strict error reporting
            $code = str_replace('error_reporting(-1);', 'error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);', $code);
            $code = str_replace('<?php', '', $code);
            eval($code);
            return true;
        }
    }
}, true, true);

$app = require_once __DIR__.'/../bootstrap/app.php';

// OVERRIDE STORAGE PATH GLOBALLY FOR VERCEL
$app->useStoragePath('/tmp/storage');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
