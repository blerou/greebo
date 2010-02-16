<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Container;
use greebo\conveniences\ActionSet;
use greebo\conveniences\Template;

$t = new lime_test(7);

$container = new Container;
$action = new ActionSet;


$t->diag('action execution');


$action($container);
$t->is($container->action_name, 'error404', 'error404 action executed on undefined action');

$action = new \greebo\test\ActionSet($t);

$container->action_name = 'index';
$container->foo = 'bar';
$action($container);
$t->ok($container->foo instanceof stdClass, 'container setter works properly');


$t->diag('template variable assignments');


$container->action_name = 'assignments';
$container->template = new Template($container->event);

$action($container);
$excepted = array('foo' => 'bar', 'baz' => true);

$container->event->connect('template.slots', function($template, $slots) use($t, $excepted) {
   $t->ok($slots === $excepted, '->assign(), ->assigned() work properly');
});
$a = $container->template->fetch();


$t->diag('sendjson');


$result = $action->sendjson(array('foo' => 'bar'));
$t->is_deeply($result, false, '->sendjson() always returns false');

ob_start();
$_SERVER['SERVER_PROTOCOL'] = '';
$container->response->send();
$result = ob_get_clean();
$t->is($result, json_encode(array('foo' => 'bar')), '->sendjson() set json encoded data into the response');