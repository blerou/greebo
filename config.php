<?php

$container = new \Greebo\Container;

$container->request = function($c) {
  return new \Greebo\Request;
};
$container->response = function($c) {
  return new \Greebo\Response;
};

return $container;