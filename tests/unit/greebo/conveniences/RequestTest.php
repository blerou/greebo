<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\conveniences\Request;

$request = new Request;


$t = new lime_test(4);


$t->diag('method');


$_SERVER['REQUEST_METHOD'] = 'POST';
$t->is($request->method('get'), false, '->method() works properly');
$t->is($request->method('post'), true, '->method() works properly');


$t->diag('ajax');


$t->is($request->ajax(), false, '->ajax() works properly');
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$t->is($request->ajax(), true, '->ajax() works properly');