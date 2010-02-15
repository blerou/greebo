<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\conveniences\Container;


$t = new lime_test(12);


$container = new Container;


$t->diag('defaults');


$t->is($container->vendor, 'greebo', 'default vendor');
$t->is($container->app, 'conveniences', 'default app');
$t->is($container->charset, 'utf-8', 'default character set');

$t->isa_ok($container->event, 'greebo\\essence\\Event', 'default event instance');
$t->isa_ok($container->request, 'greebo\\conveniences\\Request', 'default request instance');
$t->isa_ok($container->response, 'greebo\\conveniences\\Response', 'default response instance');

$t->is($container->action_name, 'index', 'default action name');


$t->diag('context related instances');


$_GET['action'] = 'assignments';
$container = new Container;
$container->app = 'test';

$t->is($container->action_name, 'assignments', 'correct action name');
$t->isa_ok($container->action, 'greebo\\test\\ActionSet', 'correct action set instance');

try {
  $container->template;
  $t->fail('template getter throws exception if template class not exists');
} catch (\greebo\conveniences\ContainerException $e) {
  $t->pass('template getter throws exception if template class not exists');
}
$container->action_name = 'index';
$template = $container->template;
$t->isa_ok($template, 'greebo\\test\\Template\\index', 'correct template instance');

$value = '<b>foo</b>';
$escaper = $template->escaper();
$t->is($escaper($value), htmlspecialchars($value), 'template default escaper works properly');