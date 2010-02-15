<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Event;
use greebo\conveniences\Response;


$t = new lime_test(1);


$response = new Response(new Event, 'utf-8');
$t->pass('->__construct() works properly');
