<?php

namespace My\Bootstrap;

require __DIR__.'/../../../../../gessence.php';
require __DIR__.'/../../../../../gconveniences.php';

class Simple extends \Greebo\Bootstrap
{
  function init()
  {
    $this->_container->vendor = 'My';
    $this->_container->app = 'Simple';
    $this->_container->lib_dir = dirname(__DIR__).'/..';
  }
  
  function prod()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
  }
}
