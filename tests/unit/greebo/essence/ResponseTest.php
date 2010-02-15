<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Event;
use greebo\essence\Response;


$t = new lime_test(1);


// fix cli SERVER_PROTOCOL
$_SERVER['SERVER_PROTOCOL'] = '';

$event = new Event;
$response = new Response($event);

$response->status(201);
//$t->pass('->status() works properly');

$response->header('Content-type', 'text/html');
//$t->pass('->header() works properly');

$response->cookie('foo', 'bar');
//$t->pass('->cookie() works properly');

$response->content('RESPONSE TEST');
//$t->pass('->content() works properly');

$event->connect('response.content', function($response, $content) {
    return $content.' FILTERED';
});

ob_start();
$response->send();
$result = ob_get_clean();

$t->is($result, 'RESPONSE TEST FILTERED', '->send() sends filtered content');