<?php

$nc = get_declared_classes();
$s = $_SERVER;

require 'c.inc';
require 'd.inc';

$m = strtolower($s['REQUEST_METHOD']);
foreach (array_diff(get_declared_classes(), $nc) as $c) {
  if (preg_match(sprintf('!^%s$!', constant("{$c}::URI")), $s['PATH_INFO'])) {
    if (!method_exists($c, $m)) continue;
    $c = new $c;
    die($c->$m());
  }
}
