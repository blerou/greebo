<?php

$config = include __DIR__.'/../config/bootstrap.simple.php';

Greebo\Greebo::create($config)->handle();