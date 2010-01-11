<?php

namespace My\simple;

require __DIR__.'/../../../../../src/greebo/essence/Greebo.php';
\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(realpath(dirname(__DIR__).'/..'));

class Bootstrap extends \greebo\conveniences\Bootstrap
{
  function init()
  {
    $this->_container->vendor = 'My';
    $this->_container->app = 'simple';

    //$this->_container->hooks->reg('response.content', function($response, $content) { return $content.'<br>finished'; });
  }
  
  function prod()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
  }
}
