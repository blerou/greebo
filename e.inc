<?php

$nc = get_declared_classes();
$s = $_SERVER;

require 'c.inc';

foreach (array_diff(get_declared_classes(), $nc) as $c) {
  if (preg_match(sprintf('!^%s$!', constant("{$c}::URI")), $s['PATH_INFO'])) {
    $c = new $c;
    $m = strtolower($s['REQUEST_METHOD']);
    die($c->$m());
  }
}
header(sprintf('%s 404', $s['SERVER_PROTOCOL']));
die('ooopss');
