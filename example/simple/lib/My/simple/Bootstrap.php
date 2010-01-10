<?php

namespace My\simple;

require __DIR__.'/../../../../../lib/greebo/essence/Greebo.php';

set_include_path(realpath(dirname(__DIR__).'/..').PATH_SEPARATOR.get_include_path());

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
