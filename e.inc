<?php

require 'r.inc';

$nc = get_declared_classes();

require 'c.inc';
require 'd.inc';

$s = $_SERVER;
$m = strtolower($s['REQUEST_METHOD']);
foreach (array_diff(get_declared_classes(), $nc) as $c)
{
  if (preg_match(sprintf('!^%s$!', constant("{$c}::URI")), $s['PATH_INFO']))
  {
    if (!method_exists($c, $m)) continue;
    $c = new $c;
    $c->req = new Req;
    $c->req->method = $m;
    return $c->$m()->send();
  }
}
