<?php

require dirname(__DIR__).'/greebo.php';

$c = new \Greebo\Container;
$c->response = function($c) { return new \Greebo\Response2($c); };
$c->controller = function($c) {
  return function() use ($c) { $c->response->content('hello'); };
};

\Greebo\Greebo::create($c)->unleash();
