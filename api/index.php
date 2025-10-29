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
$tmpPackagesCache = '/tmp/bootstrap/cache/packages.php';

if (file_exists($packagesCache)) {
  $packages = include $packagesCache;

  // Remove development packages
  $devPackages = ['laravel/pail', 'laravel/sail', 'nunomaduro/collision'];
  foreach ($devPackages as $devPackage) {
    if (isset($packages[$devPackage])) {
      unset($packages[$devPackage]);
    }
  }

  // Write cleaned packages to /tmp (writable location)
  file_put_contents($tmpPackagesCache, '<?php return ' . var_export($packages, true) . ';');

  // Also copy services.php to /tmp
  $servicesCache = __DIR__ . '/../bootstrap/cache/services.php';
  $tmpServicesCache = '/tmp/bootstrap/cache/services.php';
  if (file_exists($servicesCache)) {
    copy($servicesCache, $tmpServicesCache);
  }
}

// Override Laravel's bootstrap path to use /tmp
$_ENV['APP_BOOTSTRAP_CACHE'] = '/tmp/bootstrap/cache';
putenv('APP_BOOTSTRAP_CACHE=/tmp/bootstrap/cache');

// Forward Vercel requests to public/index.php
require_once __DIR__ . '/../public/index.php';
