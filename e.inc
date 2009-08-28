<?php

$no_class = get_declared_classes();

require 'c.inc';

$uri = str_replace('/e.php', '', $_SERVER['PATH_INFO']);
foreach (array_diff(get_declared_classes(), $no_class) as $c)
{
  if (preg_match(sprintf('!^%s$!', constant("{$c}::URI")), $uri))
  {
    $c = new $c;
    $m = strtolower($_SERVER['REQUEST_METHOD']);
    die($c->call($m));
  }
}

header(sprintf('%s 404', $_SERVER['SERVER_PROTOCOL']));
die('ooopss');

class C
{
  protected $t = array();
  
  function call($m)
  {
    $this->m = $m;
    return $this->$m();
  }
  
  function set($name, $value)
  {
    $this->t[$name] = $value;
  }
  
  function render()
  {
    $f = sprintf('%s_%s', strtolower(get_class($this)), $this->m);
    return $f($this->t);
  }
}
