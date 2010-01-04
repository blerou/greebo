<?php

require __DIR__.'/../../../../greebo.php';
require __DIR__.'/../../../../greebo_extra.php';

namespace My\Bootstrap;

class Simple extends \Greebo\Bootstrap
{
  function init()
  {
    $this->_container->vendor = 'My';
    $this->_container->app = 'Simple';
    $this->_container->lib_dir = dirname(__DIR__).'/lib';
  }
  
  function prod()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
  }
}
