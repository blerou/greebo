<?php

namespace My\escaper;

require __DIR__.'/../../../../../src/greebo/essence/Greebo.php';

\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(realpath(__DIR__.'/../..'));

use greebo\guards\Escaper;

class Bootstrap extends \greebo\conveniences\Bootstrap
{
    function init()
    {
        error_reporting(0);
        ini_set('display_errors', false);
    }

    function devInit()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', true);
    }

    function setup()
    {
        $container = $this->container();
        $container->vendor = 'My';
        $container->app = 'escaper';

        Escaper::register($container->event);
    }
}
