<?php

namespace Greebo;

class Greebo
{
  private $_container;
  
  function __construct(Container $container) {
    $this->_container = $container;
  }
  
  function unleash() {
    $this->_container->hooks->fire('startup' $this->_container);
    
    $controller = $this->_container->controller;
    $controller();
    
    $this->_container->response->send();
    
    $this->_container->hooks->fire('shutdown' $this->_container);
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
    $this->vendor = 'Greebo';
    $this->app = 'default';
    $this->request = $this->shared(function($c) { return new Request($c); });
    $this->response = $this->shared(function($c) { return new Response($c); });
    $this->hooks = $this->shared(function($c) { return new Hooks; });
    $this->action = $this->request->param('action', 'index');
    $this->controller = $this->shared(function($c) {
      $class = sprintf('%s\\Controller\\%s', $c->vendor, $c->app);
      return new $class($c);
    });
    $this->template = $this->shared(function($c) {
      $class = sprintf('%s\\Template\\%s\\%s', $c->vendor, $c->app, $c->action);
      $class = new $class($c);
      $class->escaper(function($val) { return htmlentities($val, ENT_QUOTES, 'utf-8'); });
      return $class;
    });
  }
  
  function __set($id, $val) {
    $this->_services[$id] = $val;
  }
  
  function __get($id) {
    return is_callable($this->_services[$id]) ? $this->_services[$id]($this) : (@$this->_services[$id] ?: null);
  }
  
  function shared($callable) {
    return function ($c) use ($callable) {
      static $object;
      if (!$object) {
        $object = $callable($c);
      }
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
    foreach ($this->_hooks[$hook] as $callable) {
      $callable($subject);
    }
  }
  
  function filter($hook, $subject, $content) {
    foreach ($this->_hooks[$hook] as $callable) {
      $content = $callable($subject, $content);
    }
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
  
  function init() {}

  function __get($name) {
    return $this->_container->$name;
  }

  function __set($name, $val) {
    $this->_container->$name = $val;
  }
}

class Controller extends Base
{
  function __invoke() {
    try {
      if (!method_exists($this, $method = $this->action.'Action')) {
        $this->forward('error404');
      }
       
      if (false !== $this->$method() && $this->template) {
        $this->response->content($this->template->fetch());
      }
    } catch (ForwardException $e) {
      $this();
    }
  }
  
  function error404Action() {
    $this->response->status(404);
    $this->response->content('Error 404 Page');
    return false;
  }
  
  function forward($action) {
    $this->action = 'error404';
    throw new ForwardException;
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
    foreach ($this->_header as $name => $header) {
      header("$name: $header");
    }
    foreach ($this->_cookie as $cookie) {
      call_user_func_array('setrawcookie', $cookie);
    }
    if (null !== $this->_content) {
      echo $this->hooks->filter('response.content', $this, $this->_content);
    }
  }
}

class Template extends Base
{
  private
    $_slots = array(),
    $_slot,
    $_escaper;
  
  function __get($slot) {
    return @$this->_slots[$slot] ?: null;
  }
  
  function __set($slot, $val) {
    $this->_slots[$slot] = $val;
  }
  
  function rec($slot) {
    $this->_slot = $slot;
    ob_start();
  }
  
  function stop() {
    $this->_slots[$this->_slot] = ob_get_clean();
    $this->_slot = null;
  }
  
  function escaper(\Closure $escaper) {
    $this->_escaper = $escaper;
  }
  
  function fetch() {
    $this->_slots = $this->hooks->filter('template.slots', $this, $this->_slots);
    ob_start();
    $this->content();
    return ob_get_clean();
  }
  
  function content() { }

  function escape($val) {
    return is_string($val) ? call_user_func($this->_escaper, $val) : $val;
  }
}

class ForwardException extends Exception { }
