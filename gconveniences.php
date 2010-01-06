<?php

namespace Greebo;

class BasicContainer extends Container
{
  function init() {
    parent::init();

    $this->vendor = 'Greebo';
    $this->app = 'default';
    $this->loader = $this->shared(function($c) { return new ClassLoader; });
    $this->response = $this->shared(function($c) { return new BasicResponse($c); });
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
}

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
    return new BasicContainer;
  }
  
  function run() {
    try
    {
      if ($this->_container->loader) {
        $this->_container->loader->registerNamespace($this->_container->vendor, $this->_container->lib_dir);
        $this->_container->loader->register();
      }
    
      $g = new Greebo($this->_container);
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

  function assign($slot, $val) {
    $this->template->$slot = $val;
  }
  
  function sendback($val) {
    $this->response->content($val);
    return false;
  }
}

class BasicResponse extends Response
{
  function init() {
    $this->header('Content-type', 'text/html; charset=utf8');
  }
  
  function redirect($uri) {
    $this->header('Location', $uri);
    $this->content = null;
    $this->send();
    exit;
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
  public function registerNamespace($namespace, $includePath = null) {
    if (!isset($this->namespaces[$namespace])) {
      $this->namespaces[$namespace] = array();
    }
    $this->namespaces[$namespace][] = $includePath;
  }

  /**
   * Installs this class loader on the SPL autoload stack.
   */
  public function register() {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * Uninstalls this class loader from the SPL autoloader stack.
   */
  public function unregister() {
    spl_autoload_unregister(array($this, 'loadClass'));
  }

  /**
   * Loads the given class or interface.
   *
   * @param string $className The name of the class to load
   */
  public function loadClass($className) {
    $vendor = substr($className, 0, stripos($className, '\\'));
    if (!$vendor && !isset($this->namespaces[''])) return;
    
    $fileName = '';
    $namespace = '';
    if (false !== ($lastNsPos = strripos($className, '\\'))) {
      $namespace = substr($className, 0, $lastNsPos);
      $className = substr($className, $lastNsPos + 1);
      $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';

    if (!isset($this->namespaces[$vendor])) {
      require $fileName;
      return;
    }
    foreach ($this->namespaces[$vendor] as $dir) {
      if (!file_exists($dir.DIRECTORY_SEPARATOR.$fileName)) continue;
      require $dir.DIRECTORY_SEPARATOR.$fileName;
      break;
    }
  }
}
