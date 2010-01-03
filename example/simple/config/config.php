<?php

$container = new \Greebo\Container;

$container->loader = $container->shared(function($c) {
  return new Greebo\ClassLoader;
});
$container->request = $container->shared(function($c) {
  return new Greebo\Request($c);
});
$container->response = $container->shared(function($c) {
  return new Greebo\Response($c);
});
$container->controller = $container->shared(function($c) {
  $class = sprintf('%s\\Controller\\%s', $c->app_vendor, $c->app_controller);
  return new $class($c);
});

return $container;