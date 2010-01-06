<?php

namespace Greebo;

class Greebo
{
  private $_container;
  
  function __construct($container) {
    $this->_container = $container;
  }
  
  function unleash() {
    $container = $this->_container;

    //$container->hooks->fire('startup', $container);
    
    if (!is_callable($controller = $container->controller))
      $controller = function($c) { $c->response->status(404); $c->response->content('error404'); };

    //$container->hooks->fire('preexec', $container);

    $controller($container);

    //$container->hooks->fire('postexec', $container);
    
    $container->response->send();
    
    //$container->hooks->fire('shutdown', $container);
  }
}

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
    $this->request = $this->shared(function($c) { return new Request($c); });
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

class Base
{
  private $_container;

  function __construct(Container $container) {
    $this->_container = $container;
    $this->init();
  }
  
  function init() { }

  function __get($name) {
    return $this->_container->$name;
  }

  function __set($name, $val) {
    $this->_container->$name = $val;
  }
}

class Request extends Base
{
  function header($name, $def = null) {
    return @$_SERVER[strtoupper($name)] ?: $def;
  }
  
  function param($name, $def = null) {
    $param = $_GET;
    if ($this->header('request_method') == 'POST') {
      $param = array_merge($param, $_POST);
    }
    return @$param[$name] ?: $def;
  }
  
  function __call($method, $args) {
    $param = @$GLOBALS['_'.strtoupper($method)];
    return @$param[$args[0]] ?: @$args[1];
  }
}

class Response extends Base
{
  private 
    $_header = array(),
    $_cookie = array(),
    $_status = 200,
    $_content = null;
  
  function status($status) {
    $this->_status = (int)$status;
  }
  
  function header($name, $val) {
    $this->_header[$name] = $val;
  }
  
  function cookie($val) {
    $this->_cookie[] = $val;
  }
  
  function content($content) {
    $this->_content = $content;
  }
  
  function send() {
    header($this->request->header('SERVER_PROTOCOL').' '.$this->_status);
    foreach ($this->_header as $name => $header)
      header("$name: $header");
    foreach ($this->_cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    if (null !== $this->_content)
      echo $this->hooks->filter('response.content', $this, $this->_content);
  }
}