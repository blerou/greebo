<?php

namespace Greebo;

class Greebo
{
  private $container;
  
  function __construct($config)
  {
    $this->container = include $config;
    $this->container->loader->registerNamespace($container->app_vendor, $container->app_lib_dir);
    $this->container->loader->register();
  }
  
  static function create($config)
  {
    return new self($config);
  }
  
  function handle()
  {
    $controller = $this->container->controller;
    $controller();
    
    $this->container->response->send();
  }
}

/**
 * @see http://github.com/fabpot/Pimple/blob/master/lib/Pimple.php
 */
class Container
{
  private $services = array();
  
  function __set($id, $value)
  {
    $this->services[$id] = $value;
  }
  
  function __get($id)
  {
    return is_callable($this->services[$id]) ? $this->services[$id]($this) : (@$this->services[$id] ?: null);
  }
  
  function shared($callable)
  {
    return function ($c) use ($callable) {
      static $object;
      if (!$object) $object = $callable($c);
      return $object;
    };
  }
}

class BasicContainer extends Container
{
  function __construct()
  {
    $this->loader = $this->shared(function($c) {
      return new ClassLoader;
    });
    $this->request = $this->shared(function($c) {
      return new Request($c);
    });
    $this->response = $this->shared(function($c) {
      return new Response($c);
    });
    $this->controller = $this->shared(function($c) {
      $class = sprintf('%s\\Controller\\%s', $c->app_vendor, $c->app_controller);
      return new $class($c);
    });
    $this->hooks = $this->shared(function($c) {
      return new Hooks;
    });
  }
}

class Hooks
{
  private $hooks = array();
  
  function reg($hook, $callable)
  {
    $this->hooks[$hook][] = $callable;
  }
  
  function fire(Hook $hook)
  {
  
  }
}

class Base
{
  private $container;

  function __construct(Container $container)
  {
    $this->container = $container;
  }

  function __get($name)
  {
    return $this->container->$name;
  }
}

class Controller extends Base
{
  function __invoke()
  {
    $action = $this->request->param('action', 'index');

    $result = (method_exists($this, $method = $action.'Action')) ? $this->$method() : null;

    // TODO - blerou - fetch view to response
  }
}

class Request extends Base
{
  function header($name, $def = null)
  {
    return @$_SERVER[strtoupper($name)] ?: $def;
  }
  
  function param($name, $def = null)
  {
    $param = $_GET;
    if ($this->header('request_method') == 'POST')
      $param = array_merge($param, $_POST);
    return @$param[$name] ?: $def;
  }
  
  function __call($method, $args)
  {
    $param = @$GLOBALS['_'.strtoupper($method)];
    return @$param[$args[0]] ?: @$args[1];
  }
}

class Response extends Base
{
  private 
    $header = array(),
    $cookie = array(),
    $status = 200,
    $content = null;
  
  function status($status)
  {
    $this->status = (int)$status;
  }
  
  function header($name, $val)
  {
    $this->header[$name] = $val;
  }
  
  function cookie($val)
  {
    $this->cookie[] = $val;
  }
  
  function content($content)
  {
    $this->content = $content;
  }
  
  function send()
  {
    header($this->request->header('SERVER_PROTOCOL').' '.$this->status);
    foreach ($this->header as $name => $header)
      header("$name: $header");
    foreach ($this->cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    if (null !== $this->content)
      echo $this->content;
  }
  
  function redirect($uri)
  {
    $this->header('Location', $uri);
    $this->content = null;
    $this->send();
    exit;
  }
}

class Template
{
  private $_vars = array();
  function fetch()
  {
    ob_start();
    echo $this->content();
    return ob_get_clean();
  }
  
  function content() {}
}


/**
 * ClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * Based on http://groups.google.com/group/php-standards/web/final-proposal
 *
 * Example usage:
 *
 *     [php]
 *     $loader = new ClassLoader();
 *     $loader->registerNamespace('Symfony', __DIR__.'/..');
 *     $loader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class ClassLoader
{
  protected $namespaces = array();

  /**
   * Creates a new loader for classes of the specified namespace.
   *
   * @param string $namespace   The namespace to use
   * @param string $includePath The path to the namespace
   */
  public function registerNamespace($namespace, $includePath = null)
  {
    if (!isset($this->namespaces[$namespace]))
    {
      $this->namespaces[$namespace] = array();
    }
    $this->namespaces[$namespace][] = $includePath;
  }

  /**
   * Installs this class loader on the SPL autoload stack.
   */
  public function register()
  {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * Uninstalls this class loader from the SPL autoloader stack.
   */
  public function unregister()
  {
    spl_autoload_unregister(array($this, 'loadClass'));
  }

  /**
   * Loads the given class or interface.
   *
   * @param string $className The name of the class to load
   */
  public function loadClass($className)
  {
    $vendor = substr($className, 0, stripos($className, '\\'));
    if (!$vendor && !isset($this->namespaces[''])) return;
    
    $fileName = '';
    $namespace = '';
    if (false !== ($lastNsPos = strripos($className, '\\')))
    {
      $namespace = substr($className, 0, $lastNsPos);
      $className = substr($className, $lastNsPos + 1);
      $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';

    if (!isset($this->namespaces[$vendor]))
    {
      require $fileName;
      return;
    }
    foreach ($this->namespaces[$vendor] as $dir)
    {
      if (!file_exists($dir.DIRECTORY_SEPARATOR.$fileName)) continue;
      require $dir.DIRECTORY_SEPARATOR.$fileName;
      break;
    }
  }
}