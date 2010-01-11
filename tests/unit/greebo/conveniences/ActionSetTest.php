1;3R
<?php

require_once __DIR__.'/../../bootstrap.php';
require __DIR__.'/../../../lib/greebo/test/Response.php';

use greebo\essence\Container as Container;
use greebo\conveniences\ActionSet;

$t = new LimeTest(2);

$container = new Container;
$container->response = $container->shared(function($c) { return new \greebo\test\Response($c); });
$container->action = function($c) { return new ActionSet($c); };
$container->action_name = 'error404';

$a = $container->action;

$t->diag('error404 page - default');

$t->same($container, $a->container(), '->container() returns global container');

$a($container);

$response = $container->response;

$t->is($response->get_status(), 404, '->error404Action() set response status code');
$t->is($response->get_content(), 'Error 404 Page', '->error404Action() set response content');
