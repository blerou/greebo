<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');

require __DIR__.'/../src/My/simple/Bootstrap.php';

$b = new \My\simple\Bootstrap('prod');
$b->run();
