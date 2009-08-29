<?php

class Req
{
  function param($name, $default = null)
  {
    $p = ${'_'.$this->method};
    return isset($p[$name]) ? $p[$name] : $default;
  }
  
  function file($name)
  {
    $f = $_FILES;
    return isset($f[$name]) ? $f[$name] : null;
  }
  
  function header($name)
  {
    $s = $_SERVER;
    return isset($s[$name]) ? $s[$name] : null;
  }
  
  function cookie($name, $default = null)
  {
    $c = $_COOKIE;
    return isset($c[$name]) ? $c[$name] : $default;
  }
}

class Res
{
  /**
   * header setter
   */
  function header($name, $value)
  {
    $this->header[$name] = $value;
  }
  
  function cookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false)
  {
    $this->cookie[] = func_get_args();
  }
  
  /**
   * content setter: string or resource
   */
  function content($content)
  {
    $this->content = $content;
  }
  
  /**
   * send response
   */
  function send()
  {
    foreach ($this->header as $name => $value)
    {
      header("{$name}: {$value}");
    }
    foreach ($this->cookie as $cookie)
    {
      call_user_func_array('setrawcookie', $cookie);
    }
    if (is_string($this->content))
    {
      echo $content;
      return;
    }
    fpassthru($this->content);
  }
}
