<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Request;

$request = new Request;


$t = new lime_test(22);


$t->diag('attributes');


$request->foo = 'bar';
$t->is($request->foo, 'bar', '->__get(), ->__set() works properly');
$t->is($request->fail, null, '->__get() returns null on invalid attribute');


$t->diag('->header()');


$t->is($request->header('REQUEST_TIME'), $_SERVER['REQUEST_TIME'], '->header() returns SERVER parameters properly');
$t->is($request->header('X-NOTHING'), null, '->header() returns null on invalid SERVER parameters');
$t->is($request->header('X-NOTHING', 'default'), 'default', '->header() returns default value on invalid SERVER parameters if added');


$t->diag('->param()');


$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['foo'] = 'bar';

$t->is($request->param('foo'), 'bar', '->param() returns correct parameter on GET request');
$t->is($request->param('bar'), null, '->param() returns null on invalid parameter on GET request');
$t->is($request->param('bar', 'def'), 'def', '->param() returns default value on invalid parameter on GET request if added');


$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['baz'] = 'foo';

$t->is($request->param('baz'), 'foo', '->param() returns correct parameter on POST request');
$t->is($request->param('bar'), null, '->param() returns null on invalid parameter on POST request');
$t->is($request->param('bar', 'def'), 'def', '->param() returns default value on invalid parameter on POST request if added');
$t->is($request->param('foo'), 'bar', '->param() returns correct GET parameter on POST request');
$_POST['foo'] = 'baz';
$t->is($request->param('foo'), 'baz', '->param() returns correct parameter on POST request');


$t->diag('universal getter');


$t->is($request->get('foo'), 'bar', '->__call() works properly for GET values');
$t->is($request->get('fail'), null, '->__call() works properly for invalid GET values');
$t->is($request->get('fail', 'def'), 'def', '->__call() works properly for invalid GET values');

$t->is($request->post('foo'), 'baz', '->__call() works properly for POST values');
$t->is($request->post('fail'), null, '->__call() works properly for invalid POST values');
$t->is($request->post('fail', 'def'), 'def', '->__call() works properly for invalid POST values');

$_COOKIE['foo'] = 'bar';
$t->is($request->cookie('foo'), 'bar', '->__call() works properly for COOKIE values');
$t->is($request->cookie('fail'), null, '->__call() works properly for invalid COOKIE values');
$t->is($request->cookie('fail', 'def'), 'def', '->__call() works properly for invalid COOKIE values');