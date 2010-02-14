1;3R
<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Greebo;
use greebo\essence\Container;
use greebo\essence\Response;


$t = new lime_test(7);


$t->pass('::register() works properly');

class MockResponse extends Response
{
    function send()
    {
    }
}

$container = new Container;
$container->response = function($c) { return new MockResponse($c->event); };
$container->action = function($c) use($t) { $t->pass('->unleash() calls action callback'); };

$container->event->connect('greebo.startup', function($c) use($container, $t) {
    $t->pass('->unleash() fire startup properly');
    $t->is_deeply($c, $container, '->__construct() takes Container instance');
});

$container->event->connect('greebo.preexec', function($c) use($container, $t) {
    $t->pass('->unleash() fire preexec properly');
});

$container->event->connect('greebo.postexec', function($c) use($container, $t) {
    $t->pass('->unleash() fire postexec properly');
});

$container->event->connect('greebo.shutdown', function($c) use($container, $t) {
    $t->pass('->unleash() fire shutdown properly');
});

ob_start();
$g = new Greebo($container);
$g->unleash();
ob_end_flush();
