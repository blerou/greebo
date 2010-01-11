<?php

namespace greebo\essence;

class Hooks
{
  private $_hooks = array();
  
  function reg($hook, $callable) {
    $this->_hooks[$hook][] = $callable;
  }
  
  function fire($hook, $subject) {
    foreach ((array)@$this->_hooks[$hook] as $callable)
      $callable($subject);
  }
  
  function filter($hook, $subject, $content) {
    foreach ((array)@$this->_hooks[$hook] as $callable)
      $content = $callable($subject, $content);
    return $content;
  }
}