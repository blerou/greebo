<?php

/*
 * This file is part of the greebo pack.
 * 
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

require_once __DIR__.'/../lib/lime/LimeAutoloader.php';
LimeAutoloader::register();

$h = new LimeTestSuite(array(
  'force_colors' => isset($argv) && in_array('--color', $argv),
  'verbose' => isset($argv) && in_array('--verbose', $argv),
));
$h->base_dir = realpath(__DIR__.'/..');

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../unit'), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
  if (preg_match('/Test\.php$/', $file))
    $h->register($file->getRealPath());

exit($h->run() ? 0 : 1);
