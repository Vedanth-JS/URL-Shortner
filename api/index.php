<?php

/**
 * Vercel Serverless Entrypoint for Laravel
 * Routes all incoming Vercel requests to the Laravel public index.
 */

// Forward to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
