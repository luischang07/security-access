<?php

// Delete package discovery cache files BEFORE Laravel loads
// This forces Laravel to skip cached packages and only load installed ones
$cacheFiles = [
  __DIR__ . '/../bootstrap/cache/packages.php',
  __DIR__ . '/../bootstrap/cache/services.php',
];

foreach ($cacheFiles as $cacheFile) {
  if (file_exists($cacheFile)) {
    @unlink($cacheFile); // Suppress error if read-only
  }
}

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

// Forward Vercel requests to public/index.php
require_once __DIR__ . '/../public/index.php';
