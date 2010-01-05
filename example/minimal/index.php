<?php

require dirname(__DIR__).'/../greebo.php';

class template extends \Greebo\Template { function content() { $this->escape('<script>alert("hello");</script>'); }}

$c = new \Greebo\Container;
$c->request = $c->shared(function($c) { return new \Greebo\Request($c); });
$c->response = $c->shared(function($c) { return new \Greebo\Response($c); });
$c->controller = function($c) {
  return function() use ($c) { $c->response->content('<script>alert("hello");</script>'); };
};

\Greebo\Greebo::create($c)->unleash();
