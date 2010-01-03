<?php

$container = new \Greebo\BasicContainer;
$container->app_vendor = 'Greebo';
$container->app_controller = 'simple';
$container->app_lib_dir = dirname(__DIR__).DIRECTORY_SEPARATOR.'lib';

return $container;