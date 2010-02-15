<?php

require_once __DIR__.'/../../bootstrap.php';

class Bootstrap extends \greebo\conveniences\Bootstrap
{
    public $t;
    function  __construct($env, $t)
    {
        $this->t = $t;
        parent::__construct($env);
    }

    function init()
    {
        $this->t->pass('->__construct() calls ->init() properly');
    }

    function test()
    {
        $this->t->pass('->__construct() calls environment related method properly');
    }
}

$t = new lime_test(9);

$bootstrap = new Bootstrap('test', $t);

$container = $bootstrap->container();
$t->isa_ok($container, 'greebo\\conveniences\\Container', '->__construct() creates correct container');
$t->is($container->env, 'test', '->__construct() sets correct environment');

$t->isa_ok($bootstrap->greebo(), 'greebo\\essence\\Greebo', '->greebo() returns Greebo instance');


$container->template = null;
$container->action = function($c) { return function($c) {}; };
$_SERVER['SERVER_PROTOCOL'] = '';
ob_start();
$bootstrap->run();
$contents = ob_get_contents();
ob_end_clean();
$t->pass('->run() works properly');

try {
  $container->action = function($c) { return function($c) { throw new Exception; }; };

  ob_start();
  $bootstrap->run();
  $result = ob_get_clean();

  $t->pass('->run() handle exception');
  $t->is($result, null, '->run() returns sends content');
} catch (Exception $e) {
  $t->fail('->run() handle exception');
  $t->fail('->run() returns sends content');
}

try {
  $container->debug = true;

  ob_start();
  $bootstrap->run();
  $result = ob_get_clean();
  
  $t->ok(strlen($result), '->run() returns backtrace in debug mode');
} catch (Exception $e) {
  $t->fail('->run() handle exception');
}