<?php

namespace My\hello;

require __DIR__.'/../../../../../src/greebo/essence/Greebo.php';
\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(realpath(__DIR__.'/../..'));

class Bootstrap extends \greebo\conveniences\Bootstrap
{
  function init()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
  }

  function setup()
  {
    $container = $this->container();
    $container->vendor = 'My';
    $container->app = 'hello';
  }
}
