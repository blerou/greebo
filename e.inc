<?php

require 'r.inc';

$no_classes = get_declared_classes();

require 'c.inc';

$server = $_SERVER;
$method = $server['REQUEST_METHOD'];
$uri = @$server['PATH_INFO'] ? $server['PATH_INFO'] : '/';
foreach (array_diff(get_declared_classes(), $no_classes) as $class)
{
  if (preg_match(sprintf('!^%s$!', constant("{$class}::URI")), $uri))
  {
    if (!method_exists($class, $method)) continue;
    $class = new $class;
    $class->req = new Req;
    $class->req->m = $method;
    return $class->$method()->send();
  }
}
