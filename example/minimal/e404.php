<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require dirname(__DIR__).'/../lib/greebo/essence/Greebo.php';

$g = new \greebo\essence\Greebo(new \greebo\essence\Container);
$g->unleash();
