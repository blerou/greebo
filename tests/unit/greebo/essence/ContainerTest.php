<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Container;


$t = new lime_test(8);


$container = new Container;

$container->foo = 'foo';
$container->bar = function($c) { return new stdClass(); };

$t->pass('setter works properly');

$t->is_deeply($container->foo, 'foo', 'getter returns simple values properly');
$t->is_deeply($container->invalid, null, 'getter returns null on invalid keys');

$bar1 = $container->bar;
$bar2 = $container->bar;
$t->ok($bar1 instanceof stdClass, 'getter returns lazy loaded instance properly');
$t->ok($bar1 !== $bar2, 'getter returns distinct instances by default');

$container->baz = $container->shared(function($c) { return new stdClass(); });

$baz1 = $container->baz;
$baz2 = $container->baz;
$t->ok($baz1 instanceof stdClass, 'getter returns lazy loaded instance properly as shared');
$t->ok($baz1 === $baz2, 'getter returns distinct instances when shared');

$t->ok(isset($container->bar) && !isset($container->invalid), 'property check works properly');