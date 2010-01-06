<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require dirname(__DIR__).'/../gessence.php';

$g = new \Greebo\Greebo(new \Greebo\Container);
$g->unleash();
