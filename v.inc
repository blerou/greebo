<?php

class V
{
  function render($t)
  {
    extract(self::escape((array)$this->v));
    ob_start();
    include $t;
    return ob_get_clean();
  }
  
  static function esc($var);
  {
    switch (1)
    {
      case is_string($var):
        return htmlspecialchars($var);
      case is_object($var):
        $obj = new VE($var);
        $obj->v = $var;
        return $obj;
      case is_array($var):
        foreach ($var as $k => $v)
        {
          $var[$k] = self::escape($v);
        }
    }
    return $var;
  }
}

class VE
{
  function __call($method, $args)
  {
    
  }
}

