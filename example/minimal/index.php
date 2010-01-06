<?php

require dirname(__DIR__).'/../gessence.php';

$c = new \Greebo\Container;
$c->controller = function($c) {
  return function($c) { $c->response->content('<script>alert("hello");</script>'); };
};

//require dirname(__DIR__).'/../gconveniences.php';
//class template extends \Greebo\Template { function content() { $this->escape('<script>alert("hello");</script>'); }}
//
//$c->controller = function($c) {
//  return function($c) { $c->response->content(call_user_func($c->template, $c)); };
//};
//$c->template = function($c) { return new template($c) };

$g = new \Greebo\Greebo($c);
$g->unleash();
