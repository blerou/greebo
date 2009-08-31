<?php

class V
{
  function render()
  {
    extract(self::esc((array)$this->v));
    ob_start();
    echo $this->template();
    return ob_get_clean();
  }
  
  function raw($var)
  {
    return @$this->v[$var];
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
          $var[$k] = self::esc($v);
        }
    }
    return $var;
  }
  
  function template() { }
}

class VE
{
  function __call($method, $args)
  {
    return V::esc(@call_user_func_array(array($this, $method), $args));
  }
}

