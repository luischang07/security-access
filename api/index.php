<?php

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Create necessary directories in /tmp for Laravel
$tmpDirs = [
  '/tmp/storage/framework/views',
  '/tmp/storage/framework/cache',
  '/tmp/storage/framework/sessions',
  '/tmp/storage/logs',
  '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
  if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
  }
}

// Fix package discovery cache for production (remove dev packages)
$packagesCache = __DIR__ . '/../bootstrap/cache/packages.php';
if (file_exists($packagesCache)) {
  $packages = include $packagesCache;

  // Remove development packages
  $devPackages = ['laravel/pail', 'laravel/sail', 'nunomaduro/collision'];
  foreach ($devPackages as $devPackage) {
    if (isset($packages[$devPackage])) {
      unset($packages[$devPackage]);
    }
  }

  // Write cleaned packages back
  file_put_contents($packagesCache, '<?php return ' . var_export($packages, true) . ';');
}

// Create a writable bootstrap/cache if it doesn't exist or isn't writable
$bootstrapCache = __DIR__ . '/../bootstrap/cache';
if (!is_writable($bootstrapCache)) {
  // Use /tmp for bootstrap cache
  define('LARAVEL_STORAGE_PATH', '/tmp/storage');
}

// Forward Vercel requests to public/index.php
require_once __DIR__ . '/../public/index.php';
