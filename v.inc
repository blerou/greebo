<?php

class V
{
  public $v = array();
  
  function render($t)
  {
    $this->escape();
    extract($this->v);
    ob_start();
    include $t;
    return ob_get_clean();
  }
  
  function escape($vars = null);
  {
    $vars = $vars ? $vars : $this->v;
    foreach ((array)$vars as $n => $v)
    {
      switch (1)
      {
        case is_string($v):
          $vars[$n] = htmlspecialchars($v);
          break;
        case is_object($v):
          $vars[$n] = new OE($v);
          break;
        case is_array($v):
          $vars[$n] = $this->escape($v);
          break;
    }
  }
}

