<?php

/**
 * Vercel Serverless Entrypoint for Laravel
 * Routes all incoming Vercel requests to the Laravel public index.
 */

// Suppress deprecation warnings caused by newer PHP versions on Vercel
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Forward to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
