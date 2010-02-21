<?php

namespace My\escaper;

require __DIR__.'/../../../../../src/greebo/essence/Greebo.php';

\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(realpath(__DIR__.'/../..'));

class Bootstrap extends \greebo\conveniences\Bootstrap
{
  function init()
  {
    $container = $this->container();
    $container->vendor = 'My';
    $container->app = 'escaper';

    \greebo\guards\Escaper::register($container->event);

    error_reporting(0);
    ini_set('display_errors', false);
  }
  
  function dev()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
  }
}
