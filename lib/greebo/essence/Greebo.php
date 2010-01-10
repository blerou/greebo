<?php

namespace greebo\essence;

Greebo::register();

class Greebo
{
  private $_container;
  
  function __construct($container) {
    $this->_container = $container;
  }
  
  function unleash() {
    $container = $this->_container;

    $container->hooks->fire('startup', $container);
    
    if (!is_callable($action = $container->action))
      $action = function($c) { $c->response->status(404); $c->response->content('error404'); };

    $container->hooks->fire('preexec', $container);
    
    $action($container);
    
    $container->hooks->fire('postexec', $container);
    
    $container->response->send();
    
    $container->hooks->fire('shutdown', $container);
  }

  static function register()
  {
    set_include_path(realpath(dirname(__DIR__).'/../').PATH_SEPARATOR.get_include_path());
    spl_autoload_register(function($class) { require ltrim(str_replace('\\', '/', $class.'.php'), '/'); });
  }
}