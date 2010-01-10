<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require dirname(__DIR__).'/../lib/greebo/essence/Greebo.php';

$c = new \greebo\essence\Container;
$c->action = function($c) {
  return function($c) { $c->response->content('<script>alert("hello");</script>'); };
};

//require dirname(__DIR__).'/../gconveniences.php';
//class template extends \Greebo\Template { function content() { $this->escape('<script>alert("hello");</script>'); }}
//
//$c->controller = function($c) {
//  return function($c) { $c->response->content(call_user_func($c->template, $c)); };
//};
//$c->template = function($c) { return new template($c) };

$g = new \greebo\essence\Greebo($c);
$g->unleash();
