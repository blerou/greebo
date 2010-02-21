<?php

namespace My\messageboard;

require __DIR__.'/../../../../../src/greebo/essence/Greebo.php';

\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(realpath(__DIR__.'/../..'));

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
        $container->app = 'messageboard';

        $container->pdo = $container->shared(function($c) {
           return new \PDO('mysql:dbname=messageboard;host=127.0.0.1', 'root', '');
        });
    }

    function devSetup()
    {
        $this->container()->debug = true;
    }
}
