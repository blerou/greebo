<?php

namespace greebo\essence;

class Request
{
  private $_attrs = array();

  function header($name, $def = null) {
    return @$_SERVER[strtoupper($name)] ?: $def;
  }
  
  function param($name, $def = null) {
    $param = $_GET;
    if ($this->header('request_method') == 'POST')
      $param = array_merge($param, $_POST);
    return @$param[$name] ?: $def;
  }
  
  function __call($method, $args) {
    $param = @$GLOBALS['_'.strtoupper($method)];
    return @$param[$args[0]] ?: @$args[1];
  }

  function __get($name) {
    return @$this->_attrs[$name] ?: null;
  }

  function __set($name, $val) {
    $this->_attrs[$name] = $val;
  }
}