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
    '/tmp/storage/app'
];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Forward to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
