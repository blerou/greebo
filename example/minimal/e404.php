<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require dirname(__DIR__).'/../src/greebo/essence/Greebo.php';
\greebo\essence\Greebo::register();

$g = new \greebo\essence\Greebo(new \greebo\essence\Container);
$g->unleash();
