<?php

namespace greebo\essence;

/**
 * @see http://github.com/fabpot/Pimple/blob/master/lib/Pimple.php
 */
class Container
{
  private $_services = array();
  
  function __construct() {
    $this->init();
  }
  
  function init() {
    $this->request = $this->shared(function($c) { return new Request; });
    $this->response = $this->shared(function($c) { return new Response($c); });
    $this->hooks = $this->shared(function($c) { return new Hooks; });
  }
  
  function __set($id, $val) {
    $this->_services[$id] = $val;
  }
  
  function __get($id) {
    return is_callable(@$this->_services[$id]) ? $this->_services[$id]($this) : (@$this->_services[$id] ?: null);
  }
  
  function shared($callable) {
    return function ($c) use ($callable) {
      static $object;
      if (!$object) $object = $callable($c);
      return $object;
    };
  }
}