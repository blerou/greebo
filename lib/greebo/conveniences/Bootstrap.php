<?php

namespace greebo\conveniences;

abstract class Bootstrap
{
  protected $_container;
    
  function __construct($env) {
    $this->_container = $this->container();
    $this->_container->env = $env;
    
    $this->init();
    $this->$env();
  }
  
  abstract function init();
  
  function container() {
    return new Container;
  }
  
  function run() {
    try
    {
      $g = new \greebo\essence\Greebo($this->_container);
      $g->unleash();
    }
    catch (Exception $e) {
      if ($this->_container->debug) {
        // TODO show backtrace
      }
      else
      {
        // TODO render error500 page
      }
    }
  }
}