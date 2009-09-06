<?php

define('METHOD', @$_POST['_method'] ? $_POST['_method'] : $_SERVER['REQUEST_METHOD']);

class Greebo
{
  static $no_classes;
  static function rape($app)
  {
    require "$app.php";

    $uri = @$_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '/';
    foreach (array_diff(get_declared_classes(), self::$no_classes) + array('E404') as $class)
    {
      if (preg_match(sprintf('!^%s$!', constant("$class::R")), $uri, $match))
      {
        if (!method_exists($class, METHOD)) continue;
        $class = new $class;
        $match[0] = new Req;
        try {
          return call_user_func_array(array($class, METHOD), $match)->send();
        } catch (Exception $e) {
        }
      }
    }
  }
}

Greebo::$no_classes = get_declared_classes();

/**
 * General request data reciever class
 * 
 * Used properties:
 *  - param: the current http method represented input data
 *  - anything which present in $GLOBALS array as a key with _ prefix (ex. server, cookie, ...)
 * 
 * @author blerou
 */
class Req
{
  function __call($method, $args)
  {
    $param = $method == 'param' ? $GLOBALS['_'.METHOD] : @$GLOBALS['_'.strtoupper($method)];
    return @$param[$args[0]] ? $param[$args[0]] : @$args[1];
  }
}

/**
 * General response class
 * 
 * Used properties:
 *  - status: http status code
 *  - header: array of http header (stored in key value pairs)
 *  - cookie: array of cookies (stored as array of setrawcookie parameters)
 *  - content: the content of response
 * 
 * @author blerou
 */
class Resp
{
  /**
   * send the aggregated response headers, cookies and content
   */
  function send()
  {
    header($_SERVER['SERVER_PROTOCOL'].' '.(@$this->status ? $this->status : 200));
    foreach ((array)@$this->header as $name => $header)
      header("$name: $header");
    foreach ((array)@$this->cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    echo $this->content;
  }
}



/**
 * Templating
 */
class T
{
  function render()
  {
    extract(self::esc((array)$this->v));
    ob_start();
    echo $this->template();
    return ob_get_clean();
  }
  
  function raw($var)
  {
    return @$this->v[$var];
  }
  
  static function esc($var)
  {
    switch (1)
    {
      case is_string($var):
        return htmlspecialchars($var);
      case is_object($var):
        $obj = new TE($var);
        $obj->v = $var;
        return $obj;
      case is_array($var):
        foreach ($var as $k => $v)
        {
          $var[$k] = self::esc($v);
        }
    }
    return $var;
  }
  
  function template()
  {
    include $this->template;
  }
}

/**
 * Template object escaper
 */
class TE
{
  function __call($method, $args)
  {
    return T::esc(@call_user_func_array(array($this, $method), $args));
  }
}


/**
 * Helper functions
 */
function render_to_response($text)
{
  $r = new Resp;
  $r->content = $text;
  return $r;
}

function redirect($uri)
{
  $r = new Resp;
  $uri = $_SERVER['SCRIPT_NAME'].$uri;
  $r->header[] = "Location: $uri";
  return $r;
}

function forward()
{
  throw new Exception;
}

/**
 * Error 404 handler
 *
 * @author blerou
 */

class E404
{
  const R = '.*';
  
  function __construct()
  {
    $this->r = new Resp;
    $r->status = 404;
  }
  
  function get()
  {
    $this->r->content = 'ooopss';
    return $this->r;
  }
  
  function post()
  {
    return $this->r;
  }
  
  function put()
  {
    return $this->r;
  }
  
  function head()
  {
    return $this->r;
  }
}

